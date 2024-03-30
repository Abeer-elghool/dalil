<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Book};
use App\Http\Requests\Admin\Book\{BookRequest, UpdateBookRequest};
use App\Http\Resources\Admin\Book\BookResource;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $books = Book::paginate(100);
        return (BookResource::collection($books))->additional(['status'=>200,'message'=>'request done']);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BookRequest $request)
    {
        //
        $book = Book::create($request->validated());
        return (new BookResource($book))->additional(['status'=>200,'message'=>'request done']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $slug)
    {
        $book = Book::where('slug',$slug)->firstOrFail();
        return (new BookResource($book))->additional(['status'=>200,'message'=>'request done']);

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBookRequest $request, string $id)
    {
        $book  = Book::findOrFail($id);
        $book->update($request->validated());
        return (new BookResource($book))->additional(['status'=>200,'message'=>'request done']);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        if($book->delete()){
            return response()->json(['data'=>null,'message'=>'Book deleted successfully','status'=>200],200);
        }
        return response()->json(['data'=>null,'message'=>'please contact support','status'=>200],200);

    }
}
