<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Multimedia — Panel Admin · Rinconcito Perruno</title>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/admin.css'])
</head>
<body>

@include('admin.partials.navbar')

<div class="admin-wrapper">
    @include('admin.partials.sidebar')

    <main class="admin-main">

        <div class="admin-header">
            <h1>Seguimiento Multimedia</h1>
            <p>Sube y gestiona fotos y vídeos de los perritos durante su estancia</p>
        </div>

        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="alert-error">
                <ul style="margin:0;padding-left:1.2rem;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- ── ZONA DE SUBIDA ── --}}
        <div class="upload-zone">
            <div class="upload-zone-header">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                    <polyline points="17 8 12 3 7 8"/>
                    <line x1="12" x2="12" y1="3" y2="15"/>
                </svg>
                Subir nuevo archivo
            </div>

            <form
                action="{{ route('multimedia.store') }}"
                method="POST"
                enctype="multipart/form-data"
                class="upload-form"
                id="upload-form"
            >
                @csrf

                {{-- Select reserva --}}
                <div class="upload-field">
                    <label for="id_reserva" class="upload-label">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:14px;height:14px;">
                            <rect width="18" height="18" x="3" y="4" rx="2" ry="2"/>
                            <line x1="16" x2="16" y1="2" y2="6"/>
                            <line x1="8" x2="8" y1="2" y2="6"/>
                            <line x1="3" x2="21" y1="10" y2="10"/>
                        </svg>
                        Reserva (perro + fechas)
                    </label>
                    <select name="id_reserva" id="id_reserva" class="upload-select" required onchange="actualizarPerro(this)">
                        <option value="">— Selecciona una reserva —</option>
                        @foreach($reservas as $reserva)
                            <option
                                value="{{ $reserva->id }}"
                                data-perro="{{ $reserva->id_perro }}"
                                {{ old('id_reserva') == $reserva->id ? 'selected' : '' }}
                            >
                                {{ $reserva->perro->nombre ?? '?' }}
                                ({{ $reserva->fecha_entrada->format('d/m/Y') }} – {{ $reserva->fecha_salida->format('d/m/Y') }})
                                · {{ $reserva->usuario->name ?? '—' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Campo oculto id_perro --}}
                <input type="hidden" name="id_perro" id="id_perro" value="{{ old('id_perro') }}">

                {{-- Input file --}}
                <div class="upload-field">
                    <label for="archivo" class="upload-label">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:14px;height:14px;">
                            <path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"/>
                            <circle cx="12" cy="13" r="3"/>
                        </svg>
                        Archivo (foto o vídeo)
                    </label>
                    <div class="file-drop-area" id="file-drop-area">
                        <input
                            type="file"
                            name="archivo"
                            id="archivo"
                            accept="image/jpeg,image/png,image/webp,video/mp4,video/quicktime"
                            class="file-input"
                            required
                            onchange="previewFile(this)"
                        >
                        <div class="file-drop-label" id="file-drop-label">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                <polyline points="17 8 12 3 7 8"/>
                                <line x1="12" x2="12" y1="3" y2="15"/>
                            </svg>
                            <span>Arrastra aquí o haz clic para seleccionar</span>
                            <small>JPG, PNG, WEBP, MP4, MOV · Máx. 50 MB</small>
                        </div>
                        <div id="file-preview" class="file-preview" style="display:none;"></div>
                    </div>
                </div>

                {{-- Descripción --}}
                <div class="upload-field">
                    <label for="descripcion" class="upload-label">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:14px;height:14px;">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                            <polyline points="14 2 14 8 20 8"/>
                            <line x1="16" x2="8" y1="13" y2="13"/>
                            <line x1="16" x2="8" y1="17" y2="17"/>
                            <polyline points="10 9 9 9 8 9"/>
                        </svg>
                        Descripción (opcional)
                    </label>
                    <textarea
                        name="descripcion"
                        id="descripcion"
                        class="upload-textarea"
                        rows="2"
                        maxlength="255"
                        placeholder="Ej: Jugando en el jardín por la mañana..."
                    >{{ old('descripcion') }}</textarea>
                </div>

                <button type="submit" class="btn-subir">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                        <polyline points="17 8 12 3 7 8"/>
                        <line x1="12" x2="12" y1="3" y2="15"/>
                    </svg>
                    Subir archivo
                </button>
            </form>
        </div>

        {{-- ── ARCHIVOS SUBIDOS ── --}}
        <div class="admin-card" style="margin-top: 2rem;">
            <div class="admin-card-title">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                    <circle cx="8.5" cy="8.5" r="1.5"/>
                    <polyline points="21 15 16 10 5 21"/>
                </svg>
                Archivos subidos
                <span style="margin-left:auto;font-family:'Nunito',sans-serif;font-size:13px;font-weight:700;color:#8B4513;">
                    {{ $multimedia->count() }} archivo{{ $multimedia->count() !== 1 ? 's' : '' }}
                </span>
            </div>

            @if($multimedia->isEmpty())
                <div style="text-align:center;padding:3rem;color:#8B4513;">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="width:48px;height:48px;color:#EDD5B3;display:block;margin:0 auto 1rem;">
                        <path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"/>
                        <circle cx="12" cy="13" r="3"/>
                    </svg>
                    <p style="font-weight:600;">Aún no hay archivos subidos</p>
                </div>
            @else
                <div class="media-grid-admin">
                    @foreach($multimedia as $item)
                    <div class="media-thumb-admin">
                        {{-- Preview --}}
                        <div class="media-preview-admin">
                            @if($item->tipo === 'foto')
                                <img
                                    src="{{ Storage::url($item->ruta_archivo) }}"
                                    alt="{{ $item->descripcion ?? 'Foto' }}"
                                    loading="lazy"
                                >
                            @else
                                <video preload="metadata" muted>
                                    <source src="{{ Storage::url($item->ruta_archivo) }}" type="video/mp4">
                                </video>
                                <div class="video-overlay-admin">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="white" stroke="white" stroke-width="1">
                                        <polygon points="5 3 19 12 5 21 5 3"/>
                                    </svg>
                                </div>
                            @endif
                            <span class="media-badge-tipo {{ $item->tipo }}">
                                {{ $item->tipo === 'foto' ? 'Foto' : 'Vídeo' }}
                            </span>
                        </div>

                        {{-- Info --}}
                        <div class="media-info-admin">
                            <div class="media-perro-admin">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:14px;height:14px;color:#C9A84C;flex-shrink:0;">
                                    <path d="M10 5.172C10 3.782 8.423 2.679 6.5 3c-2.823.47-4.113 6.006-4 7 .08.703 1.725 1.722 3.656 1 1.261-.472 1.96-1.45 2.344-2.5"/>
                                    <path d="M14.267 5.172c0-1.39 1.577-2.493 3.5-2.172 2.823.47 4.113 6.006 4 7-.08.703-1.725 1.722-3.656 1-1.261-.472-1.96-1.45-2.344-2.5"/>
                                    <path d="M8 14v.5"/><path d="M16 14v.5"/>
                                    <path d="M11.25 16.25h1.5L12 17l-.75-.75z"/>
                                    <path d="M4.42 11.247A13.152 13.152 0 0 0 4 14.556C4 18.728 7.582 21 12 21s8-2.272 8-6.444c0-1.061-.162-2.2-.493-3.309m-9.243-6.082A8.801 8.801 0 0 1 12 5c.78 0 1.5.108 2.161.306"/>
                                </svg>
                                <strong>{{ $item->perro->nombre ?? '—' }}</strong>
                            </div>
                            <p class="media-fecha-admin">Subido por: {{ $item->subidoPor->name ?? 'Admin' }}</p>
                            @if($item->descripcion)
                                <p class="media-desc-admin">{{ Str::limit($item->descripcion, 60) }}</p>
                            @endif
                            <p class="media-fecha-admin">{{ $item->created_at->format('d/m/Y H:i') }}</p>
                        </div>

                        {{-- Eliminar --}}
                        <form
                            action="{{ route('multimedia.destroy', $item->id) }}"
                            method="POST"
                            onsubmit="return confirm('¿Seguro que quieres eliminar este archivo? Esta acción no se puede deshacer.')"
                        >
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-eliminar" title="Eliminar archivo">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="3 6 5 6 21 6"/>
                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/>
                                    <line x1="10" x2="10" y1="11" y2="17"/>
                                    <line x1="14" x2="14" y1="11" y2="17"/>
                                </svg>
                                Eliminar
                            </button>
                        </form>
                    </div>
                    @endforeach
                </div>
            @endif

            {{ $multimedia->links() }}
        </div>

    </main>
