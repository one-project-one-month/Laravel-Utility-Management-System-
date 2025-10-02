<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Dashboard\RoomController;
use App\Http\Controllers\Api\Dashboard\UserController;
use App\Http\Controllers\Api\Dashboard\TenantController;
use App\Http\Controllers\Api\Dashboard\ReceiptController;
use App\Http\Controllers\Api\Dashboard\ContractController;
use App\Http\Controllers\Api\Dashboard\TotalUnitController;
use App\Http\Controllers\Api\Dashboard\ContractTypeController;
use App\Http\Controllers\Api\Dashboard\CustomerServiceController;
use App\Http\Controllers\Api\Dashboard\InvoiceController;


Route::post('/v1/auth/login', [AuthController::class, 'login']);

Route::prefix('v1')->group(function () {

    Route::middleware(['auth:sanctum', "Role.check:Admin"])->group(function () {

        // Users Route
        Route::resource('users', UserController::class, ['only' => ['index', 'store', 'update', 'show']]);

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
        Route::apiResource('/receipts', ReceiptController::class)->except(['destroy']);

        //Invoices
        Route::apiResource('invoices', InvoiceController::class)
        ->only(['index', 'show', 'update', 'destroy']);

      

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


    });

    Route::middleware(['auth:sanctum', "Role.check:Tenant"])->group(function () {


     });
});

