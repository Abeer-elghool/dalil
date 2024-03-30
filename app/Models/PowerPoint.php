<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Files\UploadFiles;
use Illuminate\Support\Str;

class PowerPoint extends Model
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
                if (file_exists(storage_path('app/public/files/powerpoint/' . $this->attributes['file']))) {
                    \File::delete(storage_path('app/public/files/powerpoint/' . $this->attributes['file']));
                }
            }
            $file = $this->uploadFile($value, 'powerpoint');
            $this->attributes['file'] = $file;
        } else {
            $this->attributes['file'] = $value;
        }
    }

    public function getFileAttribute()
    {
        $image = isset($this->attributes['file']) && $this->attributes['file'] ? 'storage/files/powerpoint/' . $this->attributes['file'] : 'images/placeholders/img.jpg';
        return asset($image);
    }

    public function setPdfFileAttribute($value)
    {
        if ($value && is_file($value)) {
            if (isset($this->attributes['pdf_file']) && $this->attributes['pdf_file']) {
                if (file_exists(storage_path('app/public/files/powerpoint/' . $this->attributes['pdf_file']))) {
                    \File::delete(storage_path('app/public/files/powerpoint/' . $this->attributes['pdf_file']));
                }
            }
            $file = $this->uploadFile($value, 'powerpoint');
            $this->attributes['pdf_file'] = $file;
        } else {
            $this->attributes['pdf_file'] = $value;
        }
    }

    public function getPdfFileAttribute()
    {
        $image = isset($this->attributes['pdf_file']) && $this->attributes['pdf_file'] ? 'storage/files/powerpoint/' . $this->attributes['pdf_file'] : 'images/placeholders/img.jpg';
        return asset($image);
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

    public function downloads()
    {
        return $this->morphMany(Download::class, 'downloadable');
    }
}
