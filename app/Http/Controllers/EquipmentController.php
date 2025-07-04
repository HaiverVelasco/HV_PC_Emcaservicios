<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Equipment;
use App\Models\Image;
use App\Models\Maintenance;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class EquipmentController extends Controller
{
    /**
     * Constructor para aplicar middleware
     */
    public function __construct()
    {
        $this->middleware('admin')->only([
            'create',
            'store',
            'edit',
            'update',
            'destroy',
            'deleteImage'
        ]);
        Log::info('EquipmentController middleware establecido');
    }

    public function index()
    {
        $areas = Area::with(['equipment' => function ($query) {
            $query->orderBy('inventory_code');
        }])->get();

        return view('equipments.show', compact('areas'));
    }

    public function create()
    {
        $areas = Area::all();
        return view('equipments.index', compact('areas'));
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'equipment_type'        => 'required|in:computador,impresora,ups,scanner,telefonia,otro',
                'area_id'               => 'required|exists:areas,id',
                'company_name'          => 'nullable|string|max:255',
                'city'                  => 'nullable|string|max:255',
                'inventory_code'        => 'required|string|max:255|unique:equipment',
                'equipment_name'        => 'required|string|max:255',
                'brand'                 => 'nullable|string|max:100',
                'model'                 => 'nullable|string|max:100',
                'reference'             => 'nullable|string|max:100',
                'serial_number'         => 'nullable|string|max:100',
                'manufacturer'          => 'nullable|string|max:100',
                'acquisition_date'      => 'nullable|date',
                'operation_start_date'  => 'nullable|date',
                'equipment_location'    => 'nullable|string|max:255',
                'value'                 => 'nullable|numeric',
                'warranty'              => 'nullable|string|max:255',
                'technical_brand_model' => 'nullable|string|max:255',
                'processor'             => 'nullable|string|max:255',
                'operating_system'      => 'nullable|string|max:255',
                'graphic_card'          => 'nullable|string|max:255',
                'ram_memory'            => 'nullable|string|max:255',
                'storage'               => 'nullable|string|max:255',
                'status'                => 'required|in:Bueno,Regular,Malo,Deshabilitado',
                'maintenance_type'      => 'nullable|in:Preventive,Corrective,Installation,Disassembly',
                'depreciation'          => 'nullable|string|max:255',
                'bad_operation'         => 'nullable|string|max:255',
                'bad_installation'      => 'nullable|string|max:255',
                'accessories'           => 'nullable|string|max:255',
                'failure'               => 'nullable|in:Unknown,No Failures',
                'observations'          => 'nullable|string',
                'description'           => 'nullable|string',
                'technician'            => 'nullable|string|max:255',
                'equipment_function'    => 'nullable|string',
                'direct_responsible'    => 'string|max:255',
                'indirect_responsible'  => 'string|max:255',
            ]);

            Log::info('Tipo de equipo recibido:', ['equipment_type' => $request->equipment_type]);

            $maintenanceData = [
                'type'            => $request->maintenance_type,
                'depreciation'    => $request->depreciation,
                'bad_operation'   => $request->bad_operation,
                'bad_installation' => $request->bad_installation,
                'accessories'     => $request->accessories,
                'failure'         => $request->failure,
                'date'            => now(),
                'description'     => $request->description ?? $request->observations,
                'technician'      => $request->technician
            ];

            $equipmentData = collect($validatedData)
                ->except([
                    'maintenance_type',
                    'depreciation',
                    'bad_operation',
                    'bad_installation',
                    'accessories',
                    'failure',
                    'description',
                    'technician'
                ])->toArray();

            $equipment = Equipment::create($equipmentData);

            $hasMaintenanceData = collect($maintenanceData)
                ->except('date')
                ->filter(function ($value) {
                    return !empty($value);
                })
                ->isNotEmpty();

            if ($hasMaintenanceData) {
                $equipment->maintenances()->create($maintenanceData);
                Log::info('Datos de mantenimiento guardados exitosamente:', $maintenanceData);
            } else {
                Log::info('No se detectaron datos de mantenimiento relevantes. No se creó registro.');
            }

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $filename = uniqid() . '_' . time() . '.' . $image->getClientOriginalExtension();
                    $image->move(public_path('storage'), $filename);
                    $equipment->images()->create([
                        'url'         => 'storage/' . $filename,
                        'description' => 'Imagen de ' . $equipment->equipment_name
                    ]);
                }
            }

            $area = Area::find($validatedData['area_id']);

            return redirect()->route('equipment.index')
                ->with('success', 'Equipo creado exitosamente.')
                ->with('info', 'Equipo asignado al área: ' . $area->name);
        } catch (\Exception $e) {
            Log::error('Error al crear equipo: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al crear el equipo: ' . $e->getMessage());
        }
    }

    public function show(Equipment $equipment = null)
    {
        if ($equipment === null) {
            $areas = Area::with('equipment.images')->get();
            return view('equipments.index', compact('areas'));
        }
        $equipment->load('images');
        return view('equipments.detail', compact('equipment'));
    }

    public function edit(Equipment $equipment)
    {
        $equipment->load('images');
        $areas = Area::all();
        $maintenance = Maintenance::where('equipment_id', $equipment->id)->latest()->first();
        return view('equipments.edit', compact('equipment', 'areas', 'maintenance'));
    }

    public function update(Request $request, Equipment $equipment)
    {
        try {
            $validatedData = $request->validate([
                'equipment_type'        => 'required|in:computador,impresora,ups,scanner,telefonia,otro',
                'area_id'               => 'required|exists:areas,id',
                'company_name'          => 'nullable|string|max:255',
                'city'                  => 'nullable|string|max:255',
                'inventory_code'        => 'required|string|max:255|unique:equipment,inventory_code,' . $equipment->id,
                'equipment_name'        => 'required|string|max:255',
                'brand'                 => 'nullable|string|max:100',
                'model'                 => 'nullable|string|max:100',
                'reference'             => 'nullable|string|max:100',
                'serial_number'         => 'nullable|string|max:100',
                'manufacturer'          => 'nullable|string|max:100',
                'acquisition_date'      => 'nullable|date',
                'operation_start_date'  => 'nullable|date',
                'equipment_location'    => 'nullable|string|max:255',
                'value'                 => 'nullable|numeric',
                'warranty'              => 'nullable|string|max:255',
                'technical_brand_model' => 'nullable|string|max:255',
                'processor'             => 'nullable|string|max:255',
                'operating_system'      => 'nullable|string|max:255',
                'graphic_card'          => 'nullable|string|max:255',
                'ram_memory'            => 'nullable|string|max:255',
                'storage'               => 'nullable|string|max:255',
                'status'                => 'required|in:Bueno,Regular,Malo,Deshabilitado',
                'maintenance_type'      => 'nullable|in:Preventive,Corrective,Installation,Disassembly',
                'depreciation'          => 'nullable|string|max:255',
                'bad_operation'         => 'nullable|string|max:255',
                'bad_installation'      => 'nullable|string|max:255',
                'accessories'           => 'nullable|string|max:255',
                'failure'               => 'nullable|in:Unknown,No Failures',
                'observations'          => 'nullable|string',
                'description'           => 'nullable|string',
                'technician'            => 'nullable|string|max:255',
                'equipment_function'    => 'nullable|string',
                'direct_responsible'    => 'string|max:255',
                'indirect_responsible'  => 'string|max:255',
                'new_images.*'          => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            Log::info('Tipo de equipo actualizado:', ['equipment_type' => $request->equipment_type]);

            $maintenanceData = [];
            foreach (
                [
                    'maintenance_type' => 'type',
                    'depreciation'     => 'depreciation',
                    'bad_operation'    => 'bad_operation',
                    'bad_installation' => 'bad_installation',
                    'accessories'      => 'accessories',
                    'failure'          => 'failure',
                    'description'      => 'description',
                    'technician'       => 'technician'
                ] as $field => $key
            ) {
                if ($request->filled($field)) {
                    $maintenanceData[$key] = $request->$field;
                }
            }
            if (!isset($maintenanceData['description']) && $request->filled('observations')) {
                $maintenanceData['description'] = $request->observations;
            }
            $maintenanceData['date'] = now();

            $equipmentData = collect($validatedData)
                ->except([
                    'maintenance_type',
                    'depreciation',
                    'bad_operation',
                    'bad_installation',
                    'accessories',
                    'failure',
                    'description',
                    'technician'
                ])->toArray();

            $equipment->update($equipmentData);

            $maintenance = $equipment->maintenances()->first();

            if (count($maintenanceData) > 1) {
                if ($maintenance) {
                    $maintenance->update($maintenanceData);
                    Log::info('Registro de mantenimiento actualizado parcialmente:', $maintenanceData);
                } else {
                    $equipment->maintenances()->create($maintenanceData);
                    Log::info('Nuevo registro de mantenimiento creado:', $maintenanceData);
                }
            } else {
                Log::info('No se detectaron cambios en los datos de mantenimiento. No se actualizó el registro.');
            }

            if ($request->hasFile('new_images')) {
                foreach ($request->file('new_images') as $image) {
                    $filename = uniqid() . '_' . time() . '.' . $image->getClientOriginalExtension();
                    $image->move(public_path('storage'), $filename);
                    $equipment->images()->create([
                        'url'         => 'storage/' . $filename,
                        'description' => 'Imagen de ' . $equipment->equipment_name
                    ]);
                }
            }

            $area = Area::find($validatedData['area_id']);

            session()->flash('success', '¡Equipo ' . $equipment->inventory_code . ' actualizado exitosamente!');
            session()->flash('info', 'Equipo asignado al área: ' . $area->name);

            return redirect()->to(url('/equipments'));
        } catch (\Exception $e) {
            Log::error('Error al actualizar equipo: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Error al actualizar el equipo: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(Equipment $equipment)
    {
        try {
            $inventoryCode = $equipment->inventory_code;

            foreach ($equipment->images as $image) {
                $imagePath = public_path($image->url);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }

            $maintenancesDeleted = $equipment->maintenances()->delete();
            Log::info("Mantenimientos eliminados para el equipo {$inventoryCode}", [
                'equipment_id'     => $equipment->id,
                'maintenance_count' => $maintenancesDeleted
            ]);

            $equipment->delete();

            return redirect()->route('equipment.index')
                ->with('success', "Equipo {$inventoryCode} eliminado exitosamente");
        } catch (\Exception $e) {
            Log::error('Error al eliminar equipo: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Error al eliminar el equipo: ' . $e->getMessage());
        }
    }

    public function generatePDF(Equipment $equipment)
    {
        $equipment->load([
            'area',
            'images',
            'maintenances' => function ($query) {
                $query->latest('date');
            }
        ]);
        $latestMaintenance = $equipment->maintenances->first();

        $pdf = PDF::loadView('equipments.pdf', compact('equipment', 'latestMaintenance'));
        if ($equipment->images->count() > 2) {
            $pdf->setPaper('a4', 'landscape');
        }
        return $pdf->stream('equipo-' . $equipment->inventory_code . '.pdf');
    }

    public function deleteImage(Image $image)
    {
        try {
            $imagePath = public_path($image->url);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
            $image->delete();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
