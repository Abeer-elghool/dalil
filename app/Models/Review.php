<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Traits\Files\UploadFiles;

class Review extends Model
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

    public function scopeActive($q)
    {
        return $q->where('active', 1);
    }
    
    public function setFileAttribute($value)
    {
        if ($value && is_file($value)) {
            if (isset($this->attributes['file']) && $this->attributes['file']) {
                if (file_exists(storage_path('app/public/files/review/' . $this->attributes['file']))) {
                    \File::delete(storage_path('app/public/files/review/' . $this->attributes['file']));
                }
            }
            $file = $this->uploadFile($value, 'review');
            $this->attributes['file'] = $file;
        } else {
            $this->attributes['file'] = $value;
        }
    }

    public function getFileAttribute()
    {
        $image = isset($this->attributes['file']) && $this->attributes['file'] ? 'storage/files/review/' . $this->attributes['file'] : 'images/placeholders/img.jpg';
        return asset($image);
    }
}
