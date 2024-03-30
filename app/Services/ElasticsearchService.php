<?php

namespace App\Services;

use Elastic\Elasticsearch\ClientBuilder;

class ElasticsearchService
{

    private $client;

    function __construct()
    {
        $this->client = ClientBuilder::create()
            ->setHosts([env('ELASTIC_HOST')])
            ->setBasicAuthentication('elastic', env('ELASTIC_PASSWORD'))
            ->build();
    }

    function get_hits(string $search, array $indexes)
    {
        if (in_array('books', $indexes) || empty($indexes)) {
            array_push($indexes, 'sections');
            array_push($indexes, 'chapters');
            array_push($indexes, 'lessons');
            $key = array_search('books', $indexes);
            if ($key !== false) {
                unset($indexes[$key]);
            }
            $indexes = implode(',', $indexes);
        }
        $search = strtolower(trim($search));
        if (strpos($search, ' ') !== false) {
            $params = [
                'index' => $indexes,
                'body' => [
                    'query' => [
                        'bool' => [
                            'should' => [
                                [
                                    'match_phrase_prefix' => [
                                        'title' => $search
                                    ]
                                ],
                                [
                                    'match_phrase_prefix' => [
                                        'desc' => $search
                                    ]
                                ],
                                [
                                    'match_phrase_prefix' => [
                                        'body' => $search
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ];
        } else {
            $params = [
                'index' => $indexes,
                'body' => [
                    'query' => [
                        'bool' => [
                            'should' => [
                                [
                                    'wildcard' => [
                                        'title' => "*$search*"
                                    ]
                                ],
                                [
                                    'wildcard' => [
                                        'desc' => "*$search*"
                                    ]
                                ],
                                [
                                    'wildcard' => [
                                        'body' => "*$search*"
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ];
        }
        $response = $this->client->search($params);
        return $response['hits']['hits'];
    }

    function checkOrCreateIndex($index): bool
    {
        $params = ['index' => $index];

        $indexExists = $this->client->indices()->exists($params);
        if ($indexExists->getStatusCode() == 404) {
            $response = $this->client->indices()->create($params);

            if (!$response['acknowledged']) {
                return false;
            }
        }
        return true;
    }

    function store_document(string $index, array $data): bool
    {
        $indexExists = $this->checkOrCreateIndex($index);
        if (!$indexExists) {
            return false;
        }
        $params = [
            'index' => $index,
            'body'  => $data
        ];
        $this->client->index($params);
        return true;
    }

    function delete_document(string $index, string $id): void
    {
        $params = [
            'index' => $index,
            'id' => $id
        ];
        $this->client->delete($params);
    }

    function delete_index(string $index): void
    {
        $params = [
            'index' => $index
        ];
        $this->client->indices()->delete($params);
    }

    public function update_document($index, $id, $dataToUpdate)
    {
        $documentIdToSearch = $id;

        $response = $this->client->search([
            'index' => $index,
            'body' => [
                'query' => [
                    'term' => [
                        '_source.id' => $documentIdToSearch,
                    ],
                ],
            ],
        ]);

        // Check if any documents match the search criteria
        if ($response['hits']['total']['value'] > 0) {
            // Retrieve the first matching document (assuming there is only one match)
            $document = $response['hits']['hits'][0];

            // Extract the Elasticsearch document ID
            $elasticsearchId = $document['_id'];

            // Use the update method to update the document by Elasticsearch ID
            $updateResponse = $this->client->update([
                'index' => $index,
                'id' => $elasticsearchId,
                'body' => [
                    'doc' => $dataToUpdate,
                ],
            ]);

            // Check the response for success or error information
            if ($updateResponse['result'] === 'updated') {
                // Document was successfully updated
                info(['message' => 'Document updated successfully', 'id' => $id]);
            } else {
                // Document update failed
                info(['message' => 'Failed to update document', 'id' => $id]);
            }
        } else {

            $this->store_document($index, $dataToUpdate);
            // No documents matched the search criteria
            info(['message' => 'new document.', 'id' => $id]);
        }
    }

    function search_resource($hits): array
    {
        $outputArray = [
            'books' => [],
            'protocols' => [],
            'power_points' => [],
            'articles' => [],
            'mcqs' => [],
            'lectures' => []
        ];

        foreach ($hits as $item) {
            $id = isset($item['_source']['id']) ? $item['_source']['id'] : null;
            $title = isset($item['_source']['title']) ? $item['_source']['title'] : '';
            $desc = isset($item['_source']['desc']) ? $item['_source']['desc'] : '';
            $slug = isset($item['_source']['slug']) ? $item['_source']['slug'] : '';
            $book_slug = isset($item['_source']['book_slug']) ? $item['_source']['book_slug'] : '';
            $section_slug = isset($item['_source']['section_slug']) ? $item['_source']['section_slug'] : '';
            $chapter_slug = isset($item['_source']['chapter_slug']) ? $item['_source']['chapter_slug'] : '';

            if (!is_null($id)) {
                $type = '';
                if (isset($item['_index'])) {
                    if ($item['_index'] === 'sections') {
                        $type = 'section';
                    } elseif ($item['_index'] === 'chapters') {
                        $type = 'chapter';
                    } elseif ($item['_index'] === 'lessons') {
                        $type = 'lesson';
                    }
                }

                if (!empty($type)) {
                    $outputArray['books'][] = [
                        'type' => $type,
                        'id' => $id,
                        'title' => $title,
                        'desc' => $desc,
                        'slug' => $slug,
                        'book_slug' => $book_slug,
                        'section_slug' => $section_slug,
                        'chapter_slug' => $chapter_slug,
                    ];
                }

                if (isset($item['_index'])) {
                    if ($item['_index'] === 'protocols' || $item['_index'] === 'power_points' || $item['_index'] === 'articles' || $item['_index'] === 'mcqs' || $item['_index'] === 'lectures') {
                        $outputArray[$item['_index']][] = [
                            'id' => $id,
                            'title' => $title,
                            'desc' => $desc,
                            'slug' => $slug
                        ];
                    }
                }
            }
        }

        return $outputArray;
    }

    function whatsapp_resource($hits): array
    {
        $data = [];
        $sections = [];
        $chapters = [];
        $lessons = [];
        for ($i = 0; $i < count($hits); $i++) {
            if ($hits[$i]['_index'] == 'sections') {
                if (isset($hits[$i]['_source']['slug'])) {
                    $sections[$i] = env('APP_URL') . '/' . $hits[$i]['_source']['slug'];
                }
            } else if ($hits[$i]['_index'] == 'chapters') {
                if (isset($hits[$i]['_source']['slug'])) {
                    $chapters[$i] = env('APP_URL') . '/' . $hits[$i]['_source']['slug'];
                }
            } else if ($hits[$i]['_index'] == 'lessons') {
                if (isset($hits[$i]['_source']['slug'])) {
                    $lessons[$i] = env('APP_URL') . '/' . $hits[$i]['_source']['slug'];
                }
            }
        }
        $data['sections'] = array_values($sections);
        $data['chapters'] = array_values($chapters);
        $data['lessons'] = array_values($lessons);
        return $data;
    }
}
