<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class PostControler extends Controller
{
    /**
     * function index
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request)
    {
        return view('posts.index', [
            'posts' => Post::published()->orderBy('id', 'asc')->paginate(10),
        ]);
    }
}
