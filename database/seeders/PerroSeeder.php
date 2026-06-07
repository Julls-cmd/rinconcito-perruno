<?php

namespace Database\Seeders;

use App\Models\Perro;
use App\Models\User;
use Illuminate\Database\Seeder;

class PerroSeeder extends Seeder
{
    public function run(): void
    {
        $cliente = User::where('email', 'cliente@ejemplo.es')->first();

        $perros = [
            [
                'nombre' => 'Rocky',
                'raza' => 'Labrador',
                'edad' => 3,
                'peso' => 28.50,
                'temperamento' => 'sociable',
                'vacunas' => true,
                'microchip' => '985112345678901',
                'observaciones' => 'Le encanta jugar con otros perros.',
                'id_usuario' => $cliente->id,
            ],
            [
                'nombre' => 'Luna',
                'raza' => 'Beagle',
                'edad' => 2,
                'peso' => 12.30,
                'temperamento' => 'activo',
                'vacunas' => true,
                'microchip' => '985112345678902',
                'observaciones' => 'Muy activa, necesita mucho ejercicio.',
                'id_usuario' => $cliente->id,
            ],
        ];

        foreach ($perros as $perro) {
            Perro::create($perro);
        }
    }
}
