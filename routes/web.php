<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EquipmentController;

Route::get('/', [EquipmentController::class, 'create'])->name('equipment.create');
Route::get('/equipments', [EquipmentController::class, 'index'])->name('equipment.index');
Route::get('/equipment/{equipment}', [EquipmentController::class, 'show'])->name('equipment.show');
Route::post('/equipment', [EquipmentController::class, 'store'])->name('equipment.store');
Route::get('/equipment/{equipment}/edit', [EquipmentController::class, 'edit'])->name('equipment.edit');
Route::put('/equipment/{equipment}', [EquipmentController::class, 'update'])->name('equipment.update');
Route::delete('/equipment/{equipment}', [EquipmentController::class, 'destroy'])->name('equipment.destroy');
