<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\TechnicalSpecificationController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\ReportController;

// Página de inicio
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Rutas de equipos (CRUD completo)
Route::resource('equipment', EquipmentController::class);

// Rutas anidadas de mantenimientos
Route::prefix('equipment/{equipment}')->group(function () {
    
    // Mantenimientos
    Route::get('maintenances', [MaintenanceController::class, 'index'])
            ->name('equipment.maintenance.index');
    Route::get('maintenances/create', [MaintenanceController::class, 'create'])
            ->name('equipment.maintenance.create');
    Route::post('maintenances', [MaintenanceController::class, 'store'])
            ->name('equipment.maintenance.store');
    Route::get('maintenances/{maintenance}', [MaintenanceController::class, 'show'])
            ->name('equipment.maintenance.show');
    
    // Especificaciones técnicas
    Route::get('specifications/edit', [TechnicalSpecificationController::class, 'edit'])
            ->name('equipment.specifications.edit');
    Route::put('specifications', [TechnicalSpecificationController::class, 'update'])
            ->name('equipment.specifications.update');
    
    // Asignaciones
    Route::get('assign', [AssignmentController::class, 'create'])
            ->name('equipment.assign.create');
    Route::post('assign', [AssignmentController::class, 'store'])
            ->name('equipment.assign.store');
});

// Rutas de reportes
Route::prefix('reports')->group(function () {
    Route::get('equipment/{equipment}', [ReportController::class, 'equipmentPdf'])
            ->name('reports.equipment');
    Route::get('area/{area}', [ReportController::class, 'areaInventory'])
            ->name('reports.area');
});
