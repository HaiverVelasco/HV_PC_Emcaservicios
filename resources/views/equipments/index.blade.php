<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Equipos-EMCASERVICIOS</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('imgs/Emcaservicios.png') }}">
    <link rel="stylesheet" href="{{ asset('css/index.css') }}?v={{ time() }}">
    <script src="{{ asset('js/index.js') }}?v={{ time() }}" defer></script>
    <script src="{{ asset('js/themeToggle.js') }}?v={{ time() }}" defer></script>
</head>

<body>
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
            <button class="alert-close">&times;</button>
        </div>
    @endif

    @if (session('info'))
        <div class="alert alert-info">
            {{ session('info') }}
            <button class="alert-close">&times;</button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-error">
            {{ session('error') }}
            <button class="alert-close">&times;</button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-error">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button class="alert-close">&times;</button>
        </div>
    @endif

    <!-- Encabezado existente -->
    <header class="sheet-header">
        @if (session('is_admin'))
            <form action="{{ route('logout') }}" method="POST" class="logout-form">
                @csrf
                <button type="submit" class="btn-logout" title="Cerrar Sesión">⏻</button>
            </form>
        @endif
        <h1 class="text-center">HOJA DE VIDA EQUIPO</h1>
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



    <!-- Formulario de equipo -->
    <main class="main-content">
        <form action="{{ route('equipment.store') }}" method="POST" class="equipment-form"
            enctype="multipart/form-data">
            @csrf

            <!-- Información General -->
            <section class="form-section">
                <h2>Información General</h2>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="area_id">Área</label>
                        <select name="area_id" id="area_id" required>
                            <option value="">Seleccione un área</option>
                            @foreach ($areas as $area)
                                <option value="{{ $area->id }}" style="color: {{ $area->color }}">
                                    {{ $area->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="direct_responsible">Responsable Directo</label>
                        <input type="text" id="direct_responsible" name="direct_responsible"
                            placeholder="Nombre del responsable directo">
                    </div>

                    <div class="form-group">
                        <label for="indirect_responsible">Responsable Indirecto</label>
                        <input type="text" id="indirect_responsible" name="indirect_responsible"
                            placeholder="Nombre del responsable indirecto">
                    </div>

                    <div class="form-group">
                        <label for="company_name">Empresa</label>
                        <input type="text" id="company_name" name="company_name" value="EMCASERVICIOS">
                    </div>

                    <div class="form-group">
                        <label for="city">Ciudad</label>
                        <input type="text" id="city" name="city" value="Popayán">
                    </div>

                    <div class="form-group">
                        <label for="inventory_code">Código de Inventario</label>
                        <input type="text" id="inventory_code" name="inventory_code" required>
                    </div>

                    <div class="form-group">
                        <label for="manufacturer">Fabricante</label>
                        <input type="text" id="manufacturer" name="manufacturer">
                    </div>

                    <div class="form-group">
                        <label for="reference">Referencia</label>
                        <input type="text" id="reference" name="reference">
                    </div>

                    <div class="form-group">
                        <label for="acquisition_date">Fecha de Adquisición</label>
                        <input type="date" id="acquisition_date" name="acquisition_date">
                    </div>

                    <div class="form-group">
                        <label for="value">Valor del Equipo</label>
                        <input type="number" id="value" name="value" step="0.01">
                    </div>

                    <div class="form-group">
                        <label for="equipment_location">Ubicación del Equipo</label>
                        <input type="text" id="equipment_location" name="equipment_location">
                    </div>
                </div>
            </section>

            <!-- Información del Equipo -->
            <section class="form-section">
                <h2>Información del Equipo</h2>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="equipment_type">Tipo de Equipo</label>
                        <select name="equipment_type" id="equipment_type" required onchange="toggleSpecificFields()">
                            <option value="">Seleccione el tipo de equipo</option>
                            <option value="computador">Computador</option>
                            <option value="impresora">Impresora</option>
                            <option value="ups">UPS</option>
                            <option value="scanner">Escáner</option>
                            <option value="telefonia">Equipo de Telefonía</option>
                            <option value="otro">Otro</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="equipment_name">Nombre del Equipo</label>
                        <input type="text" id="equipment_name" name="equipment_name" required>
                    </div>

                    <div class="form-group">
                        <label for="brand">Marca</label>
                        <input type="text" id="brand" name="brand" required>
                    </div>

                    <div class="form-group">
                        <label for="model">Modelo</label>
                        <input type="text" id="model" name="model" required>
                    </div>

                    <div class="form-group">
                        <label for="serial_number">Número de Serie</label>
                        <input type="text" id="serial_number" name="serial_number" required>
                    </div>

                    <div class="form-group">
                        <label for="processor">Procesador</label>
                        <input type="text" id="processor" name="processor">
                    </div>

                    <div class="form-group">
                        <label for="operating_system">Sistema Operativo</label>
                        <input type="text" id="operating_system" name="operating_system">
                    </div>

                    <div class="form-group">
                        <label for="ram_memory">Memoria RAM</label>
                        <input type="text" id="ram_memory" name="ram_memory">
                    </div>

                    <div class="form-group">
                        <label for="storage">Almacenamiento</label>
                        <input type="text" id="storage" name="storage">
                    </div>

                    <div class="form-group full-width">
                        <label for="equipment_function">Función del Equipo</label>
                        <textarea id="equipment_function" name="equipment_function" rows="3"
                            placeholder="Para que se utiliza, que info maneja..."></textarea>
                    </div>

                    <!-- Campos específicos para impresoras -->
                    <div class="specific-fields printer-fields" style="display: none;">
                        <div class="specific-fields-grid">
                            <div class="form-group">
                                <label for="printing_technology">Tecnología de Impresión</label>
                                <select name="printing_technology" id="printing_technology">
                                    <option value="laser">Láser</option>
                                    <option value="inkjet">Inyección de Tinta</option>
                                    <option value="matrix">Matriz de Puntos</option>
                                    <option value="thermal">Térmica</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Campos específicos para equipos de red -->
                    <div class="specific-fields network-fields" style="display: none;">
                        <!-- Estos campos ya se han movido al lado de tarjeta gráfica -->
                    </div>

                    <!-- Campo para otros tipos de equipo -->
                    <div class="specific-fields other-fields" style="display: none;">
                        <div class="form-group">
                            <label for="technical_specifications">Especificaciones Técnicas</label>
                            <textarea id="technical_specifications" name="technical_specifications" rows="4"></textarea>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Estado y Mantenimiento -->
            <section class="form-section">
                <h2>Estado y Mantenimiento</h2>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="status">Estado</label>
                        <select name="status" id="status" required>
                            <option value="">Seleccione un Estado</option>
                            <option value="Bueno" style="color: #4CAF50">Bueno</option>
                            <option value="Regular" style="color: #FFC107">Regular</option>
                            <option value="Malo" style="color: #F44336">Malo</option>
                            <option value="Deshabilitado" style="color: #9E9E9E">Deshabilitado</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="maintenance_type">Tipo de Mantenimiento</label>
                        <select name="maintenance_type" id="maintenance_type">
                            <option value="">Seleccione un tipo</option>
                            <option value="Preventive">Preventivo</option>
                            <option value="Corrective">Correctivo</option>
                            <option value="Installation">Instalación</option>
                            <option value="Disassembly">Desinstalación</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="technician">Técnico</label>
                        <input type="text" id="technician" name="technician" placeholder="Nombre del técnico">
                    </div>

                    <div class="form-group full-width">
                        <label for="maintenance_description">Descripción del Mantenimiento</label>
                        <textarea id="maintenance_description" name="description" rows="3"
                            placeholder="Detalles del mantenimiento realizado"></textarea>
                    </div>
                </div>
            </section>

            <!-- Imágenes del Equipo -->
            <section class="form-section">
                <h2>Imágenes del Equipo</h2>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="images">Seleccionar Imágenes</label>
                        <div class="image-upload-container">
                            <input type="file" id="images" name="images[]" accept="image/*" multiple
                                class="image-upload-input" onchange="previewImages(event)">
                            <label for="images" class="image-upload-label">
                                📸 Seleccionar Archivos
                            </label>
                        </div>
                        <div id="image-preview" class="image-preview-container"></div>
                    </div>
                </div>
            </section>

            <!-- Observaciones -->
            <section class="form-section">
                <h2>Observaciones</h2>
                <div class="form-group full-width">
                    <textarea name="observations" id="observations" rows="4" 
                    placeholder="Ejemplo: Observaciones adicionales..." ></textarea>
                </div>
            </section>

            <div class="form-actions">
                <button type="submit" class="btn-submit">Guardar Equipo</button>
                <button type="reset" class="btn-reset">Limpiar</button>
                <a href="{{ route('equipment.index') }}" class="btn-show">Ver Lista de Equipos</a>
            </div>
        </form>
    </main>
    <button class="theme-toggle" aria-label="Toggle dark mode"></button>
</body>

</html>