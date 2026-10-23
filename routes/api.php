<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DeviceMonitorController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Endpoint untuk menerima tembakan data dari PowerShell
Route::post('/devices/sync', [DeviceMonitorController::class, 'store']);
