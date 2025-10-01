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
use App\Http\Controllers\Api\Dashboard\BillController;


Route::post('/v1/auth/login', [AuthController::class, 'login']);
Route::post('/v1/auth/refresh', [AuthController::class, 'refresh']);
Route::post('/v1/auth/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::prefix('v1')->group(function () {

    Route::middleware(['auth:sanctum', "Role.check:Admin"])->group(function () {

        // Users Route
        Route::resource('users', UserController::class, ['only' => ['index', 'store', 'update', 'show']]);

        // Contracts Route
        Route::apiResource('contracts', ContractController::class)->except(['destroy']);

        // Contract Types
        Route::resource('contract-types', ContractTypeController::class, ['only' => ['index', 'store', 'update', 'show']]);

        //Tenant
        Route::resource('tenants', TenantController::class, ['only' => ['index', 'store', 'update', 'show']]);

        //Receipt
        Route::apiResource('/receipts', ReceiptController::class)->except(['destroy']);

        //Customer services
        Route::resource('customer-services', CustomerServiceController::class, ['only' => ['index', 'update', 'show']]);

        // Total Units
        Route::resource('total-units', TotalUnitController::class, ['only' => ['index', 'store', 'update', 'show']]);

        // Rooms Route
        Route::resource('bills', RoomController::class, ['only' => ['index', 'update', 'show']]);

        // Bills
        Route::resource('bills', BillController::class, ['only' => ['index', 'store', 'update', 'show']]);



    });

    Route::middleware(['auth:sanctum', "Role.check:Tenant"])->group(function () {


     });
});

