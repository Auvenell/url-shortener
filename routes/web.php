<?php

use App\Http\Controllers\UrlHandlerController;
use App\Models\urlHandler;
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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/key-generate', function () {
    return view('keyGenerationService');
});

Route::post('/shorten', [UrlHandlerController::class, 'shorten'])->name('shorten.url');
Route::get('/top100', [UrlHandlerController::class, 'top100'])->name('top100.url');

Route::fallback([UrlHandlerController::class, 'redirectUrl'])->name('redirectUrl.url');