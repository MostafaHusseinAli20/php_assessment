<?php

use App\Http\Controllers\Products\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::group(['prefix' => 'v1'], function(){
    Route::put('products/{product}/adjust-stock', [ProductController::class, 'adjustStock']);
    Route::get('products/low-stock', [ProductController::class, 'lowStock']);
    Route::apiResource('products', ProductController::class);
});
