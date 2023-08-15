<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
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
    'create', 'edit'
]);

Route::resource('restaurant', RestaurantController::class)->except([
    'create', 'edit'
]);

Route::prefix('restaurant')->group(function () {
    Route::get('/{id}/products', [RestaurantController::class, 'products']);
});

Route::resource('product', ProductController::class)->except([
   'index', 'create', 'edit'
]);
