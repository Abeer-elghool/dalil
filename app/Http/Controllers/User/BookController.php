<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Book;
use App\Http\Resources\User\Book\BookResource;
use App\Services\ViewService;

class BookController extends Controller
{
    public function books(Request $request)
    {
        $books = Book::when($request->search, fn ($q) => $q->where('title', 'like', '%' . $request->search . '%'))->get();
        return BookResource::collection($books)->additional(['status' => 200, 'messages' => '']);
    }

    public function book_by_slug($slug)
    {
        $book = Book::where('slug', $slug)->firstOrFail();
        ViewService::view($book->id, $book, 'App\\Models\\Book');
        return BookResource::make($book)->additional(['status' => 200, 'messages' => '']);
    }
    
    public function first_book()
    {
        $book = Book::first();
        ViewService::view($book->id, $book, 'App\\Models\\Book');
        return BookResource::make($book)->additional(['status' => 200, 'messages' => '']);
    }
    
    public function second_book()
    {
        $book = Book::latest()->first();
        return BookResource::make($book)->additional(['status' => 200, 'messages' => '']);
    }
}
