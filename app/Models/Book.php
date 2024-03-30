<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Traits\Files\UploadFiles;

class Book extends Model
{
    use HasFactory,UploadFiles;
    protected $guarded = ['id','created_at','updated_at'];

    public function setTitleAttribute($value)
    {
        $this->attributes['title']  = $value;
        $this->attributes['slug'] = Str::slug($value,'-');
    }


    public function sections()
    {
        return $this->hasMany(Section::class, 'book_id', 'id');
    }


    public function chapters()
    {
        return $this->hasMany(Chapter::class, 'book_id', 'id');
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class, 'book_id', 'id');
    }

    public function views()
    {
        return $this->morphMany(View::class, 'viewable');
    }

    public function setFileAttribute($value)
    {
        if ($value && is_file($value)) {
            if (isset($this->attributes['file']) && $this->attributes['file']) {
                if (file_exists(storage_path('app/public/files/book/'. $this->attributes['file']))) {
                    \File::delete(storage_path('app/public/files/book/'. $this->attributes['file']));
                }
            }
            $file = $this->uploadFile($value,'book');
            $this->attributes['file'] = $file;
        }else{
            $this->attributes['file'] = $value;
        }
    }

    public function getFileAttribute()
    {
        $image = isset($this->attributes['file']) && $this->attributes['file'] ? 'storage/files/book/'.$this->attributes['file'] : 'images/placeholders/img.jpg';
        return asset($image);
    }

    public function downloads()
    {
        return $this->morphMany(Download::class, 'downloadable');
    }

}
