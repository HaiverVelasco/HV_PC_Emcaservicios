<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Software;
use App\Models\Equipment;
use Illuminate\Auth\Events\Validated;
use Illuminate\Contracts\Support\ValidatedData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EquipmentController extends Controller
{
    public function index()
    {
        $areas = Area::with(['equipment' => function($query) {
            $query->orderBy('inventory_code');
        }])->get();
        
        return view('equipments.show', compact('areas'));
    }

    public function create()
    {
        $areas = Area::all();
        $softwares = Software::all();
        return view('equipments.index', compact('areas', 'softwares'));
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'area_id' => 'required|exists:areas,id',
                'company_name' => 'nullable|string|max:255',
                'city' => 'nullable|string|max:255',
                'inventory_code' => 'required|string|max:255|unique:equipment',
                'equipment_name' => 'required|string|max:255',
                'brand' => 'nullable|string|max:100',
                'model' => 'nullable|string|max:100',
                'reference' => 'nullable|string|max:100',
                'serial_number' => 'nullable|string|max:100',
                'manufacturer' => 'nullable|string|max:100',
                'acquisition_date' => 'nullable|date',
                'operation_start_date' => 'nullable|date',
                'equipment_location' => 'nullable|string|max:255',
                'value' => 'nullable|numeric',
                'warranty' => 'nullable|string|max:255',
                'technical_brand_model' => 'nullable|string|max:255',
                'processor' => 'nullable|string|max:255',
                'operating_system' => 'nullable|string|max:255',
                'graphic_card' => 'nullable|string|max:255',
                'ram_memory' => 'nullable|string|max:255',
                'storage' => 'nullable|string|max:255',
                'status' => 'required|in:Bueno,Regular,Malo,Deshabilitado',
                'maintenance_type' => 'nullable|in:Preventive,Corrective,Installation,Disassembly',
                'depreciation' => 'nullable|string|max:255',
                'bad_operation' => 'nullable|string|max:255',
                'bad_installation' => 'nullable|string|max:255',
                'accessories' => 'nullable|string|max:255',
                'failure' => 'nullable|in:Unknown,No Failures',
                'observations' => 'nullable|string',
            ]);

            $equipment = Equipment::create($validatedData);

            if ($request->has('softwares')) {
                $equipment->softwares()->attach($request->softwares);
            }

            // Obtener el nombre del área
            $area = Area::find($validatedData['area_id']);

            return redirect()->back()
                ->with('success', '¡Equipo creado exitosamente! Código de inventario: ' . $equipment->inventory_code)
                ->with('info', 'Equipo asignado al área: ' . $area->name);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al crear el equipo: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show(Equipment $equipment)
    {
        $equipment->load(['area', 'softwares']);
        return view('equipments.show', compact('equipment'));
    }

    public function edit(Equipment $equipment)
    {
        $areas = Area::all();
        $softwares = Software::all();
        return view('equipments.edit', compact('equipment', 'areas', 'softwares'));
    }

    public function update(Request $request, Equipment $equipment)
    {
        $validatedData = $request->validate([
            // Similar validation to store method
        ]);

        $equipment->update($validatedData);

        // Update software attachments
        if ($request->has('softwares')) {
            $equipment->softwares()->sync($request->softwares);
        }

        return redirect()->route('equipments.index')
            ->with('success', 'Equipment updated successfully.');
    }

    public function destroy(Equipment $equipment)
    {
        $equipment->delete();
        return redirect()->route('equipments.index')
            ->with('success', 'Equipment deleted successfully.');
    }
}
