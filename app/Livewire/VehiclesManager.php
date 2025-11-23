<?php

namespace App\Livewire;

use App\Models\Vehicle;
use App\Models\Station;
use Livewire\Component;
use Livewire\WithPagination;

class VehiclesManager extends Component
{
    use WithPagination;

    // Filtros
    public $search = '';
    public $typeFilter = 'all';
    public $statusFilter = 'all';
    public $stationFilter = 'all';

    // Modal
    public $showModal = false;
    public $editMode = false;
    public $vehicleId;

    // Formulario
    public $type = 'scooter';
    public $brand = '';
    public $model = '';
    public $plate = '';
    public $status = 'available';
    public $station_id = '';
    public $current_location_lat = '';
    public $current_location_lng = '';

    // Location modal
    public $showLocationModal = false;
    public $selectedVehicle = null;

    protected $listeners = ['deleteConfirmed' => 'delete'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingTypeFilter()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingStationFilter()
    {
        $this->resetPage();
    }

    public function getVehiclesProperty()
    {
        $query = Vehicle::with('station');

        // Búsqueda
        if ($this->search) {
            $query->where(function($q) {
                $q->where('brand', 'like', '%' . $this->search . '%')
                  ->orWhere('model', 'like', '%' . $this->search . '%')
                  ->orWhere('plate', 'like', '%' . $this->search . '%');
            });
        }

        // Filtros
        if ($this->typeFilter !== 'all') {
            $query->where('type', $this->typeFilter);
        }

        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        if ($this->stationFilter !== 'all') {
            $query->where('station_id', $this->stationFilter);
        }

        return $query->latest()->paginate(10);
    }

    public function createVehicle()
    {
        $this->resetForm();
        $this->editMode = false;
        $this->showModal = true;
    }

    public function editVehicle($id)
    {
        $vehicle = Vehicle::findOrFail($id);
        
        $this->vehicleId = $vehicle->id;
        $this->type = $vehicle->type;
        $this->brand = $vehicle->brand;
        $this->model = $vehicle->model;
        $this->plate = $vehicle->plate;
        $this->status = $vehicle->status;
        $this->station_id = $vehicle->station_id;
        $this->current_location_lat = $vehicle->current_location_lat;
        $this->current_location_lng = $vehicle->current_location_lng;
        
        $this->editMode = true;
        $this->showModal = true;
    }

    public function save()
    {
        $rules = [
            'type' => 'required|in:scooter,bicycle,skateboard',
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'plate' => 'required|string|max:255|unique:vehicles,plate,' . $this->vehicleId,
            'status' => 'required|in:available,reserved,maintenance,damaged',
            'station_id' => 'required|exists:stations,id',
            'current_location_lat' => 'nullable|numeric|between:-90,90',
            'current_location_lng' => 'nullable|numeric|between:-180,180',
        ];

        $this->validate($rules);

        $data = [
            'type' => $this->type,
            'brand' => $this->brand,
            'model' => $this->model,
            'plate' => $this->plate,
            'status' => $this->status,
            'station_id' => $this->station_id,
            'current_location_lat' => $this->current_location_lat ?: null,
            'current_location_lng' => $this->current_location_lng ?: null,
        ];

        if ($this->editMode) {
            Vehicle::find($this->vehicleId)->update($data);
            $message = 'Vehículo actualizado exitosamente';
        } else {
            Vehicle::create($data);
            $message = 'Vehículo creado exitosamente';
        }

        $this->closeModal();
        $this->dispatch('vehicle-saved', message: $message);
    }

    public function changeStatus($id, $newStatus)
    {
        $vehicle = Vehicle::findOrFail($id);
        $vehicle->update(['status' => $newStatus]);
        
        $this->dispatch('status-changed', message: 'Estado actualizado a: ' . $this->getStatusLabel($newStatus));
    }

    public function viewLocation($id)
    {
        $this->selectedVehicle = Vehicle::with('station')->findOrFail($id);
        $this->showLocationModal = true;
    }

    public function closeLocationModal()
    {
        $this->showLocationModal = false;
        $this->selectedVehicle = null;
    }

    public function confirmDelete($id)
    {
        $this->dispatch('confirm-delete', vehicleId: $id);
    }

    public function delete($id)
    {
        Vehicle::findOrFail($id)->delete();
        $this->dispatch('vehicle-deleted', message: 'Vehículo eliminado exitosamente');
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
        $this->resetValidation();
    }

    public function resetForm()
    {
        $this->vehicleId = null;
        $this->type = 'scooter';
        $this->brand = '';
        $this->model = '';
        $this->plate = '';
        $this->status = 'available';
        $this->station_id = '';
        $this->current_location_lat = '';
        $this->current_location_lng = '';
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->typeFilter = 'all';
        $this->statusFilter = 'all';
        $this->stationFilter = 'all';
        $this->resetPage();
    }

    private function getStatusLabel($status)
    {
        return match($status) {
            'available' => 'Disponible',
            'reserved' => 'Reservado',
            'maintenance' => 'Mantenimiento',
            'damaged' => 'Dañado',
            default => ucfirst($status),
        };
    }

    public function render()
    {
        return view('livewire.vehicles-manager', [
            'vehicles' => $this->vehicles,
            'stations' => Station::all(),
        ])->layout('components.admin-layout');
    }
}
