<?php

namespace App\Livewire;

use App\Models\Vehicle;
use App\Models\Maintenance;
use Livewire\Component;
use Livewire\WithPagination;

class TechnicianVehicles extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = 'all';
    
    // Modals
    public $showHistoryModal = false;
    public $showReportModal = false;
    public $selectedVehicleId;
    
    // Formulario de reporte
    public $title = '';
    public $description = '';
    public $priority = 'medium';
    public $newStatus = '';

    protected $listeners = ['maintenanceCreated'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function getVehiclesProperty()
    {
        $query = Vehicle::with(['telemetry', 'station']);

        // Búsqueda
        if ($this->search) {
            $query->where(function($q) {
                $q->where('brand', 'like', '%' . $this->search . '%')
                  ->orWhere('model', 'like', '%' . $this->search . '%')
                  ->orWhere('plate', 'like', '%' . $this->search . '%');
            });
        }

        // Filtro de estado
        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        return $query->paginate(12);
    }

    public function getSelectedVehicleProperty()
    {
        if (!$this->selectedVehicleId) {
            return null;
        }
        return Vehicle::with(['telemetry', 'station'])->find($this->selectedVehicleId);
    }

    public function getMaintenanceHistoryProperty()
    {
        if (!$this->selectedVehicleId) {
            return collect();
        }
        return Maintenance::with(['technician', 'creator'])
            ->where('vehicle_id', $this->selectedVehicleId)
            ->latest()
            ->take(10)
            ->get();
    }

    public function viewHistory($vehicleId)
    {
        \Log::info('viewHistory llamado para vehículo: ' . $vehicleId);
        $this->selectedVehicleId = $vehicleId;
        $this->showHistoryModal = true;
        \Log::info('Modal debería abrirse. showHistoryModal: ' . ($this->showHistoryModal ? 'true' : 'false'));
    }

    public function openReportModal($vehicleId)
    {
        $this->selectedVehicleId = $vehicleId;
        $vehicle = $this->selectedVehicle;
        $this->newStatus = $vehicle->status;
        $this->title = '';
        $this->description = '';
        $this->priority = 'medium';
        $this->showReportModal = true;
    }

    public function createReport()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'priority' => 'required|in:low,medium,high,urgent',
            'newStatus' => 'required|in:available,in_use,maintenance,charging,damaged',
        ]);

        $vehicle = Vehicle::findOrFail($this->selectedVehicleId);

        // Crear mantenimiento
        $maintenance = Maintenance::create([
            'vehicle_id' => $this->selectedVehicleId,
            'technician_id' => auth()->id(),
            'created_by' => auth()->id(),
            'title' => $this->title,
            'description' => $this->description,
            'priority' => $this->priority,
            'status' => 'assigned',
            'assigned_at' => now(),
        ]);

        // Actualizar estado del vehículo si cambió
        if ($vehicle->status !== $this->newStatus) {
            $vehicle->update(['status' => $this->newStatus]);
        }

        $this->showReportModal = false;
        $this->dispatch('maintenance-created', message: 'Reporte de mantenimiento creado exitosamente');
    }

    public function updateVehicleStatus($vehicleId, $status)
    {
        \Log::info('updateVehicleStatus llamado', [
            'vehicle_id' => $vehicleId,
            'status' => $status,
        ]);

        $vehicle = Vehicle::findOrFail($vehicleId);
        $vehicle->update(['status' => $status]);

        $this->dispatch('status-updated', message: 'Estado del vehículo actualizado');
    }

    public function closeHistoryModal()
    {
        $this->showHistoryModal = false;
        $this->selectedVehicleId = null;
    }

    public function closeReportModal()
    {
        $this->showReportModal = false;
        $this->selectedVehicleId = null;
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.technician-vehicles', [
            'vehicles' => $this->vehicles,
            'selectedVehicle' => $this->selectedVehicle,
            'maintenanceHistory' => $this->maintenanceHistory,
        ])->layout('components.technician-layout');
    }
}
