<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generar Reporte de Equipos - EMCASERVICIOS</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('imgs/Emcaservicios.png') }}">
    <link rel="stylesheet" href="{{ asset('css/pages/show.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('css/pages/edit.css') }}?v={{ time() }}">
    <script src="{{ asset('js/utils/themeToggle.js') }}?v={{ time() }}" defer></script>
    <style>
        /* Estilos base mejorados */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .card {
            background-color: var(--card-bg);
            border-radius: 8px;
            box-shadow: var(--card-shadow);
            overflow: hidden;
            margin-bottom: 20px;
        }

        .card-header {
            background-color: var(--primary-color);
            color: white;
            padding: 15px 20px;
            font-weight: bold;
            font-size: 18px;
        }

        .card-body {
            padding: 20px;
        }

        .row {
            display: flex;
            flex-wrap: wrap;
            margin: 0 -10px;
        }

        .justify-content-center {
            justify-content: center;
        }

        .col-md-8 {
            flex: 0 0 66.66%;
            max-width: 66.66%;
            padding: 0 10px;
        }

        .col-md-6 {
            flex: 0 0 50%;
            max-width: 50%;
            padding: 0 10px;
        }

        .col-md-4 {
            flex: 0 0 33.33%;
            max-width: 33.33%;
            padding: 0 10px;
        }

        .form-select {
            width: 100%;
            padding: 12px 15px;
            border-radius: 6px;
            border: 1px solid var(--border-color);
            background-color: var(--card-bg);
            color: var(--text-color);
            font-size: 16px;
            transition: all 0.3s;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 10px center;
            background-size: 20px;
        }

        .form-select:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(0, 51, 102, 0.2);
        }

        .mb-3 {
            margin-bottom: 1rem;
        }

        .mb-0 {
            margin-bottom: 0;
        }

        .text-md-end {
            text-align: right;
        }

        .col-form-label {
            padding-top: 10px;
        }

        .offset-md-4 {
            margin-left: 33.33%;
        }

        .btn-primary {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
        }

        .btn-primary:hover {
            background-color: var(--secondary-color);
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
        }

        .btn-primary i {
            margin-right: 8px;
        }

        /* Media queries adicionales para mejorar la responsividad */
        @media (max-width: 576px) {
            .card-body {
                padding: 15px;
            }

            .btn-primary {
                width: 100%;
            }
        }

        .invalid-feedback {
            color: #dc3545;
            display: block;
            margin-top: 5px;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <!-- Encabezado existente -->
    <header class="sheet-header">
        <h1 class="text-center">GENERAR REPORTE DE EQUIPOS</h1>
        <div class="company-info">
            <a href="https://www.pdacauca.gov.co/#"><img src="{{ asset('imgs/Emcaservicios.png') }}" alt="Logo empresa"
                    class="company-logo"></a>
            <div class="company-contacts">
                <p>EMPRESA CAUCANA DE SERVICIOS PÚBLICOS S.A. E.S.P.</p>
                <p>Carrera 4 N° 22N-02 / Edificio de Infraestructura, primer piso</p>
                <p>Popayán - Cauca - Colombia</p>
            </div>
        </div>
    </header>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Filtrar Equipos para Reporte') }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('reports.export') }}">
                            @csrf

                            <div class="row mb-3">
                                <label for="area_id"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Área') }}</label>

                                <div class="col-md-6">
                                    <select id="area_id" class="form-select @error('area_id') is-invalid @enderror"
                                        name="area_id">
                                        <option value="">Todas las áreas</option>
                                        @foreach($areas as $area)
                                            <option value="{{ $area->id }}" {{ isset($selectedAreaId) && $selectedAreaId == $area->id ? 'selected' : '' }}>
                                                {{ $area->name }}
                                            </option>
                                        @endforeach
                                    </select>

                                    @error('area_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="estado"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Estado') }}</label>

                                <div class="col-md-6">
                                    <select id="estado" class="form-select @error('estado') is-invalid @enderror"
                                        name="estado">
                                        <option value="">Todos los estados</option>
                                        <option value="Bueno">Bueno</option>
                                        <option value="Regular">Regular</option>
                                        <option value="Malo">Malo</option>
                                        <option value="Deshabilitado">Deshabilitado</option>
                                    </select>

                                    @error('estado')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-file-excel me-2"></i>{{ __('Generar Reporte Excel') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-actions">
            <a href="{{ route('equipment.index') }}"
                style="background-color: var(--border-color); color: var(--text-color); padding: 12px 25px; border-radius: 6px; text-decoration: none; font-weight: bold; transition: all 0.3s ease;">
                Volver
            </a>
        </div> <button class="theme-toggle" aria-label="Toggle dark mode"></button>
</body>

</html>