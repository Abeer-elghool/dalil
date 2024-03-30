<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Review\{ReviewRequest, UpdateReviewRequest};
use App\Http\Resources\Admin\Review\{ReviewResource, ReviewShowResource};
use App\Models\Review;
use Illuminate\Support\Facades\DB;
use Throwable;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::latest()->paginate(100);
        return ReviewResource::collection($reviews)->additional(['status' => 200, 'message' => '']);
    }

    public function show($uuid)
    {
        $review = Review::where('uuid', $uuid)->firstOrFail();
        return ReviewShowResource::make($review)->additional(['status' => 200, 'message' => '']);
    }

    public function store(ReviewRequest $request)
    {
        DB::beginTransaction();
        try {
            $review = Review::create($request->validated() + ['admin_id' => auth('admin')->id()]);
            DB::commit();
            return ReviewResource::make($review)->additional(['status' => 200, 'message' => 'Review created successfully.']);
        } catch (Throwable $e) {
            DB::rollBack();
            return response()->json(['status' => 500, 'data' => null, 'message' => 'create failed.'], 500);
        }
    }

    public function update(UpdateReviewRequest $request, Review $review)
    {
        DB::beginTransaction();
        try {
            $review->update($request->validated());
            DB::commit();
            return ReviewResource::make($review)->additional(['status' => 200, 'message' => 'Review updated successfully.']);
        } catch (Throwable $e) {
            DB::rollBack();
            return response()->json(['status' => 500, 'data' => null, 'message' => 'update failed.'], 500);
        }
    }

    public function destroy(Review $review)
    {
        $review->delete();
        return response()->json(['message' => 'Review deleted successfully']);
    }
}
