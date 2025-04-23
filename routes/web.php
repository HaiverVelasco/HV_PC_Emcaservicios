<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\Auth\LoginController;

// Rutas de autenticación
Route::get('/', [LoginController::class, 'showLoginOptions'])->name('login');
Route::get('/admin/login', [LoginController::class, 'showAdminLogin'])->name('login.admin');
Route::post('/admin/login', [LoginController::class, 'adminLogin'])->name('login.admin.submit');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Rutas públicas para visualización básica
Route::get('/equipments', [EquipmentController::class, 'index'])->name('equipment.index');
Route::get('/equipment/list', [EquipmentController::class, 'show'])->name('equipment.list');
Route::get('/equipment/{equipment}', [EquipmentController::class, 'show'])->name('equipment.show');
Route::get('/equipment/{equipment}/pdf', [EquipmentController::class, 'generatePDF'])->name('equipment.pdf');

// Rutas protegidas solo para administradores
Route::middleware(['admin'])->group(function () {
    Route::prefix('equipment')->group(function () {
        Route::get('/create', [EquipmentController::class, 'create'])->name('equipment.create');
        Route::post('/', [EquipmentController::class, 'store'])->name('equipment.store');
        Route::get('/{equipment}/edit', [EquipmentController::class, 'edit'])->name('equipment.edit');
        Route::put('/{equipment}', [EquipmentController::class, 'update'])->name('equipment.update');
        Route::delete('/{equipment}', [EquipmentController::class, 'destroy'])->name('equipment.destroy');
        Route::delete('/image/{image}', [EquipmentController::class, 'deleteImage'])->name('equipment.image.delete');
    });
});
