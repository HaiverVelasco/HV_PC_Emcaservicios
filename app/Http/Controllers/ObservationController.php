<?php

namespace App\Http\Controllers;

use App\Models\Observation;
use App\Models\Equipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class ObservationController extends Controller
{
    /**
     * Constructor para aplicar middleware
     */
    public function __construct()
    {
        // Solo aplicar middleware de admin a acciones que requieren modificar datos
        $this->middleware('admin')->only([
            'create',
            'store',
            'edit',
            'update',
            'destroy'
        ]);
    }

    /**
     * Display a listing of observations for a specific equipment.
     */
    public function index(Request $request, $equipmentId = null)
    {
        try {
            if ($equipmentId) {
                $equipment = Equipment::findOrFail($equipmentId);
                $observations = $equipment->observations()
                    ->latest()
                    ->paginate(10);

                return view('observations.index', compact('observations', 'equipment'));
            }

            // Show all observations if no equipment specified
            $observations = Observation::with('equipment')
                ->latest()
                ->paginate(15);

            return view('observations.all', compact('observations'));
        } catch (\Exception $e) {
            Log::error('Error al cargar observaciones: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al cargar las observaciones.');
        }
    }

    /**
     * Show the form for creating a new observation.
     */
    public function create($equipmentId)
    {
        try {
            $equipment = Equipment::findOrFail($equipmentId);
            return view('observations.create', compact('equipment'));
        } catch (\Exception $e) {
            Log::error('Error al cargar formulario de observación: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Equipo no encontrado.');
        }
    }

    /**
     * Store a newly created observation in storage.
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'equipment_id' => 'required|exists:equipment,id',
                'observation' => 'required|string|min:5|max:1000',
                'user_name' => 'nullable|string|max:255',
            ]);

            // If no user_name provided, use authenticated user's name
            if (empty($validatedData['user_name']) && Auth::check()) {
                $validatedData['user_name'] = Auth::user()->name ?? 'Usuario';
            }

            $observation = Observation::create($validatedData);

            Log::info('Nueva observación creada', [
                'observation_id' => $observation->id,
                'equipment_id' => $observation->equipment_id,
                'user' => $observation->user_name
            ]);

            // Redirect based on where the request came from
            if ($request->has('redirect_to') && $request->redirect_to === 'equipment_detail') {
                return redirect()->route('equipment.show', $observation->equipment_id)
                    ->with('success', 'Observación agregada exitosamente.');
            }

            return redirect()->route('observations.index', $observation->equipment_id)
                ->with('success', 'Observación agregada exitosamente.');
        } catch (\Exception $e) {
            Log::error('Error al crear observación: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al agregar la observación: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified observation.
     */
    public function show(Observation $observation)
    {
        try {
            $observation->load('equipment');
            return view('observations.show', compact('observation'));
        } catch (\Exception $e) {
            Log::error('Error al mostrar observación: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Observación no encontrada.');
        }
    }

    /**
     * Show the form for editing the specified observation.
     */
    public function edit(Observation $observation)
    {
        try {
            $observation->load('equipment');
            return view('observations.edit', compact('observation'));
        } catch (\Exception $e) {
            Log::error('Error al cargar formulario de edición: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al cargar la observación.');
        }
    }

    /**
     * Update the specified observation in storage.
     */
    public function update(Request $request, Observation $observation)
    {
        try {
            $validatedData = $request->validate([
                'observation' => 'required|string|min:5|max:1000',
                'user_name' => 'nullable|string|max:255',
            ]);

            $observation->update($validatedData);

            Log::info('Observación actualizada', [
                'observation_id' => $observation->id,
                'equipment_id' => $observation->equipment_id
            ]);

            return redirect()->route('observations.index', $observation->equipment_id)
                ->with('success', 'Observación actualizada exitosamente.');
        } catch (\Exception $e) {
            Log::error('Error al actualizar observación: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al actualizar la observación: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified observation from storage.
     */
    public function destroy(Observation $observation)
    {
        try {
            $equipmentId = $observation->equipment_id;
            $observation->delete();

            Log::info('Observación eliminada', [
                'observation_id' => $observation->id,
                'equipment_id' => $equipmentId
            ]);

            return redirect()->route('observations.index', $equipmentId)
                ->with('success', 'Observación eliminada exitosamente.');
        } catch (\Exception $e) {
            Log::error('Error al eliminar observación: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Error al eliminar la observación: ' . $e->getMessage());
        }
    }

    /**
     * Get observations for an equipment via AJAX
     */
    public function getObservations($equipmentId)
    {
        try {
            $equipment = Equipment::findOrFail($equipmentId);
            $observations = $equipment->observations()
                ->latest()
                ->limit(10)
                ->get();

            return response()->json([
                'success' => true,
                'observations' => $observations,
                'equipment' => $equipment->only(['id', 'inventory_code', 'equipment_name'])
            ]);
        } catch (\Exception $e) {
            Log::error('Error al obtener observaciones via AJAX: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar las observaciones.'
            ], 500);
        }
    }

    /**
     * Generate PDF for a specific observation.
     */
    public function generatePDF(Observation $observation)
    {
        try {
            $observation->load('equipment');
            $equipment = $observation->equipment;

            // Configurar datos para la vista
            $date = now()->format('d/m/Y H:i:s');

            // Generar PDF
            $pdf = PDF::loadView('observations.pdf', compact('observation', 'equipment', 'date'));

            // Establecer opciones de PDF
            $pdf->setPaper('letter', 'portrait');

            // Generar nombre de archivo
            $fileName = 'Observacion_' . $equipment->inventory_code . '_' . date('dmY_His') . '.pdf';

            Log::info('PDF de observación generado', [
                'observation_id' => $observation->id,
                'equipment_id' => $equipment->id
            ]);

            // Descargar PDF
            return $pdf->stream($fileName);
        } catch (\Exception $e) {
            Log::error('Error al generar PDF de observación: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al generar el PDF: ' . $e->getMessage());
        }
    }
}
