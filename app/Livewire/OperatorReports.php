<?php

namespace App\Livewire;

use App\Models\Vehicle;
use App\Models\Maintenance;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Log;

class OperatorReports extends Component
{
    use WithFileUploads;

    public $vehicleId = '';
    public $issueType = '';
    public $description = '';
    public $photo = null;
    public $photoPreview = null;
    public $priority = 'medium';

    protected $rules = [
        'vehicleId' => 'required|exists:vehicles,id',
        'issueType' => 'required|in:battery,brakes,lights,structure,other',
        'description' => 'required|string|min:10|max:1000',
        'photo' => 'nullable|image|max:2048',
        'priority' => 'required|in:low,medium,high,urgent',
    ];

    protected $messages = [
        'vehicleId.required' => 'Debes seleccionar un vehículo',
        'issueType.required' => 'Debes seleccionar el tipo de problema',
        'description.required' => 'La descripción es obligatoria',
        'description.min' => 'La descripción debe tener al menos 10 caracteres',
    ];

    public function getStationIdProperty()
    {
        return auth()->user()->station_id;
    }

    public function getVehiclesProperty()
    {
        if (!$this->stationId) return collect();

        return Vehicle::where('station_id', $this->stationId)
            ->orderBy('brand')
            ->orderBy('model')
            ->get();
    }

    public function updatedPhoto()
    {
        $this->validate([
            'photo' => 'image|max:2048',
        ]);

        $this->photoPreview = $this->photo->temporaryUrl();
    }

    public function submitReport()
    {
        $this->validate();

        $vehicle = Vehicle::findOrFail($this->vehicleId);

        // Upload photo if provided
        $photoUrl = null;
        if ($this->photo) {
            $photoUrl = $this->photo->store('reports', 'public');
        }

        // Create maintenance ticket
        $issueTypeLabels = [
            'battery' => 'Problema de Batería',
            'brakes' => 'Problema de Frenos',
            'lights' => 'Problema de Luces',
            'structure' => 'Problema Estructural',
            'other' => 'Otro Problema',
        ];

        $maintenance = Maintenance::create([
            'vehicle_id' => $this->vehicleId,
            'created_by' => auth()->id(),
            'title' => $issueTypeLabels[$this->issueType],
            'description' => $this->description . ($photoUrl ? "\n\nFoto adjunta: " . asset('storage/' . $photoUrl) : ''),
            'status' => 'pending',
            'priority' => $this->priority,
            'notes' => "Reportado por operador: " . auth()->user()->name . " desde estación: " . auth()->user()->station->name,
        ]);

        // Update vehicle status if high priority
        if (in_array($this->priority, ['high', 'urgent'])) {
            $vehicle->update(['status' => 'maintenance']);
        }

        // Log notification to admin (simulated)
        Log::channel('single')->info('ADMIN NOTIFICATION: New maintenance report', [
            'maintenance_id' => $maintenance->id,
            'vehicle' => $vehicle->brand . ' ' . $vehicle->model . ' (' . $vehicle->plate . ')',
            'issue_type' => $issueTypeLabels[$this->issueType],
            'priority' => $this->priority,
            'reported_by' => auth()->user()->name,
            'station' => auth()->user()->station->name,
            'timestamp' => now()->toDateTimeString(),
        ]);

        // Reset form
        $this->reset(['vehicleId', 'issueType', 'description', 'photo', 'photoPreview', 'priority']);
        $this->resetValidation();

        // Dispatch success event
        $this->dispatch('report-submitted', 
            message: '✓ Reporte enviado exitosamente. Se ha creado un ticket de mantenimiento #' . $maintenance->id
        );
    }

    public function render()
    {
        return view('livewire.operator-reports', [
            'vehicles' => $this->vehicles,
        ])->layout('components.operator-layout');
    }
}
