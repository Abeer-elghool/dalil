<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Comment\CommentRequest;
use App\Models\Chapter;
use App\Models\Lesson;
use App\Models\Section;
use App\Models\Comment;
use App\Services\CommentService;
use Illuminate\Http\Request;
use App\Http\Resources\User\Comment\CommentResource;

class CommentController extends Controller
{
    protected $commentService;

    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
    }

    public function storeSectionComment(CommentRequest $request, Section $section)
    {
        $comment = $this->commentService->createComment($request, $section);

        return CommentResource::make($comment)->additional(['status' => 200, 'message' => 'done']);
    }

    public function storeChapterComment(CommentRequest $request, Chapter $chapter)
    {
        $comment = $this->commentService->createComment($request, $chapter);

        return CommentResource::make($comment)->additional(['status' => 200, 'message' => 'done']);
    }

    public function storeLessonComment(CommentRequest $request, Lesson $lesson)
    {
        $comment = $this->commentService->createComment($request, $lesson);

        return CommentResource::make($comment)->additional(['status' => 200, 'message' => 'done']);
    }
    
    public function getComments(Request $request)
    {
        $type = 'App\Models\Lesson';
        switch ($request->type){
              case 'section':
                $type = 'App\Models\Section';
                break;
              case 'chapter':
                $type = 'App\Models\Chapter';
                break;
        }
        
        $comment = Comment::where(['commentable_type'=>$type,'commentable_id'=>$request->id,'user_id'=>auth('api')->id()])->get();
        return CommentResource::collection($comment)->additional(['status' => 200, 'message' => 'done']);
    }
}
