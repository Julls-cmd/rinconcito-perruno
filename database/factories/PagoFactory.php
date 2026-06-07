<?php

namespace Database\Factories;

use App\Models\Pago;
use App\Models\Reserva;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Pago>
 */
class PagoFactory extends Factory
{
    protected $model = Pago::class;

    public function definition(): array
    {
        return [
            'importe'           => 36.00,
            // ENUM metodo: tarjeta, efectivo, transferencia
            'metodo'            => 'tarjeta',
            // ENUM estado: pendiente, completado, fallido, reembolsado
            'estado'            => 'completado',
            'stripe_payment_id' => 'pi_'.fake()->unique()->bothify('############'),
            'fecha_pago'        => now(),
            'id_reserva'        => Reserva::factory(),
            'id_bono'           => null,
        ];
    }
}
