<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('welcome'); // Tu login actual
})->name('login');

Route::get('/registro', function () {
    return view('auth.register'); // La nueva vista de registro
})->name('register');

Route::post('/registro', [AuthController::class, 'register'])->name('register.store');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');
Route::get('/tarjeton/nuevo', [AuthController::class, 'createTarjeton'])->name('tarjeton.create');
Route::post('/tarjeton/guardar', [AuthController::class, 'storeTarjeton'])->name('tarjeton.store');
Route::get('/mis-tarjetones', [AuthController::class, 'dashboard'])->name('estudiante.dashboard');

Route::get('/tarjeton/editar/{id}', [AuthController::class, 'editTarjeton'])->name('tarjeton.edit');
Route::put('/tarjeton/actualizar/{id}', [AuthController::class, 'updateTarjeton'])->name('tarjeton.update');
Route::delete('/tarjeton/eliminar/{id}', [AuthController::class, 'destroyTarjeton'])->name('tarjeton.destroy');
