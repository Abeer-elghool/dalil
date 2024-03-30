<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use App\Traits\Files\UploadFiles;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable, UploadFiles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [
        'id',
        'created_at',
        'updated_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];


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

    public function receivesBroadcastNotificationsOn()
    {
        return 'dalil-user-notification.' . $this->id;
    }

    public function setImageAttribute($value)
    {
        if ($value && is_file($value)) {
            if (isset($this->attributes['image']) && $this->attributes['image']) {
                if (file_exists(storage_path('app/public/files/user/' . $this->attributes['image']))) {
                    \File::delete(storage_path('app/public/files/user/' . $this->attributes['image']));
                }
            }
            $file = $this->uploadFile($value, 'user');
            $this->attributes['image'] = $file;
        } else {
            $this->attributes['image'] = $value;
        }
    }

    public function getImageAttribute()
    {
        $image = isset($this->attributes['image']) && $this->attributes['image'] ? 'storage/files/user/' . $this->attributes['image'] : null;
        if(!$image){
            $image =  $this->attributes['gender'] === 'male' ? 'images/placeholders/man.png' : 'images/placeholders/woman.png';
        }
        return asset($image);
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id', 'id');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id', 'id');
    }

    public function area()
    {
        return $this->belongsTo(Area::class, 'area_id', 'id');
    }

    public function specialty()
    {
        return $this->belongsTo(Specialty::class, 'specialty_id', 'id');
    }

    public function education()
    {
        return $this->belongsTo(Education::class, 'education_id', 'id');
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function views()
    {
        return $this->hasMany(View::class);
    }

    public function user_mcqs()
    {
        return $this->hasMany(UserMcq::class, 'user_id', 'id');
    }

    public function saved_parts()
    {
        return $this->hasMany(SavedPart::class, 'user_id', 'id');
    }

    public function search_histories()
    {
        return $this->hasMany(SearchHistory::class, 'user_id', 'id');
    }
}
