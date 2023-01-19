<?php

use App\Http\Controllers\Web\PostControler;
use Illuminate\Support\Facades\Route;

Route::prefix('posts')->name('posts.')->group(function () {
    Route::get('/', [PostControler::class, 'index'])->name('index');
});

Route::get('/', fn () => ['Laravel' => app()->version()])->name('index');

require __DIR__ . '/auth.php';
