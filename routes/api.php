<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/log-in', ['App\Http\Controllers\AuthenticationController', 'logIn']);
Route::get('/log-out', ['App\Http\Controllers\AuthenticationController', 'logOut'])->middleware("auth:sanctum");
Route::post('/sign-up', ['App\Http\Controllers\AuthenticationController', 'signUp']);

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('/user/current-user', ['App\Http\Controllers\UserController', 'getCurrentUser']);
    Route::get('/user/{id}', ['App\Http\Controllers\UserController', 'getUserById']);
    Route::patch('/user/{id}', ['App\Http\Controllers\UserController', 'changeUserInfo']);
    Route::delete('/user/{id}', ['App\Http\Controllers\UserController', 'deactivateUser']);
});
