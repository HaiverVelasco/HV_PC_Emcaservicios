<?php

namespace App\Exports;

use App\Models\Equipment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class EquipmentExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $area_id;
    protected $estado;

    public function __construct($area_id = null, $estado = null)
    {
        $this->area_id = $area_id;
        $this->estado = $estado;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $query = Equipment::with(['area']);

        // Filtrar por área si se proporciona
        if ($this->area_id) {
            $query->where('area_id', $this->area_id);
        }

        // Filtrar por estado si se proporciona
        if ($this->estado) {
            $query->where('status', $this->estado);
        }

        return $query->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'Área',
            'Nombre del Equipo',
            'Tipo de Equipo',
            'Marca',
            'Modelo',
            'Número de Serie',
            'Código de Inventario',
            'Responsable Directo',
            'Responsable Indirecto',
            'Estado',
            'Ubicación',
            'Función del Equipo',
            'Fecha de Adquisición',
            'Fecha de Creación',
            'Fecha de Actualización'
        ];
    }

    /**
     * @param mixed $row
     * @return array
     */
    public function map($row): array
    {
        return [
            $row->id,
            $row->area ? $row->area->name : 'N/A',
            $row->equipment_name,
            $row->equipment_type,
            $row->brand,
            $row->model,
            $row->serial_number ?? $row->inventory_code,
            $row->inventory_code,
            $row->direct_responsible,
            $row->indirect_responsible,
            $row->status,
            $row->equipment_location,
            $row->equipment_function,
            $row->acquisition_date,
            $row->created_at->format('Y-m-d'),
            $row->updated_at->format('Y-m-d')
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Estilo para los encabezados
            1    => ['font' => ['bold' => true], 'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => 'DDDDDD']]],
        ];
    }
}
