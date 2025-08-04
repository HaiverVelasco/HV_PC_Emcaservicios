<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Equipos-EMCASERVICIOS</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('imgs/Emcaservicios.png') }}">
    <link rel="stylesheet" href="{{ asset('css/show.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('css/edit.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('css/sessionTimer.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('css/button-animation.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('css/custom-scrollbar.css') }}?v={{ time() }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="{{ asset('js/themeToggle.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('js/enhanced-scrollbar.js') }}?v={{ time() }}"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
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

    <!-- Encabezado existente -->
    <header class="sheet-header">
        <div class="header-content">
            <h1 class="text-center">EQUIPOS REGISTRADOS</h1>
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

    <div class="search-container">
        <div class="search-wrapper">
            <form class="search-form" onsubmit="return false;">
                <div class="search-input-container">
                    <input type="text" id="searchEquipment" placeholder="🔍 Buscar por código, nombre, serie"
                        class="search-input">
                    <div class="search-results" style="display: none;">
                        <!-- Los resultados se insertarán aquí dinámicamente -->
                    </div>
                </div>
            </form>
            <div class="navigation-controls">
                <!-- El botón de navegación rápida se insertará aquí dinámicamente -->
            </div>
        </div>
    </div>

    <main class="equipment-container">
        @foreach ($areas as $area)
            <section class="area-section" style="border-color: {{ $area->color }}">
                <h2 class="area-title" style="background-color: {{ $area->color }}">
                    {{ $area->name }}
                    @if (isAdmin() && $area->equipment->count() > 0)
                        <div class="download-buttons">
                            <button class="btn-download-area-qr"
                                onclick="downloadAreaQRs('{{ $area->id }}', '{{ $area->name }}')">
                                Descargar QRs
                            </button>
                            <button class="btn-download-area-qr"
                                onclick="downloadAreaPDFs('{{ $area->id }}', '{{ $area->name }}')">
                                Descargar PDFs
                            </button>
                        </div>
                    @endif
                </h2>

                @if ($area->equipment->count() > 0)
                    <div class="equipment-type-filters">
                        <button class="filter-btn active" data-type="all">
                            Todos
                            <span class="filter-count">{{ $area->equipment->count() }}</span>
                        </button>
                        <button class="filter-btn" data-type="computador">
                            Computadores
                            <span
                                class="filter-count">{{ $area->equipment->where('equipment_type', 'computador')->count() }}</span>
                        </button>
                        <button class="filter-btn" data-type="impresora">
                            Impresoras
                            <span
                                class="filter-count">{{ $area->equipment->where('equipment_type', 'impresora')->count() }}</span>
                        </button>
                        <button class="filter-btn" data-type="ups">
                            UPS
                            <span class="filter-count">{{ $area->equipment->where('equipment_type', 'ups')->count() }}</span>
                        </button>
                        <button class="filter-btn" data-type="scanner">
                            Escáneres
                            <span
                                class="filter-count">{{ $area->equipment->where('equipment_type', 'scanner')->count() }}</span>
                        </button>
                        <button class="filter-btn" data-type="telefonia">
                            Telefonía
                            <span
                                class="filter-count">{{ $area->equipment->where('equipment_type', 'telefonia')->count() }}</span>
                        </button>
                        <button class="filter-btn" data-type="otro">
                            Otros
                            <span class="filter-count">{{ $area->equipment->where('equipment_type', 'otro')->count() }}</span>
                        </button>
                    </div>

                    <div class="equipment-grid">
                        <div class="no-equipment-message filter-message" style="display: none;">
                            <div class="message-container">
                                <div class="message-icon">🔍</div>
                                <p>No se encontraron equipos del tipo seleccionado en esta área.</p>
                                <button class="reset-filter">Mostrar todos los equipos</button>
                            </div>
                        </div>
                        @foreach ($area->equipment as $equipment)
                            <div class="equipment-card" data-type="{{ $equipment->equipment_type }}">
                                <div class="equipment-header">
                                    <span class="inventory-code">{{ $equipment->inventory_code }}</span>
                                    <span class="status-badge status-{{ str_replace(' ', '-', strtolower($equipment->status)) }}">
                                        {{ $equipment->status }}
                                    </span>
                                </div>
                                <div class="equipment-body">
                                    <h3>{{ $equipment->equipment_name }}</h3>
                                    <p><strong>Marca:</strong> {{ $equipment->brand }}</p>
                                    <p><strong>Modelo:</strong> {{ $equipment->model }}</p>
                                    <p><strong>Responsable Indirecto:</strong> {{ $equipment->indirect_responsible }}</p>
                                </div>
                                @if ($equipment->images->count() > 0)
                                    <div class="equipment-images">
                                        <div class="images-preview">
                                            @foreach ($equipment->images->take(1) as $image)
                                                <a href="{{ asset($image->url) }}" data-lightbox="equipment-{{ $equipment->id }}"
                                                    data-title="{{ $equipment->equipment_name }}">
                                                    <img src="{{ asset($image->url) }}" alt="Imagen del equipo" class="equipment-thumbnail">
                                                </a>
                                            @endforeach
                                            @if ($equipment->images->count() > 1)
                                                <button class="more-images" data-total="{{ $equipment->images->count() - 1 }}">
                                                    +{{ $equipment->images->count() - 1 }}
                                                </button>
                                            @endif
                                        </div>
                                        <div class="images-gallery" style="display: none;">
                                            @foreach ($equipment->images as $image)
                                                <a href="{{ asset($image->url) }}" data-lightbox="equipment-{{ $equipment->id }}"
                                                    data-title="{{ $equipment->equipment_name }} - {{ Carbon\Carbon::parse($image->created_at)->format('d/m/Y H:i') }}">
                                                    <img src="{{ asset($image->url) }}" alt="Imagen del equipo" class="equipment-thumbnail">
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                <div class="equipment-footer">
                                    <!-- Botones solo para administradores -->
                                    @if (isAdmin())
                                        <a href="{{ route('equipment.edit', $equipment->id) }}" class="btn-edit">Editar</a>
                                        <form action="{{ route('equipment.destroy', $equipment->id) }}" mzethod="POST"
                                            class="delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-delete"
                                                onclick="return confirm('¿Está seguro que desea eliminar este equipo?')">
                                                Eliminar
                                            </button>
                                        </form>

                                        <a href="{{ route('equipment.pdf', $equipment->id) }}" class="btn-pdf" target="_blank">Generar
                                            PDF</a>
                                        <a href="{{ route('maintenance.index', $equipment->id) }}"
                                            class="btn-maintenance">Mantenimientos</a>

                                        <button class="btn-qr" onclick="generateQR(
                                                                                                                        '{{ $equipment->id }}', 
                                                                                                                        '{{ $equipment->equipment_name }}', 
                                                                                                                        '{{ route('equipment.pdf', $equipment->id) }}'
                                                                                                                        )">
                                            QR
                                        </button>
                                        <a href="{{ route('observations.index', ['equipment' => $equipment->id]) }}"
                                            class="btn-observations">
                                            📋 Observaciones
                                            @if($equipment->observations && $equipment->observations->count() > 0)
                                                <span class="obs-count">({{ $equipment->observations->count() }})</span>
                                            @else
                                                <span class="obs-count">(0)</span>
                                            @endif
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-area-message">
                        <div class="message-container">
                            <div class="message-icon">📦</div>
                            <p>No hay equipos registrados en esta área</p>
                        </div>
                    </div>
                @endif
            </section>
        @endforeach
    </main>

    @if (isAdmin())
        <div class="floating-button">
            <a href="{{ route('equipment.list') }}" class="btn-add">
                Agregar Equipo
            </a>
        </div>
    @endif

    <script src="{{ asset('js/area-navigation.js') }}"></script>
    <script src="{{ asset('js/show.js') }}?v={{ time() }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>

    @if (isAdmin())
        <!-- Script y variables para el control de tiempo de sesión (solo para administradores) -->
        <script>
            // Variables para el control de tiempo de sesión
            const sessionStartTime = {{ session('admin_session_start_time', 0) }};
            const sessionExpiryTime = {{ session('admin_session_start_time', 0) + 120 * 60 * 1000 }}; // 2 horas en milisegundos
        </script>
        <script src="{{ asset('js/sessionTimer.js') }}?v={{ time() }}"></script>
    @endif

    <script>
        lightbox.option({
            'resizeDuration': 200,
            'wrapAround': true,
            'albumLabel': "Imagen %1 de %2"
        });

        // Asegurarnos de que el DOM esté cargado
        document.addEventListener('DOMContentLoaded', function () {
            // Función para generar QR
            window.generateQR = function (id, name, pdfUrl) {
                const modal = document.getElementById('qrModal');
                const qrContainer = document.getElementById('qrcode');
                const qrTitle = document.getElementById('qrTitle');

                qrContainer.innerHTML = '';

                new QRCode(qrContainer, {
                    text: pdfUrl,
                    width: 256,
                    height: 256,
                    colorDark: "#000000",
                    colorLight: "#ffffff",
                    correctLevel: QRCode.CorrectLevel.L // Cambiado a L para menor densidad
                });

                qrTitle.textContent = `Código QR - ${name}`;
                modal.style.display = 'block';

                // Agregar mensaje informativo
                const infoText = document.createElement('p');
                infoText.className = 'qr-info-text';
                infoText.textContent = 'Escanea para ver el PDF del equipo';
                qrContainer.appendChild(infoText);
            };

            // Event listeners para el modal
            const closeBtn = document.querySelector('.close-modal');
            const modal = document.getElementById('qrModal');

            if (closeBtn) {
                closeBtn.onclick = () => modal.style.display = "none";
            }

            window.onclick = (event) => {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            };
        });

        // Función para descargar QR
        function downloadQR() {
            const canvas = document.querySelector("#qrcode canvas");
            if (canvas) {
                const image = canvas.toDataURL("image/png");
                const link = document.createElement('a');
                link.download = 'qr-equipo.png';
                link.href = image;
                link.click();
            }
        }
    </script>

    <!-- Modal del QR -->
    <div id="qrModal" class="qr-modal">
        <div class="qr-modal-content">
            <span class="close-modal">&times;</span>
            <h2 id="qrTitle"></h2>
            <div id="qrcode"></div>
            <p class="qr-info-text">Escanea para ver la información del equipo</p>
            <button class="btn-download" onclick="downloadQR()">Descargar QR</button>
        </div>
    </div>

    <!-- Modal para descarga masiva de QRs -->
    <div id="bulkQRModal" class="bulk-qr-modal">
        <div class="bulk-qr-modal-content">
            <span class="close-bulk-modal">&times;</span>
            <h2 class="bulk-modal-title">Descarga de Códigos QR</h2>
            <div class="bulk-status">
                <span class="loading-indicator"></span>
                <span id="bulkQRStatus">Procesando...</span>
            </div>
        </div>
    </div>

    <!-- Botón para cambiar el tema (solo visible para visitantes) -->
    @if (!isAdmin())
        <button class="theme-toggle" aria-label="Toggle dark mode"></button>
    @endif

</body>

</html>