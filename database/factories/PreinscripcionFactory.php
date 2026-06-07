<?php

namespace Database\Factories;

use App\Models\Preinscripcion;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Preinscripcion>
 */
class PreinscripcionFactory extends Factory
{
    protected $model = Preinscripcion::class;

    public function definition(): array
    {
        return [
            'nombre_perro' => fake()->firstName(),
            'raza' => fake()->randomElement(['Labrador', 'Bulldog', 'Beagle', 'Mestizo']),
            'edad' => fake()->numberBetween(1, 15),
            'peso' => fake()->randomFloat(2, 2, 45),
            'vacunas' => true,
            // ENUM temperamento: tranquilo, activo, agresivo, sociable
            'temperamento' => fake()->randomElement(['tranquilo', 'activo', 'agresivo', 'sociable']),
            'observaciones' => fake()->optional()->sentence(),
            // ENUM estado: pendiente, aprobada, rechazada
            'estado' => 'pendiente',
            'id_usuario' => null,
            'nombre_contacto' => fake()->name(),
            'email_contacto' => fake()->unique()->safeEmail(),
            'telefono_contacto' => fake()->numerify('6########'),
        ];
    }
}
