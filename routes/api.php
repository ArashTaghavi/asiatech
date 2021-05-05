<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('api')->group(function () {

    //============================== Auth ==============================
    Route::post('/register', [\App\Http\Controllers\AuthController::class, 'register']);
    Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login']);
    //============================== Auth ==============================

    Route::middleware('auth:api')->group(function () {

        Route::post('/logout', [\App\Http\Controllers\AuthController::class, 'logout']);
        Route::get('/user-orders', [\App\Http\Controllers\OrderController::class, 'user_orders']);
        // ================================ Orders ================================
        Route::post('/orders', [\App\Http\Controllers\OrderController::class, 'store']);
        // ================================ Orders ================================

        Route::middleware('is_admin')->group(function () {

            Route::get('/orders', [\App\Http\Controllers\OrderController::class, 'index']);

            // ================================ Foods ================================
            Route::resource('/foods', \App\Http\Controllers\FoodController::class)->except(['edit', 'create']);
            // ================================ Foods ================================

            // ================================ Categories ================================
            Route::resource('/categories', \App\Http\Controllers\CategoryController::class)->except(['edit', 'create']);
            // ================================ Categories ================================

            // ================================ SubCategories ================================
            Route::resource('/sub-categories', \App\Http\Controllers\SubCategoryController::class)->except(['edit', 'create']);
            // ================================ SubCategories ================================
        });
    });
});
