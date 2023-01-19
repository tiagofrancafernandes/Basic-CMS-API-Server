<?php

namespace Database\Factories;

use App\Models\Post;
use Tests\Helpers\TestStr;
use Illuminate\Support\Str;
use Database\Seeders\CategorySeeder;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'title' => fn ($attr) => TestStr::addRandom(),
            'slug' =>  fn ($attr) => Str::slug('factory ' . $attr['title']),
            'content' => \fake()->paragraphs(4),
            'tags' => (rand() % 2) ? \fake()->words(rand(1, 8)) : [],
            'published_at' => now(),
            'status' => \Arr::random(
                \array_keys(
                    [
                        Post::STATUS_DRAFT,
                        Post::STATUS_PUBLISHED,
                        Post::STATUS_UNPUBLISHED,
                    ]
                )
            ),
            'category_id' => (
                (rand() % 2) ||
                (rand() % 2) ||
                (rand() % 3)
            ) ? CategorySeeder::randomOrCreate() : \null,
        ];
    }
}
