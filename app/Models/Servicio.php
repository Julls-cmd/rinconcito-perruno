<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Servicio extends Model
{
    use HasFactory;

    protected $table = 'servicios';

    protected $fillable = [
        'nombre', 'descripcion', 'precio_base',
        'incluye_recogida', 'incluye_estetica', 'activo',
    ];

    protected $casts = [
        'precio_base' => 'decimal:2',
        'incluye_recogida' => 'boolean',
        'incluye_estetica' => 'boolean',
        'activo' => 'boolean',
    ];

    public function reservas()
    {
        return $this->hasMany(Reserva::class, 'id_servicio');
    }
}