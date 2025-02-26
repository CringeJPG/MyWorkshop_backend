<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::group(['middleware' => 'auth:sanctum'], function() {
// });

Route::post('/log-in', ['App\Http\Controllers\AuthenticationController', 'logIn']);
Route::get('/log-out', ['App\Http\Controllers\AuthenticationController', 'logOut'])->middleware("auth:sanctum");
Route::post('/sign-up', ['App\Http\Controllers\AuthenticationController', 'signUp']);
