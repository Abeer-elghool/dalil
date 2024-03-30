<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Files\UploadFiles;

class ContactUs extends Model
{
    use HasFactory, UploadFiles;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function setFileAttribute($value)
    {
        if ($value && is_file($value)) {
            if (isset($this->attributes['file']) && $this->attributes['file']) {
                if (file_exists(storage_path('app/public/files/contact_us/' . $this->attributes['file']))) {
                    \File::delete(storage_path('app/public/files/contact_us/' . $this->attributes['file']));
                }
            }
            $file = $this->uploadFile($value, 'contact_us');
            $this->attributes['file'] = $file;
        } else {
            $this->attributes['file'] = $value;
        }
    }

    public function getFileAttribute()
    {
        $image = isset($this->attributes['file']) && $this->attributes['file'] ? 'storage/files/contact_us/' . $this->attributes['file'] : 'images/placeholders/img.jpg';
        return asset($image);
    }
}
