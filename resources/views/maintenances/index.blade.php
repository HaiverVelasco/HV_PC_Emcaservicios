<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Historial de Mantenimiento - EMCASERVICIOS</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('imgs/Emcaservicios.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Estilos CSS -->
    <link rel="stylesheet" href="{{ asset('css/show.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('css/edit.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('css/maintenance-unified.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('css/sessionTimer.css') }}?v={{ time() }}">

    <!-- Scripts -->
    <script src="{{ asset('js/themeToggle.js') }}?v={{ time() }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>

<body class="maintenance-page">
    <!-- Alertas -->
    <div class="alerts-container">
        @if (session()->has('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
                <button class="alert-close">&times;</button>
            </div>
        @endif
        @if (session()->has('info'))
            <div class="alert alert-info" role="alert">
                {{ session('info') }}
                <button class="alert-close">&times;</button>
            </div>
        @endif
        @if (session()->has('error'))
            <div class="alert alert-error" role="alert">
                {{ session('error') }}
                <button class="alert-close">&times;</button>
            </div>
        @endif
    </div>

    <!-- Encabezado -->
    <header class="sheet-header">
        <div class="header-content">
            <h1 class="text-center">HISTORIAL DE MANTENIMIENTO</h1>
        </div>
        <div class="company-info">
            <a href="https://www.pdacauca.gov.co/#">
                <img src="{{ asset('imgs/Emcaservicios.png') }}" alt="Logo empresa" class="company-logo">
            </a>
            <div class="company-contacts">
                <p>EMPRESA CAUCANA DE SERVICIOS PÚBLICOS S.A. E.S.P.</p>
                <p>Carrera 4 N° 22N-02 / Edificio de Infraestructura, primer piso</p>
                <p>Popayán - Cauca - Colombia</p>
            </div>
        </div>
    </header>

    <div class="maintenance-container">
        <div class="maintenance-header">
            <h2>Historial de Mantenimiento</h2>
            <a href="{{ route('equipment.show', $equipment->id) }}" class="btn-back">
                <i class="fas fa-arrow-left"></i> Volver al equipo
            </a>
        </div>

        <!-- Información del equipo -->
        <div class="equipment-info-card">
            <div class="equipment-info-header">
                <h2 class="equipment-info-title">{{ $equipment->equipment_name }}</h2>
                <span class="status-badge status-{{ str_replace(' ', '-', strtolower($equipment->status)) }}">
                    {{ $equipment->status }}
                </span>
            </div>
            <div class="equipment-info-grid">
                <p><strong>Código de inventario:</strong> {{ $equipment->inventory_code }}</p>
                <p><strong>Serial:</strong> {{ $equipment->serial_number }}</p>
                @if ($equipment->direct_responsible)
                    <p><strong>Responsable:</strong> {{ $equipment->direct_responsible ?? 'No hay responsable' }}</p>
                @endif
                <p><strong>Área:</strong> {{ $equipment->area->name ?? 'Sin área asignada' }}</p>
            </div>
        </div>

        <!-- Formulario de mantenimiento (solo admin) -->
        @if (isAdmin())
            <div class="maintenance-form-container">
                <h3 class="maintenance-form-title">Registrar nuevo mantenimiento</h3>
                <form action="{{ route('maintenance.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="equipment_id" value="{{ $equipment->id }}">

                    <div class="form-row">
                        <div class="form-group">
                            <label for="date">Fecha del mantenimiento:</label>
                            <input type="date" id="date" name="date" class="form-control" required
                                value="{{ old('date', date('Y-m-d')) }}">
                        </div>
                        <div class="form-group">
                            <label for="type">Tipo de mantenimiento:</label>
                            <select id="type" name="type" class="form-control" required>
                                <option value="">Seleccione un tipo</option>
                                <option value="Preventivo" {{ old('type') == 'Preventivo' ? 'selected' : '' }}>Preventivo
                                </option>
                                <option value="Correctivo" {{ old('type') == 'Correctivo' ? 'selected' : '' }}>Correctivo
                                </option>
                                <option value="Instalación" {{ old('type') == 'Instalación' ? 'selected' : '' }}>Instalación
                                </option>
                                <option value="Desinstalación" {{ old('type') == 'Desinstalación' ? 'selected' : '' }}>
                                    Desinstalación</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="technician">Técnico responsable:</label>
                        <input type="text" id="technician" name="technician" class="form-control" required
                            value="{{ old('technician') }}">
                    </div>

                    <div class="form-group">
                        <label for="description">Descripción del mantenimiento:</label>
                        <textarea id="description" name="description" class="form-control" rows="4"
                            required>{{ old('description') }}</textarea>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="failure">Falla encontrada:</label>
                            <select id="failure" name="failure" class="form-control">
                                <option value="">Seleccione estado de falla</option>
                                <option value="Desconocido" {{ old('failure') == 'Desconocido' ? 'selected' : '' }}>
                                    Desconocido</option>
                                <option value="Sin Fallas" {{ old('failure') == 'Sin Fallas' ? 'selected' : '' }}>Sin Fallas
                                </option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="depreciation">Desgaste encontrado:</label>
                            <input type="text" id="depreciation" name="depreciation" class="form-control"
                                value="{{ old('depreciation') }}">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="bad_operation">Mala operación:</label>
                            <input type="text" id="bad_operation" name="bad_operation" class="form-control"
                                value="{{ old('bad_operation') }}">
                        </div>
                        <div class="form-group">
                            <label for="bad_installation">Mala instalación:</label>
                            <input type="text" id="bad_installation" name="bad_installation" class="form-control"
                                value="{{ old('bad_installation') }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="accessories">Accesorios:</label>
                        <input type="text" id="accessories" name="accessories" class="form-control"
                            value="{{ old('accessories') }}">
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn-maintenance btn-add-maintenance">
                            <i class="fas fa-save"></i> Registrar Mantenimiento
                        </button>
                    </div>
                </form>
            </div>
        @endif

        <!-- Historial de mantenimientos -->
        <div class="maintenance-history">
            <h3 class="maintenance-history-title">Historial de mantenimientos</h3>
            <div class="maintenance-count">
                <span>Total: {{ $equipment->maintenances->count() }} mantenimientos</span>
            </div>

            @if ($equipment->maintenances->count() > 0)
                <div class="maintenance-grid">
                    <div class="maintenance-grid-scroll">
                        @foreach ($equipment->maintenances as $maintenance)
                            <div class="maintenance-card">
                                <div class="maintenance-card-header">
                                    <span class="maintenance-type type-{{ $maintenance->type }}">{{ $maintenance->type }}</span>
                                    <span><i class="far fa-calendar-alt"></i>
                                        {{ date('d/m/Y', strtotime($maintenance->date)) }}</span>
                                </div>
                                <div class="maintenance-details">
                                    <p><strong><i class="fas fa-user-cog"></i> Técnico:</strong> {{ $maintenance->technician }}
                                    </p>
                                    @if ($maintenance->failure)
                                        <p><strong><i class="fas fa-exclamation-triangle"></i> Falla:</strong>
                                            {{ $maintenance->failure }}</p>
                                    @endif
                                    @if ($maintenance->depreciation)
                                        <p><strong><i class="fas fa-chart-line"></i> Desgaste:</strong>
                                            {{ $maintenance->depreciation }}</p>
                                    @endif
                                    @if ($maintenance->bad_operation)
                                        <p><strong><i class="fas fa-user-times"></i> Mala operación:</strong>
                                            {{ $maintenance->bad_operation }}</p>
                                    @endif
                                    @if ($maintenance->bad_installation)
                                        <p><strong><i class="fas fa-tools"></i> Mala instalación:</strong>
                                            {{ $maintenance->bad_installation }}</p>
                                    @endif
                                    @if ($maintenance->accessories)
                                        <p><strong><i class="fas fa-puzzle-piece"></i> Accesorios:</strong>
                                            {{ $maintenance->accessories }}</p>
                                    @endif
                                </div>
                                <div class="maintenance-actions">
                                    <a href="{{ route('maintenance.pdf', $maintenance->id) }}"
                                        class="btn-maintenance btn-pdf-maintenance" target="_blank">
                                        <i class="fas fa-file-pdf"></i> PDF
                                    </a>
                                    @if (isAdmin())
                                        <a href="{{ route('maintenance.edit', $maintenance->id) }}"
                                            class="btn-maintenance btn-edit-maintenance">
                                            <i class="fas fa-edit"></i> Editar
                                        </a>
                                        <form action="{{ route('maintenance.destroy', $maintenance->id) }}" method="POST"
                                            style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-maintenance btn-delete-maintenance"
                                                onclick="return confirm('¿Está seguro que desea eliminar este registro de mantenimiento?');">
                                                <i class="fas fa-trash-alt"></i> Eliminar
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="empty-maintenance">
                    <p><i class="fas fa-info-circle"></i> No hay registros de mantenimiento para este equipo.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/maintenance.js') }}?v={{ time() }}"></script>

    @if (isAdmin())
        <script>
            // Variables para el control de tiempo de sesión
            const sessionStartTime = {{ session('admin_session_start_time', 0) }};
            const sessionExpiryTime = {{ session('admin_session_start_time', 0) + 120 * 60 * 1000 }};
        </script>
        <script src="{{ asset('js/sessionTimer.js') }}?v={{ time() }}"></script>
    @endif
    <script>
        // Script para manejar las alertas
        document.addEventListener('DOMContentLoaded', function () {
            const handleAlertClose = (alert) => {
                alert.style.opacity = '0';
                setTimeout(() => alert.style.display = 'none', 300);
            };

            // Manejador para botones de cierre
            document.querySelectorAll('.alert-close').forEach(button => {
                button.addEventListener('click', () => handleAlertClose(button.parentElement));
            });

            // Auto-ocultar alertas después de 5 segundos
            setTimeout(() => {
                document.querySelectorAll('.alert').forEach(handleAlertClose);
            }, 5000);
        });
    </script>
</body>

</html>