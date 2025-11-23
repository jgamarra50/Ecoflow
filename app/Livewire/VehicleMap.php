<?php

namespace App\Livewire;

use App\Models\Station;
use App\Models\Vehicle;
use Livewire\Component;

class VehicleMap extends Component
{
    public function getStationsProperty()
    {
        return Station::all();
    }

    public function getAvailableVehiclesProperty()
    {
        return Vehicle::with('station')
            ->where('status', 'available')
            ->get();
    }

    public function render()
    {
        return view('livewire.vehicle-map', [
            'stations' => $this->stations,
            'vehicles' => $this->availableVehicles,
        ]);
    }
}
