<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\ObservationController;
use App\Http\Controllers\Auth\LoginController;

// Rutas de autenticación
Route::get('/', [LoginController::class, 'showLoginOptions'])->name('login');
Route::get('/admin/login', [LoginController::class, 'showAdminLogin'])->name('login.admin');
Route::post('/admin/login', [LoginController::class, 'adminLogin'])->name('login.admin.submit');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Rutas públicas para visualización básica
Route::get('/equipments', [EquipmentController::class, 'index'])->name('equipment.index');
Route::get('/equipment/list', [EquipmentController::class, 'create'])->name('equipment.list');
Route::get('/equipment/{equipment}', [EquipmentController::class, 'index'])->name('equipment.show');
Route::get('/equipment/{equipment}/pdf', [EquipmentController::class, 'generatePDF'])->name('equipment.pdf');

// Rutas para mantenimiento
Route::get('/equipment/{equipment}/maintenance', [MaintenanceController::class, 'index'])->name('maintenance.index');
Route::get('/equipment/{equipment}/maintenance/create', [MaintenanceController::class, 'create'])->name('maintenance.create');
Route::post('/maintenance', [MaintenanceController::class, 'store'])->name('maintenance.store');
Route::delete('/maintenance/{maintenance}', [MaintenanceController::class, 'destroy'])->name('maintenance.destroy');
Route::get('/maintenance/{maintenance}/pdf', [MaintenanceController::class, 'generatePDF'])->name('maintenance.pdf');

// Rutas para observaciones
Route::get('/equipment/{equipment}/observations', [ObservationController::class, 'index'])->name('observations.index');
Route::post('/observations', [ObservationController::class, 'store'])->name('observations.store');
Route::delete('/observations/{observation}', [ObservationController::class, 'destroy'])->name('observations.destroy');
Route::get('/observations/{observation}/pdf', [ObservationController::class, 'generatePDF'])->name('observations.pdf');

// Rutas protegidas solo para administradores
Route::middleware(['admin'])->group(function () {
    Route::prefix('equipment')->group(function () {
        Route::get('/create', [EquipmentController::class, 'create'])->name('equipment.create');
        Route::post('/', [EquipmentController::class, 'store'])->name('equipment.store');
        Route::get('/{equipment}/edit', [EquipmentController::class, 'edit'])->name('equipment.edit');
        Route::put('/{equipment}', [EquipmentController::class, 'update'])->name('equipment.update');
        Route::delete('/{equipment}', [EquipmentController::class, 'destroy'])->name('equipment.destroy');
        Route::delete('/image/{image}', [EquipmentController::class, 'deleteImage'])->name('equipment.image.delete');
        Route::get('/area/{area}/pdfs', [EquipmentController::class, 'generateAreaPDFs'])->name('equipment.area.pdfs');
    });
});
