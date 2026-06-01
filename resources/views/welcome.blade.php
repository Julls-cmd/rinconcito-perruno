
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Guardería canina en Trebujena, Cádiz. Recogida a domicilio, cámaras 24h y estética integrada.">
    <title>Rinconcito Perruno — Guardería Canina en Trebujena</title>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/css/landing.css', 'resources/js/app.js', 'resources/js/landing.js'])
</head>
<body>

<!-- ================= NAVBAR ================= -->
<header>
    <nav class="navbar" aria-label="Navegación principal">
        <a href="/" class="navbar-logo">
            <img src="{{ asset('images/logo.jpeg') }}" alt="Logo Rinconcito Perruno" class="navbar-logo-img">
            Rinconcito Perruno
        </a>
        <ul class="navbar-links">
            <li><a href="#servicios">Servicios</a></li>
            <li><a href="#como-funciona">Cómo funciona</a></li>
            <li><a href="#contacto">Contacto</a></li>
            @if (Route::has('login'))
                @auth
                    @if(Auth::user()->hasRole('admin'))
                        <li><a href="{{ route('admin.index') }}">Panel Admin</a></li>
                    @else
                        <li><a href="{{ url('/dashboard') }}">Mi panel</a></li>
                    @endif
                @else
                    <li><a href="{{ route('login') }}">Acceder</a></li>
                    <li><a href="{{ route('register') }}" class="btn-nav">Reservar ahora</a></li>
                @endauth
            @endif
        </ul>
    </nav>
</header>


