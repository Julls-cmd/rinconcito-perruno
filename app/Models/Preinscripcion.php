<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Preinscripcion extends Model
{
    use HasFactory;

    protected $table = 'preinscripciones';

    protected $fillable = [
        'nombre_perro', 'raza', 'edad', 'peso',
        'vacunas', 'temperamento', 'observaciones',
        'estado', 'id_usuario', 'nombre_contacto',
        'email_contacto', 'telefono_contacto',
    ];

    protected $casts = [
        'vacunas' => 'boolean',
        'edad' => 'integer',
        'peso' => 'decimal:2',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }
}