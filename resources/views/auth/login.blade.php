<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - EMCASERVICIOS</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('imgs/Emcaservicios.png') }}">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>

<body>
    <div class="login-container">
        <div class="login-header">
            <img src="{{ asset('imgs/Emcaservicios.png') }}" alt="Logo EMCASERVICIOS" class="login-logo">
            <h1>Sistema de Inventario</h1>
        </div>

        <div class="login-options">
            <a href="{{ route('login.admin') }}" class="login-button admin">
                <span class="icon">👤</span>
                Acceso Administrativo
            </a>
            <a href="{{ route('equipment.index') }}" class="login-button guest">
                <span class="icon">👥</span>
                Acceso Visitante
            </a>
        </div>
    </div>
</body>

</html>
