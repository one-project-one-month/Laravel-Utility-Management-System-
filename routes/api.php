<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\AuthController;

use App\Http\Controllers\Api\Dashboard\TotalUnitController;


Route::post('/v1/auth/login',[AuthController::class,'login']);

Route::prefix('v1')->group(function() {

Route::middleware(['auth:sanctum',"Role.check:Admin"])->group(function() {

});

Route::middleware(['auth:sanctum',"Role.check:Tenant"])->group(function() {

    Route::get('/total-units', [TotalUnitController::class, 'index']);
    Route::post('/total-units', [TotalUnitController::class, 'store']);
    Route::get('/total-units/{id}', [TotalUnitController::class, 'show']);
    Route::put('/total-units/{id}', [TotalUnitController::class, 'update']);

});

});
