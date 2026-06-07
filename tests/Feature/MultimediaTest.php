<?php

namespace Tests\Feature;

use App\Models\Multimedia;
use App\Models\Perro;
use App\Models\Reserva;
use App\Models\Servicio;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class MultimediaTest extends RinconcitoPerrunoTestCase
{
    public function test_cliente_puede_ver_multimedia_de_su_perro(): void
    {
        ['perro' => $perro] = $this->crearCliente();

        $this->get('/multimedia/'.$perro->id)->assertOk();
    }

    public function test_cliente_no_puede_ver_multimedia_de_perro_ajeno(): void
    {
        $this->crearCliente();

        // MultimediaController@index hace abort(403) si el perro no es del usuario.
        $otroUsuario = User::factory()->create();
        $perroAjeno = Perro::factory()->create(['id_usuario' => $otroUsuario->id]);

        $this->get('/multimedia/'.$perroAjeno->id)->assertForbidden();
    }

    public function test_admin_puede_subir_multimedia(): void
    {
        Storage::fake('public');

        ['admin' => $admin] = $this->crearAdmin();

        // Perro + reserva sobre los que se sube el archivo.
        $cliente = User::factory()->create();
        $perro = Perro::factory()->create(['id_usuario' => $cliente->id]);
        $servicio = Servicio::factory()->create();
        $reserva = Reserva::factory()->create([
            'id_usuario'  => $cliente->id,
            'id_perro'    => $perro->id,
            'id_servicio' => $servicio->id,
        ]);

        $response = $this->post('/multimedia', [
            'archivo'     => UploadedFile::fake()->image('foto.jpg'),
            'id_reserva'  => $reserva->id,
            'id_perro'    => $perro->id,
            'descripcion' => 'Foto de prueba',
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('multimedia', [
            'id_perro' => $perro->id,
            'tipo'     => 'foto',
        ]);
        $this->assertDatabaseHas('multimedia', ['subido_por' => $admin->id]);

        $registro = Multimedia::first();
        Storage::disk('public')->assertExists($registro->ruta_archivo);
    }

    public function test_cliente_no_puede_subir_multimedia(): void
    {
        $this->crearCliente();

        $this->post('/multimedia', [
            'archivo'    => UploadedFile::fake()->image('foto.jpg'),
            'id_reserva' => 1,
            'id_perro'   => 1,
        ])->assertForbidden();
    }

    public function test_admin_puede_eliminar_multimedia(): void
    {
        $this->crearAdmin();

        $multimedia = Multimedia::factory()->create();

        $this->delete('/multimedia/'.$multimedia->id)->assertStatus(302);

        $this->assertDatabaseMissing('multimedia', ['id' => $multimedia->id]);
    }

    public function test_cliente_no_puede_eliminar_multimedia(): void
    {
        $this->crearCliente();

        $multimedia = Multimedia::factory()->create();

        $this->delete('/multimedia/'.$multimedia->id)->assertForbidden();
    }
}
