<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Equipo {{ $equipment->inventory_code }}</title>
    <style>
        :root {
            --primary-color: #003366;
            --secondary-color: #276fb7;
            --text-color: #333;
            --border-color: #e0e0e0;
        }

        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: var(--text-color);
            margin: 0;
            padding: 20px;
        }

        .header {
            background: linear-gradient(135deg, #003366 0%, #276fb7 100%);
            color: black;
            text-align: center;
            padding: 30px;
            margin-bottom: 40px;
            border-radius: 0 0 15px 15px;
        }

        .logo {
            max-width: 150px;
            margin: 20px 0;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }

        .header h1 {
            font-size: 24px;
            margin: 15px 0;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
        }

        .header p {
            font-size: 16px;
            margin: 10px 0;
        }

        .equipment-info {
            background: #ffffff;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 30px;
            border: 1px solid var(--border-color);
            page-break-inside: avoid;
            margin-bottom: 20px;
        }

        .equipment-info h2 {
            color: var(--primary-color);
            font-size: 20px;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--secondary-color);
            color: #003366;
            border-bottom: 2px solid #276fb7;
            padding-bottom: 8px;
            margin-bottom: 15px;
        }

        .info-row {
            margin: 15px 0;
            display: flex;
            align-items: center;
        }

        .label {
            font-weight: bold;
            min-width: 200px;
            color: var(--primary-color);
        }

        .info-row span:not(.label) {
            color: #333;
            flex: 1;
        }

        .status {
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 14px;
            text-transform: uppercase;
        }

        .status-bueno {
            background: #4CAF50;
            color: white;
        }

        .status-regular {
            background: #FFC107;
            color: black;
        }

        .status-malo {
            background: #F44336;
            color: white;
        }

        .status-deshabilitado {
            background: #9E9E9E;
            color: white;
        }

        .observations {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 6px;
            margin-top: 10px;
        }

        .page-footer {
            text-align: center;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid var(--border-color);
            font-size: 12px;
            color: #666;
        }

        .images-section {
            background: #ffffff;
            border-radius: 8px;
            padding: 20px;
            margin: 30px 0;
            border: 1px solid #e0e0e0;
        }

        .images-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 50px;
            margin-top: 20px;
        }

        .image-container {
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .equipment-image {
            width: 50%;
            max-width: 50%;
            height: auto;
            object-fit: unset;
            display: block;
        }

        .image-info {
            padding: 10px;
            background: #f8f9fa;
            font-size: 14px;
            color: #333;
        }


        @media (max-width: 768px) {
            .images-grid {
                grid-template-columns: 1fr;
            }
        }

        @media print {
            .equipment-info {
                break-inside: avoid;
            }

            .page-break {
                page-break-before: always;
            }
        }
    </style>
</head>

<body>
    <div class="header">
        <img src="{{ public_path('imgs/Emcaservicios.png') }}" alt="Logo" class="logo">
        <h1>HOJA DE VIDA DEL EQUIPO</h1>
        <p>EMPRESA CAUCANA DE SERVICIOS PÚBLICOS S.A. E.S.P.</p>
        <p>Carrera 4 N° 22N-02 / Edificio de Infraestructura, primer piso</p>
        <p>Popayán - Cauca - Colombia</p>
    </div>

    <div class="equipment-info">
        <h2>Información General</h2>
        <div class="info-row">
            <span class="label">Código de Inventario:</span>
            <span>{{ $equipment->inventory_code }}</span>
        </div>
        <div class="info-row">
            <span class="label">Nombre del Equipo:</span>
            <span>{{ $equipment->equipment_name }}</span>
        </div>
        <div class="info-row">
            <span class="label">Área:</span>
            <span>{{ $equipment->area->name }}</span>
        </div>
        <div class="info-row">
            <span class="label">Estado:</span>
            <span class="status status-{{ strtolower($equipment->status) }}">
                {{ $equipment->status }}
            </span>
        </div>
    </div>

    <div class="equipment-info">
        <h2>Especificaciones Técnicas</h2>
        <div class="info-row">
            <span class="label">Marca:</span>
            <span>{{ $equipment->brand }}</span>
        </div>
        <div class="info-row">
            <span class="label">Modelo:</span>
            <span>{{ $equipment->model }}</span>
        </div>
        <div class="info-row">
            <span class="label">Serie:</span>
            <span>{{ $equipment->serial_number }}</span>
        </div>
        <div class="info-row">
            <span class="label">Procesador:</span>
            <span>{{ $equipment->processor }}</span>
        </div>
        <div class="info-row">
            <span class="label">Memoria RAM:</span>
            <span>{{ $equipment->ram_memory }}</span>
        </div>
        <div class="info-row">
            <span class="label">Almacenamiento:</span>
            <span>{{ $equipment->storage }}</span>
        </div>
        <div class="info-row">
            <span class="label">Sistema Operativo:</span>
            <span>{{ $equipment->operating_system }}</span>
        </div>
    </div>

    <div class="equipment-info">
        <h2>Información Adicional</h2>
        <div class="info-row">
            <span class="label">Empresa:</span>
            <span>{{ $equipment->company_name }}</span>
        </div>
        <div class="info-row">
            <span class="label">Ciudad:</span>
            <span>{{ $equipment->city }}</span>
        </div>
        <div class="info-row">
            <span class="label">Última Actualización:</span>
            <span>{{ $equipment->last_update_date ? Carbon\Carbon::parse($equipment->last_update_date)->format('d/m/Y') : 'No registrada' }}</span>
        </div>
        <div class="info-row">
            <span class="label">Referencia:</span>
            <span>{{ $equipment->reference ?: 'No especificada' }}</span>
        </div>
        <div class="info-row">
            <span class="label">Fabricante:</span>
            <span>{{ $equipment->manufacturer ?: 'No especificado' }}</span>
        </div>
        <div class="info-row">
            <span class="label">Fecha de Adquisición:</span>
            <span>{{ $equipment->acquisition_date ? Carbon\Carbon::parse($equipment->acquisition_date)->format('d/m/Y') : 'No registrada' }}</span>
        </div>
        <div class="info-row">
            <span class="label">Ubicación del Equipo:</span>
            <span>{{ $equipment->equipment_location ?: 'No especificada' }}</span>
        </div>
        <div class="info-row">
            <span class="label">Valor:</span>
            <span>${{ number_format($equipment->value, 2, ',', '.') ?: '0,00' }}</span>
        </div>
    </div>

    <div class="equipment-info">
        <h2>Información de Instalación y Garantía</h2>
        <div class="info-row">
            <span class="label">Instalación:</span>
            <span>{{ $equipment->installation ?: 'No especificada' }}</span>
        </div>
        <div class="info-row">
            <span class="label">Garantía:</span>
            <span>{{ $equipment->warranty ?: 'No especificada' }}</span>
        </div>
        <div class="info-row">
            <span class="label">Fecha Inicio Operación:</span>
            <span>{{ $equipment->operation_start_date ? Carbon\Carbon::parse($equipment->operation_start_date)->format('d/m/Y') : 'No registrada' }}</span>
        </div>
        <div class="info-row">
            <span class="label">Marca/Modelo Técnico:</span>
            <span>{{ $equipment->technical_brand_model ?: 'No especificado' }}</span>
        </div>
    </div>

    <div class="equipment-info">
        <h2>Estado y Mantenimiento</h2>
        <div class="info-row">
            <span class="label">Tipo de Mantenimiento:</span>
            <span>{{ $equipment->maintenance_type ?: 'No especificado' }}</span>
        </div>
        <div class="info-row">
            <span class="label">Depreciación:</span>
            <span>{{ $equipment->depreciation ?: 'No especificada' }}</span>
        </div>
        <div class="info-row">
            <span class="label">Mal Funcionamiento:</span>
            <span>{{ $equipment->bad_operation ?: 'No registrado' }}</span>
        </div>
        <div class="info-row">
            <span class="label">Mala Instalación:</span>
            <span>{{ $equipment->bad_installation ?: 'No registrada' }}</span>
        </div>
        <div class="info-row">
            <span class="label">Accesorios:</span>
            <span>{{ $equipment->accessories ?: 'No especificados' }}</span>
        </div>
        <div class="info-row">
            <span class="label">Fallas:</span>
            <span>{{ $equipment->failure ?: 'Sin fallas registradas' }}</span>
        </div>
    </div>

    @if ($equipment->images->count() > 0)
        <div class="images-section">
            <h2>Imágenes del Equipo</h2>
            <div class="images-grid">
                @foreach ($equipment->images as $image)
                    <div class="image-container">
                        <img src="{{ public_path($image->url) }}" alt="Imagen del equipo" class="equipment-image">
                        <div class="image-info">
                            Fecha: {{ Carbon\Carbon::parse($image->created_at)->format('d/m/Y H:i') }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <div class="equipment-info">
        <h2>Observaciones</h2>
        <div class="observations">
            {{ $equipment->observations ?: 'Sin observaciones' }}
        </div>
    </div>

    <div class="page-footer">
        <p>Documento generado el {{ date('d/m/Y H:i:s') }}</p>
        <p>EMCASERVICIOS S.A. E.S.P. - Todos los derechos reservados</p>
    </div>
</body>

</html>
