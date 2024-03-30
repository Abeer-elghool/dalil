<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\User\ContactUs\ContactUsResource;
use App\Http\Resources\User\HighSearchKeyword\HighSearchKeywordResource;
use App\Http\Resources\User\SearchHistory\SearchHistoryResource;
use App\Http\Resources\User\Setting\SettingResource;
use App\Http\Resources\User\StaticPage\StaticPageResource;
use App\Http\Resources\User\Search\{BookSearchResource,SectionSearchResource,ChapterSearchResource,LessonSearchResource,ProtocolSearchResource,PowerPointSearchResource,ArticleSearchResource,McqSearchResource,LectureSearchResource};
use App\Models\{Author,StaticPage,ContactUs,HighSearchKeyword,LatestNews,SearchHistory,Review,Team,Setting};
use App\Services\ElasticsearchService;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\User\Author\AuthorResource;
use App\Http\Resources\User\Team\TeamResource;
use App\Http\Resources\User\LatestNews\LatestNewsResource;
use Illuminate\Http\Request;
use App\Http\Resources\User\Review\ReviewResource;
use Illuminate\Support\Facades\Cache;
use App\Models\{Book,Section,Chapter,Lesson,PowerPoint,Protocol,Article,Mcq,Lecture};
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Jobs\SaveVisitJob;

class HomeController extends Controller
{
    /**
     * Clears the cache.
     */
    function clearCache()
    {
        // TODO: Implement the function logic
        Cache::flush();
        return 'done';
    }
    function search(Request $request, $keyword): JsonResponse
    {
        $indexes = $request->indexes ?? [];
        $elasticsearch_service = new ElasticsearchService();
        $data = $elasticsearch_service->get_hits($keyword, $indexes);
        // high searched keywords
        $keyword = trim(strtolower($keyword));
        $keyword_exist = HighSearchKeyword::where(['keyword' => $keyword])->first();
        if ($keyword_exist) {
            $keyword_exist->increment('count');
        } else {
            HighSearchKeyword::create(['keyword' => $keyword]);
        }

        // Search History
        if (auth('api')->check()) {
            $search_exist = auth('api')->user()->search_histories()->where(['keyword' => $keyword])->first();
            if ($search_exist) {
                $search_exist->increment('count');
            } else {
                auth('api')->user()->search_histories()->create(['keyword' => $keyword]);
            }
        }
        //
        return response()->json(['status' => 200, 'data' => $elasticsearch_service->search_resource($data), 'message' => '']);
    }

    function whatsapp_search($keyword): JsonResponse
    {
        $elasticsearch_service = new ElasticsearchService();
        $data = $elasticsearch_service->get_hits($keyword);
        return response()->json(['status' => 200, 'data' => $elasticsearch_service->whatsapp_resource($data), 'message' => '']);
    }

    public function settings()
    {
        $settings = Setting::active()->latest()->get();
        return SettingResource::collection($settings)->additional(['status' => 200, 'messages' => '']);
    }

    public function static_pages()
    {
        $static_pages = StaticPage::active()->latest()->get();
        return StaticPageResource::collection($static_pages)->additional(['status' => 200, 'messages' => '']);
    }

    public function about()
    {
        $about = StaticPage::where('key', 'about')->active()->latest()->get();
        return StaticPageResource::collection($about)->additional(['status' => 200, 'messages' => '']);
    }

    public function contact_us()
    {
        $contact_us = ContactUs::latest()->paginate(100);
        return ContactUsResource::collection($contact_us)->additional(['status' => 200, 'message' => '']);
    }

    public function high_searched_keywords()
    {
        $keywords = HighSearchKeyword::get();
        return HighSearchKeywordResource::collection($keywords)->additional(['status' => 200, 'message' => '']);
    }

    public function search_history()
    {
        $keywords = auth('api')->user()->search_histories()->latest()->get();
        return SearchHistoryResource::collection($keywords)->additional(['status' => 200, 'message' => '']);
    }