<!-- ================= HERO ================= -->
<main>
    <section class="hero" aria-label="Presentación">
        <div class="hero-text">
            <div class="hero-badge">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                Trebujena, Cádiz · 2.000 m² al aire libre
            </div>
            <h1 class="hero-title">Tu perro merece el mejor <span>rinconcito</span></h1>
            <p class="hero-sub">Guardería canina con cámaras 24h, recogida a domicilio incluida y estética integrada. Porque tu mascota es de la familia.</p>
            <div class="hero-btns">
                <a href="{{ route('register') }}" class="btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                    Reservar plaza
                </a>
                <a href="{{ route('reservas.index') }}" class="btn-secondary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                    Ver disponibilidad
                </a>
            </div>
        </div>
        <div class="hero-image">
            <div class="hero-circle">
                <img src="{{ asset('images/logo.jpeg') }}" alt="Logo Rinconcito Perruno" class="hero-logo-img">
            </div>
            <div class="hero-badge-float float-1">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"/><circle cx="12" cy="13" r="3"/></svg>
                Cámaras 24h
            </div>
            <div class="hero-badge-float float-2">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                Recogida en 50 km
            </div>
        </div>
    </section>


    <!-- ================= STATS ================= -->
    <section class="stats" aria-label="Datos destacados">
        <div class="stat"><span class="stat-num" data-target="50">50</span><span class="stat-label">Radio de recogida (km)</span></div>
        <div class="stat"><span class="stat-num" data-target="24">24</span><span class="stat-label">Cámaras en directo (h)</span></div>
        <div class="stat"><span class="stat-num">2.000 m²</span><span class="stat-label">Espacio natural</span></div>
        <div class="stat"><span class="stat-num" data-target="100">100</span><span class="stat-label">Profesionales fijos (%)</span></div>
    </section>


    <!-- ================= SERVICIOS ================= -->
    <section id="servicios" class="section" aria-label="Servicios y tarifas">
        <header class="section-header">
            <span class="section-tag">Tarifas</span>
            <h2 class="section-title">Nuestros servicios</h2>
            <div class="section-line"></div>
            <p class="section-sub">Todo lo que tu perro necesita en un solo lugar, sin sorpresas en el precio</p>
        </header>
        <div class="services-grid">
            @foreach($servicios as $servicio)
            <article class="service-card {{ $servicio->incluye_recogida && $servicio->incluye_estetica ? 'featured' : '' }}">
                @if($servicio->incluye_recogida && $servicio->incluye_estetica)
                    <span class="service-badge">Más popular</span>
                @endif
                <div class="service-icon">
                    @if($servicio->incluye_recogida && $servicio->incluye_estetica)
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                    @elseif($servicio->incluye_recogida)
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 17H3a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11a2 2 0 0 1 2 2v3"/><rect width="13" height="8" x="9" y="11" rx="1.5"/><circle cx="11" cy="19" r="2"/><circle cx="18" cy="19" r="2"/></svg>
                    @elseif($servicio->incluye_estetica)
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="6" cy="6" r="3"/><path d="M8.12 8.12 12 12"/><path d="M20 4 8.12 15.88"/><circle cx="18" cy="18" r="3"/><path d="M11.88 11.88 16 16"/></svg>
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                    @endif
                </div>
                <h3 class="service-title">{{ $servicio->nombre }}</h3>
                <p class="service-desc">{{ $servicio->descripcion }}</p>
                <div class="service-price">
                    Desde <span class="service-price-num">{{ number_format($servicio->precio_base, 0) }}€</span>/día
                </div>
            </article>
            @endforeach
        </div>
    </section>


    <!-- ================= CÓMO FUNCIONA ================= -->
    <section id="como-funciona" class="how-section" aria-label="Cómo funciona">
        <div class="container" style="max-width:1100px;margin:0 auto;">
            <header class="section-header">
                <span class="section-tag">Proceso</span>
                <h2 class="section-title">¿Cómo funciona?</h2>
                <div class="section-line"></div>
                <p class="section-sub">En 4 sencillos pasos tu perro estará en las mejores manos</p>
            </header>
            <div class="how-grid">
                <div class="how-step">
                    <div class="how-num">1</div>
                    <h3 class="how-title">Preinscripción</h3>
                    <p class="how-desc">Rellena el formulario con los datos de tu perro. Te contactamos en menos de 24 horas.</p>
                </div>
                <div class="how-step">
                    <div class="how-num">2</div>
                    <h3 class="how-title">Elige fechas</h3>
                    <p class="how-desc">Consulta el calendario de disponibilidad y selecciona las fechas de la estancia.</p>
                </div>
                <div class="how-step">
                    <div class="how-num">3</div>
                    <h3 class="how-title">Recogida</h3>
                    <p class="how-desc">Pasamos a buscar a tu perro en tu domicilio a la hora indicada, sin coste adicional.</p>
                </div>
                <div class="how-step">
                    <div class="how-num">4</div>
                    <h3 class="how-title">Seguimiento 24h</h3>
                    <p class="how-desc">Accede en cualquier momento a fotos y vídeos de tu perro desde tu panel personal.</p>
                </div>
            </div>
        </div>
    </section>


    <!-- ================= POR QUÉ ELEGIRNOS ================= -->
    <section class="features-section" aria-label="Ventajas">
        <div class="container" style="max-width:1100px;margin:0 auto;">
            <header class="section-header">
                <span class="section-tag" style="background:rgba(201,168,76,0.2);color:var(--gold);">Ventajas</span>
                <h2 class="section-title" style="color:var(--gold);">¿Por qué elegirnos?</h2>
                <div class="section-line"></div>
                <p class="section-sub" style="color:rgba(245,230,208,0.7);">Lo que nos hace diferentes a cualquier otra guardería de la provincia</p>
            </header>
            <div class="features-grid">
                <article class="feature-card">
                    <div class="feature-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"/><circle cx="12" cy="13" r="3"/></svg>
                    </div>
                    <h3 class="feature-title">Cámaras 24h</h3>
                    <p class="feature-desc">Accede en tiempo real a fotos y vídeos de tu perro desde tu móvil en cualquier momento del día.</p>
                </article>
                <article class="feature-card">
                    <div class="feature-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V9z"/><path d="M8 7V5a2 2 0 0 1 4 0v2"/></svg>
                    </div>
                    <h3 class="feature-title">2.000 m² naturales</h3>
                    <p class="feature-desc">Instalaciones únicas con celdas individuales y amplias zonas comunes al aire libre en plena naturaleza.</p>
                </article>
                <article class="feature-card">
                    <div class="feature-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    </div>
                    <h3 class="feature-title">Equipo fijo</h3>
                    <p class="feature-desc">Siempre los mismos profesionales especializados. Sin variabilidad ni cambios en la calidad del cuidado.</p>
                </article>
                <article class="feature-card">
                    <div class="feature-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 12V22H4V12"/><path d="M22 7H2v5h20V7z"/><path d="M12 22V7"/><path d="M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7z"/><path d="M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z"/></svg>
                    </div>
                    <h3 class="feature-title">Bonos y descuentos</h3>
                    <p class="feature-desc">Cartera de bonos para estancias largas. Cuanto más nos visitas, mayor es el descuento que obtienes.</p>
                </article>
            </div>
        </div>
    </section>


    <!-- ================= CONTACTO ================= -->
    <section id="contacto" class="contact-section" aria-label="Contacto">
        <div class="contact-inner">
            <header class="section-header">
                <span class="section-tag">Hablamos</span>
                <h2 class="section-title">¿Tienes alguna duda?</h2>
                <div class="section-line"></div>
                <p class="section-sub">Estamos disponibles para resolver cualquier consulta sobre tu mascota</p>
            </header>
            <div class="contact-grid">
                <article class="contact-card">
                    <div class="contact-icon whatsapp">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                    </div>
                    <h3>WhatsApp</h3>
                    <p>Escríbenos directamente. Respondemos en menos de 1 hora en horario de atención.</p>
                    <a href="https://wa.me/34666000000" class="btn-whatsapp" target="_blank" rel="noopener">
                        <!-- Icono oficial WhatsApp SVG -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 32 32" fill="none"><g><circle cx="16" cy="16" r="16" fill="#25D366"/><path d="M23.472 19.339c-.355-.177-2.104-1.037-2.43-1.156-.326-.119-.563-.177-.8.178-.237.355-.914 1.156-1.122 1.393-.207.237-.414.267-.769.089-.355-.178-1.5-.553-2.86-1.763-1.057-.944-1.771-2.108-1.98-2.463-.207-.355-.022-.546.156-.723.16-.159.355-.414.533-.622.178-.207.237-.355.355-.592.119-.237.06-.444-.03-.622-.089-.178-.8-1.924-1.096-2.637-.288-.692-.581-.597-.8-.608-.207-.009-.444-.011-.681-.011-.237 0-.622.089-.948.444-.326.355-1.24 1.211-1.24 2.951 0 1.74 1.268 3.422 1.445 3.659.178.237 2.5 3.82 6.037 5.209.844.29 1.5.463 2.014.592.846.202 1.617.174 2.228.106.68-.075 2.104-.859 2.402-1.691.296-.832.296-1.545.207-1.691-.089-.148-.326-.237-.681-.414z" fill="#fff"/></g></svg>
                        Abrir WhatsApp
                    </a>
                </article>
                <article class="contact-card">
                    <div class="contact-icon phone">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.56 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                    </div>
                    <h3>Teléfono</h3>
                    <p>Llámanos para consultas urgentes o para hablar directamente con nuestro equipo.</p>
                    <a href="tel:+34666000000" class="btn-phone">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.56 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                        Llamar ahora
                    </a>
                </article>
            </div>
        </div>
    </section>


    <!-- ================= CTA FINAL ================= -->
    <section class="cta-section" aria-label="Reserva">
        <div class="cta-inner">
            <h2 class="cta-title">¿Listo para reservar?</h2>
            <p class="cta-sub">Plazas limitadas. Asegura la estancia de tu perro con total tranquilidad.</p>
            <a href="{{ route('register') }}" class="btn-cta">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                Reservar plaza ahora
            </a>
        </div>
    </section>


    <!-- ================= FOOTER ================= -->
    <footer class="footer">
        <div class="footer-inner">
            <div class="footer-logo">
                <img src="{{ asset('images/logo.jpeg') }}" alt="Logo Rinconcito Perruno" class="footer-logo-img">
                Rinconcito Perruno
            </div>
            <nav class="footer-links" aria-label="Enlaces legales">
                <a href="#">Aviso legal</a>
                <a href="#">Política de privacidad</a>
                <a href="#">Cookies</a>
            </nav>
            <div class="footer-copy">© 2026 Rinconcito Perruno · Trebujena, Cádiz</div>
        </div>
    </footer>

</main>

</body>
</html>