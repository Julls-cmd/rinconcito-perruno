<x-guest-layout>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">

    <div style="text-align:center;margin-bottom:1.5rem;">
        <h1 style="font-family:'Fredoka One',cursive;font-size:24px;color:#3D1C02;margin-top:0.75rem;">Crear cuenta</h1>
        <p style="font-size:13px;color:#8B4513;">Únete a Rinconcito Perruno</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Nombre -->
        <div style="margin-bottom:1rem;">
            <label style="display:block;font-family:'Nunito',sans-serif;font-weight:700;font-size:14px;color:#3D1C02;margin-bottom:6px;">
                Nombre completo
            </label>
            <input
                type="text"
                name="name"
                value="{{ old('name') }}"
                required
                autofocus
                style="width:100%;padding:12px 16px;border:2px solid #EDD5B3;border-radius:12px;font-family:'Nunito',sans-serif;font-size:14px;color:#3D1C02;background:#FDFAF6;outline:none;transition:border-color .2s;"
                onfocus="this.style.borderColor='#C9A84C'"
                onblur="this.style.borderColor='#EDD5B3'"
            >
            @error('name')
                <p style="color:#dc2626;font-size:12px;margin-top:4px;">{{ $message }}</p>
            @enderror
        </div>

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
                style="width:100%;padding:12px 16px;border:2px solid #EDD5B3;border-radius:12px;font-family:'Nunito',sans-serif;font-size:14px;color:#3D1C02;background:#FDFAF6;outline:none;transition:border-color .2s;"
                onfocus="this.style.borderColor='#C9A84C'"
                onblur="this.style.borderColor='#EDD5B3'"
            >
            @error('email')
                <p style="color:#dc2626;font-size:12px;margin-top:4px;">{{ $message }}</p>
            @enderror
        </div>

        <!-- Teléfono -->
        <div style="margin-bottom:1rem;">
            <label style="display:block;font-family:'Nunito',sans-serif;font-weight:700;font-size:14px;color:#3D1C02;margin-bottom:6px;">
                Teléfono
            </label>
            <input
                type="tel"
                name="telefono"
                value="{{ old('telefono') }}"
                placeholder="666 000 000"
                style="width:100%;padding:12px 16px;border:2px solid #EDD5B3;border-radius:12px;font-family:'Nunito',sans-serif;font-size:14px;color:#3D1C02;background:#FDFAF6;outline:none;transition:border-color .2s;"
                onfocus="this.style.borderColor='#C9A84C'"
                onblur="this.style.borderColor='#EDD5B3'"
            >
            @error('telefono')
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

        <!-- Confirmar Password -->
        <div style="margin-bottom:1.5rem;">
            <label style="display:block;font-family:'Nunito',sans-serif;font-weight:700;font-size:14px;color:#3D1C02;margin-bottom:6px;">
                Confirmar contraseña
            </label>
            <input
                type="password"
                name="password_confirmation"
                required
                style="width:100%;padding:12px 16px;border:2px solid #EDD5B3;border-radius:12px;font-family:'Nunito',sans-serif;font-size:14px;color:#3D1C02;background:#FDFAF6;outline:none;transition:border-color .2s;"
                onfocus="this.style.borderColor='#C9A84C'"
                onblur="this.style.borderColor='#EDD5B3'"
            >
            @error('password_confirmation')
                <p style="color:#dc2626;font-size:12px;margin-top:4px;">{{ $message }}</p>
            @enderror
        </div>

        <!-- Botón -->
        <button type="submit" style="width:100%;background:#3D1C02;color:#F5E6D0;padding:14px;border-radius:25px;border:2px solid #C9A84C;font-family:'Nunito',sans-serif;font-weight:800;font-size:15px;cursor:pointer;transition:background .2s;">
            Crear cuenta
        </button>

        <!-- Login -->
        <p style="text-align:center;margin-top:1.25rem;font-family:'Nunito',sans-serif;font-size:14px;color:#8B4513;">
            ¿Ya tienes cuenta?
            <a href="{{ route('login') }}" style="color:#C9A84C;font-weight:700;text-decoration:none;">Inicia sesión</a>
        </p>
    </form>
</x-guest-layout>