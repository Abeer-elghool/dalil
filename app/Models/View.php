<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class View extends Model
{
    use HasFactory;
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function viewable()
    {
        return $this->morphTo();
    }

    public function article()
    {
        return $this->morphTo('viewable', 'viewable_type', 'viewable_id')->where('viewable_type', Article::class);
    }

    public function book()
    {
        return $this->morphTo('viewable', 'viewable_type', 'viewable_id')->where('viewable_type', Book::class);
    }

    public function section()
    {
        return $this->morphTo('viewable', 'viewable_type', 'viewable_id')->where('viewable_type', Section::class);
    }

    public function chapter()
    {
        return $this->morphTo('viewable', 'viewable_type', 'viewable_id')->where('viewable_type', Chapter::class);
    }

    public function lesson()
    {
        return $this->morphTo('viewable', 'viewable_type', 'viewable_id')->where('viewable_type', Lesson::class);
    }
}
