<?php

namespace App\Livewire;

use App\Models\Reservation;
use App\Models\Vehicle;
use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class OperatorDashboard extends Component
{
    // QR Scanner Form
    public $plateInput = '';
    public $scanMode = 'delivery'; // 'delivery' or 'return'
    
    // Modal
    public $showProcessModal = false;
    public $selectedReservation = null;
    public $notes = '';
    public $vehicleCondition = 'good';
    public $photoUrl = null;

    protected $listeners = ['processCompleted'];

    public function mount()
    {
        // Verify operator has station assigned
        if (!auth()->user()->station_id) {
            session()->flash('error', 'No tienes una estación asignada. Contacta al administrador.');
        }
    }

    public function getStationIdProperty()
    {
        return auth()->user()->station_id;
    }

    public function getTodayReservationsProperty()
    {
        if (!$this->stationId) return collect();

        return Reservation::with(['vehicle', 'user'])
            ->where('station_id', $this->stationId)
            ->whereDate('start_date', '<=', Carbon::today())
            ->whereDate('end_date', '>=', Carbon::today())
            ->whereIn('status', ['active', 'confirmed', 'pending'])
            ->orderBy('start_date')
            ->get();
    }

    public function getPendingDeliveriesProperty()
    {
        if (!$this->stationId) return collect();

        return Reservation::with(['vehicle', 'user'])
            ->where('station_id', $this->stationId)
            ->where('status', 'confirmed')
            ->whereDate('start_date', '<=', Carbon::today())
            ->orderBy('start_date')
            ->limit(10)
            ->get();
    }

    public function getPendingReturnsProperty()
    {
        if (!$this->stationId) return collect();

        return Reservation::with(['vehicle', 'user'])
            ->where('station_id', $this->stationId)
            ->where('status', 'active')
            ->whereDate('end_date', '<=', Carbon::today())
            ->orderBy('end_date')
            ->limit(10)
            ->get();
    }

    public function getStatsProperty()
    {
        if (!$this->stationId) {
            return [
                'today_total' => 0,
                'pending_deliveries' => 0,
                'pending_returns' => 0,
                'completed_today' => 0,
            ];
        }

        return [
            'today_total' => $this->todayReservations->count(),
            'pending_deliveries' => $this->pendingDeliveries->count(),
            'pending_returns' => $this->pendingReturns->count(),
            'completed_today' => Reservation::where('station_id', $this->stationId)
                ->whereDate('updated_at', Carbon::today())
                ->where('status', 'completed')
                ->count(),
        ];
    }

    public function scanPlate()
    {
        $this->validate([
            'plateInput' => 'required|string',
        ], [
            'plateInput.required' => 'Ingresa la placa del vehículo',
        ]);

        // Find vehicle by plate
        $vehicle = Vehicle::where('plate', strtoupper($this->plateInput))->first();

        if (!$vehicle) {
            $this->dispatch('scan-error', message: 'Vehículo no encontrado con placa: ' . $this->plateInput);
            return;
        }

        // Find reservation based on scan mode
        if ($this->scanMode === 'delivery') {
            $reservation = Reservation::where('vehicle_id', $vehicle->id)
                ->where('station_id', $this->stationId)
                ->where('status', 'confirmed')
                ->whereDate('start_date', '<=', Carbon::today())
                ->orderBy('start_date')
                ->first();
        } else {
            $reservation = Reservation::where('vehicle_id', $vehicle->id)
                ->where('station_id', $this->stationId)
                ->where('status', 'active')
                ->orderBy('end_date')
                ->first();
        }

        if (!$reservation) {
            $message = $this->scanMode === 'delivery' 
                ? 'No hay entregas pendientes para este vehículo'
                : 'No hay devoluciones pendientes para este vehículo';
            $this->dispatch('scan-error', message: $message);
            return;
        }

        // Show process modal
        $this->selectedReservation = $reservation;
        $this->showProcessModal = true;
        $this->plateInput = '';
    }

    public function processDelivery()
    {
        $this->validate([
            'vehicleCondition' => 'required|in:good,fair,poor',
            'notes' => 'nullable|string|max:500',
        ]);

        $this->selectedReservation->update([
            'status' => 'active',
            'delivery_notes' => $this->notes,
            'delivery_condition' => $this->vehicleCondition,
            'delivered_at' => now(),
            'delivered_by' => auth()->id(),
        ]);

        // Update vehicle status
        $this->selectedReservation->vehicle->update([
            'status' => 'reserved',
        ]);

        $this->closeModal();
        $this->dispatch('process-completed', message: '✓ Entrega registrada exitosamente');
    }

    public function processReturn()
    {
        $this->validate([
            'vehicleCondition' => 'required|in:good,fair,poor',
            'notes' => 'nullable|string|max:500',
        ]);

        $this->selectedReservation->update([
            'status' => 'completed',
            'return_notes' => $this->notes,
            'return_condition' => $this->vehicleCondition,
            'returned_at' => now(),
            'returned_by' => auth()->id(),
        ]);

        // Update vehicle status
        $this->selectedReservation->vehicle->update([
            'status' => 'available',
        ]);

        $this->closeModal();
        $this->dispatch('process-completed', message: '✓ Devolución registrada exitosamente');
    }

    public function viewReservationDetails($reservationId)
    {
        $this->selectedReservation = Reservation::with(['vehicle', 'user', 'station'])
            ->findOrFail($reservationId);
        $this->showProcessModal = true;
        
        // Set scan mode based on reservation status
        $this->scanMode = $this->selectedReservation->status === 'confirmed' ? 'delivery' : 'return';
    }

    public function closeModal()
    {
        $this->showProcessModal = false;
        $this->selectedReservation = null;
        $this->notes = '';
        $this->vehicleCondition = 'good';
        $this->photoUrl = null;
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.operator-dashboard', [
            'stats' => $this->stats,
            'todayReservations' => $this->todayReservations,
            'pendingDeliveries' => $this->pendingDeliveries,
            'pendingReturns' => $this->pendingReturns,
        ])->layout('components.operator-layout');
    }
}
