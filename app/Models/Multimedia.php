<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Multimedia extends Model
{
    use HasFactory;

    protected $table = 'multimedia';

    protected $fillable = [
        'ruta_archivo', 'tipo', 'descripcion',
        'id_reserva', 'id_perro', 'subido_por',
    ];

    public function reserva()
    {
        return $this->belongsTo(Reserva::class, 'id_reserva');
    }

    public function perro()
    {
        return $this->belongsTo(Perro::class, 'id_perro');
    }

    public function subidoPor()
    {
        return $this->belongsTo(User::class, 'subido_por');
    }
}