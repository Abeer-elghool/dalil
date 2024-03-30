<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Country;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // Get all from the JSON file
        $JSON_countries = Country::allJSON();
        foreach ($JSON_countries as $country) {
            if($country['iso_3166_2'] !='SA'){
                Country::firstOrCreate([
                    'title'   => ((isset($country['name'])) ? $country['name'] : null),
                    'country_code'=>((isset($country['dial_code'])) ? $country['dial_code'] : null),
                    'iso'     => ((isset($country['iso_3166_2'])) ? $country['iso_3166_2'] : null)
                ]);
            }
        }
    }
}
