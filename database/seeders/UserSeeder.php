<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin user
        $admin = User::create([
            'name' => 'Administrador',
            'email' => 'admin@test.com',
            'password' => Hash::make('password'),
            'phone' => '3001234567',
            'address' => 'Bucaramanga, Santander',
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);
        $admin->assignRole('admin');

        // Cliente users
        $cliente1 = User::create([
            'name' => 'Juan Pérez',
            'email' => 'cliente1@test.com',
            'password' => Hash::make('password'),
            'phone' => '3101234567',
            'address' => 'Calle 45 #23-10, Bucaramanga',
            'role' => 'cliente',
            'email_verified_at' => now(),
        ]);
        $cliente1->assignRole('cliente');

        $cliente2 = User::create([
            'name' => 'María González',
            'email' => 'cliente2@test.com',
            'password' => Hash::make('password'),
            'phone' => '3201234567',
            'address' => 'Carrera 27 #52-12, Floridablanca',
            'role' => 'cliente',
            'email_verified_at' => now(),
        ]);
        $cliente2->assignRole('cliente');

        // Operadores de prueba (CON ESTACIONES ASIGNADAS)
        $operador1 = User::create([
            'name' => 'Carlos Rodríguez',
            'email' => 'operador1@test.com',
            'password' => Hash::make('password'),
            'phone' => '3301234567',
            'address' => 'Avenida Quebrada Seca, Bucaramanga',
            'role' => 'operador',
            'station_id' => 1, // Estación Cabecera
            'email_verified_at' => now(),
        ]);
        $operador1->assignRole('operador');

        $operador2 = User::create([
            'name' => 'Ana López',
            'email' => 'operador2@test.com',
            'password' => Hash::make('password'),
            'phone' => '3401234567',
            'address' => 'Calle 56 #34-21, Piedecuesta',
            'role' => 'operador',
            'station_id' => 2, // Estación Cañaveral
            'email_verified_at' => now(),
        ]);
        $operador2->assignRole('operador');

        // Tecnico users
        $tecnico1 = User::create([
            'name' => 'Pedro Martínez',
            'email' => 'tecnico1@test.com',
            'password' => Hash::make('password'),
            'phone' => '3501234567',
            'address' => 'Carrera 33 #48-15, Bucaramanga',
            'role' => 'tecnico',
            'email_verified_at' => now(),
        ]);
        $tecnico1->assignRole('tecnico');

        $tecnico2 = User::create([
            'name' => 'Laura Sánchez',
            'email' => 'tecnico2@test.com',
            'password' => Hash::make('password'),
            'phone' => '3601234567',
            'address' => 'Calle 72 #28-45, Floridablanca',
            'role' => 'tecnico',
            'email_verified_at' => now(),
        ]);
        $tecnico2->assignRole('tecnico');
    }
}
