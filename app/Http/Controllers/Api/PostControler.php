<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PostControler extends Controller
{
    /**
     * function index
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        return response()->json(
            Post::published()->orderBy('id', 'asc')->paginate()
        );
    }

    /**
     * function store
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'title' => 'required|string|min:2',
            'slug' => 'required|string|min:2|unique:App\Models\Post,slug',
            'content.*' => 'required|string|min:2',
            'tags' => 'required|string|min:2',
            'status' => 'required|integer|in:' . implode(',', [
                Post::STATUS_DRAFT,
                Post::STATUS_PUBLISHED,
                Post::STATUS_UNPUBLISHED,
            ]),
            'category_id' => 'nullable|integer|exists:App\Models\Category,id',
        ]);

        $postData = $request->only([
            'title',
            'slug',
            'content',
            'tags',
            'status',
        ]);

        $postData['tags'] = \explode(',', $postData['tags']);

        $post = Post::create($postData);

        return response()->json([
            'created' => $post ? __('Success') : __('Error'),
            'post' => $post,
        ], $post ? 201 : 422);
    }

    /**
     * function show
     *
     * @param Request $request
     * @param string $slug
     *
     * @return JsonResponse
     */
    public function show(Request $request, string $slug): JsonResponse
    {
        return response()->json(Post::whereSlug($slug)->firstOrFail());
    }

    /**
     * function update
     *
     * @param Request $request
     * @param string $slug
     *
     * @return JsonResponse
     */
    public function update(Request $request, string $slug): JsonResponse
    {
        $request->validate([
            'title' => 'required|string|min:2',
            'content.*' => 'required|string|min:2',
            'tags' => 'required|string|min:2',
            'status' => 'required|integer|in:' . implode(',', [
                Post::STATUS_DRAFT,
                Post::STATUS_PUBLISHED,
                Post::STATUS_UNPUBLISHED,
            ]),
            'category_id' => 'nullable|integer|exists:App\Models\Category,id',
        ]);

        $post = Post::whereSlug($slug)->firstOrFail();

        $postData = $request->only([
            'title',
            'content',
            'tags',
            'status',
        ]);

        $postData['tags'] = \explode(',', $postData['tags']);

        $updatedPost = $post->update($postData);

        return response()->json([
            'updated' => (bool) $updatedPost,
            'message' => $updatedPost ? __('Success') : __('Error'),
            'post' => $post,
        ], $updatedPost ? 200 : 422);
    }

    /**
     * function destroy
     *
     * @param Request $request
     * @param string $slug
     *
     * @return JsonResponse
     */
    public function destroy(Request $request, string $slug): JsonResponse
    {
        $post = Post::whereSlug($slug)->firstOrFail();

        $deleted = $post->delete();

        return response()->json([
            'deleted' => (bool) $deleted,
            'message' => $deleted ? __('Success') : __('Error'),
            'post' => $post,
        ], $deleted ? 200 : 422);
    }
}
