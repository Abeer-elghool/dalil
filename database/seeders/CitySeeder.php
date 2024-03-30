<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\{Country, City};

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $JSON_cities = City::allJSON();
        foreach ($JSON_cities as $city) {
            $country = $city['country'];
            if(in_array($country,['SA','AE','EG','OM','KW','QA','BH','JO','IQ','MA'])){
                $country_id = $country ?  Country::where('iso', $country)->first() :null;
                City::firstOrCreate([
                    'title'   => ((isset($city['name'])) ? $city['name'] : null),
                    'country_id' => $country_id ? $country_id->id : null
                ]);
            }
        }
    }
}
