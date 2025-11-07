<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Dashboard\BillController;
use App\Http\Controllers\Api\Dashboard\ContractController;
use App\Http\Controllers\Api\Dashboard\ContractTypeController;
use App\Http\Controllers\Api\Dashboard\CustomerServiceController;
use App\Http\Controllers\Api\Dashboard\InvoiceController;
use App\Http\Controllers\Api\Dashboard\RoomController;
use App\Http\Controllers\Api\Dashboard\UserController;
use App\Http\Controllers\Api\Dashboard\TenantController;
use App\Http\Controllers\Api\Dashboard\ReceiptController;
use App\Http\Controllers\Api\Dashboard\TotalUnitController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Client\ReceiptController as ClientReceiptController;
use App\Http\Controllers\Api\Client\ContractController as ClientContractController;
use App\Http\Controllers\Api\Client\InvoiceController as ClientInvoiceController;
use App\Http\Controllers\Api\Client\CustomerServiceController as ClientCustomerServiceController;
use App\Http\Controllers\Api\Client\BillController as ClientBillController;
use App\Http\Controllers\Api\Dashboard\OccupantController;
use App\Http\Controllers\Api\Client\PasswordController as ClientPasswordController;
use App\Http\Controllers\Api\Client\TenantController as ClientTenantController;
use App\Http\Controllers\Api\Client\RoomController as ClientRoomController;

Route::post('/v1/auth/login', [AuthController::class, 'login']);
Route::post('/v1/auth/refresh', [AuthController::class, 'refresh']);
Route::post('/v1/auth/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::prefix('v1/')->group(function () {

    Route::middleware(['auth:sanctum', 'Role.check:Admin,Staff'])->group(function ()
    {
        Route::resource('users', UserController::class, ['only' => ['index', 'store', 'update', 'show']]);

        Route::resource('tenants', TenantController::class, ['only' => ['index', 'store', 'update', 'show']]);

        Route::resource('occupants',OccupantController::class,['only'=> ['index','store','update','show']]);

        Route::resource('rooms', RoomController::class, ['only' => ['index','store','update','show']]);

        Route::resource('contracts', ContractController::class, ['only' => ['index', 'store', 'update', 'show']]);

        Route::resource('contract-types', ContractTypeController::class, ['only' => ['index', 'store', 'update', 'show']]);

        Route::resource('bills', BillController::class, ['only' => ['index', 'store', 'show']]);

        Route::resource('total-units', TotalUnitController::class, ['only' => ['index', 'show']]);

        Route::apiResource('invoices', InvoiceController::class)->only(['index','store', 'show', 'update']);

        Route::resource('customer-services', CustomerServiceController::class, ['only' => ['index', 'update', 'show']]);

        Route::resource('receipts', ReceiptController::class, ['only' => ['index','store','update','show']]);
        Route::post('receipts/{id}/send', [ReceiptController::class, 'send'])->name('receipts.send');
    });

    Route::middleware(['auth:sanctum'])->group(function() {
         Route::resource('tenants', TenantController::class, ['only' => ['show']]);
    });

    Route::middleware(['auth:sanctum', "Role.check:Tenant", "ValidateLoggedInTenantUserId"])->group(function ()
    {
        // Tenant Customer Services
        Route::post('tenants/{id}/customer-services/create', [ClientCustomerServiceController::class, 'create']);
        Route::get('tenants/{id}/customer-services/history/{status?}', [ClientCustomerServiceController::class, 'history']);

        // Receipt latest
        Route::get('/tenants/{id}/receipts/latest', action: [ClientReceiptController::class, 'latest']);
        Route::get('/tenants/{id}/receipts/history',[ClientReceiptController::class,'history']);

        Route::get('/tenants/{id}/contracts', [ClientContractController::class, 'index']);

        Route::get('/tenants/{id}/invoices/latest', [ClientInvoiceController::class, 'latest']);
        Route::get('/tenants/{id}/invoices/history', [ClientInvoiceController::class, 'history']);

        //Bill Latest
        Route::get('/tenants/{id}/bills/latest', [ClientBillController::class,'latestBill']);
        Route::get('/tenants/{id}/bills/history', [ClientBillController::class,'billHistory']);

        Route::put('/tenants/{id}/password-update', [ClientPasswordController::class, 'update']);

        //Tenant
        Route::put('/tenants/{id}/update',[ClientTenantController::class,'update']);

        //Room
        Route::get('tenants/{id}/room', [ClientRoomController::class , 'show'] );
     });

    });
