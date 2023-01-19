<?php

namespace Tests\Feature\Web;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;
use App\Models\Post;

class PostWebTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     *
     * @return void
     */
    public function postIndex()
    {
        Post::factory([
            'title' => 'My first post',
            'status' => Post::STATUS_PUBLISHED,
        ])->createOne();

        $postStatuses = [
            Post::STATUS_DRAFT,
            Post::STATUS_PUBLISHED,
            Post::STATUS_UNPUBLISHED,
        ];

        foreach ($postStatuses as $statusKey => $statusValue) {
            Post::factory(2)->create([
                'title' => \fake()->words(rand(3, 8), true),
                'status' => $statusKey,
            ]);
        }

        $response = $this->get(
            route('posts.index')
        );

        $response->assertSee('<h1>Posts</h1>', $escaped = false);
        $response->assertSee('<td>My first post</td>', $escaped = false);
    }
}
