<x-app-layout>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/css/multimedia.css', 'resources/js/app.js'])

    <div class="multimedia-page-wrapper">

        {{-- CABECERA --}}
        <div class="multimedia-header">
            <h1>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"/>
                    <circle cx="12" cy="13" r="3"/>
                </svg>
                Seguimiento de {{ $perro->nombre }}
            </h1>
            <a href="{{ route('dashboard') }}" class="btn-back">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m15 18-6-6 6-6"/>
                </svg>
                Volver al panel
            </a>
        </div>

        {{-- FILTROS --}}
        @if($multimedia->count() > 0)
        <div class="multimedia-filters">
            <button class="filter-btn active" onclick="filtrar('todos', this)">
                Todos ({{ $multimedia->count() }})
            </button>
            <button class="filter-btn" onclick="filtrar('foto', this)">
                <span>Fotos ({{ $multimedia->where('tipo', 'foto')->count() }})</span>
            </button>
            <button class="filter-btn" onclick="filtrar('video', this)">
                <span>Vídeos ({{ $multimedia->where('tipo', 'video')->count() }})</span>
            </button>
        </div>
        @endif

        {{-- GRID DE MULTIMEDIA --}}
        <div class="multimedia-grid" id="multimedia-grid">

            @forelse($multimedia as $item)
            <div class="multimedia-card" data-tipo="{{ $item->tipo }}">

                @if($item->tipo === 'foto')
                    <img
                        src="{{ Storage::url($item->ruta_archivo) }}"
                        alt="{{ $item->descripcion ?? 'Foto de ' . $perro->nombre }}"
                        class="multimedia-img"
                        loading="lazy"
                    >
                @else
                    <video
                        class="multimedia-video"
                        controls
                        preload="metadata"
                        aria-label="Vídeo de {{ $perro->nombre }}"
                    >
                        <source src="{{ Storage::url($item->ruta_archivo) }}" type="video/mp4">
                        Tu navegador no soporta la reproducción de vídeo.
                    </video>
                @endif

                <div class="multimedia-info">
                    <span class="multimedia-tipo {{ $item->tipo }}">
                        @if($item->tipo === 'foto')
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"/>
                                <circle cx="12" cy="13" r="3"/>
                            </svg>
                            Foto
                        @else
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polygon points="23 7 16 12 23 17 23 7"/>
                                <rect width="15" height="14" x="1" y="5" rx="2" ry="2"/>
                            </svg>
                            Vídeo
                        @endif
                    </span>

                    @if($item->descripcion)
                        <p class="multimedia-desc">{{ $item->descripcion }}</p>
                    @endif

                    <div class="multimedia-meta">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect width="18" height="18" x="3" y="4" rx="2" ry="2"/>
                            <line x1="16" x2="16" y1="2" y2="6"/>
                            <line x1="8" x2="8" y1="2" y2="6"/>
                            <line x1="3" x2="21" y1="10" y2="10"/>
                        </svg>
                        {{ $item->created_at->format('d/m/Y') }}
                        @if($item->reserva)
                            &nbsp;·&nbsp; Estancia {{ $item->reserva->fecha_entrada->format('d/m') }} – {{ $item->reserva->fecha_salida->format('d/m/Y') }}
                        @endif
                    </div>
                </div>

            </div>
            @empty

            <div class="multimedia-empty">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"/>
                    <circle cx="12" cy="13" r="3"/>
                </svg>
                <h3>Aún no hay fotos ni vídeos</h3>
                <p>
                    Cuando {{ $perro->nombre }} esté en la guardería, nuestro equipo irá subiendo
                    fotos y vídeos para que no te pierdas nada.
                </p>
            </div>

            @endforelse
        </div>

    </div>

    {{-- JS de filtrado --}}
    <script>
    function filtrar(tipo, btn) {
        // Actualizar botones
        document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');

        // Filtrar cards
        document.querySelectorAll('#multimedia-grid .multimedia-card').forEach(card => {
            if (tipo === 'todos' || card.dataset.tipo === tipo) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
    }
    </script>

</x-app-layout>
