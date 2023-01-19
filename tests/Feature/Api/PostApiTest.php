<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\Post;
use Tests\Helpers\TestStr;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostApiTest extends TestCase
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

        Post::factory()->createOne([
            'title' => $title,
            'status' => Post::STATUS_PUBLISHED,
        ]);

        $postStatuses = [
            Post::STATUS_DRAFT,
            Post::STATUS_PUBLISHED,
            Post::STATUS_UNPUBLISHED,
        ];

        foreach ($postStatuses as $statusKey => $statusValue) {
            Post::factory(2)->create([
                'title' => fn () => TestStr::addRandom('postIndex'),
                'status' => $statusKey,
                'tags' => ['food', 'delivery']
            ]);
        }

        $response = $this->get(
            route('api.posts.index')
        );

        $response->assertStatus(200);

        $response->assertJson(
            fn (AssertableJson $json) =>
                $json->hasAll(['data', 'path', 'links', ])->etc()
                ->has('data', 3)
                ->has(
                    'data.0',
                    fn (AssertableJson $json) => $json->where('id', 1)
                    ->where('title', $title)
                    ->where('status', 1)
                    ->hasAll(['tags'])->etc()
                    // ->where('author', fn ($author) => str($author)->is('Victoria'))
                )
                ->has('data', fn (AssertableJson $json) => $json->each(
                    fn ($item) => $item->where('status', 1)
                    ->etc()
                ))
        );
    }

    /**
     *
     * @test
     *
     * @return void
     */
    public function failToStorePost(): void
    {
        $postData = Post::factory()->make()->toArray();
        unset($postData['status']);

        $response = $this->post(
            route('api.posts.store'),
            $postData
        );

        $response->assertStatus(302);

        $response->assertSessionHasErrors(['status']);
        $response->assertSessionHasErrors([
            'status' => 'The status field is required.'
        ]);

        $response = $this->post(
            route('api.posts.store'),
            []
        );

        $response->assertStatus(302);

        $response->assertSessionHasErrors([
            'title' => 'The title field is required.',
            'slug' => 'The slug field is required.',
            'status' => 'The status field is required.',
            'tags' => 'The tags field is required.',
            // 'content' => 'The content field is required.',
        ]);
    }

    /**
     *
     * @test
     *
     * @return void
     */
    public function storePost(): void
    {
        $postData = Post::factory()->make()->toArray();
        $postData['tags'] = 'abc,def';
        $postData['status'] = Post::STATUS_PUBLISHED;

        $response = $this->post(
            route('api.posts.store'),
            $postData
        );

        $response
            ->assertStatus(201)
            ->assertJson([
                'created' => true,
            ]);
    }

    /**
     *
     * @test
     *
     * @return void
     */
    public function getPost(): void
    {
        $postData = Post::factory()->make()->toArray();
        $postData['tags'] = 'abc,def';
        $postData['status'] = Post::STATUS_PUBLISHED;

        $response = $this->postJson(
            route('api.posts.store'),
            $postData
        );

        $response->assertStatus(201);

        $response = $this->get(
            route('api.posts.show', $postData['slug']),
        );

        $response
            ->assertStatus(200)
            ->assertJson([
                'slug' => $postData['slug'],
                'title' => $postData['title'],
            ]);
    }

    /**
     *
     * @test
     *
     * @return void
     */
    public function getPostFail(): void
    {
        $response = $this->get(
            route('api.posts.show', 'not-exists'),
        );

        $response->assertStatus(404);
    }

    /**
     *
     * @test
     *
     * @return void
     */
    public function updatePost(): void
    {
        $postData = Post::factory()->createOne([
            'status' => Post::STATUS_PUBLISHED,
        ]);

        $newPostData = [
            'title' => 'New title of post',
            'status' => Post::STATUS_PUBLISHED,
        ];

        $response = $this->patch(
            route('api.posts.update', $postData->slug),
            $newPostData
        );

        $response
            ->assertStatus(200)
            ->assertJson([
                'updated' => true,
            ])
            ->assertJsonPath('post.title', $newPostData['title']);
    }

    /**
     *
     * @test
     *
     * @return void
     */
    public function deletePost(): void
    {
        $postData = Post::factory()->createOne();

        $response = $this->get(
            route('api.posts.show', $postData->slug),
        );

        $response->assertStatus(200);

        $response = $this->delete(
            route('api.posts.destroy', $postData->slug)
        );

        $response
            ->assertStatus(200)
            ->assertJson([
                'deleted' => true,
            ])
            ->assertJsonPath('message', 'Success');
    }
}
