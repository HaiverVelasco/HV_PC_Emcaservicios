<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Equipment;
use App\Models\Image;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
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
            ]);

            // Para debug
            Log::info('Tipo de equipo recibido:', ['equipment_type' => $request->equipment_type]);

            $equipment = Equipment::create($validatedData);

            // Handle image uploads
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('equipment_images', 'public');
                    
                    $equipment->images()->create([
                        'url' => $path,
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

        // Vista de equipo individual
        return view('equipments.show', compact('equipment'));
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
                'new_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            // Para debug
            Log::info('Tipo de equipo actualizado:', ['equipment_type' => $request->equipment_type]);

            $equipment->update($validatedData);

            // Handle new image uploads
            if ($request->hasFile('new_images')) {
                foreach ($request->file('new_images') as $image) {
                    $path = $image->store('equipment_images', 'public');
                    $equipment->images()->create([
                        'url' => $path,
                        'description' => 'Imagen de ' . $equipment->equipment_name
                    ]);
                }
            }

            // Obtener el nombre del área
            $area = Area::find($validatedData['area_id']);

            return redirect()->route('equipment.show', $equipment->id)
                ->with('success', '¡Equipo actualizado exitosamente! Código de inventario: ' . $equipment->inventory_code)
                ->with('info', 'Equipo asignado al área: ' . $area->name);
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
            
            // Eliminar las imágenes del almacenamiento
            foreach ($equipment->images as $image) {
                Storage::disk('public')->delete($image->url);
            }
            
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
        $equipment->load(['area', 'images']);
        
        $pdf = PDF::loadView('equipments.pdf', compact('equipment'));
        // Usar orientación horizontal si hay muchas imágenes
        if ($equipment->images->count() > 2) {
            $pdf->setPaper('a4', 'landscape');
        }
        
        return $pdf->download('equipo-' . $equipment->inventory_code . '.pdf');
    }

    public function deleteImage(Image $image)
    {
        try {
            Storage::disk('public')->delete($image->url);
            $image->delete();
            
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
