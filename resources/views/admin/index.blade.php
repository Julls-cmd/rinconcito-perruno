<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Admin — Rinconcito Perruno</title>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/admin.css'])
</head>
<body>

@include('admin.partials.navbar')

<div class="admin-wrapper">
    @include('admin.partials.sidebar')

    <main class="admin-main">
        <div class="admin-header">
            <h1>Panel de administración</h1>
            <p>Bienvenido, {{ Auth::user()->name }}</p>
        </div>

        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon teal">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                </div>
                <div>
                    <div class="stat-num">{{ $totalUsuarios }}</div>
                    <div class="stat-label">Clientes registrados</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon gold">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                </div>
                <div>
                    <div class="stat-num">{{ $totalReservas }}</div>
                    <div class="stat-label">Reservas totales</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon orange">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
                </div>
                <div>
                    <div class="stat-num">{{ $reservasPendientes }}</div>
                    <div class="stat-label">Reservas pendientes</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon brown">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                </div>
                <div>
                    <div class="stat-num">{{ $preinscripcionesPendientes }}</div>
                    <div class="stat-label">Preinscripciones pendientes</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon teal">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 5.172C10 3.782 8.423 2.679 6.5 3c-2.823.47-4.113 6.006-4 7 .08.703 1.725 1.722 3.656 1 1.261-.472 1.96-1.45 2.344-2.5"/><path d="M14.267 5.172c0-1.39 1.577-2.493 3.5-2.172 2.823.47 4.113 6.006 4 7-.08.703-1.725 1.722-3.656 1-1.261-.472-1.96-1.45-2.344-2.5"/><path d="M8 14v.5"/><path d="M16 14v.5"/><path d="M11.25 16.25h1.5L12 17l-.75-.75z"/></svg>
                </div>
                <div>
                    <div class="stat-num">{{ $totalPerros }}</div>
                    <div class="stat-label">Perros registrados</div>
                </div>
            </div>
        </div>

        <div class="admin-grid">
            <div class="admin-card">
                <div class="admin-card-title">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                    Últimas reservas
                    <a href="{{ route('admin.reservas') }}" class="ver-todo">Ver todas</a>
                </div>
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Cliente</th>
                            <th>Perro</th>
                            <th>Entrada</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ultimasReservas as $reserva)
                        <tr>
                            <td>{{ $reserva->usuario->name ?? '—' }}</td>
                            <td>{{ $reserva->perro->nombre ?? '—' }}</td>
                            <td>{{ $reserva->fecha_entrada->format('d/m/Y') }}</td>
                            <td><span class="badge badge-{{ $reserva->estado }}">{{ ucfirst($reserva->estado) }}</span></td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="empty-row">No hay reservas todavía</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="admin-card">
                <div class="admin-card-title">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                    Últimas preinscripciones
                    <a href="{{ route('admin.preinscripciones') }}" class="ver-todo">Ver todas</a>
                </div>
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Perro</th>
                            <th>Contacto</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ultimasPreinscripciones as $p)
                        <tr>
                            <td>{{ $p->nombre_perro }} ({{ $p->raza }})</td>
                            <td>{{ $p->email_contacto ?? '—' }}</td>
                            <td><span class="badge badge-{{ $p->estado }}">{{ ucfirst($p->estado) }}</span></td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="empty-row">No hay preinscripciones todavía</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

</body>
</html>