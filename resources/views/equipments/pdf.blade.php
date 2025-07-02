<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Equipo {{ $equipment->inventory_code }}</title>
    <style>
        :root {
            --primary-color: #003366;
            --secondary-color: #276fb7;
            --green-color: #4CAF50;
            --text-color: #222;
            --border-color: #000;
            --bg-light: #f8f9fa;
            --bg-section: #f4f7fb;
            --cell-bg: #ffffff;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            font-size: 9px;
            line-height: 1.3;
            color: var(--text-color);
            padding: 12px;
            background: #f6f8fa;
        }

        .container {
            max-width: 100%;
            margin: 0 auto;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.07);
            padding: 10px 14px 14px 14px;
        }

        /* Header */
        .header {
            display: flex;
            flex-wrap: nowrap;
            border: 2px solid var(--border-color);
            margin-bottom: 12px;
            border-radius: 6px 6px 0 0;
            background: var(--bg-section);
            align-items: stretch;
            overflow: hidden;
        }

        /* Sección del logo */
        .logo-section {
            padding: 8px 4px;
            background-color: #f0f0f0;
            position: relative;
            text-align: center
        }

        /* Imagen del logo */
        .logo {
            max-width: 70px;
            max-height: 70px;
            width: auto;
            height: auto;
        }

        /* Sección del título */
        .title-section {
            flex: 1 1 auto;
            padding: 10px 8px;
            background-color: #f8f8f8;
            display: flex;
            flex-direction: column;
            justify-content: center;
            text-align: center;
        }

        /* Título principal */
        .title-section h1 {
            font-size: 16px;
            font-weight: bold;
            margin: 0 0 4px 0;
            letter-spacing: 1px;
            line-height: 1.2;
        }

        /* Texto adicional */
        .title-section p {
            font-size: 9px;
            margin: 1px 0;
            line-height: 1.1;
        }

        /* Sección del código */
        .code-section {
            padding: 10px 4px;
            background-color: var(--primary-color);
            color: white;
            font-weight: bold;
            font-size: 11px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Main Content */
        .main-content {
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-bottom: 10px;
        }

        /* Section Styling */
        .section {
            border: 1px solid var(--border-color);
            margin-bottom: 10px;
            border-radius: 6px;
            background: var(--bg-section);
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.03);
        }

        .section-header {
            background-color: var(--primary-color);
            color: white;
            padding: 6px 12px;
            font-weight: bold;
            text-align: center;
            font-size: 10px;
            border-radius: 6px 6px 0 0;
            letter-spacing: 0.5px;
        }

        .section-content {
            padding: 0;
        }

        /* Table Styling */
        .field-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        .field-table td {
            border: 1px solid #ddd;
            padding: 8px 10px;
            vertical-align: middle;
            font-size: 8.5px;
        }

        .field-table .label-cell {
            background-color: var(--bg-light);
            font-weight: 600;
            color: var(--primary-color);
            text-align: right;
            width: 120px;
            border-right: 2px solid var(--primary-color);
        }

        .field-table .value-cell {
            background-color: var(--cell-bg);
            text-align: left;
            word-break: break-word;
        }

        .field-table .full-width {
            width: 100%;
        }

        /* Two column layout for general info */
        .two-col-table {
            display: flex;
            gap: 2px;
        }

        .two-col-table .col-table {
            flex: 1;
        }

        .two-col-table .field-table {
            border-radius: 0 0 4px 4px;
        }

        /* Status */
        .status {
            padding: 3px 10px;
            border-radius: 12px;
            font-weight: bold;
            font-size: 8px;
            text-transform: uppercase;
            display: inline-block;
            text-align: center;
            min-width: 80px;
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

        /* Observations */
        .observations-cell {
            background: var(--bg-light);
            padding: 10px;
            font-size: 8.5px;
            color: #444;
            min-height: 60px;
            vertical-align: top;
            border: 1px solid #ddd;
        }

        /* Footer */
        .footer {
            text-align: center;
            margin-top: 18px;
            padding-top: 10px;
            border-top: 1px solid var(--border-color);
            font-size: 7.5px;
            color: #666;
            letter-spacing: 0.2px;
        }

        /* Responsive for print */
        @media print {
            body {
                padding: 5px;
                background: white;
            }

            .container {
                box-shadow: none;
                border-radius: 0;
                padding: 0;
            }

            .header,
            .section {
                border-radius: 0;
                box-shadow: none;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="logo-section">
                <img src="{{ public_path('imgs/Emcaservicios.png') }}" alt="Logo" class="logo">
            </div>
            <div class="title-section">
                <h1>HOJA DE VIDA DEL EQUIPO</h1>
                <p>EMPRESA CAUCANA DE SERVICIOS PÚBLICOS S.A. E.S.P.</p>
                <p>Carrera 4 N° 22N-02 / Edificio de Infraestructura, primer piso</p>
                <p>Popayán - Cauca - Colombia</p>
            </div>
            <div class="code-section">
                {{ 'Cod.Inventario: ' . $equipment->inventory_code }}
            </div>
        </div>

        <div class="main-content">
            <!-- Información General -->
            <div class="section">
                <div class="section-header">INFORMACIÓN GENERAL</div>
                <div class="section-content">
                    <div class="two-col-table">
                        <div class="col-table">
                            <table class="field-table">
                                <tr>
                                    <td class="label-cell">Nombre:</td>
                                    <td class="value-cell">{{ $equipment->equipment_name }}</td>
                                </tr>
                                <tr>
                                    <td class="label-cell">Área:</td>
                                    <td class="value-cell">{{ $equipment->area->name }}</td>
                                </tr>
                                <tr>
                                    <td class="label-cell">Tipo:</td>
                                    <td class="value-cell">{{ $equipment->equipment_type ?: 'No especificado' }}</td>
                                </tr>
                                <tr>
                                    <td class="label-cell">Ubicación:</td>
                                    <td class="value-cell">{{ $equipment->equipment_location ?: 'No especificada' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="label-cell">Función:</td>
                                    <td class="value-cell">{{ $equipment->equipment_function }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-table">
                            <table class="field-table">
                                <tr>
                                    <td class="label-cell">Estado:</td>
                                    <td class="value-cell">
                                        <span class="status status-{{ strtolower($equipment->status) }}">
                                            {{ $equipment->status }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="label-cell">Resp. Directo:</td>
                                    <td class="value-cell">{{ $equipment->direct_responsible ?: 'No especificado' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="label-cell">Resp. Indirecto:</td>
                                    <td class="value-cell">{{ $equipment->indirect_responsible ?: 'No especificado' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="label-cell">Última Act.:</td>
                                    <td class="value-cell">
                                        {{ $equipment->last_update_date ? Carbon\Carbon::parse($equipment->last_update_date)->format('d/m/Y') : 'No registrada' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="label-cell">Fabricante:</td>
                                    <td class="value-cell">{{ $equipment->manufacturer ?: 'No especificado' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Especificaciones Técnicas -->
            <div class="section">
                <div class="section-header">ESPECIFICACIONES TÉCNICAS</div>
                <div class="section-content">
                    <table class="field-table">
                        <tr>
                            <td class="label-cell">Marca:</td>
                            <td class="value-cell">{{ $equipment->brand }}</td>
                            <td class="label-cell">Modelo:</td>
                            <td class="value-cell">{{ $equipment->model }}</td>
                        </tr>
                        <tr>
                            <td class="label-cell">N° Serie:</td>
                            <td class="value-cell">{{ $equipment->serial_number }}</td>
                            <td class="label-cell">Procesador:</td>
                            <td class="value-cell">{{ $equipment->processor }}</td>
                        </tr>
                        <tr>
                            <td class="label-cell">Memoria RAM:</td>
                            <td class="value-cell">{{ $equipment->ram_memory }}</td>
                            <td class="label-cell">Almacenamiento:</td>
                            <td class="value-cell">{{ $equipment->storage }}</td>
                        </tr>
                        <tr>
                            <td class="label-cell">Sistema Operativo:</td>
                            <td class="value-cell" colspan="3">{{ $equipment->operating_system }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Estado del Equipo y Observaciones -->
            <div class="section">
                <div class="section-header">ESTADO DEL EQUIPO Y DIAGNÓSTICO</div>
                <div class="section-content">
                    <table class="field-table">
                        <tr>
                            <td class="label-cell">Mal Funcionamiento:</td>
                            <td class="value-cell">{{ $equipment->bad_operation ?: 'No registrado' }}</td>
                            <td class="label-cell">Mala Instalación:</td>
                            <td class="value-cell">{{ $equipment->bad_installation ?: 'No registrada' }}</td>
                        </tr>
                        <tr>
                            <td class="label-cell">Fallas Detectadas:</td>
                            <td class="value-cell">{{ $equipment->failure ?: 'Sin fallas registradas' }}</td>
                            <td class="label-cell">Accesorios:</td>
                            <td class="value-cell">{{ $equipment->accessories ?: 'No especificados' }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Observaciones -->
            <div class="section">
                <div class="section-header">OBSERVACIONES GENERALES</div>
                <div class="section-content">
                    <table class="field-table">
                        <tr>
                            <td class="observations-cell">
                                {{ $equipment->observations ?: 'Sin observaciones registradas hasta la fecha.' }}
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="footer">
            <p>Documento generado el {{ date('d/m/Y H:i:s') }} | EMCASERVICIOS S.A. E.S.P. - Todos los derechos
                reservados</p>
        </div>
    </div>
</body>

</html>