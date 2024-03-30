<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;
use App\Http\Resources\User\Review\ReviewResource;
use App\Services\ViewService;

class ReviewController extends Controller
{
    public function reviews(Request $request)
    {
        $reviews = Review::active()->when($request->search, fn ($q) => $q->where('title', 'like', '%' . $request->search . '%'))->latest()->get();
        return ReviewResource::collection($reviews)->additional(['status' => 200, 'messages' => '']);
    }

    public function review_by_slug($slug)
    {
        $review = Review::active()->where('slug', $slug)->latest()->firstOrFail();
        return ReviewResource::make($review)->additional(['status' => 200, 'messages' => '']);
    }
}
