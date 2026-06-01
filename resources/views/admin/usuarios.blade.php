<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios — Admin Rinconcito Perruno</title>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/admin.css'])
</head>
<body>

@include('admin.partials.navbar')

<div class="admin-wrapper">
    @include('admin.partials.sidebar')

    <main class="admin-main">
        <div class="admin-header">
            <h1>Clientes</h1>
            <p>Listado de clientes registrados</p>
        </div>

        <div class="admin-card">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Teléfono</th>
                        <th>Perros</th>
                        <th>Registro</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($usuarios as $usuario)
                    <tr>
                        <td><strong>{{ $usuario->name }}</strong></td>
                        <td>{{ $usuario->email }}</td>
                        <td>{{ $usuario->telefono ?? '—' }}</td>
                        <td>{{ $usuario->perros->count() }}</td>
                        <td>{{ $usuario->created_at->format('d/m/Y') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="empty-row">No hay clientes registrados</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div style="margin-top:1rem;">{{ $usuarios->links() }}</div>
        </div>
    </main>
</div>

</body>
</html>