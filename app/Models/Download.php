<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Download extends Model
{
    use HasFactory;
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function downloadable()
    {
        return $this->morphTo();
    }

    public function section()
    {
        return $this->morphTo('downloadable', 'downloadable_type', 'downloadable_id')->where('downloadable_type', Section::class);
    }

    public function chapter()
    {
        return $this->morphTo('downloadable', 'downloadable_type', 'downloadable_id')->where('downloadable_type', Chapter::class);
    }

    public function lesson()
    {
        return $this->morphTo('downloadable', 'downloadable_type', 'downloadable_id')->where('downloadable_type', Lesson::class);
    }

    public function power_point()
    {
        return $this->morphTo('downloadable', 'downloadable_type', 'downloadable_id')->where('downloadable_type', PowerPoint::class);
    }

    public function lecture()
    {
        return $this->morphTo('downloadable', 'downloadable_type', 'downloadable_id')->where('downloadable_type', Lecture::class);
    }

    public function protocol()
    {
        return $this->morphTo('downloadable', 'downloadable_type', 'downloadable_id')->where('downloadable_type', Protocol::class);
    }
}
