<?php

namespace Database\Factories;

use App\Models\Multimedia;
use App\Models\Perro;
use App\Models\Reserva;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Multimedia>
 */
class MultimediaFactory extends Factory
{
    protected $model = Multimedia::class;

    public function definition(): array
    {
        return [
            'ruta_archivo' => 'multimedia/test.jpg',
            // ENUM tipo: foto, video
            'tipo' => 'foto',
            'descripcion' => fake()->optional()->sentence(),
            // En el esquema id_reserva es obligatorio; se autogenera si no se pasa.
            'id_reserva' => Reserva::factory(),
            'id_perro' => Perro::factory(),
            'subido_por' => null,
        ];
    }
}
