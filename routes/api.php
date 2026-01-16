<?php

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\FaqController;
use App\Http\Controllers\Api\productController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/signup', [AuthController::class, 'signup']);
Route::post('/support-post', [ApiController::class, 'support']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return $request->user()->load('userDetail');
    });

    Route::resource('products', ProductController::class)->names('products');
    Route::get('/get-products', [ProductController::class, 'getProducts']);
    Route::post('/products/{product}/status', [ProductController::class, 'updateProductStatus']);
    Route::post('/products/{product}/featured', [ProductController::class, 'updateProductFeatured']);
    Route::post('/products/{product}/upload-images', [ProductController::class, 'uploadProductImages']);
    Route::delete('products/{id}/remove-product-image', [ProductController::class, 'removeProductImage']);

    Route::resource('users', UserController::class)->names('users');
    Route::get('/get-users', [UserController::class, 'getUsers']);
    Route::delete('users/{id}/remove-profile-image', [UserController::class, 'removeProfileImage']);

    Route::resource('faqs', FaqController::class)->names('faqs');
    Route::get('/get-faqs', [FaqController::class, 'getFaqs']);
    Route::post('/faqs/{faq}/status', [FaqController::class, 'updateFaqStatus']);

    Route::get('/get-support-queries', [ApiController::class, 'getSupportQueries']);
    Route::get('/get-categories/{id?}', [ApiController::class, 'getCategories']);
    Route::post('/add-product-category/{id?}', [ApiController::class, 'addProductCategory']);
    Route::delete('/delete-product-category/{id}', [ApiController::class, 'deleteProductCategory']);
    Route::delete('categories/{id}/remove-icon', [ApiController::class, 'removeCategoryIcon']);
});