</div>

<script>
// Actualiza el campo oculto id_perro al cambiar la reserva
function actualizarPerro(select) {
    const option = select.options[select.selectedIndex];
    document.getElementById('id_perro').value = option.dataset.perro || '';
}

// Previsualización del archivo seleccionado
function previewFile(input) {
    const preview = document.getElementById('file-preview');
    const label   = document.getElementById('file-drop-label');

    if (!input.files || !input.files[0]) return;

    const file = input.files[0];
    const url  = URL.createObjectURL(file);
    const isVideo = file.type.startsWith('video/');

    label.style.display = 'none';
    preview.style.display = 'block';
    preview.innerHTML = isVideo
        ? `<video src="${url}" controls preload="metadata" style="max-width:100%;max-height:200px;border-radius:8px;"></video><p class="preview-name">${file.name}</p>`
        : `<img src="${url}" alt="Vista previa" style="max-width:100%;max-height:200px;border-radius:8px;object-fit:cover;"><p class="preview-name">${file.name}</p>`;
}

// Inicializar id_perro si ya hay una opción seleccionada (old())
document.addEventListener('DOMContentLoaded', function () {
    const sel = document.getElementById('id_reserva');
    if (sel && sel.value) actualizarPerro(sel);
});
</script>

</body>
</html>
