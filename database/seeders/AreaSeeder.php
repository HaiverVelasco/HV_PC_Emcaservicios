<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AreaSeeder extends Seeder
{
    public function run(): void
    {
        $areas = [
            [
                'name' => 'Area de Gerencia',
                'description' => 'Área de dirección y gestión estratégica',
                'status' => true,
                'color' => '#276fb7',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Area de Control Interno',
                'description' => 'Área de control y auditoría interna',
                'status' => true,
                'color' => '#276fb7',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Area Administrativa',
                'description' => 'Área de gestión administrativa',
                'status' => true,
                'color' => '#276fb7',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Área Jurídica',
                'description' => 'Área de asuntos legales',
                'status' => true,
                'color' => '#276fb7',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Área Financiera',
                'description' => 'Área de gestión financiera',
                'status' => true,
                'color' => '#276fb7',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Área Técnica',
                'description' => 'Área de soporte y gestión técnica',
                'status' => true,
                'color' => '#276fb7',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Área de Planeación',
                'description' => 'Área de planificación y proyectos',
                'status' => true,
                'color' => '#276fb7',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Área de Aseguramiento',
                'description' => 'Área de aseguramiento y gestión de la calidad',
                'status' => true,
                'color' => '#276fb7',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Área de Subgerencia Administrativa',
                'description' => 'Área de subgerencia y apoyo administrativo',
                'status' => true,
                'color' => '#276fb7',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Área de Contabilidad',
                'description' => 'Área de contabilidad',
                'status' => true,
                'color' => '#276fb7',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        DB::table('areas')->insert($areas);
    }
}
