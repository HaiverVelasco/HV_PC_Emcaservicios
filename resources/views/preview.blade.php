<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Inventario - EMCASERVICIOS</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('imgs/Emcaservicios.png') }}">
    <link rel="stylesheet" href="{{ asset('css/preview.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('css/show.css')}}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('css/index.css')}}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('css/social-media.css')}}?v={{ time() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="{{ asset('js/themeToggle.js') }}?v={{ time() }}" defer></script>
    <script src="{{ asset('js/tabs.js') }}?v={{ time() }}" defer></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    @if (session('success'))
        <div class="alert alert-success" id="success-alert">
            {{ session('success') }}
            <button class="alert-close"
                onclick="document.getElementById('success-alert').style.display='none'">&times;</button>
        </div>
        <script>
            setTimeout(function () {
                var alert = document.getElementById('success-alert');
                if (alert) {
                    alert.style.display = 'none';
                }
            }, 4000); // 4 segundos
        </script>
    @endif

    <!-- Encabezado existente -->
    <header class="sheet-header">
        @if(session('is_admin'))
            <form action="{{ route('logout') }}" method="POST" class="logout-form">
                @csrf
                <button type="submit" class="btn-logout" title="Cerrar Sesión">⏻</button>
            </form>
        @endif
        <h1 class="text-center">Sistema de Inventario - EMCASERVICIOS</h1>
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

    <main class="main-content">

        <!-- Botones de acción -->
        <div class="action-buttons">
            @if(session('is_admin'))
                <a href="{{ route('equipment.list') }}" class="action-button">
                    <div class="action-button-icon">
                        <i class="fas fa-plus-circle"></i>
                    </div>
                    Agregar Nuevo Equipo
                </a>
            @endif
            <a href="{{ route('equipment.index') }}" class="action-button">
                <div class="action-button-icon">
                    <i class="fas fa-desktop"></i>
                </div>
                Ver Todos los Equipos
            </a>
        </div>

        <!-- Contenedor de Tabs -->
        <div class="tabs-container">
            <!-- Headers de las secciones -->
            <div class="section-headers">
                <div class="header-tab active" data-section="emcaservicios">
                    <h2>EMCASERVICIOS</h2>
                    <p>Empresa dedicada a la gestión de servicios públicos del Cauca</p>
                    <div class="active-indicator">
                        <i class="chevron-down"></i>
                    </div>
                </div>
                <div class="header-tab" data-section="inventario">
                    <h2>Sistema de Inventario</h2>
                    <p>Gestión inteligente y eficiente de recursos tecnológicos</p>
                    <div class="active-indicator">
                        <i class="chevron-down"></i>
                    </div>
                </div>
            </div>

            <!-- Contenido de las secciones -->
            <div class="sections-content">
                <!-- Sección EMCASERVICIOS -->
                <section class="section-content emcaservicios-section active" id="emcaservicios-content">
                    <div class="info-grid">
                        <div class="info-card info-card-mision">
                            <div class="info-card-bar"></div>
                            <img src="{{ asset('imgs/Mision.png') }}" alt="Misión" class="info-card-img">
                            <h3>Nuestra Misión</h3>
                            <p>Somos una empresa caucana dedicada a la presentación de servicios públicos domiciliarios
                                de
                                acueducto, alcantarillado y aseo, así como actividades complementarias, la cual atiende
                                lo
                                dispuesto en el plan departamental de agua.</p>
                        </div>

                        <div class="info-card">
                            <img src="{{ asset('imgs/Vision.png') }}" alt="Visión" class="info-card-img">
                            <h3>Nuestra Visión</h3>
                            <p>La empresa caucana de servicios publicos EMCASERVICIOS S.A E.S.P. sera reconocida a nivel
                                departamental por su excelencia en la gestion integral del sector de agua potable,
                                saneamiento
                                basico y ejecucion.</p>
                        </div>

                        <div class="info-card">
                            <img src="{{ asset('imgs/Valores.png') }}" alt="Valores" class="info-card-img">
                            <h3>Valores Corporativos</h3>
                            <p>Aumentar la cobertura, calidad y continuidad en el servicio de acueducto y alcantarillado
                                en
                                Cauca. Promover la mejora en el desempeño de los prestadores de servicios públicos de
                                agua y
                                saneamiento en los municipios del Plan Departamental de Agua. Minimizar el impacto
                                negativo en
                                el medio ambiente relacionado con el sector de agua potable y saneamiento.</p>
                        </div>
                    </div>
                </section>

                <!-- Sección Sistema de Inventario -->
                <section class="section-content inventario-section" id="inventario-content">

                    <!-- Información del Sistema -->
                    <div class="info-grid">
                        <div class="info-card">
                            <img src="{{ asset('imgs/sistema.png') }}" alt="Sistema" class="info-card-img">
                            <h3>Descripción</h3>
                            <p>Sistema integral para la gestión y control de equipos informáticos, diseñado
                                específicamente para
                                las necesidades de EMCASERVICIOS.</p>
                        </div>

                        <div class="info-card">
                            <img src="{{ asset('imgs/caracteristicas.png') }}" alt="Características"
                                class="info-card-img">
                            <h3>Características</h3>
                            <p>Registro detallado de equipos, seguimiento de mantenimientos, gestión de áreas y
                                generación de
                                reportes automatizados.</p>
                        </div>

                        <div class="info-card">
                            <img src="{{ asset('imgs/beneficios.png') }}" alt="Beneficios" class="info-card-img">
                            <h3>Beneficios</h3>
                            <p>Control centralizado, historial detallado, alertas de servicio y optimización de recursos
                                tecnológicos de la empresa.</p>
                        </div>
                    </div>
    </main>
</body>

</html>