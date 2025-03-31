<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Equipos-EMCASERVICIOS</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('imgs/Emcaservicios.png') }}">
    <link rel="stylesheet" href="{{asset('css/show.css')}}">
    <link rel="stylesheet" href=" {{asset('css/edit.css')}} ">
    
</head>
<body>
    <div class="alerts-container">
        @if(session()->has('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
                <button class="alert-close">&times;</button>
            </div>
        @endif

        @if(session()->has('info'))
            <div class="alert alert-info" role="alert">
                {{ session('info') }}
                <button class="alert-close">&times;</button>
            </div>
        @endif

        @if(session()->has('error'))
            <div class="alert alert-error" role="alert">
                {{ session('error') }}
                <button class="alert-close">&times;</button>
            </div>
        @endif
    </div>

    <!-- Encabezado existente -->
    <header class="sheet-header">
        <h1 class="text-center">EQUIPOS REGISTRADOS</h1>
        <div class="company-info">
            <img src="{{ asset('imgs/Emcaservicios.png') }}" alt="Logo empresa" class="company-logo">
            <div class="company-contacts">
                <p>EMPRESA CAUCANA DE SERVICIOS PÚBLICOS S.A. E.S.P.</p>
                <p>Carrera 4 N° 22N-02 / Edificio de Infraestructura, primer piso</p>
                <p>Popayán - Cauca - Colombia</p>
            </div>
        </div>
    </header>

    <main class="equipment-container">
        @foreach($areas as $area)
            <section class="area-section" style="border-color: {{ $area->color }}">
                <h2 class="area-title" style="background-color: {{ $area->color }}">
                    {{ $area->name }}
                </h2>
                <div class="equipment-grid">
                    @forelse($area->equipment as $equipment)
                        <div class="equipment-card">
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
                                <p><strong>Serie:</strong> {{ $equipment->serial_number }}</p>
                                <p><strong>Procesador:</strong> {{ $equipment->processor }}</p>
                                <p><strong>RAM:</strong> {{ $equipment->ram_memory }}</p>
                                <p><strong>Almacenamiento:</strong> {{ $equipment->storage }}</p>
                            </div>
                            <div class="equipment-footer">
                                <a href="{{ route('equipment.edit', $equipment->id) }}" class="btn-edit">Editar</a>
                                <a href="{{ route('equipment.pdf', $equipment->id) }}" class="btn-pdf" target="_blank">Generar PDF</a>
                                <form action="{{ route('equipment.destroy', $equipment->id) }}" method="POST" class="delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-delete" onclick="return confirm('¿Está seguro que desea eliminar este equipo?')">
                                        Eliminar
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <p class="no-equipment">No hay equipos registrados en esta área</p>
                    @endforelse
                </div>
            </section>
        @endforeach
    </main>

    <div class="floating-button">
        <a href="{{ route('equipment.create') }}" class="btn-add">
            Agregar Equipo
        </a>
    </div>

    <script src="{{ asset('js/show.js') }}"></script>
</body>
</html>