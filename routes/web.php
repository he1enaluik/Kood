<?php

use App\Http\Controllers\Admin\ProductCatalogController;
use App\Http\Controllers\Admin\ProductImageController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\StripeCheckoutController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/tooted', [PageController::class, 'products'])->name('products');
Route::get('/toode/{slug}', [PageController::class, 'productShow'])->name('product.show');
Route::get('/kontakt', [PageController::class, 'contact'])->name('contact');
Route::post('/kontakt', [MessageController::class, 'submitContact'])->name('contact.submit');
Route::get('/tellimus', [PageController::class, 'order'])->name('order');
Route::post('/tellimus', [MessageController::class, 'submitOrder'])->name('order.submit');
Route::post('/stripe/checkout', [StripeCheckoutController::class, 'create'])->name('stripe.checkout');
Route::get('/tellimus-onnestus', [PageController::class, 'orderSuccess'])->name('order.success');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth', 'admin'])->prefix('admin/api')->group(function () {
    Route::get('/products', [ProductCatalogController::class, 'show'])->name('admin.products.show');
    Route::put('/products', [ProductCatalogController::class, 'update'])->name('admin.products.update');
    Route::post('/products/upload-image', [ProductImageController::class, 'store'])->name('admin.products.upload-image');
});
