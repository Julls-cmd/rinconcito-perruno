<?php

namespace Database\Seeders;

use App\Models\Servicio;
use Illuminate\Database\Seeder;

class ServicioSeeder extends Seeder
{
    public function run(): void
    {
        $servicios = [
            [
                'nombre' => 'Guardería Básica',
                'descripcion' => 'Estancia diaria con cuidado básico, alimentación y paseos supervisados en 2.000 m².',
                'precio_base' => 18.00,
                'incluye_recogida' => false,
                'incluye_estetica' => false,
                'activo' => true,
            ],
            [
                'nombre' => 'Guardería con Recogida',
                'descripcion' => 'Estancia diaria con recogida y entrega a domicilio incluida en un radio de 50 km desde Trebujena.',
                'precio_base' => 22.00,
                'incluye_recogida' => true,
                'incluye_estetica' => false,
                'activo' => true,
            ],
            [
                'nombre' => 'Guardería con Estética',
                'descripcion' => 'Guardería más servicio de peluquería canina profesional en el mismo establecimiento.',
                'precio_base' => 35.00,
                'incluye_recogida' => false,
                'incluye_estetica' => true,
                'activo' => true,
            ],
            [
                'nombre' => 'Pack Completo',
                'descripcion' => 'Recogida, guardería, estética y entrega. El servicio todo en uno al mejor precio.',
                'precio_base' => 42.00,
                'incluye_recogida' => true,
                'incluye_estetica' => true,
                'activo' => true,
            ],
        ];

        foreach ($servicios as $servicio) {
            Servicio::create($servicio);
        }
    }
}
