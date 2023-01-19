<?php

namespace Database\Factories;

use Tests\Helpers\TestStr;
use Illuminate\Support\Str;
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
            'name' => fn ($attr) => TestStr::addRandom(),
            'slug' =>  fn ($attr) => Str::slug('factory ' . $attr['name']),
            'parent_id' => \null,
        ];
    }
}
