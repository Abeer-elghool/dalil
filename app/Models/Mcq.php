<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Traits\Files\UploadFiles;
use Illuminate\Support\Str;

class Mcq extends Model
{
    use HasFactory, UploadFiles;
    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (!$model->uuid) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }

    public function setTitleAttribute($value)
    {
        $this->attributes['title']  = $value;
        $this->attributes['slug'] = Str::slug($value, '-');
    }

    public function setFileAttribute($value)
    {
        if ($value && is_file($value)) {
            if (isset($this->attributes['file']) && $this->attributes['file']) {
                if (file_exists(storage_path('app/public/files/mcq/' . $this->attributes['file']))) {
                    \File::delete(storage_path('app/public/files/mcq/' . $this->attributes['file']));
                }
            }
            $file = $this->uploadFile($value, 'mcq');
            $this->attributes['file'] = $file;
        } else {
            $this->attributes['file'] = $value;
        }
    }

    public function getFileAttribute()
    {
        $image = isset($this->attributes['file']) && $this->attributes['file'] ? 'storage/files/mcq/' . $this->attributes['file'] : 'images/placeholders/img.jpg';
        return asset($image);
    }


    public function questions(): BelongsToMany
    {
        return $this->belongsToMany(Question::class, 'mcq_question', 'mcq_id', 'question_id');
    }

    public function mcqQuestions()
    {
        return $this->hasMany(McqQuestion::class, 'mcq_id', 'id');
    }

    public function userMcq()
    {
        return $this->hasMany(UserMcq::class, 'mcq_id', 'id');
    }

    public function chapters(): BelongsToMany
    {
        return $this->belongsToMany(Chapter::class, 'mcq_section', 'mcq_id', 'chapter_id');
    }

    public function sections(): BelongsToMany
    {
        return $this->belongsToMany(Section::class, 'mcq_section', 'mcq_id', 'section_id');
    }

    public function mcqSections()
    {
        return $this->hasMany(McqSection::class, 'mcq_id', 'id');
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    public function views()
    {
        return $this->morphMany(View::class, 'viewable');
    }
}
