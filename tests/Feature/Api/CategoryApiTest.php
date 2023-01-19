<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\Post;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     *
     * @return void
     */
    public function categoryIndex()
    {
        $name = 'My first category categoryIndex';

        $categoryData = Category::factory([
            'name' => $name,
        ])->createOne();

        Category::factory(2)->create();

        $response = $this->get(
            route('api.categories.index')
        );

        $response->assertStatus(200);
        $response->assertJsonPath('data.0.name', $name);
        $response->assertJsonPath('data.0.slug', Str::slug($name));

        $response->assertJson(
            fn (AssertableJson $json) =>
                $json->hasAll(['data', 'path', 'links', ])->etc()
                ->has('data', 3)
        );
    }

    /**
     *
     * @test
     *
     * @return void
     */
    public function failToStoreCategory(): void
    {
        $categoryData = Category::factory()->make()->toArray();
        unset($categoryData['slug']);

        $response = $this->post(
            route('api.categories.store'),
            $categoryData
        );

        $response->assertStatus(302);

        $response->assertSessionHasErrors([
            'slug' => 'The slug field is required.'
        ]);

        $response = $this->post(
            route('api.categories.store'),
            []
        );

        $response->assertStatus(302);

        $response->assertSessionHasErrors([
            'name' => 'The name field is required.',
            'slug' => 'The slug field is required.',
        ]);
    }

    /**
     *
     * @test
     *
     * @return void
     */
    public function storeCategory(): void
    {
        $categoryData = Category::factory()->make()->toArray();
        $categoryData['tags'] = 'abc,def';
        $categoryData['status'] = Post::STATUS_PUBLISHED;

        $response = $this->post(
            route('api.categories.store'),
            $categoryData
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
    public function getCategory(): void
    {
        $name = 'My first category getCategory';

        $categoryData = Category::factory([
            'name' => $name,
        ])->createOne();

        $response = $this->get(
            route('api.categories.show', $categoryData->slug),
        );

        $response->assertStatus(200);

        $response
            ->assertStatus(200)
            ->assertJson([
                'slug' => $categoryData->slug,
                'name' => $categoryData->name,
            ]);
    }

    /**
     *
     * @test
     *
     * @return void
     */
    public function failToGetCategory(): void
    {
        $response = $this->get(
            route('api.categories.show', 'not-exists'),
        );

        $response->assertStatus(404);
    }

    /**
     *
     * @test
     *
     * @return void
     */
    public function updateCategory(): void
    {
        $name = 'My first category updateCategory';

        $categoryData = Category::factory([
            'name' => $name,
        ])->createOne();

        $newPostData = [
            'name' => 'New name of category',
        ];

        $response = $this->patch(
            route('api.categories.update', $categoryData['slug']),
            $newPostData
        );

        $response
            ->assertStatus(200)
            ->assertJson([
                'updated' => true,
            ])
            ->assertJsonPath('category.name', $newPostData['name']);
    }

    /**
     *
     * @test
     *
     * @return void
     */
    public function deleteCategory(): void
    {
        $categoryData = Category::factory()->createOne();

        $response = $this->get(
            route('api.categories.show', $categoryData->slug),
        );

        $response->assertStatus(200);

        $response = $this->delete(
            route('api.categories.destroy', $categoryData->slug)
        );

        $response
            ->assertStatus(200)
            ->assertJson([
                'deleted' => true,
            ])
            ->assertJsonPath('message', 'Success');
    }
}
