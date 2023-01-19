<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => \ucfirst(\fake()->words(rand(1, 3), true)),
            'slug' =>  fn ($attributes) => \Str::slug($attributes['name']),
            'parent_id' => \null,
        ];
    }
}
