<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Dashboard\ContractTypeController;
use App\Http\Controllers\Api\Dashboard\UserController;
use App\Http\Controllers\Api\Dashboard\ReceiptController;



Route::post('/v1/auth/login',[AuthController::class,'login'])->name('login');

Route::prefix('v1')->group(function() {

Route::middleware(['auth:sanctum',"Role.check:Admin"])->group(function() {

    // Users Route
    Route::post('/users',[UserController::class,'create']);
    Route::get('/users', [UserController::class,'index']);
    Route::put('/users/{id}', [UserController::class,'update']);
    Route::get('/users/{id}', [UserController::class,'show']);

    
    // Contract Types
    Route::get('/contract-types', [ContractTypeController::class, 'index']);
    Route::get('/contract-types/{id}', [ContractTypeController::class, 'show']);
    Route::post('/contract-types', [ContractTypeController::class, 'store']);
    Route::get('/contract-types/{id}', [ContractTypeController::class, 'update']);
    


    //Receipt
     Route::post('/receipts',[ReceiptController::class,'create']);

});

Route::middleware(['auth:sanctum',"Role.check:Tenant"])->group(function() {

});

});
