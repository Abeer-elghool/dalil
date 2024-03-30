<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Photo;
use App\Http\Resources\User\Photo\PhotoResource;
use App\Services\ViewService;

class PhotoController extends Controller
{
    public function photos(Request $request)
    {
        $photos = Photo::when($request->search, fn ($q) => $q->where('title', 'like', '%' . $request->search . '%'))->latest()->get();
        return PhotoResource::collection($photos)->additional(['status' => 200, 'messages' => '']);
    }

    public function photo_by_slug($slug)
    {
        $photo = Photo::where('slug', $slug)->firstOrFail();
        ViewService::view($photo->id, $photo, 'App\\Models\\Photo');
        return PhotoResource::make($photo)->additional(['status' => 200, 'messages' => '']);
    }
}
