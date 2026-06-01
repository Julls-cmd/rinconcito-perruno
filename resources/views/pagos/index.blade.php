<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis pagos — Rinconcito Perruno</title>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/css/landing.css', 'resources/css/pagos.css', 'resources/js/app.js'])
</head>
<body>

<nav class="navbar">
    <a href="/" class="navbar-logo">
        <img src="{{ asset('images/logo.jpeg') }}" alt="Logo" style="height:38px;width:38px;object-fit:contain;border-radius:50%;border:2px solid #C9A84C;">
        Rinconcito Perruno
    </a>
    <ul class="navbar-links">
        <li><a href="{{ url('/dashboard') }}">Mi panel</a></li>
        <li><a href="{{ route('reservas.index') }}">Reservas</a></li>
    </ul>
</nav>

<div class="page-wrapper">

    <a href="{{ url('/dashboard') }}" class="back-link">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
        Volver al panel
    </a>

    <div class="page-header">
        <h1>Mis pagos</h1>
        <p>Historial de pagos y bonos disponibles</p>
    </div>

    <div class="pagos-grid">

        <!-- HISTORIAL -->
        <div class="pago-card">
            <div class="card-title">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>
                Historial de pagos
            </div>

            @forelse($pagos as $pago)
            <div class="pago-item">
                <div class="pago-info">
                    <h4>{{ $pago->reserva->perro->nombre ?? '—' }}</h4>
                    <p>{{ $pago->reserva->servicio->nombre ?? '—' }} · {{ $pago->reserva->fecha_entrada->format('d/m/Y') }}</p>
                    @if($pago->stripe_payment_id)
                        <p style="font-size:11px;color:#8B4513;margin-top:2px;">ID: {{ substr($pago->stripe_payment_id, 0, 20) }}...</p>
                    @endif
                </div>
                <div style="text-align:right;">
                    <div style="font-family:'Fredoka One',cursive;font-size:20px;color:#3D1C02;">{{ number_format($pago->importe, 2) }}€</div>
                    <span class="badge badge-{{ $pago->estado }}">{{ ucfirst($pago->estado) }}</span>
                </div>
            </div>
            @empty
            <div class="empty-state">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>
                <p>No tienes pagos registrados todavía.</p>
            </div>
            @endforelse
        </div>

        <!-- BONOS -->
        <div class="pago-card">
            <div class="card-title">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 12V22H4V12"/><path d="M22 7H2v5h20V7z"/><path d="M12 22V7"/><path d="M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7z"/><path d="M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z"/></svg>
                Mis bonos activos
            </div>

            @forelse($bonos as $bono)
            <div class="bono-item-pago">
                <div>
                    <div class="bono-name">{{ $bono->nombre }}</div>
                    <div class="bono-desc">{{ $bono->descripcion }}</div>
                    @if($bono->descuento_porcentaje > 0)
                        <div class="bono-descuento">{{ $bono->descuento_porcentaje }}% de descuento</div>
                    @else
                        <div class="bono-descuento">{{ number_format($bono->descuento_fijo, 2) }}€ de descuento</div>
                    @endif
                </div>
                <div class="bono-usos-badge">{{ $bono->usos_restantes }} usos</div>
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

</body>
</html>