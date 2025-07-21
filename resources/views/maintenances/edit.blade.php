<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Mantenimiento - EMCASERVICIOS</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('imgs/Emcaservicios.png') }}">
    <link rel="stylesheet" href="{{ asset('css/show.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('css/edit.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('css/sessionTimer.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('css/button-animation.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('css/custom-scrollbar.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('css/maintenance-unified.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="{{ asset('js/themeToggle.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('js/enhanced-scrollbar.js') }}?v={{ time() }}"></script>
</head>

<body>
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
            <h1 class="text-center">EDITAR MANTENIMIENTO</h1>
        </div>
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

    <div class="maintenance-container">
        <div class="maintenance-header">
            <h2>Editar Registro de Mantenimiento</h2>
            <a href="{{ route('maintenance.index', $maintenance->equipment_id) }}" class="btn-maintenance btn-back">
                <i class="fas fa-arrow-left"></i> Volver al historial
            </a>
        </div>

        <div class="maintenance-form-container">
            <form action="{{ route('maintenance.update', $maintenance->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-row">
                    <div class="form-group">
                        <label for="date">Fecha del mantenimiento:</label>
                        <input type="date" id="date" name="date" class="form-control" required
                            value="{{ old('date', $maintenance->date) }}">
                    </div>

                    <div class="form-group">
                        <label for="type">Tipo de mantenimiento:</label>
                        <select id="type" name="type" class="form-control" required>
                            <option value="">Seleccione un tipo</option>
                            <option value="Preventivo" {{ old('type', $maintenance->type) == 'Preventivo' ? 'selected' : '' }}>
                                Preventivo</option>
                            <option value="Correctivo" {{ old('type', $maintenance->type) == 'Correctivo' ? 'selected' : '' }}>
                                Correctivo</option>
                            <option value="Instalación" {{ old('type', $maintenance->type) == 'Instalación' ? 'selected' : '' }}>
                                Instalación
                            </option>
                            <option value="Desinstalación" {{ old('type', $maintenance->type) == 'Desinstalación' ? 'selected' : '' }}>
                                Desinstalación
                            </option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="technician">Técnico responsable:</label>
                    <input type="text" id="technician" name="technician" class="form-control" required
                        value="{{ old('technician', $maintenance->technician) }}">
                </div>

                <div class="form-group">
                    <label for="description">Descripción del mantenimiento:</label>
                    <textarea id="description" name="description" class="form-control" rows="4"
                        required>{{ old('description', $maintenance->description) }}</textarea>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn-maintenance btn-add-maintenance">
                        <i class="fas fa-save"></i> Actualizar Mantenimiento
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="{{ asset('js/show.js') }}?v={{ time() }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    @if (isAdmin())
        <!-- Script para el control de tiempo de sesión -->
        <script>
            // Variables para el control de tiempo de sesión
            const sessionStartTime = {{ session('admin_session_start_time', 0) }};
            const sessionExpiryTime =
                        {{ session('admin_session_start_time', 0) + 120 * 60 * 1000 }}; // 2 horas en milisegundos
        </script>
        <script src="{{ asset('js/sessionTimer.js') }}?v={{ time() }}"></script>
    @endif

    <script>
        // Script para cerrar las alertas
        document.addEventListener('DOMContentLoaded', function () {
            const alertCloseButtons = document.querySelectorAll('.alert-close');

            alertCloseButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const alert = this.parentElement;
                    alert.style.opacity = '0';
                    setTimeout(() => {
                        alert.style.display = 'none';
                    }, 300);
                });
            });

            // Ocultar alertas automáticamente después de 5 segundos
            setTimeout(() => {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(alert => {
                    alert.style.opacity = '0';
                    setTimeout(() => {
                        alert.style.display = 'none';
                    }, 300);
                });
            }, 5000);
        });
    </script>
</body>

</html>