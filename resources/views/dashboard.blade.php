<x-app-layout>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/dashboard.css'])

    <div class="dashboard-wrapper">

        <!-- BIENVENIDA -->
        <div class="welcome-card">
            <div class="welcome-text">
                <h2>Hola, {{ explode(' ', Auth::user()->name)[0] }}</h2>
                <p>Bienvenido a tu panel de Rinconcito Perruno</p>
            </div>
            <img src="{{ asset('images/logo.jpeg') }}" alt="Logo" class="welcome-logo">
        </div>

        <!-- STATS -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon brown">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                </div>
                <div>
                    <div class="stat-num">{{ $reservas->count() }}</div>
                    <div class="stat-label">Reservas</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon gold">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 5.172C10 3.782 8.423 2.679 6.5 3c-2.823.47-4.113 6.006-4 7 .08.703 1.725 1.722 3.656 1 1.261-.472 1.96-1.45 2.344-2.5"/><path d="M14.267 5.172c0-1.39 1.577-2.493 3.5-2.172 2.823.47 4.113 6.006 4 7-.08.703-1.725 1.722-3.656 1-1.261-.472-1.96-1.45-2.344-2.5"/><path d="M8 14v.5"/><path d="M16 14v.5"/><path d="M11.25 16.25h1.5L12 17l-.75-.75z"/><path d="M4.42 11.247A13.152 13.152 0 0 0 4 14.556C4 18.728 7.582 21 12 21s8-2.272 8-6.444c0-1.061-.162-2.2-.493-3.309m-9.243-6.082A8.801 8.801 0 0 1 12 5c.78 0 1.5.108 2.161.306"/></svg>
                </div>
                <div>
                    <div class="stat-num">{{ $perros->count() }}</div>
                    <div class="stat-label">Mis perros</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon teal">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 12V22H4V12"/><path d="M22 7H2v5h20V7z"/><path d="M12 22V7"/><path d="M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7z"/><path d="M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z"/></svg>
                </div>
                <div>
                    <div class="stat-num">{{ $bonos->count() }}</div>
                    <div class="stat-label">Bonos activos</div>
                </div>
            </div>
        </div>

        <!-- ACCIONES RÁPIDAS -->
        <div class="actions-grid">
            <a href="{{ route('reservas.index') }}" class="action-btn">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                <span>Nueva reserva</span>
                <small>Consultar disponibilidad</small>
            </a>
            <a href="{{ route('preinscripcion.create') }}" class="action-btn">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                <span>Preinscripción</span>
                <small>Registrar mi perro</small>
            </a>
            @if($perros->isNotEmpty())
            <a href="{{ route('multimedia.index', $perros->first()->id) }}" class="action-btn">
            @else
            <a href="#" class="action-btn" style="opacity:.5;pointer-events:none;">
            @endif
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"/><circle cx="12" cy="13" r="3"/></svg>
                <span>Seguimiento</span>
                <small>Fotos y vídeos</small>
            </a>
            <a href="{{ route('pagos.index') }}" class="action-btn">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>
    <span>Mis pagos</span>
    <small>Historial y bonos</small>
</a>

        <!-- GRID PRINCIPAL -->
        <div class="dashboard-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; width: 100%; align-items: stretch; justify-items: stretch;">

            <!-- RESERVAS -->
            <div class="card" style="width: 100%; height: 100%; box-sizing: border-box;">
                <div class="card-title">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                    Mis reservas recientes
                </div>

                @forelse($reservas as $reserva)
<div class="reserva-item">
    <div class="reserva-info">
        <h4>{{ $reserva->perro->nombre ?? 'Sin perro' }}</h4>
        <p>{{ $reserva->servicio->nombre ?? 'Sin servicio' }} · {{ $reserva->fecha_entrada->format('d/m/Y') }} — {{ $reserva->fecha_salida->format('d/m/Y') }}</p>
    </div>
    <div style="display:flex;align-items:center;gap:10px;">
        <span class="reserva-badge badge-{{ $reserva->estado }}">{{ ucfirst($reserva->estado) }}</span>
        @if(!$reserva->pago)
            <a href="{{ route('pagos.checkout', $reserva->id) }}" style="background:#3D1C02;color:#C9A84C;padding:6px 14px;border-radius:20px;font-size:12px;font-weight:700;text-decoration:none;border:1px solid #C9A84C;">
                Pagar
            </a>
        @endif
    </div>
