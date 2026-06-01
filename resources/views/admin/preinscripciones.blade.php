<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preinscripciones — Admin Rinconcito Perruno</title>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
  @vite(['resources/css/admin.css'])
</head>
<body>

@include('admin.partials.navbar')

<div class="admin-wrapper">
    @include('admin.partials.sidebar')

    <main class="admin-main">
        <div class="admin-header">
            <h1>Preinscripciones</h1>
            <p>Gestiona las solicitudes de preinscripción</p>
        </div>

        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        <div class="admin-card">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Perro</th>
                        <th>Raza</th>
                        <th>Edad</th>
                        <th>Temperamento</th>
                        <th>Vacunas</th>
                        <th>Contacto</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($preinscripciones as $p)
                    <tr>
                        <td><strong>{{ $p->nombre_perro }}</strong></td>
                        <td>{{ $p->raza }}</td>
                        <td>{{ $p->edad }} años</td>
                        <td>{{ ucfirst($p->temperamento) }}</td>
                        <td>
                            @if($p->vacunas)
                                <span class="badge badge-confirmada">Sí</span>
                            @else
                                <span class="badge badge-cancelada">No</span>
                            @endif
                        </td>
                        <td>
                            <div>{{ $p->nombre_contacto }}</div>
                            <div style="font-size:12px;color:#8B4513;">{{ $p->email_contacto }}</div>
                        </td>
                        <td>{{ $p->created_at->format('d/m/Y') }}</td>
                        <td><span class="badge badge-{{ $p->estado }}">{{ ucfirst($p->estado) }}</span></td>
                        <td>
                            @if($p->estado === 'pendiente')
                            <div class="action-btns">
                                <form method="POST" action="{{ route('admin.preinscripciones.aprobar', $p->id) }}">
                                    @csrf
                                    <button type="submit" class="btn-aprobar">Aprobar</button>
                                </form>
                                <form method="POST" action="{{ route('admin.preinscripciones.rechazar', $p->id) }}">
                                    @csrf
                                    <button type="submit" class="btn-rechazar">Rechazar</button>
                                </form>
                            </div>
                            @else
                                <span style="font-size:13px;color:#8B4513;">Gestionada</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="9" class="empty-row">No hay preinscripciones registradas</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div style="margin-top:1rem;">{{ $preinscripciones->links() }}</div>
        </div>
    </main>
</div>

</body>
</html>