<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Like\LikeRequest;
use App\Models\Like;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function like(LikeRequest $request)
    {
        $userId = auth()->id();
        $likeableType = "App\\Models\\". $request->likeable_type;
        $likeableId = $request->likeable_id;
        $type = $request->type;

        // Check if the user has already liked or disliked this item
        $existingLike = Like::where('user_id', $userId)
            ->where('likeable_type', $likeableType)
            ->where('likeable_id', $likeableId)
            ->first();

        if ($existingLike) {
            // If the user has already liked or disliked this item, update the existing record
            $existingLike->type = $type;
            $existingLike->save();
        } else {
            // If the user has not yet liked or disliked this item, create a new record
            $like = new Like();
            $like->user_id = $userId;
            $like->likeable_type = $likeableType;
            $like->likeable_id = $likeableId;
            $like->type = $type;
            $like->save();
        }

        return response()->json(['status' => 200, 'data' => null, 'message' => $request->type . ' successfully']);
    }
}
