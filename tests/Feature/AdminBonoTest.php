<?php

namespace Tests\Feature;

use App\Models\Bono;
use App\Models\User;

class AdminBonoTest extends RinconcitoPerrunoTestCase
{
    public function test_admin_puede_crear_y_asignar_bono_a_un_cliente(): void
    {
        $this->crearAdmin();

        $cliente = User::factory()->create();
        $cliente->assignRole('cliente');

        $response = $this->post('/admin/bonos', [
            'nombre'       => 'Bono Bienvenida',
            'descripcion'  => '10% en tu primera estancia',
            'tipo'         => 'porcentaje',
            'valor'        => 10,
            'usos_maximos' => 3,
            'id_usuario'   => $cliente->id,
        ]);

        $response->assertRedirect(route('admin.bonos'));
        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('bonos', [
            'nombre'         => 'Bono Bienvenida',
            'id_usuario'     => $cliente->id,
            'usos_maximos'   => 3,
            'usos_restantes' => 3,
            'activo'         => 1,
        ]);
    }

    public function test_admin_puede_ver_la_pagina_de_bonos_con_un_bono_existente(): void
    {
        $this->crearAdmin();

        $cliente = User::factory()->create();
        $cliente->assignRole('cliente');
        Bono::factory()->create([
            'id_usuario' => $cliente->id,
            'nombre'     => 'Bono Fidelidad',
        ]);

        $this->get('/admin/bonos')
            ->assertOk()
            ->assertSee('Bono Fidelidad')
            ->assertSee($cliente->name);
    }

    public function test_crear_bono_requiere_campos_obligatorios(): void
    {
        $this->crearAdmin();

        $response = $this->post('/admin/bonos', [
            // Falta nombre, valor, id_usuario...
            'tipo' => 'porcentaje',
        ]);

        $response->assertSessionHasErrors(['nombre', 'valor', 'usos_maximos', 'id_usuario']);
    }

    public function test_admin_puede_eliminar_bono(): void
    {
        $this->crearAdmin();

        $cliente = User::factory()->create();
        $bono = Bono::factory()->create(['id_usuario' => $cliente->id]);

        $this->delete("/admin/bonos/{$bono->id}")->assertRedirect(route('admin.bonos'));

        $this->assertDatabaseMissing('bonos', ['id' => $bono->id]);
    }

    public function test_cliente_no_puede_acceder_a_la_gestion_de_bonos(): void
    {
        $this->crearCliente();

        $this->get('/admin/bonos')->assertForbidden();
        $this->post('/admin/bonos', [])->assertForbidden();
    }
}
