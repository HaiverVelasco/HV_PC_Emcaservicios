<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Registro de Observación</title>
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
            padding: 15px 10px;
            border-bottom: 2px solid #000;
            margin-bottom: 25px;
            text-align: center;
        }

        .header img {
            max-width: 130px;
            height: auto;
            margin-bottom: 10px;
        }

        .header h1 {
            font-size: 16px;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .header h2 {
            font-size: 14px;
            margin: 5px 0;
            color: #333;
        }

        .header p {
            font-size: 10px;
            margin-top: 5px;
            color: #555;
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
            border: 1px solid #ccc;
        }

        table,
        th,
        td {
            border: 1px solid #ccc;
        }

        th,
        td {
            padding: 12px;
            text-align: left;
            font-size: 11px;
            vertical-align: middle;
        }

        th {
            background-color: #f5f5f5;
            font-weight: 600;
            color: #333;
            width: 25%;
        }

        tr:nth-child(even) {
            background-color: #fbfbfb;
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

        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 10px;
            padding: 10px 0;
            border-top: 1px solid #999;
        }

        .observation-text {
            padding: 20px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin: 20px 0;
            line-height: 1.6;
            font-size: 12px;
            min-height: 100px;
        }

        .observation-metadata {
            font-style: italic;
            text-align: right;
            font-size: 10px;
            color: #666;
            margin-top: 15px;
            margin-bottom: 30px;
        }

        h3 {
            font-size: 14px;
            color: #333;
            margin-bottom: 15px;
            padding-bottom: 5px;
            border-bottom: 1px solid #eee;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <img src="{{ public_path('imgs/Emcaservicios.png') }}" alt="Logo Emcaservicios">
            <h2>REGISTRO DE OBSERVACIÓN</h2>
            <h1>EMPRESA CAUCANA DE SERVICIOS PÚBLICOS S.A. E.S.P.</h1>
            <p>Carrera 4 N° 22N-02 / Edificio de Infraestructura, primer piso - Popayán - Cauca - Colombia</p>
        </div>

        <div class="info-section">
            <h3>INFORMACIÓN DEL EQUIPO</h3>
            <table>
                <tr>
                    <th>Número de Serie</th>
                    <td>{{ $equipment->serial_number ?? $equipment->inventory_code }}</td>
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
                    <th>Responsable</th>
                    <td>{{ $equipment->direct_responsible ?? 'No especificado' }}</td>
                    <th>Área</th>
                    <td>{{ $equipment->area->name ?? 'No especificada' }}</td>
                </tr>
                <tr>
                    <th>Estado</th>
                    <td colspan="3">{{ $equipment->status }}</td>
                </tr>
            </table>
        </div>

        <div class="info-section">
            <h3>DETALLES DE LA OBSERVACIÓN</h3>
            <table>
                <tr>
                    <th>Fecha de Registro</th>
                    <td>{{ \Carbon\Carbon::parse($observation->created_at)->format('d/m/Y - H:i') }}</td>
                    <th>Registrado por</th>
                    <td>{{ $observation->user_name ?? 'Admin' }}</td>
                </tr>
            </table>

            <div class="observation-text">
                <h3>OBSERVACIÓN:</h3>
                {{ $observation->observation }}
            </div>

        </div>
    </div>

    <div class="footer">
        <p>Documento generado el {{ $date }} - EMCASERVICIOS S.A. E.S.P.</p>
    </div>
</body>

</html>