<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\BusinessController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->group(function () {
    // Disponibilidad pública
    Route::get('/availability/{businessId}', [PublicController::class, 'getAvailableSlots']);
    
    // Citas para Dashboard (Protegida)
    Route::middleware('auth:sanctum')->get('/appointments', [BusinessController::class, 'getAppointments']);
});
