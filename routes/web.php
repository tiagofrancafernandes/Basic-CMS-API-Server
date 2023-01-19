<?php

use App\Http\Controllers\Web\PostControler;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', fn () => view('welcome'))->name('index');

Route::prefix('posts')->name('posts.')->group(function () {
    Route::get('/', [PostControler::class, 'index'])->name('index');
});
