<?php

namespace Tests\Feature;

use App\Models\Pago;
use App\Models\Perro;
use App\Models\Reserva;
use App\Models\Servicio;
use App\Models\User;

class PagoTest extends RinconcitoPerrunoTestCase
{
    public function test_cliente_puede_ver_historial_de_pagos(): void
    {
        $this->crearCliente();

        // /pagos llama a createSetupIntent() de Cashier: el FakeStripeHttpClient
        // de la clase base evita la llamada real a la API de Stripe.
        $this->get('/pagos')->assertOk();
    }

    public function test_usuario_no_autenticado_no_puede_ver_pagos(): void
    {
        $this->get('/pagos')->assertRedirect('/login');
    }

    public function test_checkout_muestra_precio_correcto(): void
    {
        ['cliente' => $cliente, 'perro' => $perro] = $this->crearCliente();

        $servicio = Servicio::factory()->create(['precio_base' => 18.00]);

        // 2 noches × 18€ = 36€
        $reserva = Reserva::factory()->create([
            'id_usuario'    => $cliente->id,
            'id_perro'      => $perro->id,
            'id_servicio'   => $servicio->id,
            'fecha_entrada' => now()->addDays(5)->toDateString(),
            'fecha_salida'  => now()->addDays(7)->toDateString(),
        ]);

        $this->get('/pagos/checkout/'.$reserva->id)
            ->assertOk()
            ->assertSee('36');
    }

    public function test_cliente_no_puede_ver_checkout_de_reserva_ajena(): void
    {
        $this->crearCliente();

        $otroUsuario = User::factory()->create();
        $perroAjeno = Perro::factory()->create(['id_usuario' => $otroUsuario->id]);
        $servicio = Servicio::factory()->create();
        $reservaAjena = Reserva::factory()->create([
            'id_usuario'  => $otroUsuario->id,
            'id_perro'    => $perroAjeno->id,
            'id_servicio' => $servicio->id,
        ]);

        $this->get('/pagos/checkout/'.$reservaAjena->id)->assertForbidden();
    }

    public function test_pagina_exito_es_accesible(): void
    {
        ['cliente' => $cliente, 'perro' => $perro, 'servicio' => $servicio] = $this->crearCliente();

        $reserva = Reserva::factory()->create([
            'id_usuario'  => $cliente->id,
            'id_perro'    => $perro->id,
            'id_servicio' => $servicio->id,
        ]);

        Pago::factory()->create([
            'id_reserva' => $reserva->id,
            'estado'     => 'completado',
        ]);

        $this->get('/pagos/exito/'.$reserva->id)->assertOk();
    }
}
