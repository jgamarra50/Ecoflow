<?php

namespace App\Livewire;

use App\Models\Maintenance;
use App\Models\User;
use App\Models\Vehicle;
use App\Services\MaintenanceVehicleStatusService;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log;

class MaintenancesManager extends Component
{
    use WithPagination;

    protected MaintenanceVehicleStatusService $maintenanceVehicleStatusService;

    public function boot(MaintenanceVehicleStatusService $maintenanceVehicleStatusService)
    {
        $this->maintenanceVehicleStatusService = $maintenanceVehicleStatusService;
    }

    // Filtros
    public $statusFilter = 'all';
    public $technicianFilter = 'all';

    // Modales
    public $showAssignModal = false;
    public $showHistoryModal = false;
    
    // Selecciones
    public $selectedMaintenanceId;
    public $selectedVehicleId;
    public $selectedTechnicianId;

    protected $listeners = ['maintenanceAssigned'];

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingTechnicianFilter()
    {
        $this->resetPage();
    }

    public function getMaintenancesProperty()
    {
        $query = Maintenance::with(['vehicle', 'technician', 'creator']);

        // Filtro por estado
        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        // Filtro por técnico
        if ($this->technicianFilter !== 'all') {
            $query->where('technician_id', $this->technicianFilter);
        }

        return $query->latest()->paginate(10);
    }

    public function getSelectedMaintenanceProperty()
    {
        if (!$this->selectedMaintenanceId) {
            return null;
        }
        return Maintenance::with(['vehicle', 'technician', 'creator'])->find($this->selectedMaintenanceId);
    }

    public function getVehicleHistoryProperty()
    {
        if (!$this->selectedVehicleId) {
            return collect();
        }
        return Maintenance::with(['technician', 'creator'])
            ->where('vehicle_id', $this->selectedVehicleId)
            ->latest()
            ->get();
    }

    public function assignTechnician($maintenanceId)
    {
        $this->selectedMaintenanceId = $maintenanceId;
        $this->selectedTechnicianId = '';
        $this->showAssignModal = true;
    }

    public function saveAssignment()
    {
        $this->validate([
            'selectedTechnicianId' => 'required|exists:users,id',
        ]);

        $maintenance = Maintenance::findOrFail($this->selectedMaintenanceId);
        
        $maintenance->update([
            'technician_id' => $this->selectedTechnicianId,
            'status' => 'assigned',
            'assigned_at' => now(),
        ]);

        $this->maintenanceVehicleStatusService->syncVehicleStatus($maintenance);

        // Notificar al técnico (simulado con log)
        $this->notifyTechnician($maintenance->fresh(['vehicle', 'technician']));

        $this->showAssignModal = false;
        $this->dispatch('maintenance-assigned', message: 'Técnico asignado exitosamente');
    }

    public function changeStatus($id, $newStatus)
    {
        $maintenance = Maintenance::findOrFail($id);
        
        $updateData = ['status' => $newStatus];
        
        // Actualizar timestamps según estado
        if ($newStatus === 'in_progress' && !$maintenance->started_at) {
            $updateData['started_at'] = now();
        } elseif ($newStatus === 'completed' && !$maintenance->completed_at) {
            $updateData['completed_at'] = now();
        }
        
        $maintenance->update($updateData);

        $this->maintenanceVehicleStatusService->syncVehicleStatus($maintenance);

        Log::info('Estado de mantenimiento cambiado', [
            'maintenance_id' => $maintenance->id,
            'vehicle' => $maintenance->vehicle->plate,
            'old_status' => $maintenance->getOriginal('status'),
            'new_status' => $newStatus,
            'changed_by' => auth()->user()->name,
        ]);

        $this->dispatch('status-changed', message: 'Estado actualizado a: ' . $maintenance->getStatusLabel());
    }

    public function viewVehicleHistory($vehicleId)
    {
        $this->selectedVehicleId = $vehicleId;
        $this->showHistoryModal = true;
    }

    public function closeAssignModal()
    {
        $this->showAssignModal = false;
        $this->selectedMaintenanceId = null;
        $this->selectedTechnicianId = '';
        $this->resetValidation();
    }

    public function closeHistoryModal()
    {
        $this->showHistoryModal = false;
        $this->selectedVehicleId = null;
    }

    private function notifyTechnician($maintenance)
    {
        Log::info('🔧 Mantenimiento asignado a técnico', [
            'maintenance_id' => $maintenance->id,
            'title' => $maintenance->title,
            'vehicle' => $maintenance->vehicle->brand . ' ' . $maintenance->vehicle->model,
            'plate' => $maintenance->vehicle->plate,
            'technician' => $maintenance->technician->name,
            'technician_email' => $maintenance->technician->email,
            'priority' => $maintenance->priority,
            'assigned_at' => $maintenance->assigned_at->format('Y-m-d H:i:s'),
        ]);

        // Aquí iría la integración real: Email, SMS, Push notification, etc.
    }

    public function getTechniciansProperty()
    {
        return User::role('tecnico')->get();
    }

    public function render()
    {
        return view('livewire.maintenances-manager', [
            'maintenances' => $this->maintenances,
            'technicians' => $this->technicians,
            'selectedMaintenance' => $this->selectedMaintenance,
            'vehicleHistory' => $this->vehicleHistory,
        ])->layout('components.admin-layout');
    }
}
