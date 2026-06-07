<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pago — Rinconcito Perruno</title>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/css/landing.css', 'resources/css/pagos.css', 'resources/js/app.js'])
    <script src="https://js.stripe.com/v3/"></script>
</head>
<body>

<nav class="navbar">
    <a href="/" class="navbar-logo">
        <img src="{{ asset('images/logo.jpeg') }}" alt="Logo" style="height:38px;width:38px;object-fit:contain;border-radius:50%;border:2px solid #C9A84C;">
        Rinconcito Perruno
    </a>
    <ul class="navbar-links">
        <li><a href="{{ url('/dashboard') }}">Mi panel</a></li>
    </ul>
</nav>

<div class="page-wrapper">

    <a href="{{ url('/dashboard') }}" class="back-link">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
        Volver al panel
    </a>

    <div class="page-header">
        <h1>Finalizar pago</h1>
        <p>Completa el pago de tu reserva de forma segura</p>
    </div>

    @if(session('error'))
        <div class="alert-error">{{ session('error') }}</div>
    @endif

    <div class="checkout-grid">

        <!-- RESUMEN -->
        <div class="resumen-card">
            <div class="card-title">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                Resumen de la reserva
            </div>

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
            <div class="resumen-item">
                <span class="resumen-label">Noches</span>
                <span class="resumen-value">{{ $noches }}</span>
            </div>
            <div class="resumen-divider"></div>
            <div class="resumen-item">
                <span class="resumen-label">Precio base</span>
                <span class="resumen-value">{{ number_format($precioBase, 2) }}€</span>
            </div>
            @if($descuento > 0)
            <div class="resumen-item descuento">
                <span class="resumen-label">Descuento ({{ $bono->nombre }})</span>
                <span class="resumen-value">-{{ number_format($descuento, 2) }}€</span>
            </div>
            @endif
            <div class="resumen-total">
                <span>Total</span>
                <span>{{ number_format($total, 2) }}€</span>
            </div>

            <div class="seguridad">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                Pago seguro con Stripe
            </div>
        </div>

        <!-- FORMULARIO DE PAGO -->
        <div class="pago-card">
            <div class="card-title">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>
                Datos de pago
            </div>

            <form id="payment-form" method="POST" action="{{ route('pagos.procesar', $reserva->id) }}">
                @csrf
                <input type="hidden" name="payment_method" id="payment_method">
                @if($bono)
                    <input type="hidden" name="bono_id" value="{{ $bono->id }}">
                @endif

                <div class="form-group">
                    <label>Número de tarjeta</label>
                    <div id="card-element" class="stripe-input"></div>
                    <div id="card-errors" class="error-msg"></div>
                </div>

                <div class="tarjeta-prueba">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/></svg>
                    <div>
                        <strong>Tarjeta de prueba:</strong> 4242 4242 4242 4242<br>
                        <span>Fecha: cualquier fecha futura · CVC: cualquier 3 dígitos</span>
                    </div>
                </div>

                <button type="submit" id="btn-pagar" class="btn-submit">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    Pagar {{ number_format($total, 2) }}€
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    const stripeKey = @json(config('cashier.key'));
    const clientSecret = @json($intent->client_secret);
</script>
<script src="{{ asset('js/checkout.js') }}"></script>

</body>
</html>