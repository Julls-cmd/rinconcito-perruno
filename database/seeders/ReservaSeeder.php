<?php

namespace Database\Seeders;

use App\Models\Perro;
use App\Models\Servicio;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ReservaSeeder extends Seeder
{
    public function run(): void
    {
        $cliente = User::where('email', 'cliente@ejemplo.es')->first();
        $rocky = Perro::where('id_usuario', $cliente->id)->where('nombre', 'Rocky')->first();
        $luna = Perro::where('id_usuario', $cliente->id)->where('nombre', 'Luna')->first();
        $servicios = Servicio::all()->keyBy('nombre');

        $reservas = [
            [
                'perro' => $rocky,
                'servicio' => $servicios['Guardería Básica'],
                'fecha_entrada' => Carbon::now()->subDays(45),
                'fecha_salida' => Carbon::now()->subDays(43),
                'estado' => 'finalizada',
            ],
            [
                'perro' => $luna,
                'servicio' => $servicios['Pack Completo'],
                'fecha_entrada' => Carbon::now()->subDays(30),
                'fecha_salida' => Carbon::now()->subDays(28),
                'estado' => 'finalizada',
            ],
            [
                'perro' => $rocky,
                'servicio' => $servicios['Guardería con Estética'],
                'fecha_entrada' => Carbon::now()->subDays(15),
                'fecha_salida' => Carbon::now()->subDays(13),
                'estado' => 'finalizada',
            ],
            [
                'perro' => $luna,
                'servicio' => $servicios['Guardería con Recogida'],
                'fecha_entrada' => Carbon::now()->subDays(7),
                'fecha_salida' => Carbon::now()->subDay(),
                'estado' => 'finalizada',
            ],
            [
                'perro' => $rocky,
                'servicio' => $servicios['Pack Completo'],
                'fecha_entrada' => Carbon::now()->subDay(),
                'fecha_salida' => Carbon::now()->addDay(),
                'estado' => 'en_curso',
            ],
            [
                'perro' => $luna,
                'servicio' => $servicios['Guardería Básica'],
                'fecha_entrada' => Carbon::now()->addDays(5),
                'fecha_salida' => Carbon::now()->addDays(8),
                'estado' => 'confirmada',
            ],
            [
                'perro' => $rocky,
                'servicio' => $servicios['Guardería con Recogida'],
                'fecha_entrada' => Carbon::now()->addDays(10),
                'fecha_salida' => Carbon::now()->addDays(12),
                'estado' => 'pendiente',
            ],
            [
                'perro' => $luna,
                'servicio' => $servicios['Guardería con Estética'],
                'fecha_entrada' => Carbon::now()->addDays(15),
                'fecha_salida' => Carbon::now()->addDays(16),
                'estado' => 'pendiente',
            ],
        ];

        $now = Carbon::now();

        foreach ($reservas as $index => $datos) {
            $reservaId = DB::table('reservas')->insertGetId([
                'fecha_entrada' => $datos['fecha_entrada']->toDateString(),
                'fecha_salida' => $datos['fecha_salida']->toDateString(),
                'estado' => $datos['estado'],
                'id_usuario' => $cliente->id,
                'id_perro' => $datos['perro']->id,
                'id_servicio' => $datos['servicio']->id,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            if ($index < 5) {
                $noches = $datos['fecha_entrada']->diffInDays($datos['fecha_salida']);

                DB::table('pagos')->insert([
                    'importe' => $noches * $datos['servicio']->precio_base,
                    'metodo' => 'tarjeta',
                    'estado' => 'completado',
                    'stripe_payment_id' => 'pi_test_'.Str::random(24),
                    'fecha_pago' => $datos['fecha_salida']->copy()->addHour(),
                    'id_reserva' => $reservaId,
                    'id_bono' => null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
    }
}
