<?php

namespace App\Livewire;

use App\Models\Vehicle;
use Livewire\Component;

class AdminTelemetry extends Component
{
    public $selectedVehicleId;
    public $batteryHistory = [];
    public $lowBatteryAlerts = [];

    public function mount()
    {
        $this->checkLowBatteryAlerts();
    }

    public function selectVehicle($vehicleId)
    {
        $this->selectedVehicleId = $vehicleId;
        $this->loadBatteryHistory();
    }

    public function loadBatteryHistory()
    {
        if (!$this->selectedVehicleId) return;

        $vehicle = Vehicle::find($this->selectedVehicleId);
        if (!$vehicle) return;

        // Get last 20 telemetry records
        $telemetries = $vehicle->telemetries()
            ->latest()
            ->limit(20)
            ->get()
            ->reverse()
            ->values();

        $this->batteryHistory = [
            'labels' => $telemetries->map(fn($t) => $t->created_at->format('H:i'))->toArray(),
            'data' => $telemetries->pluck('battery_level')->toArray(),
        ];
    }

    public function checkLowBatteryAlerts()
    {
        $this->lowBatteryAlerts = Vehicle::with('latestTelemetry')
            ->whereHas('latestTelemetry', function ($query) {
                $query->where('battery_level', '<', 30);
            })
            ->get()
            ->map(function ($vehicle) {
                return [
                    'id' => $vehicle->id,
                    'name' => $vehicle->brand . ' ' . $vehicle->model,
                    'plate' => $vehicle->plate,
                    'battery' => $vehicle->latestTelemetry->battery_level,
                ];
            })
            ->toArray();
    }

    public function getVehiclesProperty()
    {
        return Vehicle::with(['latestTelemetry', 'station'])
            ->orderBy('status')
            ->get();
    }

    public function render()
    {
        // Refresh alerts on each render
        $this->checkLowBatteryAlerts();

        return view('livewire.admin-telemetry', [
            'vehicles' => $this->vehicles,
        ])->layout('components.app-layout');
    }
}
