<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\Maintenance;
use Illuminate\Http\Request;

class MaintenanceController extends Controller
{
    public function index(Equipment $equipment)
    {
        $maintenances = $equipment->maintenances()
                                ->with('requestingArea')
                                ->latest()
                                ->paginate(10);
        
        return view('maintenance.index', [
            'equipment' => $equipment,
            'maintenances' => $maintenances
        ]);
    }

    public function create(Equipment $equipment)
    {
        $maintenanceTypes = [
            'PREVENTIVE' => 'Mantenimiento Preventivo',
            'CORRECTIVE' => 'Mantenimiento Correctivo',
            'INSTALLATION' => 'Instalación',
            'DISASSEMBLY' => 'Desmontaje'
        ];
        
        return view('maintenance.create', [
            'equipment' => $equipment,
            'types' => $maintenanceTypes
        ]);
    }

    public function store(Request $request, Equipment $equipment)
    {
        $validated = $request->validate([
            'type' => 'required|in:PREVENTIVE,CORRECTIVE,INSTALLATION,DISASSEMBLY',
            'date' => 'required|date',
            'description' => 'required|string|max:500',
            'performed_actions' => 'required|string|max:1000',
            'cost' => 'nullable|numeric|min:0',
            'depreciation' => 'boolean',
            'bad_operation' => 'boolean',
            'no_issues_found' => 'boolean'
        ]);
        
        $validated['requesting_area_id'] = auth()->user()->area_id;
        
        $maintenance = $equipment->maintenances()->create($validated);
        
        return redirect()->route('equipment.maintenance.show', [
                        'equipment' => $equipment,
                        'maintenance' => $maintenance
                    ])
                    ->with('success', 'Mantenimiento registrado exitosamente');
    }

    public function show(Equipment $equipment, Maintenance $maintenance)
    {
        $maintenance->load('requestingArea', 'responsibleEmployee');
        
        return view('maintenance.show', [
            'equipment' => $equipment,
            'maintenance' => $maintenance
        ]);
    }
}
