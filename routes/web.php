<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\AuthController;

// Rotas Públicas
Route::get('/', [PropertyController::class, 'index'])->name('home');
Route::controller(AuthController::class)->group(function () {
    Route::get('/login', 'showLogin')->name('login');
    Route::post('/login', 'login');
    Route::get('/register', 'showRegister')->name('register');
    Route::post('/register', 'register');
});

// Rotas Protegidas (verificação manual nos controllers)
Route::prefix('/')->group(function () {
    // Autenticação
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Propriedades
    Route::controller(PropertyController::class)->group(function () {
        // CRUD
        Route::get('/properties/create', 'showAddForm')->name('properties.create');
        Route::post('/properties', 'storeProperty')->name('properties.store');
        Route::get('/properties/{id}/edit', 'edit')->name('properties.edit');
        Route::put('/properties/{id}', 'update')->name('properties.update');
        Route::delete('/properties/{id}', 'destroy')->name('properties.destroy');
        
        // Listagens
        Route::get('/my-properties', 'myProperties')->name('my-properties');
        Route::get('/my-bookings', 'myBookings')->name('my-bookings');
        
        // Reservas
        Route::post('/book/{id}', 'book')->name('book');
    });
});