<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Define all permissions
        $permissions = [
            // Admin permissions
            'gestionar-usuarios',
            'gestionar-vehiculos',
            'ver-reportes',
            'gestionar-stock',
            
            // Cliente permissions
            'reservar-vehiculo',
            'consultar-reservas',
            'cancelar-reserva',
            
            // Operador permissions
            'reportar-problemas',
            'registrar-entrega',
            'registrar-devolucion',
            
            // Tecnico permissions
            'ver-mantenimientos',
            'actualizar-mantenimiento',
            'actualizar-estado-vehiculo',
        ];

        // Create all permissions
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles and assign permissions
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->givePermissionTo([
            'gestionar-usuarios',
            'gestionar-vehiculos',
            'ver-reportes',
            'gestionar-stock',
        ]);

        $clienteRole = Role::firstOrCreate(['name' => 'cliente']);
        $clienteRole->givePermissionTo([
            'reservar-vehiculo',
            'consultar-reservas',
            'cancelar-reserva',
        ]);

        $operadorRole = Role::firstOrCreate(['name' => 'operador']);
        $operadorRole->givePermissionTo([
            'reportar-problemas',
            'registrar-entrega',
            'registrar-devolucion',
        ]);

        $tecnicoRole = Role::firstOrCreate(['name' => 'tecnico']);
        $tecnicoRole->givePermissionTo([
            'ver-mantenimientos',
            'actualizar-mantenimiento',
            'actualizar-estado-vehiculo',
        ]);
    }
}
