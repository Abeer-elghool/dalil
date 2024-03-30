<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Files\UploadFiles;

class Setting extends Model
{
    use HasFactory, UploadFiles;
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function scopeActive($q)
    {
        $q->where('active', 1);
    }

    public function setFileAttribute($value)
    {
        if ($value && is_file($value)) {
            if (isset($this->attributes['file']) && $this->attributes['file']) {
                if (file_exists(storage_path('app/public/files/setting/' . $this->attributes['file']))) {
                    \File::delete(storage_path('app/public/files/setting/' . $this->attributes['file']));
                }
            }
            $file = $this->uploadFile($value, 'setting');
            $this->attributes['file'] = $file;
        } else {
            $this->attributes['file'] = $value;
        }
    }

    public function getFileAttribute()
    {
        $image = isset($this->attributes['file']) && $this->attributes['file'] ? 'storage/files/setting/' . $this->attributes['file'] : 'images/placeholders/img.jpg';
        return asset($image);
    }
}
