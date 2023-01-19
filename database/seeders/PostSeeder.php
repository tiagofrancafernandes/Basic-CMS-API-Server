<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        static::manualRun(30);
    }

    /**
     * manualRun function
     *
     * @param integer|null $countForType
     * @return void
     */
    public static function manualRun(?int $countForType = 10)
    {
        $postStatuses = [
            Post::STATUS_DRAFT,
            Post::STATUS_PUBLISHED,
            Post::STATUS_UNPUBLISHED,
        ];

        foreach (\array_flip($postStatuses) as $statusKey) {
            Post::factory($countForType ?: 10)->create([
                'title' => \fake()->words(rand(3, 8), true),
                'status' => $statusKey,
            ]);
        }
    }
}
