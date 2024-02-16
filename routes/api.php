<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\SearchController;


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
    Route::get('/users/{userId}/posts', 'userPosts');
    Route::get('/posts', 'allPosts');
    Route::middleware('auth:sanctum')->put('/posts/{id}', 'update');
    Route::middleware('auth:sanctum')->delete('/posts/{id}', 'deletePost');
});


Route::controller(UserController::class)->group(function () {
    Route::middleware('auth:sanctum')->post('/update-profile', 'updateProfile');
    Route::middleware('auth:sanctum')->delete('/user/delete', 'deleteAccount');
    Route::get('/user/{userId}', 'userProfile');
});


Route::controller(CommentController::class)->group(function () {
    Route::middleware('auth:sanctum')->post('/posts/{post}/comments', 'storeComment');
    Route::middleware('auth:sanctum')->post('/comments/{comment}/replies', 'storeReply');
    Route::get('/posts/{postId}/comments', 'getPostComments');
});


Route::controller(SearchController::class)->group(function () {
    Route::middleware('auth:sanctum')->get('/search', 'search');
});


Route::middleware('auth:sanctum')->get('/auth/user', function (Request $request) {
    return $request->user();
});