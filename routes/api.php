<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Dashboard\BillController;
use App\Http\Controllers\Api\Dashboard\ContractController;
use App\Http\Controllers\Api\Dashboard\ContractTypeController;
use App\Http\Controllers\Api\Dashboard\CustomerServiceController;
use App\Http\Controllers\Api\Dashboard\InvoiceController;
use App\Http\Controllers\Api\Dashboard\ReceiptController;
use App\Http\Controllers\Api\Dashboard\RoomController;
use App\Http\Controllers\Api\Dashboard\TenantController;
use App\Http\Controllers\Api\Dashboard\TotalUnitController;
use App\Http\Controllers\Api\Dashboard\UserController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\Client\CustomerServiceController as ClientCustomerServiceController;


Route::post('/v1/auth/login', [AuthController::class, 'login']);
Route::post('/v1/auth/refresh', [AuthController::class, 'refresh']);
Route::post('/v1/auth/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::middleware(['auth:sanctum', 'Role.check:Admin'])->group(function () {
    // Users Route
    Route::resource('users', UserController::class, ['only' => ['index', 'store', 'update', 'show']]);

    // Contracts Route
    Route::resource('contracts', ContractController::class, ['only' => ['index', 'store', 'update', 'show']]);

    // Contract Types
    Route::resource('contract-types', ContractTypeController::class, ['only' => ['index', 'store', 'update', 'show']]);

    // Tenant
    Route::resource('tenants', TenantController::class, ['only' => ['index', 'store', 'update', 'show']]);

    // Receipt
    Route::resource('receipts', ReceiptController::class, ['only' => ['index', 'update', 'show']]);

    // Invoices
    Route::apiResource('invoices', InvoiceController::class)->only(['index', 'show', 'update']);

    // Customer services
    Route::resource('customer-services', CustomerServiceController::class, ['only' => ['index', 'update', 'show']]);

    // Total Units
    Route::resource('total-units', TotalUnitController::class, ['only' => ['index', 'show']]);

    // Rooms Route
    Route::resource('rooms', RoomController::class, ['only' => ['index', 'update', 'show']]);

    // Bills
    Route::resource('bills', BillController::class, ['only' => ['index', 'store', 'show']]);
});

Route::middleware(['auth:sanctum', 'Role.check:Tenant'])->group(function () {

    // Tenant Customer Services
    Route::prefix('v1/tenants/{id}/customer-services')->group(function () {
        Route::post('/create', [ClientCustomerServiceController::class, 'create']);
        Route::get('/history/{status}', [ClientCustomerServiceController::class, 'history']);
        Route::get('/history', [ClientCustomerServiceController::class, 'history']);
    });

});

