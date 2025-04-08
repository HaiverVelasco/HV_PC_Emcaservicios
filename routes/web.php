<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\Auth\LoginController;

// Rutas de autenticación
Route::get('/', [LoginController::class, 'showLoginOptions'])->name('login');
Route::get('/admin/login', [LoginController::class, 'showAdminLogin'])->name('login.admin');
Route::post('/admin/login', [LoginController::class, 'adminLogin'])->name('login.admin.submit');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Equipment Resource Routes
Route::get('/equipments', [EquipmentController::class, 'index'])->name('equipment.index');
Route::get('/equipment/list', [EquipmentController::class, 'show'])->name('equipment.list'); // Nueva ruta para la lista

// Single Equipment Routes
Route::prefix('equipment')->group(function () {
    Route::get('/create', [EquipmentController::class, 'create'])->name('equipment.create');
    Route::get('/{equipment}', [EquipmentController::class, 'show'])->name('equipment.show');
    Route::post('/', [EquipmentController::class, 'store'])->name('equipment.store');
    Route::get('/{equipment}/edit', [EquipmentController::class, 'edit'])->name('equipment.edit');
    Route::put('/{equipment}', [EquipmentController::class, 'update'])->name('equipment.update');
    Route::delete('/{equipment}', [EquipmentController::class, 'destroy'])->name('equipment.destroy');
    
    // Rutas adicionales para la gestión de imágenes y PDF
    Route::get('/{equipment}/pdf', [EquipmentController::class, 'generatePDF'])->name('equipment.pdf');
    Route::delete('/image/{image}', [EquipmentController::class, 'deleteImage'])->name('equipment.image.delete');
});
