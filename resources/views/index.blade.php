<header class="main-header">
    <div class="header-content">
        <h1>EMCASERVICIOS</h1>
        @if (session('is_admin'))
            <form action="{{ route('logout') }}" method="POST" class="logout-form">
                @csrf
                <button type="submit" class="btn-logout" title="Cerrar Sesión">⏻</button>
            </form>
        @endif
    </div>
</header>
