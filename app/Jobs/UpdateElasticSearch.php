<?php

namespace App\Jobs;

use App\Models\Book;
use App\Models\Chapter;
use App\Models\Lesson;
use App\Models\Section;
use App\Services\ElasticsearchService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateElasticSearch implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $elastic = new ElasticsearchService();

        // lessons
        $lessons = Lesson::get();
        $elastic->delete_index('lessons');
        $elastic->checkOrCreateIndex('lessons');
        $lessons->each(function ($lesson) use ($elastic) {
            $data = [];
            $data['id'] = $lesson->id;
            $data['title'] = $lesson->title;
            $data['desc'] = $lesson->desc;
            $data['slug'] = $lesson->slug;
            $data['book_slug'] = $lesson->book->slug;
            $data['section_slug'] = $lesson->section->slug;
            $data['chapter_slug'] = $lesson->chapter->slug;
            $elastic->update_document('lessons', $lesson->id, $data);
        });

        // chapters
        $chapters = Chapter::get();
        $elastic->delete_index('chapters');
        $elastic->checkOrCreateIndex('chapters');
        $chapters->each(function ($chapter) use ($elastic) {
            $data = [];
            $data['id'] = $chapter->id;
            $data['title'] = $chapter->title;
            $data['desc'] = $chapter->desc;
            $data['slug'] = $chapter->slug;
            $data['book_slug'] = $chapter->book->slug;
            $data['section_slug'] = $chapter->section->slug;
            $elastic->update_document('chapters', $chapter->id, $data);
        });

        // sections
        $sections = Section::get();
        $elastic->delete_index('sections');
        $elastic->checkOrCreateIndex('sections');
        $sections->each(function ($chapter) use ($elastic) {
            $data = [];
            $data['id'] = $chapter->id;
            $data['title'] = $chapter->title;
            $data['desc'] = $chapter->desc;
            $data['slug'] = $chapter->slug;
            $data['book_slug'] = $chapter->book->slug;
            $elastic->update_document('sections', $chapter->id, $data);
        });
    }
}
