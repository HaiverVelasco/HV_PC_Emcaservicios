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
        }

        .equipment-info h2 {
            color: var(--primary-color);
            font-size: 20px;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--secondary-color);
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