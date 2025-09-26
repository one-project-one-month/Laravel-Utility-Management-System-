<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware(['auth','Role.check:Admin'])->group(function() {

    // Route::get('/products',function() {
    //     return response()->json([
    //         'message' => 'Hi'
    //     ],200);
    // });
});
