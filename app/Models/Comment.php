<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function commentable()
    {
        return $this->morphTo();
    }

    public function section()
    {
        return $this->morphTo('commentable', 'commentable_type', 'commentable_id')->where('commentable_type', Section::class);
    }

    public function chapter()
    {
        return $this->morphTo('commentable', 'commentable_type', 'commentable_id')->where('commentable_type', Chapter::class);
    }

    public function lesson()
    {
        return $this->morphTo('commentable', 'commentable_type', 'commentable_id')->where('commentable_type', Lesson::class);
    }
}
