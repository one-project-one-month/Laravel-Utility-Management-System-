<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Dashboard\UserController;
use App\Http\Controllers\Api\Dashboard\ReceiptController;
use App\Http\Controllers\Api\Dashboard\ContractController;
use App\Http\Controllers\Api\Dashboard\ContractTypeController;
use App\Http\Controllers\Api\Dashboard\TenantController;
use App\Http\Controllers\Api\Dashboard\TotalUnitController;
use App\Http\Controllers\Api\Dashboard\CustomerServiceController;


Route::post('/v1/auth/login', [AuthController::class, 'login']);

Route::prefix('v1')->group(function () {

    Route::middleware(['auth:sanctum', "Role.check:Admin"])->group(function () {

        // Users Route
        Route::resource('users', UserController::class, ['only' => ['index', 'store', 'update', 'show']]);

<<<<<<< HEAD
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
    
=======
        // Contracts Route
        Route::apiResource('contracts', ContractController::class)->except(['destroy']);

        // Contract Types
        Route::get('/contract-types', [ContractTypeController::class, 'index']);
        Route::get('/contract-types/{id}', [ContractTypeController::class, 'show']);
        Route::post('/contract-types', [ContractTypeController::class, 'store']);
        Route::get('/contract-types/{id}', [ContractTypeController::class, 'update']);

        Route::patch('/contract-types/{id}', [ContractTypeController::class, 'update']);

        //Tenant
        Route::resource('tenants', TenantController::class, ['only' => ['index', 'store', 'update', 'show']]);

        //Receipt
        Route::get('/receipts', [ReceiptController::class, 'index']);
        Route::post('/receipts', [ReceiptController::class, 'create']);
        Route::get('/receipts/{id}', [ReceiptController::class, 'show']);
        Route::put('/receipts/{id}', [ReceiptController::class, 'update']);

        //Customer services
        Route::get('/customer-services', [CustomerServiceController::class, 'index']);
        Route::get('/customer-services/{id}', [CustomerServiceController::class, 'show']);
        Route::put('/customer-services/{id}', [CustomerServiceController::class, 'update']);

        // Total Units
        Route::get('/total-units', [TotalUnitController::class, 'index']);
        Route::post('/total-units', [TotalUnitController::class, 'store']);
        Route::get('/total-units/{id}', [TotalUnitController::class, 'show']);
        Route::put('/total-units/{id}', [TotalUnitController::class, 'update']);

        // Rooms Route
        //  Route::post('/rooms',[RoomController::class,'create']);
         Route::get('/rooms',[RoomController::class,'index']);
         Route::get('/rooms/{id}',[RoomController::class,'show']);
         Route::patch('/rooms/{id}',[RoomController::class,'update']);
>>>>>>> b475cff3f15e206520e8c246f6d290104d4a77f7


    });

    Route::middleware(['auth:sanctum', "Role.check:Tenant"])->group(function () {


     });
});

