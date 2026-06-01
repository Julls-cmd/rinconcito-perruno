<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pago completado — Rinconcito Perruno</title>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/css/landing.css', 'resources/css/pagos.css', 'resources/js/app.js'])
</head>
<body>

<nav class="navbar">
    <a href="/" class="navbar-logo">
        <img src="{{ asset('images/logo.jpeg') }}" alt="Logo" style="height:38px;width:38px;object-fit:contain;border-radius:50%;border:2px solid #C9A84C;">
        Rinconcito Perruno
    </a>
</nav>

<div class="page-wrapper">
    <div class="exito-card">
        <div class="exito-icon">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
        </div>
        <h1>¡Pago completado!</h1>
        <p>Tu reserva ha sido confirmada correctamente.</p>

        <div class="exito-detalles">
            <div class="resumen-item">
                <span class="resumen-label">Perro</span>
                <span class="resumen-value">{{ $reserva->perro->nombre }}</span>
            </div>
            <div class="resumen-item">
                <span class="resumen-label">Servicio</span>
                <span class="resumen-value">{{ $reserva->servicio->nombre }}</span>
            </div>
            <div class="resumen-item">
                <span class="resumen-label">Entrada</span>
                <span class="resumen-value">{{ $reserva->fecha_entrada->format('d/m/Y') }}</span>
            </div>
            <div class="resumen-item">
                <span class="resumen-label">Salida</span>
                <span class="resumen-value">{{ $reserva->fecha_salida->format('d/m/Y') }}</span>
            </div>
            @if($pago)
            <div class="resumen-divider"></div>
            <div class="resumen-item">
                <span class="resumen-label">Total pagado</span>
                <span class="resumen-value" style="color:#4AADA0;font-weight:800;">{{ number_format($pago->importe, 2) }}€</span>
            </div>
            @endif
        </div>

        <a href="{{ url('/dashboard') }}" class="btn-submit" style="text-decoration:none;display:inline-flex;align-items:center;gap:8px;justify-content:center;">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/></svg>
            Ir a mi panel
        </a>
    </div>
</div>

</body>
</html>