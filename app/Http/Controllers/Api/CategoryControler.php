<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryControler extends Controller
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
            Category::orderBy('id', 'asc')->paginate()
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
            'name' => 'required|string|min:2',
            'slug' => 'required|string|min:2|unique:App\Models\Category,slug',
            'parent_id' => 'nullable|integer|exists:App\Models\Category,id',
        ]);

        $category = Category::create(
            $request->only([
                'name',
                'slug',
            ])
        );

        return response()->json([
            'created' => $category ? __('Success') : __('Error'),
            'category' => $category,
        ], $category ? 201 : 422);
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
        return response()->json(Category::whereSlug($slug)->firstOrFail());
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
            'name' => 'required|string|min:2',
            'parent_id' => 'nullable|integer|exists:App\Models\Category,id',
        ]);

        $category = Category::whereSlug($slug)->firstOrFail();

        $updatedPost = $category->update(
            $request->only([
                'name',
            ])
        );

        return response()->json([
            'updated' => (bool) $updatedPost,
            'message' => $updatedPost ? __('Success') : __('Error'),
            'category' => $category,
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
        $category = Category::whereSlug($slug)->firstOrFail();

        $deleted = $category->delete();

        return response()->json([
            'deleted' => (bool) $deleted,
            'message' => $deleted ? __('Success') : __('Error'),
            'category' => $category,
        ], $deleted ? 200 : 422);
    }
}
