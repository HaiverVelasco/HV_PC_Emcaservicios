<?php

namespace App\Exports;

use App\Models\Equipment;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class EquipmentSheetExport implements FromQuery, WithTitle, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithColumnFormatting
{
    protected $area_id;             
    protected $area_name;
    protected $estado;

    public function __construct($area_id, $area_name, $estado = null)
    {
        $this->area_id = $area_id;
        $this->area_name = $area_name;
        $this->estado = $estado;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $query = Equipment::query()->with(['area']);

        // Filtrar por área si se proporciona
        if ($this->area_id) {
            $query->where('area_id', $this->area_id);
        }

        // Filtrar por estado si se proporciona
        if ($this->estado) {
            $query->where('status', $this->estado);
        }

        // Ordenar por nombre de equipo
        return $query->orderBy('equipment_name');
    }

    /**
     * @return string
     */
    public function title(): string
    {
        if ($this->estado) {
            return "{$this->area_name} - {$this->estado}";
        }
        return $this->area_name;
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
     * @return array
     */
    public function columnFormats(): array
    {
        return [
            'N' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'O' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'P' => NumberFormat::FORMAT_DATE_DDMMYYYY,
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        // Aplicar estilo a la cabecera
        $sheet->getStyle('A1:P1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '003366'],
            ],
        ]);

        // Obtener el último número de fila
        $lastRow = $sheet->getHighestRow();

        // Aplicar borde a todas las celdas con datos
        $sheet->getStyle('A1:P' . $lastRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => 'CCCCCC'],
                ],
            ],
        ]);

        // Aplicar color alternado a las filas (para mejor legibilidad)
        for ($row = 2; $row <= $lastRow; $row++) {
            if ($row % 2 == 0) {
                $sheet->getStyle('A' . $row . ':P' . $row)->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('F2F2F2');
            }
        }

        return [];
    }
}
