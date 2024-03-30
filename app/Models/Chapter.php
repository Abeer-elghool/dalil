<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Traits\Files\UploadFiles;

class Chapter extends Model
{
    use HasFactory,UploadFiles;
    protected $guarded = ['id','created_at','updated_at'];
    public function setTitleAttribute($value)
    {
        $this->attributes['title']  = $value;
        $this->attributes['slug'] = Str::slug($value,'-');
    }


    public function book()
    {
        return $this->belongsTo(Book::class, 'book_id', 'id');
    }

    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id', 'id');
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class, 'book_id', 'id');
    }

    public function questions()
    {
        return $this->hasMany(Question::class, 'chapter_id', 'id');
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

    public function setFileAttribute($value)
    {
        if ($value && is_file($value)) {
            if (isset($this->attributes['file']) && $this->attributes['file']) {
                if (file_exists(storage_path('app/public/files/chapter/'. $this->attributes['file']))) {
                    \File::delete(storage_path('app/public/files/chapter/'. $this->attributes['file']));
                }
            }
            $file = $this->uploadFile($value,'chapter');
            $this->attributes['file'] = $file;
        }else{
            $this->attributes['file'] = $value;
        }
    }

    public function getFileAttribute()
    {
        $image = isset($this->attributes['file']) && $this->attributes['file'] ? 'storage/files/chapter/'.$this->attributes['file'] : 'images/placeholders/img.jpg';
        return asset($image);
    }

}