    public function home_pluck_apis(Request $request)
    {
        $ipAddress = $request->ip();
        SaveVisitJob::dispatch($ipAddress);
        $user_id = auth('api')->check() ? auth('api')->id() : null;
        if (!Cache::has('reviews')) {
            $reviews = Review::select('id', 'uuid', 'title', 'desc', 'slug','file', 'author_name', 'author_position', 'created_at')->active()->latest()->get();

            Cache::forever('reviews', $reviews);
        } else {
            $reviews = Cache::get('reviews');
        }
        if (!Cache::has('search_history')) {
            $data['search_history'] = SearchHistoryResource::collection(SearchHistory::select('id', 'keyword', 'created_at')->where('user_id', $user_id)->latest()->take(5)->get());
            Cache::forever('search_history', $data['search_history']);
        } else {
            $data['search_history'] = Cache::get('search_history');
        }
        if (!Cache::has('high_searched_keywords')) {
            $data['high_searched_keywords'] = HighSearchKeywordResource::collection(HighSearchKeyword::select('id', 'keyword')->orderBy('count', 'DESC')->take(5)->get());
            Cache::forever('high_searched_keywords', $data['high_searched_keywords']);
        } else {
            $data['high_searched_keywords'] = Cache::get('high_searched_keywords');
        }
        if (!Cache::has('authors')) {
            $data['authors'] = AuthorResource::collection(Author::select('id', 'uuid', 'name', 'slug', 'position', 'about', 'file')->active()->get());
            Cache::forever('authors', $data['authors']);
        } else {
            $data['authors'] = Cache::get('authors');
        }
        if (!Cache::has('scientific_team')) {
            $data['scientific_team'] = TeamResource::collection(Team::select('id', 'uuid', 'name', 'position', 'about','file')->active()->get());
            Cache::forever('scientific_team', $data['scientific_team']);
        } else {
            $data['scientific_team'] = Cache::get('scientific_team');
        }
        if (!Cache::has('latest_news')) {
            $data['latest_news'] = LatestNewsResource::collection(LatestNews::select('id', 'uuid', 'title', 'desc', 'author_name', 'file', 'created_at')->latest()->take(12)->get());
            Cache::forever('latest_news', $data['latest_news']);
        } else {
            $data['latest_news'] = Cache::get('latest_news');
        }
        $data['reviews'] = ReviewResource::collection($reviews);
        return response()->json(['status' => 200, 'data' => $data, 'message' => '']);
    }

    function book_search(Request $request)  {
        $keyword = $request->keyword;
        $limit = $request->limit ? $request->limit : 7;
        $book = Book::where('slug',$request->book_slug)->firstOrFail();

        $sections = Section::with(['book'])
            ->select('id','title','slug','book_id')
            ->where('book_id',$book->id)
            ->when($keyword,function($q)use($keyword){
                $q->where('title', 'LIKE', '%' . $keyword . '%')
                ->orWhere('desc', 'LIKE', '%' . $keyword . '%');
            })->limit($limit)->get();

        $chapters = Chapter::with(['book','section'])
            ->select('id','title','slug','book_id','section_id')
            ->where('book_id',$book->id)
            ->when($keyword,function($q)use($keyword){
                $q->where('title', 'LIKE', '%' . $keyword . '%')
                ->orWhere('desc', 'LIKE', '%' . $keyword . '%');
            })->limit($limit)->get();

        $lessons = Lesson::with(['chapter','section','book'])->select('id','title','slug','chapter_id','section_id','book_id')
                    ->where('book_id',$book->id)
                    ->when($keyword,function($q)use($keyword){
                        $q->where('title', 'LIKE', '%' . $keyword . '%')
                        ->orWhere('desc', 'LIKE', '%' . $keyword . '%');
                    })->limit($limit)->get();

        $results['sections'] = SectionSearchResource::collection($sections);
        $results['chapters'] = ChapterSearchResource::collection($chapters);
        $results['lessons']  = LessonSearchResource::collection($lessons);
        return response()->json(['status' => 200, 'data' => $results, 'message' => '']);

    }

