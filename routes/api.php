<?php

use App\Http\Controllers\Api\Admin\ProductCatalogApiController;
use App\Http\Controllers\Api\AuthTokenController;
use App\Http\Controllers\Api\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{slug}', [ProductController::class, 'show']);

Route::post('/auth/login', [AuthTokenController::class, 'login']);
Route::post('/auth/register', [AuthTokenController::class, 'register']);

Route::middleware('jwt.auth')->group(function () {
    Route::get('/auth/me', [AuthTokenController::class, 'me']);

    Route::middleware('jwt.admin')->prefix('admin')->group(function () {
        Route::get('/products', [ProductCatalogApiController::class, 'show']);
        Route::put('/products', [ProductCatalogApiController::class, 'update']);
        Route::post('/products/upload-image', [ProductCatalogApiController::class, 'uploadImage']);
    });
});
