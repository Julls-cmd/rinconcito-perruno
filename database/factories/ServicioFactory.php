<?php

namespace Database\Factories;

use App\Models\Servicio;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Servicio>
 */
class ServicioFactory extends Factory
{
    protected $model = Servicio::class;

    public function definition(): array
    {
        return [
            'nombre' => fake()->randomElement(['Guardería', 'Hospedaje', 'Paseo', 'Estética canina']),
            'descripcion' => fake()->sentence(),
            'precio_base' => fake()->randomFloat(2, 15, 50),
            'incluye_recogida' => fake()->boolean(),
            'incluye_estetica' => fake()->boolean(),
            'activo' => true,
        ];
    }
}
