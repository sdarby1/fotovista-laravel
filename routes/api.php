<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\FollowController;


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
    Route::middleware('check.role:admin')->delete('/admin/posts/{id}', 'deleteUserPost');
    Route::middleware('auth:sanctum')->post('/posts/{id}/toggle-like', 'toggleLike');
    Route::get('/posts/{id}/likes', 'getLikes');
    Route::get('/post/sort', 'sortPosts');
});


Route::controller(UserController::class)->group(function () {
    Route::middleware('auth:sanctum')->post('/update-profile', 'updateProfile');
    Route::middleware('auth:sanctum')->delete('/user/delete', 'deleteAccount');
    Route::get('/user/{userId}', 'userProfile');
    Route::middleware('check.role:admin')->delete('/admin/users/{id}', 'deleteUser');
});


Route::controller(CommentController::class)->group(function () {
    Route::middleware('auth:sanctum')->post('/posts/{post}/comments', 'storeComment');
    Route::middleware('auth:sanctum')->post('/comments/{comment}/replies', 'storeReply');
    Route::get('/posts/{postId}/comments', 'getPostComments');
    Route::middleware('check.role:admin')->delete('/admin/comment/{id}', 'deleteComment');
    Route::middleware('check.role:admin')->delete('/admin/reply/{id}', 'deleteReply');
});


Route::controller(SearchController::class)->group(function () {
    Route::middleware('auth:sanctum')->get('/search', 'search');
});


Route::controller(FollowController::class)->group(function () {
    Route::middleware('auth:sanctum')->post('/users/{userId}/follow', 'follow');
    Route::middleware('auth:sanctum')->post('/users/{userId}/unfollow', 'unfollow');
    Route::get('/users/{userId}/followers', 'followers');
    Route::get('/users/me/following', 'following');
    Route::get('/users/{userId}/isFollowing', 'isFollowing');
});


Route::middleware('auth:sanctum')->get('/auth/user', function (Request $request) {
    return $request->user();
});