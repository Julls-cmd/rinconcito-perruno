<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bonos — Admin Rinconcito Perruno</title>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/admin.css'])
</head>
<body>

@include('admin.partials.navbar')

<div class="admin-wrapper">
    @include('admin.partials.sidebar')

    <main class="admin-main">
        <div class="admin-header">
            <h1>Bonos</h1>
            <p>Crea bonos de descuento y asígnalos a un cliente</p>
        </div>

        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        {{-- FORMULARIO DE CREACIÓN --}}
        <div class="admin-card" style="margin-bottom:1.5rem;">
            <h3 style="margin:0 0 1rem;color:#3D1C02;font-family:'Fredoka One',cursive;">Nuevo bono</h3>

            <form method="POST" action="{{ route('admin.bonos.store') }}">
                @csrf
                <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:1rem;">

                    <div>
                        <label style="display:block;font-weight:700;color:#3D1C02;font-size:13px;margin-bottom:4px;">Nombre <span style="color:#dc2626;">*</span></label>
                        <input type="text" name="nombre" value="{{ old('nombre') }}" required maxlength="100" style="width:100%;padding:8px 10px;border:1px solid #EDD5B3;border-radius:8px;">
                        @error('nombre')<p style="color:#dc2626;font-size:12px;margin-top:4px;">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label style="display:block;font-weight:700;color:#3D1C02;font-size:13px;margin-bottom:4px;">Cliente <span style="color:#dc2626;">*</span></label>
                        <select name="id_usuario" required style="width:100%;padding:8px 10px;border:1px solid #EDD5B3;border-radius:8px;">
                            <option value="">Selecciona un cliente</option>
                            @foreach($usuarios as $usuario)
                                <option value="{{ $usuario->id }}" {{ old('id_usuario') == $usuario->id ? 'selected' : '' }}>
                                    {{ $usuario->name }} ({{ $usuario->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('id_usuario')<p style="color:#dc2626;font-size:12px;margin-top:4px;">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label style="display:block;font-weight:700;color:#3D1C02;font-size:13px;margin-bottom:4px;">Tipo de descuento <span style="color:#dc2626;">*</span></label>
                        <select name="tipo" required style="width:100%;padding:8px 10px;border:1px solid #EDD5B3;border-radius:8px;">
                            <option value="porcentaje" {{ old('tipo') === 'porcentaje' ? 'selected' : '' }}>Porcentaje (%)</option>
                            <option value="fijo" {{ old('tipo') === 'fijo' ? 'selected' : '' }}>Importe fijo (€)</option>
                        </select>
                        @error('tipo')<p style="color:#dc2626;font-size:12px;margin-top:4px;">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label style="display:block;font-weight:700;color:#3D1C02;font-size:13px;margin-bottom:4px;">Valor <span style="color:#dc2626;">*</span></label>
                        <input type="number" name="valor" value="{{ old('valor') }}" step="0.01" min="0.01" required style="width:100%;padding:8px 10px;border:1px solid #EDD5B3;border-radius:8px;">
                        @error('valor')<p style="color:#dc2626;font-size:12px;margin-top:4px;">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label style="display:block;font-weight:700;color:#3D1C02;font-size:13px;margin-bottom:4px;">Usos máximos <span style="color:#dc2626;">*</span></label>
                        <input type="number" name="usos_maximos" value="{{ old('usos_maximos', 1) }}" min="1" max="100" required style="width:100%;padding:8px 10px;border:1px solid #EDD5B3;border-radius:8px;">
                        @error('usos_maximos')<p style="color:#dc2626;font-size:12px;margin-top:4px;">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label style="display:block;font-weight:700;color:#3D1C02;font-size:13px;margin-bottom:4px;">Caducidad (opcional)</label>
                        <input type="date" name="fecha_expiracion" value="{{ old('fecha_expiracion') }}" min="{{ date('Y-m-d', strtotime('+1 day')) }}" style="width:100%;padding:8px 10px;border:1px solid #EDD5B3;border-radius:8px;">
                        @error('fecha_expiracion')<p style="color:#dc2626;font-size:12px;margin-top:4px;">{{ $message }}</p>@enderror
                    </div>

                    <div style="grid-column:1/-1;">
                        <label style="display:block;font-weight:700;color:#3D1C02;font-size:13px;margin-bottom:4px;">Descripción (opcional)</label>
                        <input type="text" name="descripcion" value="{{ old('descripcion') }}" maxlength="255" style="width:100%;padding:8px 10px;border:1px solid #EDD5B3;border-radius:8px;">
                        @error('descripcion')<p style="color:#dc2626;font-size:12px;margin-top:4px;">{{ $message }}</p>@enderror
                    </div>
                </div>

                <button type="submit" class="btn-aprobar" style="margin-top:1rem;">Crear y asignar bono</button>
            </form>
        </div>

        {{-- LISTADO DE BONOS --}}
        <div class="admin-card">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Cliente</th>
                        <th>Bono</th>
                        <th>Descuento</th>
                        <th>Usos</th>
                        <th>Caduca</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bonos as $bono)
                    <tr>
                        <td>{{ $bono->usuario->name ?? '—' }}</td>
                        <td>{{ $bono->nombre }}</td>
                        <td>
                            @if($bono->descuento_porcentaje > 0)
                                {{ rtrim(rtrim(number_format($bono->descuento_porcentaje, 2), '0'), '.') }}%
                            @else
                                {{ number_format($bono->descuento_fijo, 2) }}€
                            @endif
                        </td>
                        <td>{{ $bono->usos_restantes }} / {{ $bono->usos_maximos }}</td>
                        <td>{{ $bono->fecha_expiracion ? $bono->fecha_expiracion->format('d/m/Y') : 'Sin caducidad' }}</td>
                        <td>
                            <span class="badge badge-{{ $bono->estaVigente() ? 'confirmada' : 'cancelada' }}">
                                {{ $bono->estaVigente() ? 'Vigente' : 'No vigente' }}
                            </span>
                        </td>
                        <td>
                            <form method="POST" action="{{ route('admin.bonos.destroy', $bono->id) }}" onsubmit="return confirm('¿Eliminar este bono?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-rechazar">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="empty-row">No hay bonos creados todavía</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div style="margin-top:1rem;">{{ $bonos->links() }}</div>
        </div>
    </main>
</div>

</body>
</html>
