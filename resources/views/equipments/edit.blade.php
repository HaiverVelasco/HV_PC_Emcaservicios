<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Equipo - EMCASERVICIOS</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('imgs/Emcaservicios.png') }}">
    <link rel="stylesheet" href="{{ asset('css/edit.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('css/index.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('css/specific-fields.css') }}?v={{ time() }}">
    <script src="{{ asset('js/edit.js') }}?v={{ time() }}" defer></script>
    <script src="{{ asset('js/themeToggle.js') }}?v={{ time() }}" defer></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
    <header class="sheet-header">
        <h1 class="text-center">EDITAR EQUIPO</h1>
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

    <main class="main-content">
        <form action="{{ route('equipment.update', $equipment->id) }}" method="POST" class="equipment-form"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Información General -->
            <section class="form-section">
                <h2>Información General</h2>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="area_id">Área</label>
                        <select name="area_id" id="area_id" required>
                            @foreach ($areas as $area)
                                <option value="{{ $area->id }}" {{ $equipment->area_id == $area->id ? 'selected' : '' }}>
                                    {{ $area->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="direct_responsible">Responsable Directo</label>
                        <input type="text" id="direct_responsible" name="direct_responsible"
                            value="{{ $equipment->direct_responsible }}">
                    </div>

                    <div class="form-group">
                        <label for="indirect_responsible">Responsable Indirecto</label>
                        <input type="text" id="indirect_responsible" name="indirect_responsible"
                            value="{{ $equipment->indirect_responsible }}">
                    </div>

                    <div class="form-group">
                        <label for="company_name">Empresa</label>
                        <input type="text" id="company_name" name="company_name" value="{{ $equipment->company_name }}">
                    </div>

                    <div class="form-group">
                        <label for="city">Ciudad</label>
                        <input type="text" id="city" name="city" value="{{ $equipment->city }}">
                    </div>

                    <div class="form-group">
                        <label for="inventory_code">Numero de serial</label>
                        <input type="text" id="inventory_code" name="inventory_code"
                            value="{{ $equipment->inventory_code }}" required>
                    </div>

                    <div class="form-group">
                        <label for="manufacturer">Fabricante</label>
                        <input type="text" id="manufacturer" name="manufacturer" value="{{ $equipment->manufacturer }}">
                    </div>

                    <div class="form-group">
                        <label for="reference">Referencia</label>
                        <input type="text" id="reference" name="reference" value="{{ $equipment->reference }}">
                    </div>

                    <div class="form-group">
                        <label for="acquisition_date">Fecha de Adquisición</label>
                        <input type="date" id="acquisition_date" name="acquisition_date"
                            value="{{ $equipment->acquisition_date }}">
                    </div>

                    <div class="form-group">
                        <label for="value">Valor del Equipo</label>
                        <input type="number" id="value" name="value" step="0.01" value="{{ $equipment->value }}">
                    </div>

                    <div class="form-group">
                        <label for="equipment_location">Ubicación del Equipo</label>
                        <input type="text" id="equipment_location" name="equipment_location"
                            value="{{ $equipment->equipment_location }}">
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
                            <option value="computador" {{ $equipment->equipment_type == 'computador' ? 'selected' : '' }}>
                                Computador</option>
                            <option value="impresora" {{ $equipment->equipment_type == 'impresora' ? 'selected' : '' }}>
                                Impresora</option>
                            <option value="ups" {{ $equipment->equipment_type == 'ups' ? 'selected' : '' }}>UPS
                            </option>
                            <option value="scanner" {{ $equipment->equipment_type == 'scanner' ? 'selected' : '' }}>
                                Escáner</option>
                            <option value="telefonia" {{ $equipment->equipment_type == 'telefonia' ? 'selected' : '' }}>
                                Equipo de Telefonía
                            </option>
                            <option value="otro" {{ $equipment->equipment_type == 'otro' ? 'selected' : '' }}>Otro
                            </option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="equipment_name">Nombre del Equipo</label>
                        <input type="text" id="equipment_name" name="equipment_name"
                            value="{{ $equipment->equipment_name }}" required>
                    </div>

                    <div class="form-group">
                        <label for="brand">Marca</label>
                        <input type="text" id="brand" name="brand" value="{{ $equipment->brand }}">
                    </div>

                    <div class="form-group">
                        <label for="model">Modelo</label>
                        <input type="text" id="model" name="model" value="{{ $equipment->model }}">
                    </div>

                    <div class="form-group">
                        <label for="reference">Referencia</label>
                        <input type="text" id="reference" name="reference" value="{{ $equipment->reference }}">
                    </div>

                    <!-- Campos específicos para computador -->
                    <div class="specific-fields computador-fields">
                        <div class="form-group">
                            <label for="processor">Procesador</label>
                            <input type="text" id="processor" name="processor" value="{{ $equipment->processor }}">
                        </div>

                        <div class="form-group">
                            <label for="operating_system">Sistema Operativo</label>
                            <input type="text" id="operating_system" name="operating_system"
                                value="{{ $equipment->operating_system }}">
                        </div>

                        <div class="form-group">
                            <label for="ram_memory">Memoria RAM</label>
                            <input type="text" id="ram_memory" name="ram_memory" value="{{ $equipment->ram_memory }}">
                        </div>

                        <div class="form-group">
                            <label for="storage">Almacenamiento</label>
                            <input type="text" id="storage" name="storage" value="{{ $equipment->storage }}">
                        </div>
                    </div>

                    <div class="form-group full-width">
                        <label for="equipment_function">Función del Equipo</label>
                        <textarea id="equipment_function" name="equipment_function" rows="4"
                            placeholder="Para qué se utiliza, qué info maneja...">{{ trim($equipment->equipment_function) }}</textarea>
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
                            <option value="Bueno" {{ $equipment->status == 'Bueno' ? 'selected' : '' }}>Bueno
                            </option>
                            <option value="Regular" {{ $equipment->status == 'Regular' ? 'selected' : '' }}>Regular
                            </option>
                            <option value="Malo" {{ $equipment->status == 'Malo' ? 'selected' : '' }}>Malo</option>
                            <option value="Deshabilitado" {{ $equipment->status == 'Deshabilitado' ? 'selected' : '' }}>
                                Deshabilitado</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="maintenance_type">Tipo de Mantenimiento</label>
                        <select name="maintenance_type" id="maintenance_type">
                            <option value="Preventive" {{ $equipment->maintenance_type == 'Preventive' ? 'selected' : '' }}>Preventivo
                            </option>
                            <option value="Corrective" {{ $equipment->maintenance_type == 'Corrective' ? 'selected' : '' }}>Correctivo
                            </option>
                            <option value="Installation" {{ $equipment->maintenance_type == 'Installation' ? 'selected' : '' }}>Instalación
                            </option>
                            <option value="Disassembly" {{ $equipment->maintenance_type == 'Disassembly' ? 'selected' : '' }}>Desinstalación
                            </option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="technician">Técnico</label>
                        <input type="text" id="technician" name="technician" placeholder="Nombre del técnico"
                            value="{{ $maintenance->technician ?? '' }}">
                    </div>

                    <div class="form-group full-width">
                        <label for="maintenance_description">Descripción del Mantenimiento</label>
                        <textarea id="maintenance_description" name="description" rows="3"
                            placeholder="Detalles del mantenimiento realizado...">{{ trim($maintenance->description ?? '') }}</textarea>
                    </div>
                </div>
            </section>

            <!-- Imágenes del Equipo -->
            <section class="form-section">
                <h2>Imágenes del Equipo</h2>
                <div class="form-grid">
                    <div class="form-group">
                        <label>Imágenes Actuales</label>
                        <div class="current-images-container">
                            @if ($equipment->images->count() > 0)
                                @foreach ($equipment->images as $image)
                                    <div class="image-preview" data-image-id="{{ $image->id }}">
                                        <img src="{{ asset($image->url) }}" alt="Imagen del equipo">
                                        <div class="image-info">
                                            <span
                                                class="image-date">{{ Carbon\Carbon::parse($image->created_at)->format('d/m/Y H:i') }}</span>
                                        </div>
                                        <button type="button" class="remove-image"
                                            onclick="deleteImage({{ $image->id }}, this)">×</button>
                                    </div>
                                @endforeach
                            @else
                                <p>No hay imágenes cargadas</p>
                            @endif
                        </div>

                        <label class="mt-3">Agregar Nuevas Imágenes</label>
                        <div class="image-upload-container">
                            <input type="file" id="new_images" name="new_images[]" accept="image/*" multiple
                                class="image-upload-input" onchange="previewImages(event)">
                            <label for="new_images" class="image-upload-label">
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
                        placeholder="Observaciones adicionales...">{{ $latestObservation ? trim($latestObservation->observation) : '' }}</textarea>
                </div>
            </section>

            <div class="form-actions">
                <button type="submit" class="btn-submit">Guardar Cambios</button>
                <a href="{{ route('equipment.index') }}" class="btn-cancel">Cancelar</a>
            </div>
        </form>
    </main>
    <button class="theme-toggle" aria-label="Toggle dark mode"></button>
</body>

</html>