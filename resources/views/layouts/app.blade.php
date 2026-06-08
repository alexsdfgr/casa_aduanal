{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Simulador Aduanal') – UPTex</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
</head>

<body>

    <nav class="navbar-uptex">

        {{-- Logo --}}
        <div class="brand">
            <img src="{{ asset('assets/img/logo.png') }}" alt="UPTex" style="height:38px;object-fit:contain;">
        </div>

        <ul>
            @php $rol = auth()->user()->rol ?? ''; @endphp

            @if($rol === 'ADMIN')
                <li><a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="bi bi-house me-1"></i>Inicio
                    </a></li>

            @elseif($rol === 'PROFESOR')
                <li><a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="bi bi-house me-1"></i>Inicio
                    </a></li>
                <li><a href="{{ route('pedimentos.index') }}"
                        class="{{ request()->routeIs('pedimentos.*') ? 'active' : '' }}">
                        <i class="bi bi-search me-1"></i>Revisar Pedimentos
                    </a></li>
                <li><a href="{{ route('usuarios.index') }}" class="{{ request()->routeIs('usuarios.*') ? 'active' : '' }}">
                        <i class="bi bi-mortarboard me-1"></i>Ver Alumnos
                    </a></li>

            @elseif($rol === 'ALUMNO')
                <li><a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="bi bi-house me-1"></i>Inicio
                    </a></li>
                <li><a href="{{ route('pedimentos.index') }}"
                        class="{{ request()->routeIs('pedimentos.index') ? 'active' : '' }}">
                        <i class="bi bi-file-earmark-text me-1"></i>Mis Pedimentos
                    </a></li>
            @endif

            <li>
                <span class="rol-badge rol-{{ strtolower($rol) }}">{{ $rol }}</span>
            </li>

            <li>
                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                    @csrf
                    <button type="submit" style="background:#E53E3E;border:none;padding:8px 14px;border-radius:6px;
                               font-weight:600;font-size:.82rem;cursor:pointer;color:#fff;
                               font-family:'Poppins',sans-serif;">
                        <i class="bi bi-box-arrow-right me-1"></i>Salir
                    </button>
                </form>
            </li>
        </ul>
    </nav>

    <div class="main-content">

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-3">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-3">
                <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show mb-3">
                <i class="bi bi-exclamation-triangle me-2"></i>
                <strong>Corrige los siguientes errores:</strong>
                <ul class="mb-0 mt-1">
                    @foreach($errors->all() as $e)
                        <li style="font-size:.85rem">{{ $e }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>

</html>