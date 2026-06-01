<x-guest-layout>
    <div style="text-align:center;margin-bottom:1.5rem;">
        <h1 style="font-family:'Fredoka One',cursive;font-size:24px;color:#3D1C02;margin-top:0.75rem;">Rinconcito Perruno</h1>
        <p style="font-size:13px;color:#8B4513;">Accede a tu panel personal</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email -->
        <div style="margin-bottom:1rem;">
            <label style="display:block;font-family:'Nunito',sans-serif;font-weight:700;font-size:14px;color:#3D1C02;margin-bottom:6px;">
                Correo electrónico
            </label>
            <input
                type="email"
                name="email"
                value="{{ old('email') }}"
                required
                autofocus
                style="width:100%;padding:12px 16px;border:2px solid #EDD5B3;border-radius:12px;font-family:'Nunito',sans-serif;font-size:14px;color:#3D1C02;background:#FDFAF6;outline:none;transition:border-color .2s;"
                onfocus="this.style.borderColor='#C9A84C'"
                onblur="this.style.borderColor='#EDD5B3'"
            >
            @error('email')
                <p style="color:#dc2626;font-size:12px;margin-top:4px;">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div style="margin-bottom:1rem;">
            <label style="display:block;font-family:'Nunito',sans-serif;font-weight:700;font-size:14px;color:#3D1C02;margin-bottom:6px;">
                Contraseña
            </label>
            <input
                type="password"
                name="password"
                required
                style="width:100%;padding:12px 16px;border:2px solid #EDD5B3;border-radius:12px;font-family:'Nunito',sans-serif;font-size:14px;color:#3D1C02;background:#FDFAF6;outline:none;transition:border-color .2s;"
                onfocus="this.style.borderColor='#C9A84C'"
                onblur="this.style.borderColor='#EDD5B3'"
            >
            @error('password')
                <p style="color:#dc2626;font-size:12px;margin-top:4px;">{{ $message }}</p>
            @enderror
        </div>

        <!-- Remember me -->
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;">
            <label style="display:flex;align-items:center;gap:8px;font-family:'Nunito',sans-serif;font-size:14px;color:#8B4513;cursor:pointer;">
                <input type="checkbox" name="remember" style="accent-color:#C9A84C;">
                Recordarme
            </label>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" style="font-family:'Nunito',sans-serif;font-size:13px;color:#C9A84C;text-decoration:none;font-weight:600;">
                    ¿Olvidaste tu contraseña?
                </a>
            @endif
        </div>

        <!-- Botón -->
        <button type="submit" style="width:100%;background:#3D1C02;color:#F5E6D0;padding:14px;border-radius:25px;border:2px solid #C9A84C;font-family:'Nunito',sans-serif;font-weight:800;font-size:15px;cursor:pointer;transition:background .2s;">
            Iniciar sesión
        </button>

        <!-- Registro -->
        <p style="text-align:center;margin-top:1.25rem;font-family:'Nunito',sans-serif;font-size:14px;color:#8B4513;">
            ¿No tienes cuenta?
            <a href="{{ route('register') }}" style="color:#C9A84C;font-weight:700;text-decoration:none;">Regístrate aquí</a>
        </p>
    </form>

    <link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
</x-guest-layout>