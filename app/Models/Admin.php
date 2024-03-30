<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use App\Traits\Files\UploadFiles;
use Illuminate\Support\Str;

class Admin extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable, UploadFiles;
    protected $guarded = ['id', 'created_at', 'updated_at'];
    protected $hidden = ['password'];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (!$model->uuid) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function setPasswordAttribute($value)
    {
        if ($value) {
            $this->attributes['password'] = bcrypt($value);
        }
    }

    // For Notification Channel
    public function receivesBroadcastNotificationsOn()
    {
        return 'dalil-admin-notification.' . $this->id;
    }

    public function setImageAttribute($value)
    {
        if ($value && is_file($value)) {
            if (isset($this->attributes['image']) && $this->attributes['image']) {
                if (file_exists(storage_path('app/public/files/admin/' . $this->attributes['image']))) {
                    \File::delete(storage_path('app/public/files/admin/' . $this->attributes['image']));
                }
            }
            $file = $this->uploadFile($value, 'admin');
            $this->attributes['image'] = $file;
        } else {
            $this->attributes['image'] = $value;
        }
    }

    public function getImageAttribute()
    {
        $image = isset($this->attributes['image']) && $this->attributes['image'] ? 'storage/files/admin/' . $this->attributes['image'] : 'images/placeholders/img.jpg';
        return asset($image);
    }

    public function scopeActive($q)
    {
        return $q->where('active', 1);
    }
}
