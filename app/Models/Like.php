<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    use HasFactory;
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function likeable()
    {
        return $this->morphTo();
    }

    public function section()
    {
        return $this->morphTo('likeable', 'likeable_type', 'likeable_id')->where('likeable_type', Section::class);
    }

    public function chapter()
    {
        return $this->morphTo('likeable', 'likeable_type', 'likeable_id')->where('likeable_type', Chapter::class);
    }

    public function lesson()
    {
        return $this->morphTo('likeable', 'likeable_type', 'likeable_id')->where('likeable_type', Lesson::class);
    }
}
