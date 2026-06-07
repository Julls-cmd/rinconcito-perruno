<?php

namespace Database\Factories;

use App\Models\Bono;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Bono>
 */
class BonoFactory extends Factory
{
    protected $model = Bono::class;

    public function definition(): array
    {
        return [
            'nombre' => fake()->words(2, true),
            'descripcion' => fake()->sentence(),
            'descuento_porcentaje' => 10.00,
            'descuento_fijo' => null,
            'condicion' => null,
            'usos_maximos' => 5,
            'usos_restantes' => fake()->numberBetween(1, 5),
            'fecha_expiracion' => now()->addDays(30)->toDateString(),
            'id_usuario' => User::factory(),
            'activo' => true,
        ];
    }
}
