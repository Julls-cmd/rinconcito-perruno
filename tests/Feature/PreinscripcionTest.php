<?php

namespace Tests\Feature;

use App\Models\Preinscripcion;

class PreinscripcionTest extends RinconcitoPerrunoTestCase
{
    /**
     * Datos válidos del perro (sin datos de contacto).
     */
    private function datosPerro(array $overrides = []): array
    {
        return array_merge([
            'nombre_perro' => 'Toby',
            'raza' => 'Labrador',
            'edad' => 3,
            'peso' => 12.5,
            'vacunas' => 1,
            'temperamento' => 'sociable',
            'observaciones' => 'Muy juguetón',
        ], $overrides);
    }

    public function test_formulario_preinscripcion_es_publico(): void
    {
        $this->get('/preinscripcion')->assertOk();
    }

    public function test_visitante_puede_enviar_preinscripcion_con_datos_contacto(): void
    {
        $response = $this->post('/preinscripcion', $this->datosPerro([
            'nombre_contacto' => 'Ana García',
            'email_contacto' => 'ana@example.com',
            'telefono_contacto' => '600123456',
        ]));

        $response->assertStatus(302);
        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('preinscripciones', [
            'nombre_perro' => 'Toby',
            'estado' => 'pendiente',
        ]);
    }

    public function test_preinscripcion_visitante_requiere_datos_contacto(): void
    {
        $response = $this->post('/preinscripcion', $this->datosPerro([
            // Falta nombre_contacto
            'email_contacto' => 'ana@example.com',
        ]));

        $response->assertSessionHasErrors('nombre_contacto');
    }

    public function test_cliente_autenticado_puede_enviar_preinscripcion_sin_contacto(): void
    {
        $this->crearCliente();

        $response = $this->post('/preinscripcion', $this->datosPerro());

        $response->assertStatus(302);
        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('preinscripciones', [
            'nombre_perro' => 'Toby',
            'estado' => 'pendiente',
        ]);
    }

    public function test_preinscripcion_inicial_esta_en_estado_pendiente(): void
    {
        $this->post('/preinscripcion', $this->datosPerro([
            'nombre_contacto' => 'Ana García',
            'email_contacto' => 'ana@example.com',
        ]));

        $this->assertDatabaseHas('preinscripciones', ['estado' => 'pendiente']);
    }

    public function test_admin_puede_aprobar_preinscripcion(): void
    {
        $this->crearAdmin();

        $preinscripcion = Preinscripcion::factory()->create(['estado' => 'pendiente']);

        $this->post("/admin/preinscripciones/{$preinscripcion->id}/aprobar");

        $this->assertDatabaseHas('preinscripciones', [
            'id' => $preinscripcion->id,
            'estado' => 'aprobada',
        ]);
    }

    public function test_admin_puede_rechazar_preinscripcion(): void
    {
        $this->crearAdmin();

        $preinscripcion = Preinscripcion::factory()->create(['estado' => 'pendiente']);

        $this->post("/admin/preinscripciones/{$preinscripcion->id}/rechazar");

        $this->assertDatabaseHas('preinscripciones', [
            'id' => $preinscripcion->id,
            'estado' => 'rechazada',
        ]);
    }

    public function test_cliente_no_puede_aprobar_preinscripcion(): void
    {
        $this->crearCliente();

        $preinscripcion = Preinscripcion::factory()->create(['estado' => 'pendiente']);

        $this->post("/admin/preinscripciones/{$preinscripcion->id}/aprobar")
            ->assertForbidden();
    }
}
