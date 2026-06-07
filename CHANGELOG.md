# Changelog

## [1.0.0] - 2026-06-07

### Security
- Vincular el precio del checkout a la sesión para evitar manipulación del bono/importe enviado en el formulario de pago
- Añadir `lockForUpdate` en la verificación de disponibilidad de reservas para prevenir condiciones de carrera
- Envolver el procesamiento de pagos en `DB::transaction` y añadir clave de idempotencia de Stripe
- Añadir `throttle` a las rutas críticas (preinscripciones, reservas, pagos)
- Inyectar las variables de Stripe con `@json` en la vista de checkout
- Registrar las excepciones de Stripe con `Log::error`
- Añadir `abort(403)` en `PagoController::exito` para evitar el acceso a pagos ajenos

### Added
- Proyecto base — Rinconcito Perruno TFG
- Tests de dominio para reservas, pagos, preinscripciones y multimedia

### Fixed
- Corregir la paginación del panel admin y validar las transiciones de estado de las reservas
- Clasificar la multimedia por su MIME type real en lugar de la extensión del archivo
- Corregir la moneda de Cashier a EUR
- Validar `fecha_expiracion` en la consulta de bonos
- Requerir autenticación en la ruta de disponibilidad
- Corregir la FK `subido_por` apuntando a `users` — mejora de Lighthouse Performance de 80 a 99

### Refactored
- Extraer `calcularDescuento` a un método privado de `PagoController`
- Usar Route Model Binding en las rutas de administración

### Chore
- Eliminar dependencias no usadas (Livewire, Spatie Media Library) y sus tablas huérfanas
- Formatear el código con Laravel Pint
- Añadir las variables de Stripe a `.env.example`
