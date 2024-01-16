<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::controller(AuthController::class)->group(function () {
    Route::post('/auth/register', 'register');
    Route::post('/auth/login', 'login');
    Route::middleware('auth:sanctum')->get('/auth/logout', 'logout');
    Route::middleware('auth:sanctum')->get('/auth/user', 'logout');
});

Route::controller(PostController::class)->group(function () {
    Route::middleware('auth:sanctum')->post('/auth/create-post', 'create');
    Route::get('/posts/{id}', 'show');
});


Route::middleware('auth:sanctum')->get('/auth/user', function (Request $request) {
    return $request->user();
});