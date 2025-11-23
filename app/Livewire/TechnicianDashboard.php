<?php

namespace App\Livewire;

use App\Models\Maintenance;
use Livewire\Component;
use Illuminate\Support\Facades\Log;

class TechnicianDashboard extends Component
{
    public $statusFilter = 'all';
    public $showDetailsModal = false;
    public $selectedMaintenanceId;
    public $notes = '';

    protected $listeners = ['maintenanceUpdated'];

    public function updatingStatusFilter()
    {
        $this->resetValidation();
    }

    public function getStatsProperty()
    {
        $maintenances = Maintenance::where('technician_id', auth()->id());
        
        return [
            'total' => $maintenances->count(),
            'in_progress' => $maintenances->where('status', 'in_progress')->count(),
            'completed_today' => $maintenances->where('status', 'completed')
                ->whereDate('completed_at', today())->count(),
            'pending' => $maintenances->whereIn('status', ['pending', 'assigned'])->count(),
        ];
    }

    public function getMaintenancesProperty()
    {
        $query = Maintenance::with(['vehicle', 'creator'])
            ->where('technician_id', auth()->id());
        
        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }
        
        return $query->latest()->get();
    }

    public function getSelectedMaintenanceProperty()
    {
        if (!$this->selectedMaintenanceId) {
            return null;
        }
        return Maintenance::with(['vehicle', 'creator'])->find($this->selectedMaintenanceId);
    }

    public function viewDetails($maintenanceId)
    {
        $this->selectedMaintenanceId = $maintenanceId;
        $maintenance = $this->selectedMaintenance;
        $this->notes = $maintenance->notes ?? '';
        $this->showDetailsModal = true;
    }

    public function updateStatus($status)
    {
        $maintenance = Maintenance::findOrFail($this->selectedMaintenanceId);
        
        $data = ['status' => $status];
        
        if ($status === 'in_progress' && !$maintenance->started_at) {
            $data['started_at'] = now();
        } elseif ($status === 'completed' && !$maintenance->completed_at) {
            $data['completed_at'] = now();
        }
        
        $maintenance->update($data);

        Log::info('Estado de mantenimiento actualizado por técnico', [
            'maintenance_id' => $maintenance->id,
            'technician' => auth()->user()->name,
            'new_status' => $status,
        ]);

        $this->dispatch('status-updated', message: 'Estado actualizado exitosamente');
    }

    public function saveNotes()
    {
        $this->validate([
            'notes' => 'nullable|string|max:1000'
        ]);
        
        Maintenance::findOrFail($this->selectedMaintenanceId)
            ->update(['notes' => $this->notes]);

        $this->dispatch('notes-saved', message: 'Notas guardadas exitosamente');
    }

    public function markAsCompleted()
    {
        $this->validate([
            'notes' => 'required|string|max:1000'
        ], [
            'notes.required' => 'Las notas son requeridas para marcar como completado'
        ]);

        $maintenance = Maintenance::findOrFail($this->selectedMaintenanceId);
        
        $maintenance->update([
            'status' => 'completed',
            'completed_at' => now(),
            'notes' => $this->notes
        ]);

        Log::info('Mantenimiento completado', [
            'maintenance_id' => $maintenance->id,
            'technician' => auth()->user()->name,
            'completed_at' => now(),
        ]);

        $this->showDetailsModal = false;
        $this->dispatch('maintenance-completed', message: 'Mantenimiento marcado como completado');
    }

    public function closeDetailsModal()
    {
        $this->showDetailsModal = false;
        $this->selectedMaintenanceId = null;
        $this->notes = '';
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.technician-dashboard', [
            'stats' => $this->stats,
            'maintenances' => $this->maintenances,
            'selectedMaintenance' => $this->selectedMaintenance,
        ])->layout('components.technician-layout');
    }
}
