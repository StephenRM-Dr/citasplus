<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BusinessController;
use App\Http\Controllers\PublicController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome'); // Landing page (no implementada aún)
});

use App\Http\Controllers\AuthController;

// Rutas de Autenticación (Prototipo)
Route::get('/login', function () {
    return view('auth.login');
})->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', function () {
    return view('auth.register');
})->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rutas Públicas de Reserva
Route::get('/book/{businessId}', [PublicController::class, 'booking'])->name('public.booking');

// API de Disponibilidad y Reservas
Route::get('/api/slots/{businessId}', [PublicController::class, 'getAvailableSlots'])->name('api.slots');
Route::post('/api/book/{businessId}', [PublicController::class, 'bookAppointment'])->name('api.book');

// Rutas de Negocio (Protegidas por auth)
Route::middleware(['auth'])->prefix('business')->group(function () {
    Route::get('/dashboard', [BusinessController::class, 'dashboard'])->name('business.dashboard');
    Route::get('/calendar', [BusinessController::class, 'calendar'])->name('business.calendar');
    Route::get('/calendar/events', [BusinessController::class, 'calendarEvents'])->name('business.calendar.events');
});
