<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\User\Author\AuthorResource;
use App\Models\Author;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AuthorController extends Controller
{
    function authors(): AnonymousResourceCollection
    {
        return AuthorResource::collection(Author::active()->latest()->get())->additional(['status' => 200, 'message' => '']);
    }

    function author_by_slug($slug): AuthorResource
    {
        return AuthorResource::make(Author::active()->where('slug', $slug)->firstOrFail())->additional(['status' => 200, 'message' => '']);
    }
}
