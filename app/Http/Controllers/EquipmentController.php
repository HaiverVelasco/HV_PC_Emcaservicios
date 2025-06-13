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
        // Aplicar middleware de admin a las acciones que requieren privilegios de administrador
        $this->middleware('admin')->only([
            'create', 'store', 'edit', 'update', 'destroy', 'deleteImage'
        ]);
        
        // Registrar información del middleware para depuración
        Log::info('EquipmentController middleware establecido');
    }

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
        return view('equipments.index', compact('areas'));
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'equipment_type' => 'required|in:computador,impresora,ups,scanner,telefonia,otro',
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
                'description' => 'nullable|string',
                'technician' => 'nullable|string|max:255',
                'equipment_function' =>'nullable|string',
                'direct_responsible' => 'nullable|string|max:255',
            ]);

            // Para debug
            Log::info('Tipo de equipo recibido:', ['equipment_type' => $request->equipment_type]);

            // Extraer los datos de mantenimiento
            $maintenanceData = [
                'type' => $request->maintenance_type,
                'depreciation' => $request->depreciation,
                'bad_operation' => $request->bad_operation,
                'bad_installation' => $request->bad_installation,
                'accessories' => $request->accessories,
                'failure' => $request->failure,
                'date' => now(),
                'description' => $request->description ?? $request->observations,
                'technician' => $request->technician
            ];

            // Filtrar los campos que no pertenecen a la tabla de equipos
            $equipmentData = collect($validatedData)
                ->except(['maintenance_type', 'depreciation', 'bad_operation', 'bad_installation', 'accessories', 'failure', 'description', 'technician'])
                ->toArray();

            $equipment = Equipment::create($equipmentData);

            // Verificar si realmente hay datos de mantenimiento para crear un registro
            $hasMaintenanceData = false;
            foreach ($maintenanceData as $key => $value) {
                if (!empty($value) && $key != 'date') {  // La fecha siempre estará presente
                    $hasMaintenanceData = true;
                    break;
                }
            }

            // Crear registro de mantenimiento solo si hay datos relevantes
            if ($hasMaintenanceData) {
                // Siempre creamos un solo registro para un equipo nuevo
                $equipment->maintenances()->create($maintenanceData);
                Log::info('Datos de mantenimiento guardados exitosamente:', $maintenanceData);
            } else {
                Log::info('No se detectaron datos de mantenimiento relevantes. No se creó registro.');
            }

            // Handle image uploads
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    // Generar un nombre único para la imagen
                    $filename = uniqid() . '_' . time() . '.' . $image->getClientOriginalExtension();
                    
                    // Mover la imagen a la carpeta pública
                    $image->move(public_path('uploads/equipment_images'), $filename);
                    
                    // Guardar la referencia en la base de datos con la ruta relativa
                    $equipment->images()->create([
                        'url' => 'uploads/equipment_images/' . $filename,
                        'description' => 'Imagen de ' . $equipment->equipment_name
                    ]);
                }
            }

            // Obtener el nombre del área
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
            // Vista de lista general
            $areas = Area::with('equipment.images')->get();
            return view('equipments.index', compact('areas'));
        }

        // Vista de equipo individual - usar la nueva vista detail.blade.php
        $equipment->load('images'); // Cargar las imágenes
        return view('equipments.detail', compact('equipment'));
    }

    public function edit(Equipment $equipment)
    {
        $equipment->load('images'); // Ensure images are eager loaded
        $areas = Area::all();
        return view('equipments.edit', compact('equipment', 'areas'));
    }

    public function update(Request $request, Equipment $equipment)
    {
        try {
            $validatedData = $request->validate([
                'equipment_type' => 'required|in:computador,impresora,ups,scanner,telefonia,otro',
                'area_id' => 'required|exists:areas,id',
                'company_name' => 'nullable|string|max:255',
                'city' => 'nullable|string|max:255',
                'inventory_code' => 'required|string|max:255|unique:equipment,inventory_code,' . $equipment->id,
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
                'description' => 'nullable|string',
                'technician' => 'nullable|string|max:255',
                'equipment_function' =>'nullable|string',
                'direct_responsible' => 'nullable|string|max:255',
                'new_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            // Para debug
            Log::info('Tipo de equipo actualizado:', ['equipment_type' => $request->equipment_type]);

            // Extraer los datos de mantenimiento que tienen valores
            $maintenanceData = [];
            
            // Solo incluir en el array los campos que tienen un valor
            if ($request->filled('maintenance_type')) {
                $maintenanceData['type'] = $request->maintenance_type;
            }
            
            if ($request->filled('depreciation')) {
                $maintenanceData['depreciation'] = $request->depreciation;
            }
            
            if ($request->filled('bad_operation')) {
                $maintenanceData['bad_operation'] = $request->bad_operation;
            }
            
            if ($request->filled('bad_installation')) {
                $maintenanceData['bad_installation'] = $request->bad_installation;
            }
            
            if ($request->filled('accessories')) {
                $maintenanceData['accessories'] = $request->accessories;
            }
            
            if ($request->filled('failure')) {
                $maintenanceData['failure'] = $request->failure;
            }
            
            if ($request->filled('description')) {
                $maintenanceData['description'] = $request->description;
            } elseif ($request->filled('observations')) {
                $maintenanceData['description'] = $request->observations;
            }
            
            if ($request->filled('technician')) {
                $maintenanceData['technician'] = $request->technician;
            }
            
            // Siempre actualizar la fecha al modificar un registro
            $maintenanceData['date'] = now();

            // Filtrar los campos que no pertenecen a la tabla de equipos
            $equipmentData = collect($validatedData)
                ->except(['maintenance_type', 'depreciation', 'bad_operation', 'bad_installation', 'accessories', 'failure', 'description', 'technician'])
                ->toArray();

            $equipment->update($equipmentData);

            // Buscar si ya existe un registro de mantenimiento para este equipo
            $maintenance = $equipment->maintenances()->first();
            
            // Si hay datos de mantenimiento para actualizar o crear
            if (count($maintenanceData) > 1) { // > 1 porque siempre tenemos la fecha
                if ($maintenance) {
                    // Actualizar solo los campos proporcionados en el registro existente
                    $maintenance->update($maintenanceData);
                    Log::info('Registro de mantenimiento actualizado parcialmente:', $maintenanceData);
                } else {
                    // Crear un nuevo registro si no existe
                    $equipment->maintenances()->create($maintenanceData);
                    Log::info('Nuevo registro de mantenimiento creado:', $maintenanceData);
                }
            } else {
                Log::info('No se detectaron cambios en los datos de mantenimiento. No se actualizó el registro.');
            }

            // Handle new image uploads
            if ($request->hasFile('new_images')) {
                foreach ($request->file('new_images') as $image) {
                    // Generar un nombre único para la imagen
                    $filename = uniqid() . '_' . time() . '.' . $image->getClientOriginalExtension();
                    
                    // Mover la imagen a la carpeta pública
                    $image->move(public_path('uploads/equipment_images'), $filename);
                    
                    // Guardar la referencia en la base de datos con la ruta relativa
                    $equipment->images()->create([
                        'url' => 'uploads/equipment_images/' . $filename,
                        'description' => 'Imagen de ' . $equipment->equipment_name
                    ]);
                }
            }

            // Obtener el nombre del área
            $area = Area::find($validatedData['area_id']);

            // Guardar mensajes en la sesión
            session()->flash('success', '¡Equipo ' . $equipment->inventory_code . ' actualizado exitosamente!');
            session()->flash('info', 'Equipo asignado al área: ' . $area->name);
            
            // Usar una redirección directa con URL completa
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
            
            // Eliminar las imágenes físicas del directorio público
            foreach ($equipment->images as $image) {
                $imagePath = public_path($image->url);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
            
            // Eliminar registros de mantenimiento asociados al equipo
            $maintenancesDeleted = $equipment->maintenances()->delete();
            Log::info("Mantenimientos eliminados para el equipo {$inventoryCode}", [
                'equipment_id' => $equipment->id,
                'maintenance_count' => $maintenancesDeleted
            ]);
            
            // La eliminación de los registros en la tabla images se manejará 
            // automáticamente por la relación definida en el modelo Equipment
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
        $equipment->load(['area', 'images', 'maintenances' => function($query) {
            $query->latest('date'); // Ordenar por fecha, más reciente primero
        }]);
        
        // Obtener el mantenimiento más reciente
        $latestMaintenance = $equipment->maintenances->first();
        
        
        $pdf = PDF::loadView('equipments.pdf', compact('equipment', 'latestMaintenance'));
        // Usar orientación horizontal si hay muchas imágenes
        if ($equipment->images->count() > 2) {
            $pdf->setPaper('a4', 'landscape');
        }
        
        return $pdf->download('equipo-' . $equipment->inventory_code . '.pdf');
    }

    public function deleteImage(Image $image)
    {
        try {
            // Eliminar el archivo físico si existe (ruta completa del archivo)
            $imagePath = public_path($image->url);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
            
            // Eliminar el registro de la base de datos
            $image->delete();
            
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
