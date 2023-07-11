<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\CategoryController;

Route::post('/auth/login', [
    AuthController::class, 'login'
]);
Route::post('/auth/refresh', [
    AuthController::class, 'refresh'
]);
Route::post('/auth/logout', [
    AuthController::class, 'logout']);

Route::post('/auth/register', [
    AuthController::class, 'register'
]);

Route::post('/auth/me', [
    AuthController::class, 'me'
])->name('me');
// Media
Route::group([
    'middlaware' => 'jwt.auth',
    'prefix' => 'media'
], function () {
    Route::get('/index', [MediaController::class, 'index']);
    Route::delete('/{media}', [MediaController::class, 'destroy']);
    Route::post('/create', [MediaController::class, 'store']);
    Route::post('/update/{media}', [MediaController::class, 'update']);
});
//Category
Route::group([
    'middlaware' => 'jwt.auth',
    'prefix' => 'category'
], function () {
    Route::post('/create', [CategoryController::class, 'store']);
    Route::post('/update/{category}', [CategoryController::class, 'update']);
    Route::get('/index', [CategoryController::class, 'index']);
    Route::post('/delete/{category}', [CategoryController::class, 'destroy']);
});



