<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Country extends Model
{
    use HasFactory;
    protected $guarded = ['id','created_at','updated_at'];
    public function setTitleAttribute($value)
    {
        $this->attributes['title']  = $value;
        $this->attributes['slug'] = Str::slug($value,'-');
    }

    public static function allJSON()
    {
        $route = dirname(dirname(__FILE__)) . '/Helpers/Countries/countries.json';
        return json_decode(file_get_contents($route), true);
    }
}
