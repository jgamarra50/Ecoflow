<?php

namespace App\Livewire;

use App\Models\Station;
use App\Models\Vehicle;
use Livewire\Component;

class PublicMap extends Component
{
    public $showStations = true;
    public $vehicleTypeFilter = 'all';
    public $autoRefresh = true;

    protected $listeners = ['refreshMap' => '$refresh'];

    public function getStationsProperty()
    {
        return Station::all();
    }

    public function getVehiclesProperty()
    {
        $query = Vehicle::with(['station', 'latestTelemetry'])
            ->where('status', 'available');

        if ($this->vehicleTypeFilter !== 'all') {
            $query->where('type', $this->vehicleTypeFilter);
        }

        return $query->get();
    }

    public function getStatsProperty()
    {
        return [
            'total' => Vehicle::where('status', 'available')->count(),
            'scooter' => Vehicle::where('status', 'available')->where('type', 'scooter')->count(),
            'skateboard' => Vehicle::where('status', 'available')->where('type', 'skateboard')->count(),
            'bicycle' => Vehicle::where('status', 'available')->where('type', 'bicycle')->count(),
        ];
    }

    public function toggleStations()
    {
        $this->showStations = !$this->showStations;
    }

    public function setVehicleFilter($type)
    {
        $this->vehicleTypeFilter = $type;
    }

    public function render()
    {
        return view('livewire.public-map', [
            'stations' => $this->stations,
            'vehicles' => $this->vehicles,
            'stats' => $this->stats,
        ])->layout('components.guest');
    }
}
