<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\User\City\CityResource;
use App\Http\Resources\User\Country\CountryResource;
use App\Http\Resources\User\Area\AreaResource;
use App\Http\Resources\User\Chapter\ChapterResource;
use App\Http\Resources\User\Education\EducationResource;
use App\Http\Resources\User\Lesson\LessonResource;
use App\Http\Resources\User\Section\SectionResource;
use App\Http\Resources\User\Specialty\SpecialtyResource;
use App\Jobs\CreateView;
use App\Models\Area;
use App\Models\Book;
use App\Models\Section;
use App\Models\Chapter;
use App\Models\Lesson;
use App\Models\City;
use App\Models\Country;
use App\Models\Education;
use App\Models\Specialty;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Services\ViewService;
use Illuminate\Support\Facades\Cache;

class HelperController extends Controller
{
    function countries(): AnonymousResourceCollection
    {
        return CountryResource::collection(Country::get())->additional(['status' => 200, 'message' => '']);
    }

    function cities_by_country_id($id): AnonymousResourceCollection
    {
        return CityResource::collection(City::where('country_id', $id)->get())->additional(['status' => 200, 'message' => '']);
    }

    function areas_by_city_id($id): AnonymousResourceCollection
    {
        return AreaResource::collection(Area::where('city_id', $id)->latest()->get())->additional(['status' => 200, 'message' => '']);
    }

    function education(): AnonymousResourceCollection
    {
        return EducationResource::collection(Education::latest()->get())->additional(['status' => 200, 'message' => '']);
    }

    function specialty(): AnonymousResourceCollection
    {
        return SpecialtyResource::collection(Specialty::latest()->get())->additional(['status' => 200, 'message' => '']);
    }

    public function sections_by_book_slug($book_slug)
    {
        if (Cache::has("/book/$book_slug/sections")) {
            $section = Cache::get("/book/$book_slug/sections");
        } else {
            $book = Book::where('slug', $book_slug)->firstOrFail();
            $section = Section::where(['book_id' => $book->id])->select('id', 'title', 'slug', 'desc')->get();
            Cache::forever("/book/$book_slug/sections", $section);
        }
        return response()->json(['status' => 200, 'message' => '', 'data' => $section]);
    }

    public function chapters_by_section_slug($book_slug, $section_slug)
    {
        if (Cache::has("/book/$book_slug/section/$section_slug/chapters")) {
            $chapter = Cache::get("/book/$book_slug/section/$section_slug/chapters");
        } else {
            $book = Book::where('slug', $book_slug)->firstOrFail();
            $section = Section::where(['book_id' => $book->id, 'slug' => $section_slug])->firstOrFail();
            $chapter = Chapter::where(['section_id' => $section->id])->select('id', 'title', 'slug', 'desc')->get();
            Cache::forever("/book/$book_slug/section/$section_slug/chapters", $chapter);
        }
        return response()->json(['status' => 200, 'message' => '', 'data' => $chapter]);
    }

    public function lessons_by_chapter_slug($book_slug, $section_slug, $chapter_slug)
    {
        if (Cache::has("/book/$book_slug/section/$section_slug/chapter/$chapter_slug/lessons")) {
            $lesson_collection = Cache::get("/book/$book_slug/section/$section_slug/chapter/$chapter_slug/lessons");
        } else {
            $book = Book::where('slug', $book_slug)->select('id')->firstOrFail();
            $section = Section::where(['book_id' => $book->id, 'slug' => $section_slug])->select('id')->firstOrFail();
            $chapter = Chapter::where(['section_id' => $section->id, 'slug' => $chapter_slug])->select('id')->firstOrFail();
            $lesson = Lesson::with(['likes', 'comments','book','chapter','section'])->where(['chapter_id' => $chapter->id])->select('id', 'title', 'slug', 'desc', 'file', 'views_count', 'book_id','section_id','chapter_id','created_at')->get();
            $lesson_collection = LessonResource::collection($lesson);
            Cache::forever("/book/$book_slug/section/$section_slug/chapter/$chapter_slug/lessons", $lesson_collection);
        }
        return response()->json(['status' => 200, 'message' => '', 'data' => $lesson_collection]);
    }

    public function single_lesson_by_lesson_slug($book_slug, $section_slug, $chapter_slug, $lesson_slug)
    {
        $book = Book::where('slug', $book_slug)->firstOrFail();
        $section = Section::where(['book_id' => $book->id, 'slug' => $section_slug])->firstOrFail();
        $chapter = Chapter::where(['section_id' => $section->id, 'slug' => $chapter_slug])->firstOrFail();
        $lesson = Lesson::with(['likes', 'comments','book','chapter','section'])->where(['chapter_id' => $chapter->id, 'slug' => $lesson_slug])->select('id', 'title', 'slug', 'views_count', 'desc', 'file','book_id','section_id','chapter_id', 'created_at')->firstOrFail();

        dispatch(new CreateView($lesson, 'App\\Models\\Lesson'));

        $next_lesson = Lesson::where('chapter_id', $chapter->id)->where('id', '>', $lesson->id)->select('id', 'slug')->first();
        $last_lesson = Lesson::where('chapter_id', $chapter->id)->where('id', '<', $lesson->id)->select('id', 'slug')->first();
        return LessonResource::make($lesson)->additional(['status' => 200, 'messages' => '', 'next_lesson' => $next_lesson, 'last_lesson' => $last_lesson]);
    }
}
