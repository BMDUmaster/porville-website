<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\ProductApiController;
use App\Http\Controllers\Api\CategoryApiController;
use App\Http\Controllers\Api\OrderApiController;
use App\Http\Controllers\Api\CouponApiController;

/*

 Porville Public API Routes
 Base URL: http://127.0.0.1:8000/api

*/

// ── Auth (public) 
Route::post('/register', [AuthApiController::class, 'register'])->middleware('throttle:3,1');
Route::post('/login',    [AuthApiController::class, 'login'])->middleware('throttle:5,1');

// ── Products (public)
Route::get('/products',              [ProductApiController::class, 'index']);
Route::get('/products/{id}',         [ProductApiController::class, 'show']);
Route::get('/products/slug/{slug}',  [ProductApiController::class, 'showBySlug']);

// ── Categories (public) 
Route::get('/categories',            [CategoryApiController::class, 'index']);
Route::get('/categories/{id}',       [CategoryApiController::class, 'show']);
Route::get('/subcategories',         [CategoryApiController::class, 'subcategories']);

// ── Coupon validation (public)
Route::post('/coupons/validate',     [CouponApiController::class, 'validate']);

// ── Protected routes (require Bearer token)
Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::post('/logout', [AuthApiController::class, 'logout']);
    Route::get('/me',      [AuthApiController::class, 'me']);

    // Orders
    Route::get('/orders',       [OrderApiController::class, 'index']);
    Route::post('/orders',      [OrderApiController::class, 'store']);
    Route::get('/orders/{id}',  [OrderApiController::class, 'show']);
});
