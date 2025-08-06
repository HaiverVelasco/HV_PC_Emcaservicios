<?php

namespace App\Http\Controllers;

use App\Exports\EquipmentMultiSheetExport;
use App\Models\Area;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    /**
     * Mostrar la vista para generar reportes
     */
    public function index(Request $request)
    {
        $areas = Area::all();
        $selectedAreaId = $request->query('area_id');
        return view('reports.index', compact('areas', 'selectedAreaId'));
    }

    /**
     * Generar y descargar el reporte de equipos
     */
    public function exportEquipment(Request $request)
    {
        $request->validate([
            'area_id' => 'nullable|exists:areas,id',
            'estado' => 'nullable|string',
        ]);

        $area_id = $request->input('area_id');
        $estado = $request->input('estado');

        // Obtener nombre del área para el nombre del archivo si se seleccionó una
        $fileName = 'equipos';
        if ($area_id) {
            $area = Area::find($area_id);
            $fileName .= '_' . str_replace(' ', '_', strtolower($area->name));
        }

        // Añadir el estado al nombre del archivo si se seleccionó uno
        if ($estado) {
            $fileName .= '_' . str_replace(' ', '_', strtolower($estado));
        }

        $fileName .= '_' . date('Y-m-d') . '.xlsx';

        return Excel::download(new EquipmentMultiSheetExport($area_id, $estado), $fileName);
    }
}
