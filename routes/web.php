<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;


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

// Rutas del Administrador
Route::prefix('admin')->group(function () {
    Route::get('/escaner', [AdminController::class, 'escaner'])->name('admin.escaner');
    // Nueva ruta para solo buscar los datos
    Route::post('/escaner/buscar', [AdminController::class, 'buscarTarjeton'])->name('admin.buscar');
    // Nueva ruta para cambiar el estatus cuando presiones el botón
    Route::post('/escaner/toggle', [AdminController::class, 'toggleEstatus'])->name('admin.toggle');
});

Route::get('/usuarios', [AdminController::class, 'listaUsuarios'])->name('admin.usuarios');
Route::post('/usuarios/update-password', [AdminController::class, 'updatePassword'])->name('admin.user.password');
