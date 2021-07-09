<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\PostController;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//Users
Route::post('user', [UserController::class, 'store']);
Route::post('auth/login', [UserController::class, 'login']);

//Posts
Route::post('post', [PostController::class, 'store']);
Route::get('post', [PostController::class, 'index']);
Route::put('post/{post}', [PostController::class, 'update']);
Route::get('post/{post}', [PostController::class, 'show']);
Route::delete('post/{post}', [PostController::class, 'destroy']);
