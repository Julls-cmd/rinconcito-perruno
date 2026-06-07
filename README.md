# Rinconcito Perruno 🐾

Aplicación web de gestión para una guardería canina, desarrollada como Trabajo de Fin de Grado (TFG) sobre **Laravel 13.8**. Permite a los clientes reservar plazas para sus perros, pagar online mediante **Stripe** (Laravel Cashier), gestionar bonos de descuento y consultar la galería multimedia de cada estancia. Incluye un panel de administración completo con control de **roles** (`admin` / `cliente`), gestión de reservas, preinscripciones y usuarios.

## Stack tecnológico

| Tecnología | Versión exacta | Función |
|---|---|---|
| PHP | 8.3.30 | Lenguaje del backend |
| Laravel | ^13.8 | Framework principal |
| MySQL | 8.4 | Base de datos relacional |
| Node.js / Vite | ^8.0.0 | Build de assets frontend |
| Tailwind CSS | ^3.1.0 | Framework de estilos |
| Alpine.js | ^3.4.2 | Interactividad ligera (dropdowns, modales) |
| FullCalendar | 6.1.10 | Calendario de disponibilidad de reservas |
| Stripe / Laravel Cashier | ^16.5 | Pagos online y gestión de clientes Stripe |
| Spatie Laravel Permission | ^7.4 | Roles y permisos (`admin`, `cliente`) |
| PHPUnit | ^12.5.12 | Suite de tests |

## Requisitos previos

- PHP >= 8.3
- MySQL 8.x
- Node.js >= 18
- Composer
- Laragon (recomendado para Windows)

## Instalación paso a paso

1. Clonar el repositorio
2. `cp .env.example .env` y rellenar variables (DB, Stripe)
3. `composer install`
4. `php artisan key:generate`
5. Crear BD `rinconcito_perruno` en MySQL
6. `php artisan migrate --seed`
7. `php artisan storage:link`
8. `npm install && npm run build`
9. `php artisan serve --port=8000`

## Credenciales de prueba

| Rol | Email | Contraseña |
|-----|-------|-----------|
| Admin | admin@rinconcitoperruno.es | password |
| Cliente | cliente@ejemplo.es | password |

## Estructura del proyecto

```
rinconcito-perruno/
├── app/        → Controladores, modelos Eloquent y providers (lógica de negocio)
├── database/   → Migraciones, seeders y factories
├── resources/  → Vistas Blade y assets CSS/JS (Tailwind, Alpine, FullCalendar)
├── routes/     → Definición de rutas web (web.php) y de autenticación (auth.php)
├── tests/      → Suite de tests Feature y Unit (PHPUnit)
└── public/     → Punto de entrada de la aplicación y assets compilados por Vite
```

## Tests

Resultado de la suite completa:

```
Tests: 55 passed (113 assertions) — 100% en verde
```

Grupos de tests de dominio:

| Grupo | Nº de tests |
|-------|-------------|
| `PagoTest` | 5 |
| `ReservaTest` | 7 |
| `PreinscripcionTest` | 8 |
| `MultimediaTest` | 6 |
| `BonoTest` (Unit) | 4 |

Comando para ejecutarlos:

```bash
php artisan test
```

## Arquitectura de seguridad

- **IDOR resuelto** — verificación de propiedad (`id_usuario`/`id_perro`) antes de acceder a reservas, pagos, perros y multimedia ajenos
- **Throttle en rutas críticas** — limitación de peticiones en preinscripciones, reservas y procesado de pagos
- **`DB::transaction` + `lockForUpdate`** — bloqueo pesimista al verificar disponibilidad y al procesar pagos, evitando condiciones de carrera y dobles reservas
- **Idempotency key de Stripe** — cada intento de pago usa una clave única (`pago_reserva_{id}`) para prevenir cobros duplicados ante reintentos
- **Precio vinculado en sesión** — el importe y el bono mostrados en el checkout se guardan en sesión y se reutilizan al procesar el pago, evitando que el cliente manipule el `bono_id` enviado por el formulario
- **`abort(403)` por propiedad** — comprobación explícita de pertenencia del recurso al usuario autenticado en cada controlador sensible

## Sostenibilidad y rendimiento

- Lighthouse Performance: **99/100** (SEO: 100, Best Practices: 100)
- Assets minificados con Vite
- Fuentes cargadas de forma no bloqueante
- Consultas con eager loading (sin problemas N+1)

Alineación con los Objetivos de Desarrollo Sostenible (ODS):
- **ODS 8** (Trabajo decente y crecimiento económico) — digitalización de la gestión de una PYME del sector servicios
- **ODS 12** (Producción y consumo responsables) — gestión eficiente de recursos mediante la optimización del calendario de reservas

## Mejoras futuras

1. Extraer la lógica de negocio de los controladores a clases `Services`/`Actions`
2. Implementar el CRUD completo de empleados
3. Integrar webhooks de Stripe para sincronizar el estado de los pagos en tiempo real
4. Exponer una API REST autenticada con Laravel Sanctum
5. Configurar CI/CD y despliegue automatizado en un VPS

## Manual de despliegue (producción)

1. Configurar `.env` con `APP_ENV=production` y `APP_DEBUG=false`
2. Ejecutar `php artisan config:cache`
3. Ejecutar `php artisan route:cache`
4. Compilar assets con `npm run build`
5. Configurar el servidor web (Apache/Nginx) apuntando al directorio `/public`