    function new_search(Request $request) {
        $keyword = $request->keyword;
        $limit = $request->limit ? $request->limit : 7;
        $sections = Section::with(['book'])
            ->select('id','title','slug','book_id')
            ->when($keyword,function($q)use($keyword){
                $q->where('title', 'LIKE', '%' . $keyword . '%')
                ->orWhere('desc', 'LIKE', '%' . $keyword . '%');
            })->limit($limit)->get();
        $sections_count = Section::select('id')
        ->when($keyword,function($q)use($keyword){
            $q->where('title', 'LIKE', '%' . $keyword . '%')
            ->orWhere('desc', 'LIKE', '%' . $keyword . '%');
        })->count();
        $chapters = Chapter::with(['book','section'])
            ->select('id','title','slug','book_id','section_id')
            ->when($keyword,function($q)use($keyword){
                $q->where('title', 'LIKE', '%' . $keyword . '%')
                ->orWhere('desc', 'LIKE', '%' . $keyword . '%');
            })->limit($limit)->get();
        $chapters_count = Chapter::select('id')
        ->when($keyword,function($q)use($keyword){
            $q->where('title', 'LIKE', '%' . $keyword . '%')
            ->orWhere('desc', 'LIKE', '%' . $keyword . '%');
        })->count();

        $lessons = Lesson::with(['chapter','section','book'])
                    ->select('id','title','slug','chapter_id','section_id','book_id')
                    ->when($keyword,function($q)use($keyword){
                        $q->where('title', 'LIKE', '%' . $keyword . '%')
                        ->orWhere('desc', 'LIKE', '%' . $keyword . '%');
                    })->limit($limit)->get();
        $lessons_count = Lesson::select('id')
                    ->when($keyword,function($q)use($keyword){
                        $q->where('title', 'LIKE', '%' . $keyword . '%')
                        ->orWhere('desc', 'LIKE', '%' . $keyword . '%');
                    })->count();


        $protocols = Protocol::select('id','title','slug')
                    ->when($keyword,function($q)use($keyword){
                        $q->where('title', 'LIKE', '%' . $keyword . '%')
                        ->orWhere('desc', 'LIKE', '%' . $keyword . '%');
                    })->limit($limit)->get();

        $protocols_count = Protocol::select('id')
        ->when($keyword,function($q)use($keyword){
            $q->where('title', 'LIKE', '%' . $keyword . '%')
            ->orWhere('desc', 'LIKE', '%' . $keyword . '%');
        })->count();


        $power_points = PowerPoint::select('id','title','slug')
                        ->when($keyword,function($q)use($keyword){
                            $q->where('title', 'LIKE', '%' . $keyword . '%')
                            ->orWhere('desc', 'LIKE', '%' . $keyword . '%');
                        })->limit($limit)->get();

        $power_points_count = PowerPoint::select('id')
        ->when($keyword,function($q)use($keyword){
            $q->where('title', 'LIKE', '%' . $keyword . '%')
            ->orWhere('desc', 'LIKE', '%' . $keyword . '%');
        })->count();

        $articles = Article::select('id','title','slug')
                    ->when($keyword,function($q)use($keyword){
                        $q->where('title', 'LIKE', '%' . $keyword . '%')
                        ->orWhere('body', 'LIKE', '%' . $keyword . '%');
                    })->limit($limit)->get();

        $articles_count = Article::select('id')
        ->when($keyword,function($q)use($keyword){
            $q->where('title', 'LIKE', '%' . $keyword . '%')
            ->orWhere('body', 'LIKE', '%' . $keyword . '%');
        })->count();


        $mcqs = Mcq::select('id','title','slug')
                ->when($keyword,function($q)use($keyword){
                    $q->where('title', 'LIKE', '%' . $keyword . '%');
                })->limit($limit)->get();

        $mcqs_count = Mcq::select('id')
        ->when($keyword,function($q)use($keyword){
            $q->where('title', 'LIKE', '%' . $keyword . '%');
        })->count();

        $lectures = Lecture::select('id','title','slug')
                    ->when($keyword,function($q)use($keyword){
                        $q->where('title', 'LIKE', '%' . $keyword . '%')
                        ->orWhere('desc', 'LIKE', '%' . $keyword . '%');
                    })->limit($limit)->get();
        $lectures_count = Lecture::select('id')
        ->when($keyword,function($q)use($keyword){
            $q->where('title', 'LIKE', '%' . $keyword . '%')
            ->orWhere('desc', 'LIKE', '%' . $keyword . '%');
        })->count();
     $book_results['sections'] = SectionSearchResource::collection($sections);
     $book_results['chapters'] = ChapterSearchResource::collection($chapters);
     $book_results['lessons']  = LessonSearchResource::collection($lessons);
     $results['protocols']['results'] = ProtocolSearchResource::collection($protocols);
     $results['protocols']['count'] = $protocols_count;

     $results['power_points']['results'] = PowerPointSearchResource::collection($power_points);
     $results['power_points']['count'] = $power_points_count;

     $results['articles']['results'] = ArticleSearchResource::collection($articles);
     $results['articles']['count'] = $articles_count;

     $results['mcqs']['results'] = McqSearchResource::collection($mcqs);
     $results['mcqs']['count'] = $mcqs_count;

     $results['lectures']['results'] = LectureSearchResource::collection($lectures);
     $results['lectures']['count'] = $lectures_count;
     $results['book']['results'] = $book_results['sections']->merge($book_results['chapters'])->merge($book_results['lessons']);
     $results['book']['count'] = $sections_count + $chapters_count + $lessons_count;
     return response()->json(['status' => 200, 'data' => $results, 'message' => '']);

    }

    function full_book_search(Request $request) {
        $currentPage = request()->input('page', 1);
        $perPage = 10;
        $keyword = $request->keyword;
        $sections = Section::with(['book'])
        ->select('id','title','slug','book_id')
        ->when($keyword,function($q)use($keyword){
            $q->where('title', 'LIKE', '%' . $keyword . '%')
            ->orWhere('desc', 'LIKE', '%' . $keyword . '%');
        })->get();

        $chapters = Chapter::with(['book','section'])
        ->select('id','title','slug','book_id','section_id')
        ->when($keyword,function($q)use($keyword){
            $q->where('title', 'LIKE', '%' . $keyword . '%')
            ->orWhere('desc', 'LIKE', '%' . $keyword . '%');
        })->get();


        $lessons = Lesson::with(['chapter','section','book'])
        ->select('id','title','slug','chapter_id','section_id','book_id')
        ->when($keyword,function($q)use($keyword){
            $q->where('title', 'LIKE', '%' . $keyword . '%')
            ->orWhere('desc', 'LIKE', '%' . $keyword . '%');
        })->get();
        $results = $lessons->merge($chapters)->merge($lessons);
        $currentResult = $results->slice(($currentPage - 1) * $perPage, $perPage);
        // $paginator = new LengthAwarePaginator(
        //     $currentResult,
        //     $results->count(),
        //     $perPage,
        //     $currentPage
        // );
        // dd($currentResult);
        $paginator = new Paginator(BookSearchResource::collection($currentResult), count($results), $currentPage,[
            'path' => Paginator::resolveCurrentPath(),
             ] );
        return $paginator;
    }
}
