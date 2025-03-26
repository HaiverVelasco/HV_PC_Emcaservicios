<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\EquipmentAssignment;
use App\Models\Area;
use App\Models\Employee;
use Illuminate\Http\Request;

class AssignmentController extends Controller
{
    public function create(Equipment $equipment)
    {
        $areas = Area::where('id', '!=', $equipment->current_area_id)->get();
        $employees = Employee::active()->get();
        
        return view('equipment.assignments.create', [
            'equipment' => $equipment,
            'areas' => $areas,
            'employees' => $employees
        ]);
    }

    public function store(Request $request, Equipment $equipment)
    {
        $validated = $request->validate([
            'to_area_id' => 'required|exists:areas,id',
            'new_responsible_id' => 'nullable|exists:employees,id',
            'assignment_reason' => 'required|string|max:500'
        ]);
        
        $validated['from_area_id'] = $equipment->current_area_id;
        $validated['previous_responsible_id'] = $equipment->current_responsible_id;
        $validated['authorized_by_id'] = auth()->id();
        $validated['assignment_date'] = now();
        
        // Crear registro de asignación
        $assignment = EquipmentAssignment::create($validated);
        
        // Actualizar equipo
        $equipment->update([
            'current_area_id' => $validated['to_area_id'],
            'current_responsible_id' => $validated['new_responsible_id']
        ]);
        
        return redirect()->route('equipment.show', $equipment)
                        ->with('success', 'Asignación registrada exitosamente');
    }
}
