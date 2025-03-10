<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/log-in', ['App\Http\Controllers\AuthenticationController', 'logIn']);
Route::delete('/log-out', ['App\Http\Controllers\AuthenticationController', 'logOut'])->middleware('auth:sanctum');
Route::post('/sign-up', ['App\Http\Controllers\AuthenticationController', 'signUp']);

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('/user/current-user', ['App\Http\Controllers\UserController', 'getCurrentUser']);
    Route::get('/user/{id}', ['App\Http\Controllers\UserController', 'getUserById']);
    Route::patch('/user/{id}', ['App\Http\Controllers\UserController', 'changeUserInfo']);
    Route::delete('/user/{id}', ['App\Http\Controllers\UserController', 'deactivateUser']);
    Route::post('/user/follow/{id}', ['App\Http\Controllers\UserController', 'followUser']);

    Route::get('/post', ['App\Http\Controllers\PostController', 'index']);
    Route::get('/post/{id}', ['App\Http\Controllers\PostController', 'show']);
    Route::post('/post', ['App\Http\Controllers\PostController', 'store']);
    Route::patch('/post/{id}', ['App\Http\Controllers\PostController', 'update']);
    Route::delete('/post/{id}', ['App\Http\Controllers\PostController', 'destroy']);

    Route::get('share/{id}', ['App\Http\Controllers\SharesController', 'getSharesById']);
    Route::post('/share/{id}', ['App\Http\Controllers\SharesController', 'store']);
    Route::delete('/share/{id}', ['App\Http\Controllers\SharesController', 'destroy']);

    Route::get('like/{id}', ['App\Http\Controllers\LikesController', 'getLikesById']);
    Route::get('like/user/{id}', ['App\Http\Controllers\LikesController', 'show']);
    Route::post('like/{id}', ['App\Http\Controllers\LikesController', 'store']);
    Route::delete('like/{id}', ['App\Http\Controllers\LikesController', 'destroy']);

    Route::get('/comment/{id}', ['App\Http\Controllers\CommentController', 'getCommentsByPostId']);
    Route::post('/comment/{id}', ['App\Http\Controllers\CommentController', 'createCommentByPostId']);
    Route::patch('/comment/{id}', ['App\Http\Controllers\CommentController', 'update']);
    Route::delete('/comment/{id}', ['App\Http\Controllers\CommentController', 'destroy']);

    Route::get('/group', ['App\Http\Controllers\GroupController', 'index']);
    Route::get('/group/{id}', ['App\Http\Controllers\GroupController', 'show']);
    Route::post('/group', ['App\Http\Controllers\GroupController', 'store']);
    Route::patch('/group/{id}', ['App\Http\Controllers\GroupController', 'update']);
    Route::delete('/group/{id}', ['App\Http\Controllers\GroupController', 'destroy']);

    Route::get('/category', ['App\Http\Controllers\CategoryController', 'index']);
    Route::get('/category/{id}', ['App\Http\Controllers\CategoryController', 'show']);
    Route::post('/category', ['App\Http\Controllers\CategoryController', 'store']);
});
