<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\Area;
use App\Models\Employee;
use App\Models\Company;
use Illuminate\Http\Request;

class EquipmentController extends Controller
{
    public function index()
    {
        $equipments = Equipment::with(['area', 'currentResponsible', 'company'])
                            ->latest()
                            ->paginate(20);
        
        return view('equipment.index', compact('equipments'));
    }

    public function create()
    {
        $areas = Area::orderBy('name_es')->get();
        $employees = Employee::active()->get();
        $companies = Company::active()->get();
        
        return view('equipment.create', compact('areas', 'employees', 'companies'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'inventory_code' => 'required|unique:equipment',
            'name_es' => 'required|string|max:255',
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'serial_number' => 'required|unique:equipment',
            'purchase_date' => 'required|date',
            'current_area_id' => 'required|exists:areas,id',
            'company_id' => 'required|exists:companies,id',
            'status' => 'required|in:GOOD,REGULAR,BAD,DISASSEMBLED'
        ]);
        
        $equipment = Equipment::create($validated);
        
        return redirect()->route('equipment.show', $equipment)
                        ->with('success', 'Equipo registrado exitosamente');
    }

    public function show(Equipment $equipment)
    {
        $equipment->load([
            'area', 
            'currentResponsible', 
            'company',
            'technicalSpecifications',
            'maintenances' => fn($q) => $q->latest()->limit(5),
            'assignments' => fn($q) => $q->latest()->limit(3)
        ]);
        
        return view('equipment.show', compact('equipment'));
    }

    public function edit(Equipment $equipment)
    {
        $areas = Area::orderBy('name_es')->get();
        $employees = Employee::active()->get();
        $companies = Company::active()->get();
        
        return view('equipment.edit', compact('equipment', 'areas', 'employees', 'companies'));
    }

    public function update(Request $request, Equipment $equipment)
    {
        $validated = $request->validate([
            'name_es' => 'required|string|max:255',
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'current_area_id' => 'required|exists:areas,id',
            'status' => 'required|in:GOOD,REGULAR,BAD,DISASSEMBLED',
            'availability' => 'required|in:AVAILABLE,IN_USE,MAINTENANCE,DECOMMISSIONED'
        ]);
        
        $equipment->update($validated);
        
        return redirect()->route('equipment.show', $equipment)
                        ->with('success', 'Equipo actualizado exitosamente');
    }

    public function destroy(Equipment $equipment)
    {
        $equipment->delete();
        
        return redirect()->route('equipment.index')
                        ->with('success', 'Equipo marcado como eliminado');
    }
}
