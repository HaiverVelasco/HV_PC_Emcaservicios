<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\Maintenance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

class MaintenanceController extends Controller
{
    public function __construct()
    {
        // Aplicar middleware de admin a las acciones que requieren privilegios
        $this->middleware('admin')->except(['index', 'show']);
    }

    public function index($equipment_id)
    {
        $equipment = Equipment::with(['maintenances' => function($query) {
            $query->orderBy('date', 'desc');
        }])->findOrFail($equipment_id);
        
        return view('maintenances.index', compact('equipment'));
    }

    public function create($equipment_id)
    {
        $equipment = Equipment::findOrFail($equipment_id);
        return view('maintenances.create', compact('equipment'));
    }    public function store(Request $request)
    {
        $validated = $request->validate([
            'equipment_id' => 'required|exists:equipment,id',
            'date' => 'required|date',
            'type' => 'required|in:Preventivo,Correctivo,Instalación,Desinstalación',
            'description' => 'required|string',
            'technician' => 'required|string|max:255',
            'failure' => 'nullable|string',
            'depreciation' => 'nullable|string|max:255',
            'bad_operation' => 'nullable|string|max:255',
            'bad_installation' => 'nullable|string|max:255',
            'accessories' => 'nullable|string|max:255',
        ]);

        // Traduccion de mantenimiento de español a inglés para la base de datos
        $typeTranslations = [
            'Preventivo' => 'Preventive',
            'Correctivo' => 'Corrective',
            'Instalación' => 'Installation',
            'Desinstalación' => 'Disassembly'
        ];

        // Traducción del estado de falla de español a inglés para la base de datos
        $failureTranslations = [
            'Desconocido' => 'Unknown',
            'Sin Fallas' => 'No Failures'
        ];

        $validated['type'] = $typeTranslations[$validated['type']] ?? $validated['type'];

        // Verificar y traducir la falla si existe
        if (isset($validated['failure']) && array_key_exists($validated['failure'], $failureTranslations)) {
            $validated['failure'] = $failureTranslations[$validated['failure']];
        }
        
        try {
            $maintenance = Maintenance::create($validated);
            
            // Actualiza también el estado del equipo con la información de este mantenimiento
            $equipment = Equipment::find($request->equipment_id);
            $equipment->last_update_date = now();
            $equipment->save();

            return redirect()->route('maintenance.index', $equipment->id)
                            ->with('success', 'Mantenimiento registrado correctamente');
        } catch (\Exception $e) {
            Log::error('Error al crear mantenimiento: ' . $e->getMessage());
            return redirect()->back()
                            ->with('error', 'Error al registrar el mantenimiento: ' . $e->getMessage())
                            ->withInput();
        }
    }

    public function show(Maintenance $maintenance)
    {
        return view('maintenances.show', compact('maintenance'));
    }

    public function edit(Maintenance $maintenance)
    {
        return view('maintenances.edit', compact('maintenance'));
    }    public function update(Request $request, Maintenance $maintenance)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'type' => 'required|in:Preventivo,Correctivo,Instalación,Desinstalación',
            'description' => 'required|string',
            'technician' => 'required|string|max:255',
            'failure' => 'nullable|string',
            'depreciation' => 'nullable|string|max:255',
            'bad_operation' => 'nullable|string|max:255',
            'bad_installation' => 'nullable|string|max:255',
            'accessories' => 'nullable|string|max:255',
        ]);
        
        // Traduccion de mantenimiento de español a inglés para la base de datos
        $typeTranslations = [
            'Preventivo' => 'Preventive',
            'Correctivo' => 'Corrective',
            'Instalación' => 'Installation',
            'Desinstalación' => 'Disassembly'
        ];

        // Traducción del estado de falla de español a inglés para la base de datos
        $failureTranslations = [
            'Desconocido' => 'Unknown',
            'Sin Fallas' => 'No Failures'
        ];

        $validated['type'] = $typeTranslations[$validated['type']] ?? $validated['type'];

        // Verificar y traducir la falla si existe
        if (isset($validated['failure']) && array_key_exists($validated['failure'], $failureTranslations)) {
            $validated['failure'] = $failureTranslations[$validated['failure']];
        }

        try {
            $maintenance->update($validated);
            
            // Actualiza la fecha de última actualización del equipo
            $equipment = Equipment::find($maintenance->equipment_id);
            $equipment->last_update_date = now();
            $equipment->save();
            
            return redirect()->route('maintenance.index', $maintenance->equipment_id)
                            ->with('success', 'Mantenimiento actualizado correctamente');
        } catch (\Exception $e) {
            Log::error('Error al actualizar mantenimiento: ' . $e->getMessage());
            return redirect()->back()
                            ->with('error', 'Error al actualizar el mantenimiento: ' . $e->getMessage())
                            ->withInput();
        }
    }

    public function destroy(Maintenance $maintenance)
    {
        $equipment_id = $maintenance->equipment_id;
        
        try {
            $maintenance->delete();
            return redirect()->route('maintenance.index', $equipment_id)
                            ->with('success', 'Mantenimiento eliminado correctamente');
        } catch (\Exception $e) {
            Log::error('Error al eliminar mantenimiento: ' . $e->getMessage());
            return redirect()->back()
                            ->with('error', 'Error al eliminar el mantenimiento');
        }
    }

    public function generatePDF(Maintenance $maintenance)
    {
        $equipment = Equipment::findOrFail($maintenance->equipment_id);
        
        $pdf = PDF::loadView('maintenances.pdf', compact('maintenance', 'equipment'));
        return $pdf->stream('mantenimiento-' . $maintenance->id . '.pdf');
    }
}
