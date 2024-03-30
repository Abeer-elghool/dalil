<?php

namespace App\Services;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentService
{
    public function createComment(Request $request, $commentable)
    {
        $comment = new Comment([
            'body' => $request->input('body'),
        ]);
        $comment->user()->associate(auth('api')->user());
        $commentable->comments()->save($comment);
        return $comment;
    }
}
