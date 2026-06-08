<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Disponibilidad — Rinconcito Perruno</title>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/css/landing.css', 'resources/css/reservas.css', 'resources/js/app.js'])
</head>
<body>

<nav class="navbar">
    <a href="/" class="navbar-logo">
        <img src="{{ asset('images/logo.jpeg') }}" alt="Rinconcito Perruno" style="height:38px;width:38px;object-fit:contain;border-radius:50%;border:2px solid #C9A84C;">
        Rinconcito Perruno
    </a>
    <ul class="navbar-links">
        <li><a href="/#servicios">Servicios</a></li>
        <li><a href="/preinscripcion">Preinscripción</a></li>
        <li><a href="/#contacto">Contacto</a></li>
        @auth
    @if(Auth::user()->hasRole('admin'))
        <li><a href="{{ route('admin.index') }}">Panel Admin</a></li>
    @else
        <li><a href="{{ url('/dashboard') }}">Mi panel</a></li>
    @endif
@else
            <li><a href="{{ route('login') }}">Acceder</a></li>
            <li><a href="{{ route('register') }}" class="btn-nav">Registrarse</a></li>
        @endauth
    </ul>
</nav>

<div class="page-wrapper">

    <a href="/" class="back-link">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
        Volver al inicio
    </a>

    <div class="page-header">
        <h1>Disponibilidad</h1>
        <p>Consulta las fechas disponibles y realiza tu reserva en tiempo real</p>
    </div>

    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert-error">{{ session('error') }}</div>
    @endif

    <div class="content-grid">

        <!-- CALENDARIO -->
        <div class="calendar-card">
            <div class="card-title">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                Calendario de disponibilidad
            </div>
            <div class="legend">
                <div class="legend-item">
                    <div class="legend-dot" style="background:#7DD4C8;"></div>
                    Hoy
                </div>
                <div class="legend-item">
                    <div class="legend-dot" style="background:#8B4513;"></div>
                    Ocupado
                </div>
                <div class="legend-item">
                    <div class="legend-dot" style="background:#F5E6D0;border:2px solid #EDD5B3;"></div>
                    Disponible
                </div>
            </div>
            <div id="calendar"></div>
        </div>

        <!-- FORMULARIO -->
        <div class="form-card">
            <div class="card-title">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                Nueva reserva
            </div>

            @auth
            <form method="POST" action="{{ route('reservas.store') }}" id="reservaForm">
                @csrf

                <div class="form-group">
                    <label>Fecha de entrada <span>*</span></label>
                    <input type="date" name="fecha_entrada" id="fecha_entrada" required min="{{ date('Y-m-d') }}" value="{{ old('fecha_entrada') }}">
                    @error('fecha_entrada')<p class="error-msg">{{ $message }}</p>@enderror
                </div>

                <div class="form-group">
                    <label>Fecha de salida <span>*</span></label>
                    <input type="date" name="fecha_salida" id="fecha_salida" required min="{{ date('Y-m-d', strtotime('+1 day')) }}" value="{{ old('fecha_salida') }}">
                    @error('fecha_salida')<p class="error-msg">{{ $message }}</p>@enderror
                </div>

                <div class="form-group">
                    <label>Servicio <span>*</span></label>
                    <select name="id_servicio" id="id_servicio" required>
                        <option value="">Selecciona un servicio</option>
                        @foreach($servicios as $servicio)
                            <option value="{{ $servicio->id }}" data-precio="{{ $servicio->precio_base }}" {{ old('id_servicio') == $servicio->id ? 'selected' : '' }}>
                                {{ $servicio->nombre }} — {{ number_format($servicio->precio_base, 0) }}€/día
                            </option>
                        @endforeach
                    </select>
                    @error('id_servicio')<p class="error-msg">{{ $message }}</p>@enderror
                </div>

                @if($perros->count() > 0)
                <div class="form-group">
                    <label>Tu perro <span>*</span></label>
                    <select name="id_perro" required>
                        <option value="">Selecciona tu perro</option>
                        @foreach($perros as $perro)
                            <option value="{{ $perro->id }}" {{ old('id_perro') == $perro->id ? 'selected' : '' }}>
                                {{ $perro->nombre }} ({{ $perro->raza }})
                            </option>
                        @endforeach
                    </select>
                    @error('id_perro')<p class="error-msg">{{ $message }}</p>@enderror
                </div>
                @else
                <div class="alert-error" style="font-size:13px;">
                    No tienes perros registrados.
                    <a href="/preinscripcion" style="color:#dc2626;text-decoration:underline;">Haz una preinscripción primero.</a>
                </div>
                @endif

                <div class="form-group">
                    <label>Dirección de recogida</label>
                    <input type="text" name="direccion_recogida" placeholder="Calle, número, ciudad" value="{{ old('direccion_recogida') }}">
                </div>

                <div class="form-group">
                    <label>Notas adicionales</label>
                    <textarea name="notas" rows="2" placeholder="Cualquier información adicional...">{{ old('notas') }}</textarea>
                </div>

                <div class="precio-display" id="precioDisplay">
                    <div class="precio-label">Precio estimado</div>
                    <div class="precio-num" id="precioTotal">0€</div>
                    <div class="precio-noches" id="precioNoches"></div>
                </div>

                <button type="submit" class="btn-submit">
                    Confirmar reserva
                </button>
            </form>
            @else
            <div class="login-prompt">
                <p>Inicia sesión para realizar una reserva</p>
                <a href="{{ route('login') }}">Iniciar sesión</a>
            </div>
            @endauth
        </div>
    </div>
</div>

<script>
    const disponibilidadUrl = "{{ route('reservas.disponibilidad') }}";
</script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
<script src="{{ asset('js/reservas.js') }}"></script>
</body>
</html>