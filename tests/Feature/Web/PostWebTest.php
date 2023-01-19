<?php

namespace Tests\Feature\Web;

use Tests\TestCase;
use App\Models\Post;
use Tests\Helpers\TestStr;
use Illuminate\Foundation\Testing\RefreshDatabase;

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
        $title = TestStr::addRandom('My first post');

        Post::factory([
            'title' => $title,
            'status' => Post::STATUS_PUBLISHED,
        ])->createOne();

        $postStatuses = [
            Post::STATUS_DRAFT,
            Post::STATUS_PUBLISHED,
            Post::STATUS_UNPUBLISHED,
        ];

        foreach ($postStatuses as $statusKey => $statusValue) {
            Post::factory(2)->create([
                'status' => $statusKey,
            ]);
        }

        $response = $this->get(
            route('posts.index')
        );

        $response->assertSee('<h1>Posts</h1>', $escaped = false);
        $response->assertSee("<td>{$title}</td>", $escaped = false);
    }
}
