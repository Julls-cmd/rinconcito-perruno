<nav class="admin-navbar">
    <a href="/admin" class="admin-navbar-logo">
        <img src="{{ asset('images/logo.jpeg') }}" alt="Rinconcito Perruno">
        Rinconcito Perruno
        <span class="admin-badge">Admin</span>
    </a>
    <div class="admin-navbar-right">
        <span>{{ Auth::user()->name }}</span>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn-logout">Cerrar sesión</button>
        </form>
    </div>
</nav>