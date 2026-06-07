<?php

namespace Database\Factories;

use App\Models\Perro;
use App\Models\Reserva;
use App\Models\Servicio;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Reserva>
 */
class ReservaFactory extends Factory
{
    protected $model = Reserva::class;

    public function definition(): array
    {
        return [
            'fecha_entrada' => now()->addDays(7)->toDateString(),
            'fecha_salida' => now()->addDays(9)->toDateString(),
            // ENUM estado: pendiente, confirmada, en_curso, finalizada, cancelada
            'estado' => 'pendiente',
            'direccion_recogida' => null,
            'notas' => null,
            'id_usuario' => User::factory(),
            'id_perro' => Perro::factory(),
            'id_servicio' => Servicio::factory(),
        ];
    }

    public function confirmada(): static
    {
        return $this->state(fn () => ['estado' => 'confirmada']);
    }
}
