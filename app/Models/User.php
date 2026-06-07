<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Billable, HasFactory, HasRoles, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'telefono',
        'direccion',
        'ciudad',
        'codigo_postal',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function perros()
    {
        return $this->hasMany(Perro::class, 'id_usuario');
    }

    public function reservas()
    {
        return $this->hasMany(Reserva::class, 'id_usuario');
    }

    public function bonos()
    {
        return $this->hasMany(Bono::class, 'id_usuario');
    }

    public function preinscripciones()
    {
        return $this->hasMany(Preinscripcion::class, 'id_usuario');
    }
}
