<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;

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


Route::post('/auth/register', [AuthController::class, 'createUser']);
Route::post('/auth/login', [AuthController::class, 'loginUser']);


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('categories', [\App\Http\Controllers\Api\CategoryController::class, 'index']);
});
    Route::resource('products', ProductController::class);

// Route::controller(ProductController::class)->group(function () {
//     Route::get('products', 'index')->name('products.index');
//     Route::post('products', 'store')->name('products.store');
//     Route::get('products/{id}', 'show')->name('products.show');
//     Route::put('products/{id}', 'update')->name('products.update');
//     Route::delete('products/{id}', 'destroy')->name('products.destroy');
//     // Route::put('products/{id}/restore', 'restore')->name('products.restore');
//     // Route::delete('products/{id}/force-delete', 'forceDelete')->name('products.force-delete');

// });

