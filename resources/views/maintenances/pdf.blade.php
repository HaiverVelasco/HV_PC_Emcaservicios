<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Registro de Mantenimiento-{{ $equipment->inventory_code }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
        }

        .container {
            width: 100%;
        }

        .header {
            padding: 10px;
            border-bottom: 2px solid #000;
            margin-bottom: 20px;
            text-align: center;
        }

        .header img {
            max-width: 150px;
            height: auto;
        }

        h1,
        h2,
        h3 {
            margin: 0;
            padding: 5px 0;
        }

        .info-section {
            margin-bottom: 20px;
            padding: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        }

        table,
        th,
        td {
            border: 1px solid #eee;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
            font-size: 12px;
            vertical-align: middle;
        }

        th {
            background-color: #fcfcfc;
            font-weight: bold;
            color: #555;
            border-bottom: 1px solid #ddd;
        }

        tr:nth-child(even) {
            background-color: #fdfdfd;
        }

        .signature-section {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
        }

        .signature-box {
            width: 45%;
            text-align: center;
        }

        .signature-line {
            border-top: 1px solid #000;
            margin-top: 40px;
            padding-top: 5px;
        }

        .maintenance-type {
            font-weight: bold;
            text-transform: uppercase;
            padding: 4px 8px;
            border-radius: 4px;
            display: inline-block;
        }

        .preventivo {
            background-color: #e6f7e6;
            color: #1e7e34;
            border: 1px solid #c3e6cb;
        }

        .correctivo {
            background-color: #f8d7da;
            color: #a71d2a;
            border: 1px solid #f5c6cb;
        }

        .instalacion {
            background-color: #cce5ff;
            color: #004085;
            border: 1px solid #b8daff;
        }

        .desinstalacion {
            background-color: #e2e3e5;
            color: #383d41;
            border: 1px solid #d6d8db;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <img src="{{ public_path('imgs/Emcaservicios.png') }}" alt="Logo Emcaservicios">
            <h1>REGISTRO DE MANTENIMIENTO</h1>
            <p>{{ date('d/m/Y', strtotime($maintenance->date)) }}</p>
        </div>

        <div class="info-section">
            <h2>INFORMACIÓN DEL EQUIPO</h2>
            <table>
                <tr>
                    <th>Serial</th>
                    <td>{{ $equipment->inventory_code }}</td>
                    <th>Nombre del Equipo</th>
                    <td>{{ $equipment->equipment_name }}</td>
                </tr>
                <tr>
                    <th>Marca</th>
                    <td>{{ $equipment->brand }}</td>
                    <th>Modelo</th>
                    <td>{{ $equipment->model }}</td>
                </tr>
                <tr>
                    <th>Ubicación</th>
                    <td>{{ $equipment->equipment_location }}</td>
                    <th>Responsable</th>
                    <td colspan="3">{{ $equipment->direct_responsible }}</td>
                </tr>
            </table>
        </div>

        <div class="info-section">
            <h2>DETALLES DEL MANTENIMIENTO</h2>
            <table>
                <tr>
                    <th>Tipo de Mantenimiento</th>
                    <td>
                        <span class="maintenance-type {{ strtolower($maintenance->type) }}">
                            {{ $maintenance->type }}
                        </span>
                    </td>
                    <th>Fecha</th>
                    <td>{{ date('d/m/Y', strtotime($maintenance->date)) }}</td>
                </tr>
                <tr>
                    <th>Técnico Responsable</th>
                    <td colspan="3">{{ $maintenance->technician }}</td>
                </tr>
                <tr>
                    <th>Descripción</th>
                    <td colspan="3">{{ $maintenance->description }}</td>
                </tr>
            </table>
        </div>

        <div style="text-align: center; margin-top: 30px; font-size: 10px;">
            <p>Este documento fue generado el {{ date('d/m/Y H:i:s') }}</p>
            <p>Emcaservicios S.A. E.S.P - Todos los derechos reservados</p>
        </div>
    </div>
</body>

</html>