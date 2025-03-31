<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Equipo - EMCASERVICIOS</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('imgs/Emcaservicios.png') }}">
    <link rel="stylesheet" href="{{asset('css/edit.css')}}">
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
    <script src="{{ asset('js/edit.js') }}"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <header class="sheet-header">
        <h1 class="text-center">EDITAR EQUIPO</h1>
        <div class="company-info">
            <img src="{{ asset('imgs/Emcaservicios.png') }}" alt="Logo empresa" class="company-logo">
            <div class="company-contacts">
                <p>EMPRESA CAUCANA DE SERVICIOS PÚBLICOS S.A. E.S.P.</p>
                <p>Carrera 4 N° 22N-02 / Edificio de Infraestructura, primer piso</p>
                <p>Popayán - Cauca - Colombia</p>
            </div>
        </div>
    </header>

    <main class="main-content">
        <form action="{{ route('equipment.update', $equipment->id) }}" method="POST" class="equipment-form" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Información General -->
            <section class="form-section">
                <h2>Información General</h2>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="area_id">Área</label>
                        <select name="area_id" id="area_id" required>
                            @foreach($areas as $area)
                                <option value="{{ $area->id }}" {{ $equipment->area_id == $area->id ? 'selected' : '' }}>
                                    {{ $area->name }}
                                </option>
                            @endforeach
                        </select>
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
                        <label for="inventory_code">Código de Inventario</label>
                        <input type="text" id="inventory_code" name="inventory_code" value="{{ $equipment->inventory_code }}" required>
                    </div>

                    <div class="form-group">
                        <label for="equipment_name">Nombre del Equipo</label>
                        <input type="text" id="equipment_name" name="equipment_name" value="{{ $equipment->equipment_name }}" required>
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
                        <label for="serial_number">Número de Serie</label>
                        <input type="text" id="serial_number" name="serial_number" value="{{ $equipment->serial_number }}">
                    </div>
                </div>
            </section>

            <!-- Información Técnica -->
            <section class="form-section">
                <h2>Información Técnica</h2>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="processor">Procesador</label>
                        <input type="text" id="processor" name="processor" value="{{ $equipment->processor }}">
                    </div>

                    <div class="form-group">
                        <label for="operating_system">Sistema Operativo</label>
                        <input type="text" id="operating_system" name="operating_system" value="{{ $equipment->operating_system }}">
                    </div>

                    <div class="form-group">
                        <label for="ram_memory">Memoria RAM</label>
                        <input type="text" id="ram_memory" name="ram_memory" value="{{ $equipment->ram_memory }}">
                    </div>

                    <div class="form-group">
                        <label for="storage">Almacenamiento</label>
                        <input type="text" id="storage" name="storage" value="{{ $equipment->storage }}">
                    </div>

                    <div class="form-group">
                        <label for="graphic_card">Tarjeta Gráfica</label>
                        <input type="text" id="graphic_card" name="graphic_card" value="{{ $equipment->graphic_card }}">
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
                            <option value="Bueno" {{ $equipment->status == 'Bueno' ? 'selected' : '' }}>Bueno</option>
                            <option value="Regular" {{ $equipment->status == 'Regular' ? 'selected' : '' }}>Regular</option>
                            <option value="Malo" {{ $equipment->status == 'Malo' ? 'selected' : '' }}>Malo</option>
                            <option value="Deshabilitado" {{ $equipment->status == 'Deshabilitado' ? 'selected' : '' }}>Deshabilitado</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="maintenance_type">Tipo de Mantenimiento</label>
                        <select name="maintenance_type" id="maintenance_type">
                            <option value="Preventive" {{ $equipment->maintenance_type == 'Preventive' ? 'selected' : '' }}>Preventivo</option>
                            <option value="Corrective" {{ $equipment->maintenance_type == 'Corrective' ? 'selected' : '' }}>Correctivo</option>
                            <option value="Installation" {{ $equipment->maintenance_type == 'Installation' ? 'selected' : '' }}>Instalación</option>
                            <option value="Disassembly" {{ $equipment->maintenance_type == 'Disassembly' ? 'selected' : '' }}>Desinstalación</option>
                        </select>
                    </div>
                </div>
            </section>

            <!-- Fallas Detectadas -->
            <section class="form-section">
                <h2>Fallas Detectadas</h2>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="depreciation">Depreciación</label>
                        <input type="text" id="depreciation" name="depreciation" value="{{ $equipment->depreciation }}">
                    </div>

                    <div class="form-group">
                        <label for="bad_operation">Mal Funcionamiento</label>
                        <input type="text" id="bad_operation" name="bad_operation" value="{{ $equipment->bad_operation }}">
                    </div>

                    <div class="form-group">
                        <label for="bad_installation">Mala Instalación</label>
                        <input type="text" id="bad_installation" name="bad_installation" value="{{ $equipment->bad_installation }}">
                    </div>
                </div>
            </section>

            <!-- Imágenes del Equipo -->
            <section class="form-section">
                <h2>Imágenes del Equipo</h2>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="images">Imágenes Actuales</label>
                        <div class="current-images-container">
                            @foreach($equipment->images as $image)
                                <div class="image-preview" data-image-id="{{ $image->id }}">
                                    <img src="{{ Storage::url($image->url) }}" alt="Imagen del equipo">
                                    <button type="button" class="remove-image" onclick="deleteImage({{ $image->id }}, this)">×</button>
                                </div>
                            @endforeach
                        </div>

                        <label for="images" class="mt-3">Agregar Nuevas Imágenes</label>
                        <div class="image-upload-container">
                            <input type="file" id="images" name="new_images[]" accept="image/*" multiple 
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
                    <textarea name="observations" id="observations" rows="4" value="{{ $equipment->observations }}" ></textarea>
                </div>
            </section>

            <div class="form-actions">
                <button type="submit" class="btn-submit">Guardar Cambios</button>
                <a href="{{ route('equipment.index') }}" class="btn-cancel">Cancelar</a>
            </div>
        </form>
    </main>
</body>
</html>