<?php

namespace Tests\Feature;

use App\Models\Perro;
use App\Models\Reserva;
use App\Models\Servicio;
use App\Models\User;

class ReservaTest extends RinconcitoPerrunoTestCase
{
    public function test_cliente_autenticado_puede_ver_calendario(): void
    {
        $this->crearCliente();

        $this->get('/disponibilidad')->assertOk();
    }

    public function test_disponibilidad_devuelve_json_con_eventos(): void
    {
        ['cliente' => $cliente, 'perro' => $perro, 'servicio' => $servicio] = $this->crearCliente();

        Reserva::factory()->confirmada()->create([
            'id_usuario'  => $cliente->id,
            'id_perro'    => $perro->id,
            'id_servicio' => $servicio->id,
        ]);

        $response = $this->getJson('/disponibilidad/eventos');

        $response->assertOk()
            ->assertJsonStructure([
                '*' => ['title', 'start', 'end'],
            ]);
    }

    public function test_cliente_puede_crear_reserva_valida(): void
    {
        ['perro' => $perro, 'servicio' => $servicio] = $this->crearCliente();

        $response = $this->post('/reservas', [
            'fecha_entrada' => now()->addDays(10)->toDateString(),
            'fecha_salida'  => now()->addDays(12)->toDateString(),
            'id_perro'      => $perro->id,
            'id_servicio'   => $servicio->id,
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('reservas', [
            'id_perro' => $perro->id,
            'estado'   => 'pendiente',
        ]);
    }

    public function test_usuario_no_autenticado_no_puede_crear_reserva(): void
    {
        $response = $this->post('/reservas', [
            'fecha_entrada' => now()->addDays(10)->toDateString(),
            'fecha_salida'  => now()->addDays(12)->toDateString(),
            'id_perro'      => 1,
            'id_servicio'   => 1,
        ]);

        $response->assertRedirect('/login');
    }

    public function test_reserva_requiere_campos_obligatorios(): void
    {
        ['perro' => $perro, 'servicio' => $servicio] = $this->crearCliente();

        $response = $this->post('/reservas', [
            // Falta fecha_entrada
            'fecha_salida' => now()->addDays(12)->toDateString(),
            'id_perro'     => $perro->id,
            'id_servicio'  => $servicio->id,
        ]);

        $response->assertSessionHasErrors('fecha_entrada');
    }

    public function test_no_se_puede_crear_reserva_con_fechas_solapadas(): void
    {
        ['cliente' => $cliente, 'perro' => $perro, 'servicio' => $servicio] = $this->crearCliente();

        // Reserva confirmada que ocupa las fechas
        Reserva::factory()->confirmada()->create([
            'id_usuario'    => $cliente->id,
            'id_perro'      => $perro->id,
            'id_servicio'   => $servicio->id,
            'fecha_entrada' => now()->addDays(7)->toDateString(),
            'fecha_salida'  => now()->addDays(9)->toDateString(),
        ]);

        // Intento de reserva solapada
        $response = $this->post('/reservas', [
            'fecha_entrada' => now()->addDays(8)->toDateString(),
            'fecha_salida'  => now()->addDays(10)->toDateString(),
            'id_perro'      => $perro->id,
            'id_servicio'   => $servicio->id,
        ]);

        // El ReservaController@store gestiona el conflicto con redirect()->back()
        // y un mensaje flash 'error' (no errores de validación).
        $response->assertRedirect();
        $response->assertSessionHas('error');
        // No se ha creado la segunda reserva.
        $this->assertDatabaseCount('reservas', 1);
    }

    public function test_cliente_no_puede_reservar_con_perro_ajeno(): void
    {
        $this->crearCliente();

        // Perro de otro usuario
        $otroUsuario = User::factory()->create();
        $perroAjeno = Perro::factory()->create(['id_usuario' => $otroUsuario->id]);
        $servicio = Servicio::factory()->create();

        $response = $this->post('/reservas', [
            'fecha_entrada' => now()->addDays(10)->toDateString(),
            'fecha_salida'  => now()->addDays(12)->toDateString(),
            'id_perro'      => $perroAjeno->id,
            'id_servicio'   => $servicio->id,
        ]);

        $response->assertForbidden();
        $this->assertDatabaseMissing('reservas', ['id_perro' => $perroAjeno->id]);
    }
}
