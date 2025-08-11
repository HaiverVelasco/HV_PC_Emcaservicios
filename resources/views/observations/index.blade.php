<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Observaciones - {{ $equipment->equipment_name }} - EMCASERVICIOS</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('imgs/Emcaservicios.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Estilos CSS -->
    <link rel="stylesheet" href="{{ asset('css/show.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('css/index.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('css/edit.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('css/maintenance-unified.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('css/sessionTimer.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('css/custom-scrollbar.css') }}?v={{ time() }}">

    <!-- Scripts -->
    <script src="{{ asset('js/themeToggle.js') }}?v={{ time() }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <style>
        .description-container {
            display: flex;
            flex-wrap: wrap;
            align-items: flex-start;
            position: relative;
        }

        .description-container strong {
            margin-right: 5px;
            flex: 0 0 auto;
        }

        .description-text {
            flex: 1 1 auto;
            min-width: 0;
            word-wrap: break-word;
            display: inline;
        }

        .description-toggle {
            display: inline-flex;
            align-items: center;
            margin-left: 5px;
            cursor: pointer;
            color: #007bff;
            transition: all 0.2s ease;
            vertical-align: middle;
            position: relative;
            top: 1px;
        }

        .description-toggle:hover {
            color: #0056b3;
        }

        .description-toggle.expanded {
            transform: rotate(180deg);
        }

        .observation-details p {
            margin-bottom: 8px;
            word-wrap: break-word;
        }

        .observation-card {
            background: var(--card-bg);
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-left: 4px solid var(--primary-color);
            transition: all 0.3s ease;
            margin-bottom: 15px;
        }

        .observation-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
        }

        .observation-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid var(--border-color);
        }

        .observation-date {
            font-size: 0.9rem;
            color: var(--text-muted);
            font-weight: 500;
        }

        .observation-author {
            font-size: 0.85rem;
            padding: 4px 8px;
            border-radius: 4px;
            font-weight: 600;
            background-color: var(--accent-color-light);
            color: var(--accent-color);
        }

        .observation-content {
            font-size: 1rem;
            line-height: 1.6;
            color: var(--text-color);
            margin: 0;
            word-wrap: break-word;
        }

        .empty-observation {
            text-align: center;
            padding: 40px 20px;
            background-color: var(--card-bg);
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            margin-top: 20px;
        }

        .empty-observation i {
            font-size: 2.5rem;
            margin-bottom: 15px;
            color: var(--text-muted);
        }

        .observation-count {
            margin: 10px 0 20px;
            color: var(--text-muted);
            font-size: 0.9rem;
        }
    </style>
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
            <h1 class="text-center">OBSERVACIONES DEL EQUIPO</h1>
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
            <div class="social-links">
                <a href="https://facebook.com/EmcaCauca" class="social-button facebook" title="Facebook">
                    <i class="facebook"><img src="{{ asset('imgs/facebook.png') }}"></i>
                </a>
                <a href="https://instagram.com/emcaservicios" class="social-button instagram" title="Instagram">
                    <i class="instagram"><img src="{{ asset('imgs/instagram.png') }}"></i>
                </a>
                <a href="https://tiktok.com/@emcaservicios" class="social-button tiktok" title="TikTok">
                    <i class="tiktok"><img src="{{ asset('imgs/tiktok.png') }}"></i>
                </a>
                <a href="https://twitter.com/emcaservicios1" class="social-button X" title="X">
                    <i class="X"> <img src="{{ asset('imgs/X.png') }}"></i>
                </a>
                <a href="https://youtube.com/emcaservicios" class="social-button youtube" title="YouTube">
                    <i class="youtube"><img src="{{ asset('imgs/youtube.png') }}"></i>
                </a>
            </div>
        </div>
    </header>

    <div class="maintenance-container">
        <div class="maintenance-header">
            <h2>Observaciones del Equipo</h2>
            <a href="{{ route('equipment.show', ['equipment' => $equipment->id]) }}#equipment-{{ $equipment->id }}"
                class="btn-back">
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
                <p><strong>Serial:</strong> {{ $equipment->inventory_code }}</p>
                @if ($equipment->direct_responsible)
                    <p><strong>Responsable:</strong> {{ $equipment->direct_responsible ?? 'No hay responsable' }}</p>
                @endif
                <p><strong>Área:</strong> {{ $equipment->area->name ?? 'Sin área asignada' }}</p>
            </div>
        </div>

        <!-- Formulario para agregar observación (solo para administradores) -->
        @if (isAdmin())
            <div class="maintenance-form-container">
                <h3 class="maintenance-form-title">Registrar nueva observación</h3>
                <form action="{{ route('observations.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="equipment_id" value="{{ $equipment->id }}">

                    <div class="form-group">
                        <textarea id="observation" name="observation" class="form-control" rows="4"
                            required>{{ old('observation') }}</textarea>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn-maintenance btn-add-maintenance">
                            <i class="fas fa-save"></i> Guardar Observación
                        </button>
                    </div>
                </form>
            </div>
        @endif

        <!-- Lista de observaciones -->
        <div class="maintenance-history">
            <h3 class="maintenance-history-title">Historial de observaciones</h3>
            <div class="observation-count">
                <span>Total: {{ $observations->count() }} observaciones</span>
            </div>

            @if ($observations->count() > 0)
                <div class="maintenance-grid">
                    <div class="maintenance-grid-scroll">
                        @foreach ($observations as $observation)
                            <div class="observation-card">
                                <div class="observation-card-header">
                                    <span class="observation-date"><i class="far fa-calendar-alt"></i>
                                        {{ \Carbon\Carbon::parse($observation->created_at)->format('d/m/Y - H:i') }}
                                    </span>
                                    <span class="observation-author">
                                        <i class="fas fa-user"></i>
                                        {{ $observation->user_name ?? 'Admin' }}
                                    </span>
                                </div>
                                <div class="observation-details">
                                    <p class="description-container">
                                        <span class="description-text">{{ $observation->observation }}</span>
                                        @if(strlen($observation->observation) > 100)
                                            <span class="description-toggle" data-full="{{ $observation->observation }}"
                                                onclick="toggleDescription(this)">
                                                <i class="fas fa-plus-circle"></i>
                                            </span>
                                        @endif
                                    </p>
                                </div>
                                <!-- Acciones para todas las observaciones -->
                                <div class="maintenance-actions">
                                    <a href="{{ route('observations.pdf', $observation->id) }}"
                                        class="btn-maintenance btn-pdf-maintenance" target="_blank">
                                        <i class="fas fa-file-pdf"></i> PDF
                                    </a>

                                    @if (isAdmin())
                                        <form action="{{ route('observations.destroy', $observation->id) }}" method="POST"
                                            style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-maintenance btn-delete-maintenance"
                                                onclick="return confirm('¿Está seguro que desea eliminar esta observación?');">
                                                <i class="fas fa-trash-alt"></i> Eliminar
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Paginación -->
                @if ($observations->hasPages())
                    <div class="pagination-wrapper">
                        {{ $observations->links() }}
                    </div>
                @endif
            @else
                <div class="empty-observation">
                    <p><i class="fas fa-info-circle"></i> No hay observaciones registradas para este equipo.</p>
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

        // Función para expandir/contraer la descripción completa
        function toggleDescription(element) {
            const parentP = element.parentNode;
            const textSpan = parentP.querySelector('.description-text');
            const fullText = element.getAttribute('data-full');

            if (element.classList.contains('expanded')) {
                // Contraer el texto
                textSpan.textContent = fullText.substring(0, 100) + '...';
                element.innerHTML = '<i class="fas fa-plus-circle"></i>';
                element.classList.remove('expanded');
            } else {
                // Expandir el texto
                textSpan.textContent = fullText;
                element.innerHTML = '<i class="fas fa-minus-circle"></i>';
                element.classList.add('expanded');
            }
        }
    </script>
</body>

</html>