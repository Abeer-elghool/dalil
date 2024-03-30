<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;
use App\Traits\Files\UploadFiles;

class Question extends Model
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

    public function answers()
    {
        return $this->hasMany(Answer::class, 'question_id', 'id');
    }

    public function mcqQuestions()
    {
        return $this->hasMany(McqQuestion::class, 'question_id', 'id');
    }

    public function mcqs(): BelongsToMany
    {
        return $this->belongsToMany(Mcq::class, 'mcq_question', 'question_id', 'mcq_id');
    }

    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id', 'id');
    }

    public function chapter()
    {
        return $this->belongsTo(Chapter::class, 'chapter_id', 'id');
    }

    public function setFileAttribute($value)
    {
        if ($value && is_file($value)) {
            if (isset($this->attributes['file']) && $this->attributes['file']) {
                if (file_exists(storage_path('app/public/files/question/' . $this->attributes['file']))) {
                    \File::delete(storage_path('app/public/files/question/' . $this->attributes['file']));
                }
            }
            $file = $this->uploadFile($value, 'question');
            $this->attributes['file'] = $file;
        } else {
            $this->attributes['file'] = $value;
        }
    }

    public function getFileAttribute()
    {
        $image = isset($this->attributes['file']) && $this->attributes['file'] ? 'storage/files/question/' . $this->attributes['file'] : 'images/placeholders/img.jpg';
        return asset($image);
    }
}
