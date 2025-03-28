<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\Maintenance;
use Illuminate\Http\Request;

class MaintenanceController extends Controller
{
    public function index()
    {
        $maintenances = Maintenance::with('equipment')->paginate(10);
        return view('maintenances.index', compact('maintenances'));
    }

    public function create()
    {
        $equipments = Equipment::all();
        return view('maintenances.create', compact('equipments'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'equipment_id' => 'required|exists:equipments,id',
            'maintenance_type' => 'required|in:preventive,corrective,installation,dismantling',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'technician' => 'required|string|max:255'
        ]);

        $maintenance = Maintenance::create($validatedData);

        return redirect()->route('maintenances.index')
            ->with('success', 'Maintenance record created successfully.');
    }

    public function edit(Maintenance $maintenance)
    {
        $equipments = Equipment::all();
        return view('maintenances.edit', compact('maintenance', 'equipments'));
    }

    public function update(Request $request, Maintenance $maintenance)
    {
        $validatedData = $request->validate([
            'equipment_id' => 'required|exists:equipments,id',
            'maintenance_type' => 'required|in:preventive,corrective,installation,dismantling',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'technician' => 'required|string|max:255'
        ]);

        $maintenance->update($validatedData);

        return redirect()->route('maintenances.index')
            ->with('success', 'Maintenance record updated successfully.');
    }

    public function destroy(Maintenance $maintenance)
    {
        $maintenance->delete();

        return redirect()->route('maintenances.index')
            ->with('success', 'Maintenance record deleted successfully.');
    }
}
