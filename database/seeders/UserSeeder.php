<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Admin;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Admin::create(
            [
                'name' => 'Abdulelah bin shihah',
                'email' => 'Abdulelah_bin_shihah@hotmail.com',
                'password' => '&aHIzCwVxsRDrZB$',
                'gender' => 'male',
                'active' => 1,
                'email_verified_at' => now(),
                'country_id' => 1,
                'city_id' => 1,
                'area_id' => 1,
                'specialty_id' => 1,
                'education_id' => 1,
            ]
        );
        Admin::create(
            [
                'name' => 'Malmaghaslah',
                'email' => 'Malmaghaslah@gmail.com',
                'password' => 'BghfAJe$3gUT@qVq',
                'gender' => 'male',
                'active' => 1,
                'email_verified_at' => now(),
                'country_id' => 1,
                'city_id' => 1,
                'area_id' => 1,
                'specialty_id' => 1,
                'education_id' => 1,
            ]
        );
        Admin::create(
            [
                'name' => 'Mariam Bagis',
                'email' => 'Mariambagis@gmail.com',
                'password' => '#Kw3(Z6snfy$KLvG',
                'gender' => 'female',
                'active' => 1,
                'email_verified_at' => now(),
                'country_id' => 1,
                'city_id' => 1,
                'area_id' => 1,
                'specialty_id' => 1,
                'education_id' => 1,
            ]
        );
        Admin::create(
            [
                'name' => 'Amal Algarni',
                'email' => 'Algarni.amal@gmail.com',
                'password' => 'QE%xRgVscI7Ue%y@',
                'gender' => 'female',
                'active' => 1,
                'email_verified_at' => now(),
                'country_id' => 1,
                'city_id' => 1,
                'area_id' => 1,
                'specialty_id' => 1,
                'education_id' => 1,
            ]
        );
        Admin::create(
            [
                'name' => 'Hadi Enazy',
                'email' => 'hadi.enazy@gmail.com',
                'password' => 'G+J4N^YcjN6YeT(M',
                'gender' => 'male',
                'active' => 1,
                'email_verified_at' => now(),
                'country_id' => 1,
                'city_id' => 1,
                'area_id' => 1,
                'specialty_id' => 1,
                'education_id' => 1,
            ]
        );
        Admin::create(
            [
                'name' => 'shadawia',
                'email' => 'shadawia04@gmail.com',
                'password' => 'vrdTrRpV^HeSj*Ip',
                'gender' => 'female',
                'active' => 1,
                'email_verified_at' => now(),
                'country_id' => 1,
                'city_id' => 1,
                'area_id' => 1,
                'specialty_id' => 1,
                'education_id' => 1,
            ]
        );
    }
}
