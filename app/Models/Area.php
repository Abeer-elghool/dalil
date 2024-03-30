<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Area extends Model
{
    use HasFactory;
    protected $guarded = ['id','created_at','updated_at'];
    public function setTitleAttribute($value)
    {
        $this->attributes['title']  = $value;
        $this->attributes['slug'] = Str::slug($value,'-');
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id', 'id');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id', 'id');
    }
}
