<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     * Facker data
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->department; // faker: The current Faker instance, department from faker-provider-collection
        return [
            "name"=> $name,
            "slug"=> Str::slug($name),
            "description"=> $this->faker->paragraph,
            "image"=> $this->faker->imageUrl(300, 300),
        ];
    }
}
