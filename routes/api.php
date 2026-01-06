<?php

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/signup', [AuthController::class, 'signup']);
Route::post('/support-post', [ApiController::class, 'support']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::resource('users', UserController::class)->names('users');
    Route::get('/get-users', [UserController::class, 'getUsers']);

    Route::get('/get-support-queries', [ApiController::class, 'getSupportQueries']);
    Route::get('/get-categories/{id?}', [ApiController::class, 'getCategories']);
    Route::post('/add-product-category/{id?}', [ApiController::class, 'addProductCategory']);
    Route::delete('/delete-product-category/{id}', [ApiController::class, 'deleteProductCategory']);
});
