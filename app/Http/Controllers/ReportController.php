<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\Area;
use Barryvdh\DomPDF\Facade\PDF;

class ReportController extends Controller
{
    public function equipmentPdf(Equipment $equipment)
    {
        $equipment->load([
            'area',
            'company',
            'currentResponsible',
            'technicalSpecifications',
            'maintenances'
        ]);
        
        $pdf = PDF::loadView('reports.equipment', [
            'equipment' => $equipment,
            'title' => 'Hoja de Vida del Equipo'
        ]);
        
        return $pdf->stream("hoja_vida_{$equipment->inventory_code}.pdf");
    }

    public function areaInventory(Area $area)
    {
        $equipments = $area->equipments()
                        ->with('currentResponsible')
                        ->orderBy('name_es')
                        ->get();
        
        $pdf = PDF::loadView('reports.area-inventory', [
            'area' => $area,
            'equipments' => $equipments,
            'date' => now()->format('d/m/Y')
        ]);
        
        return $pdf->download("inventario_{$area->name_es}.pdf");
    }
}
