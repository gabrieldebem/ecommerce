<?php

use App\Http\Controllers\AuthUserAction;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StoreUserAction;
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

Route::post('/users', StoreUserAction::class);
Route::post('/auth', AuthUserAction::class);
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/users', fn (Request $request) => $request->user());

    Route::prefix('/products')->group(function () {
        Route::get('/', [ProductController::class, 'index']);
        Route::post('/', [ProductController::class, 'store']);
        Route::get('/{product}', [ProductController::class, 'show']);
        Route::put('/{product}', [ProductController::class, 'update']);
        Route::delete('/{product}', [ProductController::class, 'destroy']);
    });
});
