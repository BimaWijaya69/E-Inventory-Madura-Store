<?php

use App\Http\Controllers\MaterialController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/test', function () {
    return response()->json('api running');
});

Route::post('/material', [MaterialController::class, 'store']);
