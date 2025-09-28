<?php

use App\Http\Controllers\Api\Dashboard\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Dashboard\ContractController;

Route::post('/v1/auth/login',[AuthController::class,'login']);

Route::prefix('v1')->group(function() {

Route::middleware(['auth:sanctum',"Role.check:Admin"])->group(function() {

    // Users Route

    Route::post('/users',[UserController::class,'create']);
    Route::get('/users', [UserController::class,'index']);

    // Contracts Route

    Route::get('/contracts', [ContractController::class,'index']);
    Route::get('/contracts/show/{contract}', [ContractController::class,'show']);
    Route::post('/contracts', [ContractController::class,'store']);

});

Route::middleware(['auth:sanctum',"Role.check:Tenant"])->group(function() {

});

});
