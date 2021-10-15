<?php

use App\Http\Controllers\UrlHandlerController;
use App\Models\urlHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/shorten', [UrlHandlerController::class, 'shorten'])->name('shorten.url');
Route::post('/check', [UrlHandlerController::class, 'check'])->name('check.url');
Route::get('/toplist', [UrlHandlerController::class, 'toplist'])->name('toplist.url');