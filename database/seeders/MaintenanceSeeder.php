<?php

namespace Database\Seeders;

use App\Models\Maintenance;
use App\Models\Vehicle;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class MaintenanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('email', 'admin@test.com')->first();
        $technician1 = User::where('email', 'tecnico1@test.com')->first();
        
        $vehicles = Vehicle::all();

        if ($vehicles->isEmpty() || !$admin) {
            return;
        }

        $maintenances = [
            // Pendientes
            [
                'vehicle_id' => $vehicles[0]->id,
                'created_by' => $admin->id,
                'title' => 'Revisión de batería',
                'description' => 'Revisión del estado general de la batería y sistemas eléctricos',
                'status' => 'pending',
                'priority' => 'high',
                'cost' => null,
            ],
            [
                'vehicle_id' => $vehicles[1]->id,
                'created_by' => $admin->id,
                'title' => 'Cambio de frenos',
                'description' => 'Reemplazo de pastillas de freno desgastadas',
                'status' => 'pending',
                'priority' => 'urgent',
                'cost' => null,
            ],

            // Asignados
            [
                'vehicle_id' => $vehicles[2]->id,
                'technician_id' => $technician1?->id,
                'created_by' => $admin->id,
                'title' => 'Mantenimiento preventivo',
                'description' => 'Mantenimiento preventivo general del vehículo',
                'status' => 'assigned',
                'priority' => 'medium',
                'cost' => 50000,
                'assigned_at' => Carbon::now()->subDays(1),
            ],

            // En progreso
            [
                'vehicle_id' => $vehicles[3]->id,
                'technician_id' => $technician1?->id,
                'created_by' => $admin->id,
                'title' => 'Reparación de motor',
                'description' => 'Reparación del motor eléctrico por falla detectada',
                'status' => 'in_progress',
                'priority' => 'urgent',
                'cost' => 150000,
                'assigned_at' => Carbon::now()->subDays(2),
                'started_at' => Carbon::now()->subHours(12),
            ],

            // Completados
            [
                'vehicle_id' => $vehicles[0]->id,
                'technician_id' => $technician1?->id,
                'created_by' => $admin->id,
                'title' => 'Cambio de neumáticos',
                'description' => 'Reemplazo de neumáticos desgastados',
                'status' => 'completed',
                'priority' => 'medium',
                'cost' => 80000,
                'assigned_at' => Carbon::now()->subDays(10),
                'started_at' => Carbon::now()->subDays(9),
                'completed_at' => Carbon::now()->subDays(8),
            ],
            [
                'vehicle_id' => $vehicles[1]->id,
                'technician_id' => $technician1?->id,
                'created_by' => $admin->id,
                'title' => 'Ajuste de luces',
                'description' => 'Ajuste y calibración del sistema de iluminación',
                'status' => 'completed',
                'priority' => 'low',
                'cost' => 25000,
                'assigned_at' => Carbon::now()->subDays(5),
                'started_at' => Carbon::now()->subDays(4),
                'completed_at' => Carbon::now()->subDays(3),
            ],
        ];

        foreach ($maintenances as $maintenance) {
            Maintenance::create($maintenance);
        }
    }
}
