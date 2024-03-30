<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class City extends Model
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

    public static function allJSON()
    {
        $route = dirname(dirname(__FILE__)) . '/Helpers/Cities/cities.json';
        return json_decode(file_get_contents($route), true);
    }
}
