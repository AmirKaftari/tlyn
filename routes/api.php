<?php

use App\Http\Controllers\Api\v1\OrderController;
use App\Http\Controllers\Api\v1\TransactionController;
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

Route::group(['prefix' => '/v1'], function() {
    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/users/{userId}/transactions', [TransactionController::class, 'userTransactions']);
});