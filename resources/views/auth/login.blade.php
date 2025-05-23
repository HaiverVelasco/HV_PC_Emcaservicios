<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - EMCASERVICIOS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="{{ asset('imgs/Emcaservicios.png') }}">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>

<body>
    <div class="floating-shapes"></div>
    <div class="login-container">
        <div class="login-content">
            <!-- Columna izquierda: Logo e información de la empresa -->
            <div class="left-column">
                <div class="login-header">
                    <img src="{{ asset('imgs/Emcaservicios.png') }}" alt="Logo EMCASERVICIOS" class="login-logo">
                    <h1>Sistema de Inventario</h1>
                </div>

                <!-- Información de la empresa -->
                <div class="company-info">
                    <h2>EMCASERVICIOS</h2>
                    <p>Empresa caucana dedicada a la prestación de servicios públicos 
                        domiciliarios de acueducto, alcantarillado, aseo y actividades 
                        complementarias, la cual atienden lo dispuesto en el Plan Departamental de Agua.</p>
                </div>

                <!-- Funcionalidad del sistema -->
                <div class="system-info">
                    <h3>Acerca del Sistema</h3>
                    <p>Plataforma de gestión para el control y seguimiento del inventario de equipos informáticos,
                        mantenimientos y registro histórico de incidencias.</p>
                </div>
            </div>

            <!-- Columna derecha: Opciones de acceso -->
            <div class="right-column">
                <div class="access-title">
                    <h3>Tipo de Acceso</h3>
                    <p>Seleccione el tipo de acceso según su rol:</p>
                </div>

                <!-- Sección de opciones de acceso -->
                <div class="login-options">
                    <a href="{{ route('login.admin') }}" class="login-button admin">
                        <span class="icon"><i class="fas fa-user-shield"></i></span>
                        Acceso Administrativo
                    </a>
                    <a href="{{ route('equipment.index') }}" class="login-button guest">
                        <span class="icon"><i class="fas fa-users"></i></span>
                        Acceso Visitante
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
