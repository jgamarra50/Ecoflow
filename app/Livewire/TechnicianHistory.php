<?php

namespace App\Livewire;

use App\Models\Maintenance;
use Livewire\Component;
use Livewire\WithPagination;

class TechnicianHistory extends Component
{
    use WithPagination;

    public $statusFilter = 'all';
    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function getMaintenancesProperty()
    {
        $query = Maintenance::with(['vehicle', 'creator'])
            ->where('technician_id', auth()->id());

        // Búsqueda
        if ($this->search) {
            $query->where(function($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%')
                  ->orWhereHas('vehicle', function($vq) {
                      $vq->where('brand', 'like', '%' . $this->search . '%')
                        ->orWhere('model', 'like', '%' . $this->search . '%')
                        ->orWhere('plate', 'like', '%' . $this->search . '%');
                  });
            });
        }

        // Filtro de estado
        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        return $query->latest()->paginate(15);
    }

    public function getStatsProperty()
    {
        $maintenances = Maintenance::where('technician_id', auth()->id());
        
        return [
            'completed' => $maintenances->where('status', 'completed')->count(),
            'in_progress' => $maintenances->where('status', 'in_progress')->count(),
            'total' => $maintenances->count(),
        ];
    }

    public function render()
    {
        return view('livewire.technician-history', [
            'maintenances' => $this->maintenances,
            'stats' => $this->stats,
        ])->layout('components.technician-layout');
    }
}
