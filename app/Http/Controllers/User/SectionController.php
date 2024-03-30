<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Section;
use Illuminate\Http\Request;
use App\Models\Book;
use App\Http\Resources\User\Section\SectionResource;
use App\Services\ViewService;

class SectionController extends Controller
{

    public function sections_by_book_slug(Request $request, $book_slug)
    {
        $book = Book::where('slug', $book_slug)->firstOrFail();
        $sections = Section::when($request->search, fn ($q) => $q->where('title', 'like', '%' . $request->search . '%'))->where('book_id', $book->id)->get();
        return SectionResource::collection($sections)->additional(['status' => 200, 'messages' => '']);
    }

    public function sections_by_slug($slug)
    {
        $section = Section::where('slug', $slug)->firstOrFail();
        ViewService::view($section->id, $section, 'App\\Models\\Section');
        return SectionResource::make($section)->additional(['status' => 200, 'messages' => '']);
    }
}
