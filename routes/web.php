<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EquipmentController;

// Equipment Resource Routes
Route::get('/', [EquipmentController::class, 'create'])->name('equipment.create');
Route::get('/equipments', [EquipmentController::class, 'index'])->name('equipment.index');

// Single Equipment Routes
Route::prefix('equipment')->group(function () {
    Route::get('/{equipment}', [EquipmentController::class, 'show'])->name('equipment.show');
    Route::post('/', [EquipmentController::class, 'store'])->name('equipment.store');
    Route::get('/{equipment}/edit', [EquipmentController::class, 'edit'])->name('equipment.edit');
    Route::put('/{equipment}', [EquipmentController::class, 'update'])->name('equipment.update');
    Route::delete('/{equipment}', [EquipmentController::class, 'destroy'])->name('equipment.destroy');
    
    // Additional Equipment Features
    Route::get('/{equipment}/pdf', [EquipmentController::class, 'generatePDF'])->name('equipment.pdf');
    Route::delete('/image/{image}', [EquipmentController::class, 'deleteImage'])->name('equipment.image.delete');
});
