<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    use HasFactory;

    protected $table = 'reservas';

    protected $fillable = [
        'fecha_entrada', 'fecha_salida', 'estado',
        'direccion_recogida', 'notas',
        'id_usuario', 'id_perro', 'id_servicio',
    ];

    protected $casts = [
        'fecha_entrada' => 'date',
        'fecha_salida' => 'date',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }

    public function perro()
    {
        return $this->belongsTo(Perro::class, 'id_perro');
    }

    public function servicio()
    {
        return $this->belongsTo(Servicio::class, 'id_servicio');
    }

    public function pago()
    {
        return $this->hasOne(Pago::class, 'id_reserva');
    }

    public function multimedia()
    {
        return $this->hasMany(Multimedia::class, 'id_reserva');
    }

    public function empleados()
    {
        return $this->belongsToMany(Empleado::class, 'reservas_empleados', 'id_reserva', 'id_empleado')
            ->withPivot('rol_en_reserva')
            ->withTimestamps();
    }
}
