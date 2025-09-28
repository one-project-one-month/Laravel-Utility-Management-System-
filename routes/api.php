<?php

use App\Http\Controllers\Api\Dashboard\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Dashboard\ReceiptController;

Route::post('/v1/auth/login',[AuthController::class,'login']);

Route::prefix('v1')->group(function() {

Route::middleware(['auth:sanctum',"Role.check:Admin"])->group(function() {
    // Users
    Route::post('/users',[UserController::class,'create']);
    Route::get('/users', [UserController::class,'index']);

    //GetReceipt
    Route::get('/receipts', [ReceiptController::class, 'index']);

});

Route::middleware(['auth:sanctum',"Role.check:Tenant"])->group(function() {


});

});
