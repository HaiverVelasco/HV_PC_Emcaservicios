<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\Maintenance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

class MaintenanceController extends Controller
{
    protected $typeTranslations = [
        'Preventivo'      => 'Preventive',
        'Correctivo'      => 'Corrective',
        'Instalación'     => 'Installation',
        'Desinstalación'  => 'Disassembly'
    ];

    protected $failureTranslations = [
        'Desconocido' => 'Unknown',
        'Sin Fallas'  => 'No Failures'
    ];

    public function __construct()
    {
        // Aplicar middleware de admin a las acciones que requieren privilegios
        $this->middleware('admin')->except(['index', 'show']);
    }

    public function index($equipment_id)
    {
        $equipment = Equipment::with(['maintenances' => function ($query) {
            $query->orderBy('date', 'desc');
        }])->findOrFail($equipment_id);

        return view('maintenances.index', compact('equipment'));
    }

    public function create($equipment_id)
    {
        $equipment = Equipment::findOrFail($equipment_id);
        return view('maintenances.create', compact('equipment'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'equipment_id'      => 'required|exists:equipment,id',
            'date'              => 'required|date',
            'type'              => 'required|in:Preventivo,Correctivo,Instalación,Desinstalación',
            'description'       => 'required|string',
            'technician'        => 'required|string|max:255',
        ]);

        $validated['type'] = $this->typeTranslations[$validated['type']] ?? $validated['type'];

        if (isset($validated['failure']) && array_key_exists($validated['failure'], $this->failureTranslations)) {
            $validated['failure'] = $this->failureTranslations[$validated['failure']];
        }

        try {
            $maintenance = Maintenance::create($validated);
            // Si necesitas actualizar algún otro campo del equipo, hazlo aquí (sin usar last_update_date)
            return redirect()->route('maintenance.index', $request->equipment_id)
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
    }

    public function update(Request $request, Maintenance $maintenance)
    {
        $validated = $request->validate([
            'date'              => 'required|date',
            'type'              => 'required|in:Preventivo,Correctivo,Instalación,Desinstalación',
            'description'       => 'required|string',
            'technician'        => 'required|string|max:255',
        ]);

        $validated['type'] = $this->typeTranslations[$validated['type']] ?? $validated['type'];

        if (isset($validated['failure']) && array_key_exists($validated['failure'], $this->failureTranslations)) {
            $validated['failure'] = $this->failureTranslations[$validated['failure']];
        }

        try {
            $maintenance->update($validated);
            // Si necesitas actualizar algún otro campo del equipo, hazlo aquí (sin usar last_update_date)
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
