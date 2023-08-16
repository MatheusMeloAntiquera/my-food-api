<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RestaurantController;

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

Route::resource('user', UserController::class)->except([
    'create',
    'edit'
]);

Route::resource('restaurant', RestaurantController::class)->except([
    'create',
    'edit'
]);

Route::prefix('restaurant')->group(function () {
    Route::get('/{id}/products', [RestaurantController::class, 'products']);
    Route::get('/{id}/orders', [RestaurantController::class, 'orders']);
});

Route::prefix('user')->group(function () {
    Route::get('/{id}/orders', [UserController::class, 'orders']);
});

Route::resource('product', ProductController::class)->except([
    'index',
    'create',
    'edit'
]);

Route::prefix('order')->group(function () {
    Route::post('/', [OrderController::class, 'store']);
    Route::get('/{id}', [OrderController::class, 'show']);
});
