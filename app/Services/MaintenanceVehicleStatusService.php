<?php

namespace App\Services;

use App\Models\Maintenance;

class MaintenanceVehicleStatusService
{
    public function syncVehicleStatus(Maintenance $maintenance): void
    {
        $maintenance->loadMissing('vehicle');

        $vehicle = $maintenance->vehicle;

        if (!$vehicle) {
            return;
        }

        $hasActiveMaintenances = Maintenance::where('vehicle_id', $vehicle->id)
            ->whereIn('status', ['pending', 'assigned', 'in_progress'])
            ->exists();

        if ($hasActiveMaintenances) {
            if ($vehicle->status !== 'damaged' && $vehicle->status !== 'maintenance') {
                $vehicle->update(['status' => 'maintenance']);
            }

            return;
        }

        if ($vehicle->status === 'maintenance') {
            $vehicle->update(['status' => 'available']);
        }
    }
}
