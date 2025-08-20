<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Administrativo - EMCASERVICIOS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="{{ asset('imgs/Emcaservicios.png') }}">
    <link rel="stylesheet" href="{{ asset('css/pages/admin_login.css') }}">
</head>

<body>
    <div class="login-container">
        <div class="login-header">
            <img src="{{ asset('imgs/Emcaservicios.png') }}" alt="Logo EMCASERVICIOS" class="login-logo">
            <h1>Acceso Administrativo</h1>
        </div>

        @if ($errors->any())
            <div class="alert alert-error">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.admin.submit') }}" class="login-form">
            @csrf
            <div class="form-group">
                <label for="username"><i class="fas fa-user"></i> Usuario</label>
                <input type="text" id="username" name="username" placeholder="Ingrese su nombre de usuario" required>
            </div>
            <div class="form-group">
                <label for="password"><i class="fas fa-lock"></i> Contraseña</label>
                <input type="password" id="password" name="password" placeholder="Ingrese su contraseña" required>
            </div>
            <button type="submit" class="login-button admin">
                <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
            </button>
            <a href="{{ route('login') }}" class="back-button">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </form>
    </div>
</body>

</html>