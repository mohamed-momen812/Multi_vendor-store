<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Store>
 */
class StoreFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->words(3, true);
        return [
            "name"=> $name,
            "slug"=> Str::slug($name),
            "description"=> $this->faker->paragraph,
            "logo_image"=> $this->faker->imageUrl(300, 300),
            "cover_image"=> $this->faker->imageUrl(800, 600),
        ];
    }
}
