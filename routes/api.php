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
