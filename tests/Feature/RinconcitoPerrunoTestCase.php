<?php

namespace Tests\Feature;

use App\Models\Perro;
use App\Models\Servicio;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Stripe\ApiRequestor;
use Tests\Support\FakeStripeHttpClient;
use Tests\TestCase;

/**
 * Clase base para los tests de dominio de Rinconcito Perruno.
 *
 * - Recrea la BD entre tests (RefreshDatabase).
 * - Limpia la caché de permisos de Spatie y crea los roles base.
 * - Evita que Vite intente resolver el manifest al renderizar vistas.
 * - Sustituye el cliente HTTP de Stripe por un fake (sin red).
 */
abstract class RinconcitoPerrunoTestCase extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Las vistas usan @vite(); sin manifest compilado esto lanzaría excepción.
        $this->withoutVite();

        // Ninguna llamada a Stripe debe salir a la red durante los tests.
        ApiRequestor::setHttpClient(new FakeStripeHttpClient());

        // Limpiar caché de permisos de Spatie entre tests.
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Crear roles base.
        Role::create(['name' => 'admin', 'guard_name' => 'web']);
        Role::create(['name' => 'cliente', 'guard_name' => 'web']);
    }

    /**
     * Crea un cliente autenticado con un perro propio y un servicio activo listos.
     *
     * @return array{cliente: User, perro: Perro, servicio: Servicio}
     */
    protected function crearCliente(): array
    {
        $cliente = User::factory()->create();
        $cliente->assignRole('cliente');

        $perro = Perro::factory()->create(['id_usuario' => $cliente->id]);
        $servicio = Servicio::factory()->create();

        $this->actingAs($cliente);

        return compact('cliente', 'perro', 'servicio');
    }

    /**
     * Crea un administrador autenticado.
     *
     * @return array{admin: User}
     */
    protected function crearAdmin(): array
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $this->actingAs($admin);

        return compact('admin');
    }
}
