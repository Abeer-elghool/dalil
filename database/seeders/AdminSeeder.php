<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Admin;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Admin::create([
            'name'      => 'Dalel-admin',
            'password'  => '123456789',
            'email'     => 'admin@dalel.com',
            'phone'     => '123456789',
            'active' => true,
            'user_type' => 'super_admin',
        ]);
    }
}
