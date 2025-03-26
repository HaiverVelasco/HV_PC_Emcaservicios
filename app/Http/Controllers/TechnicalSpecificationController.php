<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\TechnicalSpecification;
use Illuminate\Http\Request;

class TechnicalSpecificationController extends Controller
{
    public function edit(Equipment $equipment)
    {
        $specs = $equipment->technicalSpecifications ?? new TechnicalSpecification();
        
        return view('equipment.specs.edit', [
            'equipment' => $equipment,
            'specs' => $specs
        ]);
    }

    public function update(Request $request, Equipment $equipment)
    {
        $validated = $request->validate([
            'type' => 'required|in:COMPUTER,PRINTER,SERVER,NETWORK,PERIPHERAL,OTHER',
            'processor' => 'nullable|string|max:100',
            'operating_system' => 'nullable|string|max:50',
            'ram' => 'nullable|string|max:20',
            'storage' => 'nullable|string|max:50',
            'voltage' => 'nullable|numeric',
            'power' => 'nullable|numeric'
        ]);
        
        $equipment->technicalSpecifications()->updateOrCreate(
            ['equipment_id' => $equipment->id],
            $validated
        );
        
        return redirect()->route('equipment.show', $equipment)
                        ->with('success', 'Especificaciones técnicas actualizadas');
    }
}
