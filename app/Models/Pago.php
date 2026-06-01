<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pago extends Model
{
    use HasFactory;

    protected $table = 'pagos';

    protected $fillable = [
        'importe', 'metodo', 'estado',
        'stripe_payment_id', 'fecha_pago',
        'id_reserva', 'id_bono',
    ];

    protected $casts = [
        'importe' => 'decimal:2',
        'fecha_pago' => 'datetime',
    ];

    public function reserva()
    {
        return $this->belongsTo(Reserva::class, 'id_reserva');
    }

    public function bono()
    {
        return $this->belongsTo(Bono::class, 'id_bono');
    }
}