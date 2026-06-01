<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservas — Admin Rinconcito Perruno</title>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/admin.css'])
</head>
<body>

@include('admin.partials.navbar')

<div class="admin-wrapper">
    @include('admin.partials.sidebar')

    <main class="admin-main">
        <div class="admin-header">
            <h1>Reservas</h1>
            <p>Gestiona todas las reservas del sistema</p>
        </div>

        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        <div class="admin-card">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Cliente</th>
                        <th>Perro</th>
                        <th>Servicio</th>
                        <th>Entrada</th>
                        <th>Salida</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reservas as $reserva)
                    <tr>
                        <td>{{ $reserva->usuario->name ?? '—' }}</td>
                        <td>{{ $reserva->perro->nombre ?? '—' }}</td>
                        <td>{{ $reserva->servicio->nombre ?? '—' }}</td>
                        <td>{{ $reserva->fecha_entrada->format('d/m/Y') }}</td>
                        <td>{{ $reserva->fecha_salida->format('d/m/Y') }}</td>
                        <td><span class="badge badge-{{ $reserva->estado }}">{{ ucfirst($reserva->estado) }}</span></td>
                        <td>
                            <div class="action-btns">
                                @if($reserva->estado === 'pendiente')
                                <form method="POST" action="{{ route('admin.reservas.confirmar', $reserva->id) }}">
                                    @csrf
                                    <button type="submit" class="btn-aprobar">Confirmar</button>
                                </form>
                                @endif
                                @if(!in_array($reserva->estado, ['cancelada', 'finalizada']))
                                <form method="POST" action="{{ route('admin.reservas.cancelar', $reserva->id) }}">
                                    @csrf
                                    <button type="submit" class="btn-rechazar">Cancelar</button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="empty-row">No hay reservas registradas</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div style="margin-top:1rem;">{{ $reservas->links() }}</div>
        </div>
    </main>
</div>

</body>
</html>