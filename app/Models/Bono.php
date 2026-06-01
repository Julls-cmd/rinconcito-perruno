<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Bono extends Model
{
    use HasFactory;

    protected $table = 'bonos';

    protected $fillable = [
        'nombre', 'descripcion', 'descuento_porcentaje',
        'descuento_fijo', 'condicion', 'usos_maximos',
        'usos_restantes', 'fecha_expiracion', 'id_usuario', 'activo',
    ];

    protected $casts = [
        'descuento_porcentaje' => 'decimal:2',
        'descuento_fijo' => 'decimal:2',
        'fecha_expiracion' => 'date',
        'activo' => 'boolean',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }

    public function pagos()
    {
        return $this->hasMany(Pago::class, 'id_bono');
    }

    public function estaVigente(): bool
    {
        return $this->activo &&
               $this->usos_restantes > 0 &&
               ($this->fecha_expiracion === null || $this->fecha_expiracion->isFuture());
    }
}