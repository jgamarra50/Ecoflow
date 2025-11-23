<?php

use App\Http\Controllers\Api\TelemetryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Telemetry API Routes
Route::prefix('vehicles')->group(function () {
    // Get all vehicles telemetry
    Route::get('/telemetry', [TelemetryController::class, 'index']);
    
    // Get specific vehicle telemetry
    Route::get('/{id}/telemetry', [TelemetryController::class, 'show']);
    
    // Get telemetry history for a vehicle
    Route::get('/{id}/telemetry/history', [TelemetryController::class, 'history']);
});