</div>
                @empty
                <div class="empty-state">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                    <p>No tienes reservas todavía.<br>¡Haz tu primera reserva!</p>
                </div>
                @endforelse
            </div>

            <!-- SIDEBAR -->
            <div class="dashboard-sidebar-col" style="display: flex; flex-direction: column; gap: 1.5rem; width: 100%;">
                <!-- MIS PERROS -->
                <div class="card" style="width: 100%; display: flex; flex-direction: column; flex: 1;">
                    <div class="card-title">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 5.172C10 3.782 8.423 2.679 6.5 3c-2.823.47-4.113 6.006-4 7 .08.703 1.725 1.722 3.656 1 1.261-.472 1.96-1.45 2.344-2.5"/><path d="M14.267 5.172c0-1.39 1.577-2.493 3.5-2.172 2.823.47 4.113 6.006 4 7-.08.703-1.725 1.722-3.656 1-1.261-.472-1.96-1.45-2.344-2.5"/><path d="M8 14v.5"/><path d="M16 14v.5"/><path d="M11.25 16.25h1.5L12 17l-.75-.75z"/><path d="M4.42 11.247A13.152 13.152 0 0 0 4 14.556C4 18.728 7.582 21 12 21s8-2.272 8-6.444c0-1.061-.162-2.2-.493-3.309m-9.243-6.082A8.801 8.801 0 0 1 12 5c.78 0 1.5.108 2.161.306"/></svg>
                        Mis perros
                    </div>
                    <div class="card-items">
                        @forelse($perros as $perro)
                        <div class="perro-item">
                            <div class="perro-avatar">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 5.172C10 3.782 8.423 2.679 6.5 3c-2.823.47-4.113 6.006-4 7 .08.703 1.725 1.722 3.656 1 1.261-.472 1.96-1.45 2.344-2.5"/><path d="M14.267 5.172c0-1.39 1.577-2.493 3.5-2.172 2.823.47 4.113 6.006 4 7-.08.703-1.725 1.722-3.656 1-1.261-.472-1.96-1.45-2.344-2.5"/><path d="M8 14v.5"/><path d="M16 14v.5"/><path d="M11.25 16.25h1.5L12 17l-.75-.75z"/><path d="M4.42 11.247A13.152 13.152 0 0 0 4 14.556C4 18.728 7.582 21 12 21s8-2.272 8-6.444c0-1.061-.162-2.2-.493-3.309m-9.243-6.082A8.801 8.801 0 0 1 12 5c.78 0 1.5.108 2.161.306"/></svg>
                            </div>
                            <div>
                                <div class="perro-name">{{ $perro->nombre }}</div>
                                <div class="perro-raza">{{ $perro->raza }} · {{ $perro->edad }} años</div>
                            </div>
                        </div>
                        @empty
                        <div class="empty-state">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 5.172C10 3.782 8.423 2.679 6.5 3c-2.823.47-4.113 6.006-4 7 .08.703 1.725 1.722 3.656 1 1.261-.472 1.96-1.45 2.344-2.5"/></svg>
                            <p>No tienes perros registrados.</p>
                        </div>
                        @endforelse
                    </div>
                </div>

                <!-- BONOS -->
                <div class="card" style="width: 100%; display: flex; flex-direction: column; flex: 1;">
                    <div class="card-title">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 12V22H4V12"/><path d="M22 7H2v5h20V7z"/><path d="M12 22V7"/><path d="M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7z"/><path d="M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z"/></svg>
                        Mis bonos
                    </div>
                    <div class="card-items">
                        @forelse($bonos as $bono)
                        <div class="bono-item">
                            <div class="bono-name">{{ $bono->nombre }}</div>
                            <div class="bono-desc">{{ $bono->descripcion }}</div>
                            <div class="bono-usos">{{ $bono->usos_restantes }} usos restantes</div>
                        </div>
                        @empty
                        <div class="empty-state">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 12V22H4V12"/><path d="M22 7H2v5h20V7z"/><path d="M12 22V7"/></svg>
                            <p>No tienes bonos activos.</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>