<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Answer;
use App\Models\Book;
use App\Models\Chapter;
use App\Models\Lesson;
use App\Models\Question;
use App\Models\Section;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\GeneralPhoto\GeneralPhotoRequest;
use App\Http\Resources\Admin\GeneralPhoto\GeneralPhotoResource;
use App\Models\GeneralPhoto;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Http\Resources\Admin\City\CityResource;
use App\Http\Resources\Admin\Country\CountryResource;
use App\Http\Resources\Admin\Area\AreaResource;
use App\Http\Resources\Admin\Education\EducationResource;
use App\Http\Resources\Admin\Specialty\SpecialtyResource;
use App\Http\Resources\User\Chapter\ChapterResource;
use App\Http\Resources\User\Lesson\LessonResource;
use App\Http\Resources\User\Section\SectionResource;
use App\Jobs\UpdateElasticSearch;
use App\Models\Area;
use App\Models\ArticleCategory;
use App\Models\City;
use App\Models\Country;
use App\Models\Education;
use App\Models\ProtocolCategory;
use App\Models\Specialty;

class HelperController extends Controller
{
    public function books(Request $request)
    {
        $books = Book::when($request->search, fn ($q) => $q->where('title', 'like', '%' . $request->search . '%'))->latest()->select('id', 'title', 'slug')->get();
        return response()->json(['status' => 200, 'data' => $books, 'messages' => '']);
    }

    public function sections_by_book_id(Request $request, $book_id)
    {
        $sections = Section::when($request->search, fn ($q) => $q->where('title', 'like', '%' . $request->search . '%'))->where('book_id', $book_id)->select('id', 'title', 'slug')->get();
        return response()->json(['status' => 200, 'data' => $sections, 'messages' => '']);
    }

    public function chapters_by_section_id(Request $request, $section_id)
    {
        $chapters = Chapter::when($request->search, fn ($q) => $q->where('title', 'like', '%' . $request->search . '%'))->where('section_id', $section_id)->select('id', 'title', 'slug')->get();
        return response()->json(['status' => 200, 'data' => $chapters, 'messages' => '']);
    }

    public function lessons_by_chapter_id(Request $request, $chapter_id)
    {
        $lessons = Lesson::when($request->search, fn ($q) => $q->where('title', 'like', '%' . $request->search . '%'))->where('chapter_id', $chapter_id)->select('id', 'title', 'slug')->get();
        return response()->json(['status' => 200, 'data' => $lessons, 'messages' => '']);
    }

    public function questions_by_section_id(Request $request, $section_id)
    {
        $questions = Question::when($request->search, fn ($q) => $q->where('title', 'like', '%' . $request->search . '%'))->where('section_id', $section_id)->select('id', 'title')->get();
        return response()->json(['status' => 200, 'data' => $questions, 'messages' => '']);
    }

    public function answers_by_question_id(Request $request, $question_id)
    {
        $answers = Answer::when($request->search, fn ($q) => $q->where('answer', 'like', '%' . $request->search . '%'))->where('question_id', $question_id)->select('id', 'answer')->get();
        return response()->json(['status' => 200, 'data' => $answers, 'messages' => '']);
    }

    function countries(): AnonymousResourceCollection
    {
        return CountryResource::collection(Country::latest()->get())->additional(['status' => 200, 'message' => '']);
    }

    function cities_by_country_id($id): AnonymousResourceCollection
    {
        return CityResource::collection(City::where('country_id', $id)->latest()->get())->additional(['status' => 200, 'message' => '']);
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

    public function upload_general_photos(GeneralPhotoRequest $request)
    {
        $general_photo = GeneralPhoto::create(['file' => $request->upload]);

        return GeneralPhotoResource::make($general_photo)->additional(['status' => 200, 'message' => '']);
    }

    public function upload_photos(GeneralPhotoRequest $request)
    {
        $general_photo = GeneralPhoto::create(['file' => $request->upload]);

        return response()->json(['url' => $general_photo->file]);
    }

    public function section_by_book_slug($book_slug, $section_slug)
    {
        $book = Book::where('slug', $book_slug)->firstOrFail();
        $section = Section::where(['book_id' => $book->id, 'slug' => $section_slug])->firstOrFail();
        return SectionResource::make($section)->additional(['status' => 200, 'message' => '']);
    }

    public function chapter_by_section_slug($book_slug, $section_slug, $chapter_slug)
    {
        $book = Book::where('slug', $book_slug)->firstOrFail();
        $section = Section::where(['book_id' => $book->id, 'slug' => $section_slug])->firstOrFail();
        $chapter = Chapter::where(['section_id' => $section->id, 'slug' => $chapter_slug])->firstOrFail();
        return ChapterResource::make($chapter)->additional(['status' => 200, 'message' => '']);
    }

    public function lesson_by_chapter_slug($book_slug, $section_slug, $chapter_slug, $lesson_slug)
    {
        $book = Book::where('slug', $book_slug)->firstOrFail();
        $section = Section::where(['book_id' => $book->id, 'slug' => $section_slug])->firstOrFail();
        $chapter = Chapter::where(['section_id' => $section->id, 'slug' => $chapter_slug])->firstOrFail();
        $lesson = Lesson::where(['chapter_id' => $chapter->id, 'slug' => $lesson_slug])->firstOrFail();
        return LessonResource::make($lesson)->additional(['status' => 200, 'message' => '']);
    }

    public function protocol_categories()
    {
        $protocol_categories = ProtocolCategory::latest()->select('id', 'uuid', 'title', 'slug')->get();
        return response()->json(['status' => 200, 'data' => $protocol_categories, 'messages' => '']);
    }

    public function article_categories()
    {
        $article_categories = ArticleCategory::latest()->select('id', 'uuid', 'title', 'slug')->get();
        return response()->json(['status' => 200, 'data' => $article_categories, 'messages' => '']);
    }

    public function update_elastic_search()
    {
        UpdateElasticSearch::dispatch();
        return response()->json(['status' => 200, 'messages' => 'job is running!']);
    }
}
