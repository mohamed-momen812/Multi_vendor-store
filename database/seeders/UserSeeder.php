<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds maually without factory
     */
    public function run(): void
    {
        User::create([
            "name" => "Mohamed Mo'men",
            "email" => "mohamedmomen@gmail.com",
            "password" => Hash::make('password'),
            'phone_number' => '01026828900',
        ]);


        // use quety builder when haven't model to the table
        DB::table('users')->insert([
            "name" => "Ahmed Mo'men",
            "email" => "Ahmedmomen@gmail.com",
            "password" => Hash::make('password'),
            'phone_number' => '010268289001',
        ]);
    }
}
