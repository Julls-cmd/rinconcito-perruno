<?php

namespace Database\Factories;

use App\Models\Perro;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Perro>
 */
class PerroFactory extends Factory
{
    protected $model = Perro::class;

    public function definition(): array
    {
        return [
            'nombre' => fake()->firstName(),
            'raza' => fake()->randomElement(['Labrador', 'Bulldog', 'Beagle', 'Pastor Alemán', 'Mestizo']),
            'edad' => fake()->numberBetween(1, 15),
            'peso' => fake()->randomFloat(2, 2, 45),
            // ENUM temperamento: tranquilo, activo, agresivo, sociable
            'temperamento' => fake()->randomElement(['tranquilo', 'activo', 'agresivo', 'sociable']),
            'vacunas' => true,
            'microchip' => fake()->unique()->numerify('###########'),
            'observaciones' => fake()->optional()->sentence(),
            'foto' => null,
            // El id_usuario debe llegar vía state o ->for(User) / ->for(..., 'usuario')
            'id_usuario' => User::factory(),
        ];
    }
}
