<?php

namespace App\Exports;

use App\Models\Area;
use App\Models\Equipment;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class EquipmentMultiSheetExport implements WithMultipleSheets
{
    protected $area_id;
    protected $estado;

    public function __construct($area_id = null, $estado = null)
    {
        $this->area_id = $area_id;
        $this->estado = $estado;
    }

    /**
     * @return array
     */
    public function sheets(): array
    {
        $sheets = [];

        // Si se selecciona una área específica, solo se crea una hoja para esa área
        if ($this->area_id) {
            $area = Area::find($this->area_id);
            $sheets[] = new EquipmentSheetExport($area->id, $area->name, $this->estado);
        } else {
            // Si no se selecciona ninguna área, se crea una hoja para cada área
            $areas = Area::all();

            // Primero agregamos una hoja con todos los equipos
            $sheets[] = new EquipmentSheetExport(null, 'Todos los Equipos', $this->estado);

            // Luego agregamos una hoja por cada área
            foreach ($areas as $area) {
                // Verificamos si hay equipos en esta área que coinciden con el filtro de estado
                $query = Equipment::where('area_id', $area->id);
                if ($this->estado) {
                    $query->where('status', $this->estado);
                }

                // Solo agregamos una hoja si hay equipos en esta área
                if ($query->count() > 0) {
                    $sheets[] = new EquipmentSheetExport($area->id, $area->name, $this->estado);
                }
            }
        }

        return $sheets;
    }
}
