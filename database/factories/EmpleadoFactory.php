<?php

namespace Database\Factories;

use App\Models\Empleado;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Empleado>
 */
class EmpleadoFactory extends Factory
{
    protected $model = Empleado::class;

    public function definition(): array
    {
        return [
            'nombre' => fake()->firstName(),
            'apellidos' => fake()->lastName().' '.fake()->lastName(),
            // ENUM rol: cuidador, administrativo, esteticista, conductor
            'rol' => fake()->randomElement(['cuidador', 'administrativo', 'esteticista', 'conductor']),
            // ENUM turno: mañana, tarde, noche
            'turno' => fake()->randomElement(['mañana', 'tarde', 'noche']),
            'telefono' => fake()->numerify('6########'),
            'email' => fake()->unique()->safeEmail(),
            'fecha_alta' => now()->subDays(fake()->numberBetween(1, 365))->toDateString(),
            'activo' => true,
        ];
    }
}
