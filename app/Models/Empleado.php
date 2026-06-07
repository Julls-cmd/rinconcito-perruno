<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    use HasFactory;

    protected $table = 'empleados';

    protected $fillable = [
        'nombre', 'apellidos', 'rol', 'turno',
        'telefono', 'email', 'fecha_alta', 'activo',
    ];

    protected $casts = [
        'fecha_alta' => 'date',
        'activo' => 'boolean',
    ];

    public function reservas()
    {
        return $this->belongsToMany(Reserva::class, 'reservas_empleados', 'id_empleado', 'id_reserva')
            ->withPivot('rol_en_reserva')
            ->withTimestamps();
    }
}
