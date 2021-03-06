<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Controller;
use App\Http\Controllers\SignupController;
use App\Http\Controllers\SigninController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\FavoriteController;

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

Route::post('/v1/signup', [SignupController::class, 'signup']);

Route::post('/v1/signin', [SigninController::class, 'signin']);

Route::post(
    '/v1/messages',
    [MessageController::class, 'postMessage']
)->middleware('auth:sanctum');

Route::post(
    '/v1/{message_id}/fav',
    [FavoriteController::class, 'giveFavorite']
)->middleware('auth:sanctum');

Route::get(
    '/v1/messages',
    [MessageController::class, 'getMessages']
);