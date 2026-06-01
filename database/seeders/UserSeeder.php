<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Crear roles
        $adminRole = Role::create(['name' => 'admin']);
        $clienteRole = Role::create(['name' => 'cliente']);

        // Crear usuario administrador
        $admin = User::create([
            'name' => 'Administrador',
            'email' => 'admin@rinconcitoperruno.es',
            'password' => Hash::make('password'),
            'telefono' => '666111222',
            'ciudad' => 'Trebujena',
            'email_verified_at' => now(),
        ]);
        $admin->assignRole('admin');

        // Crear usuario cliente de prueba
        $cliente = User::create([
            'name' => 'Juan García',
            'email' => 'cliente@ejemplo.es',
            'password' => Hash::make('password'),
            'telefono' => '666333444',
            'ciudad' => 'Jerez de la Frontera',
            'email_verified_at' => now(),
        ]);
        $cliente->assignRole('cliente');
    }
}