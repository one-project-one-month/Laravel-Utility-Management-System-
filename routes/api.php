<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Dashboard\UserController;
use App\Http\Controllers\Api\Dashboard\ReceiptController;
use App\Http\Controllers\Api\Dashboard\ContractController;
use App\Http\Controllers\Api\Dashboard\ContractTypeController;

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


   
 
   
    // Contract Types
    Route::get('/contract-types', [ContractTypeController::class, 'index']);
    Route::get('/contract-types/{id}', [ContractTypeController::class, 'show']);
    Route::post('/contract-types', [ContractTypeController::class, 'store']);
    Route::patch('/contract-types/{id}', [ContractTypeController::class, 'update']);
    


    //Receipt
     Route::get('/receipts', [ReceiptController::class, 'index']);
     Route::post('/receipts',[ReceiptController::class,'create']);
     Route::get('/receipts/{id}', [ReceiptController::class, 'show']);
     Route::put('/receipts/{id}', [ReceiptController::class,'update']);

});

Route::middleware(['auth:sanctum',"Role.check:Tenant"])->group(function() {


});

});
