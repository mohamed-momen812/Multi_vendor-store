<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Category;
use App\Models\Product;
use App\Models\Store;
use App\Models\User;
use Database\Factories\AdminFactory;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database. with factory
     */
    public function run(): void
    {


        Admin::factory(3)->create();
        // Store::factory(5)->create(); // created in factory
        // User::factory(10)->create();
        // Category::factory(10)->create();
        // Product::factory(20)->create();

        // $this->call(UserSeeder::class);
    }
}
