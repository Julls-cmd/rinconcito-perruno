<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perro extends Model
{
    use HasFactory;

    protected $table = 'perros';

    protected $fillable = [
        'nombre',
        'raza',
        'edad',
        'peso',
        'temperamento',
        'vacunas',
        'microchip',
        'observaciones',
        'foto',
        'id_usuario',
    ];

    protected $casts = [
        'vacunas' => 'boolean',
        'edad' => 'integer',
        'peso' => 'decimal:2',
    ];

    // Relación con Usuario (pertenece a un usuario)
    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }

    // Relación con Reservas (tiene muchas reservas)
    public function reservas()
    {
        return $this->hasMany(Reserva::class, 'id_perro');
    }

    // Relación con Multimedia (tiene muchos archivos)
    public function multimedia()
    {
        return $this->hasMany(Multimedia::class, 'id_perro');
    }
}
