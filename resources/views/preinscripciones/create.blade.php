<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preinscripción — Rinconcito Perruno</title>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/css/landing.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Nunito', sans-serif; background: #F5E6D0; min-height: 100vh; }
        .page-wrapper { max-width: 720px; margin: 0 auto; padding: 2rem 1.5rem; }

        .page-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        .page-header img {
            height: 80px; width: 80px;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid #C9A84C;
            box-shadow: 0 4px 15px rgba(201,168,76,0.3);
            margin-bottom: 1rem;
        }
        .page-header h1 {
            font-family: 'Fredoka One', cursive;
            font-size: 32px;
            color: #3D1C02;
            margin-bottom: 0.5rem;
        }
        .page-header p { font-size: 15px; color: #8B4513; }

        .form-card {
            background: white;
            border-radius: 20px;
            padding: 2.5rem;
            border: 2px solid #EDD5B3;
            box-shadow: 0 8px 30px rgba(61,28,2,0.08);
        }

        .form-section {
            margin-bottom: 2rem;
            padding-bottom: 2rem;
            border-bottom: 1px solid #EDD5B3;
        }
        .form-section:last-of-type { border-bottom: none; margin-bottom: 0; }

        .form-section-title {
            font-family: 'Fredoka One', cursive;
            font-size: 18px;
            color: #3D1C02;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .form-section-title svg { width: 20px; height: 20px; color: #C9A84C; }

        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
        .form-group { margin-bottom: 1rem; }
        .form-group.full { grid-column: 1 / -1; }

        label {
            display: block;
            font-weight: 700;
            font-size: 14px;
            color: #3D1C02;
            margin-bottom: 6px;
        }
        label span { color: #dc2626; }

        input[type="text"],
        input[type="email"],
        input[type="number"],
        input[type="tel"],
        select,
        textarea {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #EDD5B3;
            border-radius: 12px;
            font-family: 'Nunito', sans-serif;
            font-size: 14px;
            color: #3D1C02;
            background: #FDFAF6;
            outline: none;
            transition: border-color .2s;
        }
        input:focus, select:focus, textarea:focus { border-color: #C9A84C; }
        textarea { resize: vertical; min-height: 100px; }

        .radio-group {
            display: flex;
            gap: 1rem;
        }
        .radio-option {
            flex: 1;
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 12px 16px;
            border: 2px solid #EDD5B3;
            border-radius: 12px;
            cursor: pointer;
            transition: border-color .2s, background .2s;
            background: #FDFAF6;
        }
        .radio-option:has(input:checked) {
            border-color: #C9A84C;
            background: rgba(201,168,76,0.08);
        }
        .radio-option input { accent-color: #C9A84C; width: 16px; height: 16px; }
        .radio-option span { font-size: 14px; font-weight: 600; color: #3D1C02; }

        .error-msg { color: #dc2626; font-size: 12px; margin-top: 4px; }

        .alert-success {
            background: rgba(125,212,200,0.2);
            border: 2px solid #7DD4C8;
            border-radius: 12px;
            padding: 1rem 1.5rem;
            color: #4AADA0;
            font-weight: 700;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .alert-success svg { width: 20px; height: 20px; flex-shrink: 0; }

        .btn-submit {
            width: 100%;
            background: #3D1C02;
            color: #F5E6D0;
            padding: 16px;
            border-radius: 25px;
            border: 2px solid #C9A84C;
            font-family: 'Nunito', sans-serif;
            font-weight: 800;
            font-size: 16px;
            cursor: pointer;
            transition: transform .2s, box-shadow .2s;
            margin-top: 1.5rem;
        }
        .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(61,28,2,0.3); }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: #8B4513;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 1.5rem;
            transition: color .2s;
        }
        .back-link:hover { color: #3D1C02; }
        .back-link svg { width: 16px; height: 16px; }

        @media (max-width: 640px) {
            .form-grid { grid-template-columns: 1fr; }
            .form-card { padding: 1.5rem; }
            .radio-group { flex-direction: column; }
        }
    </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar">
    <a href="/" class="navbar-logo">
        <img src="{{ asset('images/logo.jpeg') }}" alt="Rinconcito Perruno" style="height:38px;width:38px;object-fit:cover;border-radius:50%;border:2px solid #C9A84C;">
        Rinconcito Perruno
    </a>
    <ul class="navbar-links">
        <li><a href="/#servicios">Servicios</a></li>
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
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
        Volver al inicio
    </a>

    <div class="page-header">
        <img src="{{ asset('images/logo.jpeg') }}" alt="Rinconcito Perruno">
        <h1>Formulario de preinscripción</h1>
        <p>Cuéntanos sobre tu perro y nos pondremos en contacto contigo en menos de 24 horas</p>
    </div>

    @if(session('success'))
    <div class="alert-success">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
        {{ session('success') }}
    </div>
    @endif

    <div class="form-card">
        <form method="POST" action="{{ route('preinscripcion.store') }}">
            @csrf

            <!-- DATOS DEL PERRO -->
            <div class="form-section">
                <div class="form-section-title">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 5.172C10 3.782 8.423 2.679 6.5 3c-2.823.47-4.113 6.006-4 7 .08.703 1.725 1.722 3.656 1 1.261-.472 1.96-1.45 2.344-2.5"/><path d="M14.267 5.172c0-1.39 1.577-2.493 3.5-2.172 2.823.47 4.113 6.006 4 7-.08.703-1.725 1.722-3.656 1-1.261-.472-1.96-1.45-2.344-2.5"/><path d="M8 14v.5"/><path d="M16 14v.5"/><path d="M11.25 16.25h1.5L12 17l-.75-.75z"/><path d="M4.42 11.247A13.152 13.152 0 0 0 4 14.556C4 18.728 7.582 21 12 21s8-2.272 8-6.444c0-1.061-.162-2.2-.493-3.309m-9.243-6.082A8.801 8.801 0 0 1 12 5c.78 0 1.5.108 2.161.306"/></svg>
                    Datos del perro
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label>Nombre del perro <span>*</span></label>
                        <input type="text" name="nombre_perro" value="{{ old('nombre_perro') }}" placeholder="Ej: Rocky" required>
                        @error('nombre_perro')<p class="error-msg">{{ $message }}</p>@enderror
                    </div>
                    <div class="form-group">
                        <label>Raza <span>*</span></label>
                        <input type="text" name="raza" value="{{ old('raza') }}" placeholder="Ej: Labrador" required>
                        @error('raza')<p class="error-msg">{{ $message }}</p>@enderror
                    </div>
                    <div class="form-group">
                        <label>Edad (años) <span>*</span></label>
                        <input type="number" name="edad" value="{{ old('edad') }}" min="0" max="30" placeholder="Ej: 3" required>
                        @error('edad')<p class="error-msg">{{ $message }}</p>@enderror
                    </div>
                    <div class="form-group">
                        <label>Peso (kg)</label>
                        <input type="number" name="peso" value="{{ old('peso') }}" min="0" max="100" step="0.1" placeholder="Ej: 25.5">
                        @error('peso')<p class="error-msg">{{ $message }}</p>@enderror
                    </div>
                    <div class="form-group full">
                        <label>Temperamento <span>*</span></label>
                        <select name="temperamento" required>
                            <option value="">Selecciona una opción</option>
                            <option value="tranquilo" {{ old('temperamento') == 'tranquilo' ? 'selected' : '' }}>Tranquilo</option>
                            <option value="activo" {{ old('temperamento') == 'activo' ? 'selected' : '' }}>Activo</option>
                            <option value="sociable" {{ old('temperamento') == 'sociable' ? 'selected' : '' }}>Sociable</option>
                            <option value="agresivo" {{ old('temperamento') == 'agresivo' ? 'selected' : '' }}>Agresivo con otros perros</option>
                        </select>
                        @error('temperamento')<p class="error-msg">{{ $message }}</p>@enderror
                    </div>
                    <div class="form-group full">
                        <label>¿Tiene las vacunas al día? <span>*</span></label>
                        <div class="radio-group">
                            <label class="radio-option">
                                <input type="radio" name="vacunas" value="1" {{ old('vacunas') == '1' ? 'checked' : '' }} required>
                                <span>Sí, vacunas al día</span>
                            </label>
                            <label class="radio-option">
                                <input type="radio" name="vacunas" value="0" {{ old('vacunas') == '0' ? 'checked' : '' }}>
                                <span>No, pendiente de vacunar</span>
                            </label>
                        </div>
                        @error('vacunas')<p class="error-msg">{{ $message }}</p>@enderror
                    </div>
                    <div class="form-group full">
                        <label>Observaciones adicionales</label>
                        <textarea name="observaciones" placeholder="Cuéntanos cualquier detalle importante sobre tu perro: alergias, medicación, comportamiento especial...">{{ old('observaciones') }}</textarea>
                        @error('observaciones')<p class="error-msg">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <!-- DATOS DE CONTACTO (solo si no está autenticado) -->
            @guest
            <div class="form-section">
                <div class="form-section-title">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    Datos de contacto
                </div>
                <div class="form-grid">
                    <div class="form-group">
                        <label>Nombre completo <span>*</span></label>
                        <input type="text" name="nombre_contacto" value="{{ old('nombre_contacto') }}" placeholder="Tu nombre y apellidos" required>
                        @error('nombre_contacto')<p class="error-msg">{{ $message }}</p>@enderror
                    </div>
                    <div class="form-group">
                        <label>Teléfono</label>
                        <input type="tel" name="telefono_contacto" value="{{ old('telefono_contacto') }}" placeholder="666 000 000">
                        @error('telefono_contacto')<p class="error-msg">{{ $message }}</p>@enderror
                    </div>
                    <div class="form-group full">
                        <label>Correo electrónico <span>*</span></label>
                        <input type="email" name="email_contacto" value="{{ old('email_contacto') }}" placeholder="tu@email.com" required>
                        @error('email_contacto')<p class="error-msg">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>
            @endguest

            @auth
            <div style="background:rgba(125,212,200,0.1);border:1px solid #7DD4C8;border-radius:12px;padding:1rem 1.25rem;margin-bottom:1rem;font-size:14px;color:#4AADA0;font-weight:600;">
                Sesión iniciada como {{ Auth::user()->name }} — usaremos tus datos de contacto registrados.
            </div>
            @endauth

            <button type="submit" class="btn-submit">
                Enviar preinscripción
            </button>
        </form>
    </div>
</div>

</body>
</html>