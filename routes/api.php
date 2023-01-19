<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PostControler;
use App\Http\Controllers\Api\CategoryControler;

Route::middleware(['auth:sanctum'])->get('/user', fn (Request $request) => $request->user());

Route::name('api.')->group(function () {
    Route::prefix('posts')->name('posts.')->group(function () {
        Route::get('/', [PostControler::class, 'index'])->name('index');
        Route::post('/', [PostControler::class, 'store'])->name('store');
        Route::get('/{slug}', [PostControler::class, 'show'])->name('show');
        Route::match(['post', 'patch'], '/{slug}/update', [PostControler::class, 'update'])->name('update');
        Route::match(['post', 'delete'], '/{slug}/destroy', [PostControler::class, 'destroy'])->name('destroy');
    });

    Route::prefix('categories')->name('categories.')->group(function () {
        Route::get('/', [CategoryControler::class, 'index'])->name('index');
        Route::post('/', [CategoryControler::class, 'store'])->name('store');
        Route::get('/{slug}', [CategoryControler::class, 'show'])->name('show');
        Route::match(['post', 'patch'], '/{slug}/update', [CategoryControler::class, 'update'])->name('update');
        Route::match(['post', 'delete'], '/{slug}/destroy', [CategoryControler::class, 'destroy'])->name('destroy');
    });
});
