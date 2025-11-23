<?php

namespace App\Livewire;

use App\Models\Reservation;
use App\Models\Vehicle;
use Livewire\Component;
use Livewire\WithFileUploads;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class OperatorDeliveries extends Component
{
    use WithFileUploads;

    public $activeTab = 'deliveries';
    
    // Delivery Form
    public $showDeliveryModal = false;
    public $selectedDeliveryReservation = null;
    public $deliveryVehicleCondition = 'good';
    public $deliveryNotes = '';
    public $documentsVerified = false;
    public $deliveryPhoto = null;
    public $deliveryPhotoPreview = null;

    // Return Form
    public $showReturnModal = false;
    public $selectedReturnReservation = null;
    public $returnVehicleCondition = 'good';
    public $returnNotes = '';
    public $returnKilometers = '';
    public $returnBatteryLevel = '';
    public $returnPhoto = null;
    public $returnPhotoPreview = null;

    protected $listeners = ['deliveryProcessed', 'returnProcessed'];

    public function getStationIdProperty()
    {
        return auth()->user()->station_id;
    }

    public function getPendingDeliveriesProperty()
    {
        if (!$this->stationId) return collect();

        return Reservation::with(['vehicle', 'user'])
            ->where('station_id', $this->stationId)
            ->where('status', 'confirmed')
            ->whereDate('start_date', '<=', Carbon::today())
            ->orderBy('start_date')
            ->get();
    }

    public function getPendingReturnsProperty()
    {
        if (!$this->stationId) return collect();

        return Reservation::with(['vehicle', 'user'])
            ->where('station_id', $this->stationId)
            ->where('status', 'active')
            ->orderBy('end_date')
            ->get();
    }

    // ========== DELIVERY METHODS ==========
    
    public function openDeliveryModal($reservationId)
    {
        $this->selectedDeliveryReservation = Reservation::with(['vehicle', 'user', 'station'])
            ->findOrFail($reservationId);
        $this->resetDeliveryForm();
        $this->showDeliveryModal = true;
    }

    public function updatedDeliveryPhoto()
    {
        $this->validate([
            'deliveryPhoto' => 'image|max:2048', // 2MB max
        ]);

        $this->deliveryPhotoPreview = $this->deliveryPhoto->temporaryUrl();
    }

    public function processDelivery()
    {
        $this->validate([
            'deliveryVehicleCondition' => 'required|in:good,fair,poor',
            'deliveryNotes' => 'nullable|string|max:1000',
            'documentsVerified' => 'accepted',
            'deliveryPhoto' => 'nullable|image|max:2048',
        ], [
            'documentsVerified.accepted' => 'Debes verificar los documentos antes de continuar',
        ]);

        $photoUrl = null;
        if ($this->deliveryPhoto) {
            $photoUrl = $this->deliveryPhoto->store('deliveries', 'public');
        }

        $this->selectedDeliveryReservation->update([
            'status' => 'active',
            'delivery_condition' => $this->deliveryVehicleCondition,
            'delivery_notes' => $this->deliveryNotes,
            'documents_verified' => true,
            'delivery_photo_url' => $photoUrl,
            'delivered_at' => now(),
            'delivered_by' => auth()->id(),
        ]);

        // Update vehicle status
        $this->selectedDeliveryReservation->vehicle->update([
            'status' => 'reserved',
        ]);

        $this->closeDeliveryModal();
        $this->dispatch('delivery-processed', message: '✓ Entrega procesada exitosamente');
    }

    public function closeDeliveryModal()
    {
        $this->showDeliveryModal = false;
        $this->selectedDeliveryReservation = null;
        $this->resetDeliveryForm();
        $this->resetValidation();
    }

    private function resetDeliveryForm()
    {
        $this->deliveryVehicleCondition = 'good';
        $this->deliveryNotes = '';
        $this->documentsVerified = false;
        $this->deliveryPhoto = null;
        $this->deliveryPhotoPreview = null;
    }

    // ========== RETURN METHODS ==========
    
    public function openReturnModal($reservationId)
    {
        $this->selectedReturnReservation = Reservation::with(['vehicle.telemetries', 'user', 'station'])
            ->findOrFail($reservationId);
        $this->resetReturnForm();
        
        // Pre-fill with current telemetry data if available
        $latestTelemetry = $this->selectedReturnReservation->vehicle->telemetries()->latest()->first();
        if ($latestTelemetry) {
            $this->returnBatteryLevel = $latestTelemetry->battery_level;
            $this->returnKilometers = $latestTelemetry->distance_traveled;
        }
        
        $this->showReturnModal = true;
    }

    public function updatedReturnPhoto()
    {
        $this->validate([
            'returnPhoto' => 'image|max:2048',
        ]);

        $this->returnPhotoPreview = $this->returnPhoto->temporaryUrl();
    }

    public function processReturn()
    {
        $this->validate([
            'returnVehicleCondition' => 'required|in:good,fair,poor',
            'returnNotes' => 'nullable|string|max:1000',
            'returnKilometers' => 'required|integer|min:0',
            'returnBatteryLevel' => 'required|integer|min:0|max:100',
            'returnPhoto' => 'nullable|image|max:2048',
        ], [
            'returnKilometers.required' => 'El kilometraje es obligatorio',
            'returnBatteryLevel.required' => 'El nivel de batería es obligatorio',
        ]);

        $photoUrl = null;
        if ($this->returnPhoto) {
            $photoUrl = $this->returnPhoto->store('returns', 'public');
        }

        $this->selectedReturnReservation->update([
            'status' => 'completed',
            'return_condition' => $this->returnVehicleCondition,
            'return_notes' => $this->returnNotes,
            'return_kilometers' => $this->returnKilometers,
            'return_battery_level' => $this->returnBatteryLevel,
            'return_photo_url' => $photoUrl,
            'returned_at' => now(),
            'returned_by' => auth()->id(),
        ]);

        // Update vehicle status and telemetry
        $this->selectedReturnReservation->vehicle->update([
            'status' => 'available',
        ]);

        // Update telemetry
        $this->selectedReturnReservation->vehicle->telemetries()->create([
            'battery_level' => $this->returnBatteryLevel,
            'distance_traveled' => $this->returnKilometers,
            'speed' => 0,
            'last_ping_at' => now(),
        ]);

        $this->closeReturnModal();
        $this->dispatch('return-processed', message: '✓ Devolución procesada exitosamente');
    }

    public function closeReturnModal()
    {
        $this->showReturnModal = false;
        $this->selectedReturnReservation = null;
        $this->resetReturnForm();
        $this->resetValidation();
    }

    private function resetReturnForm()
    {
        $this->returnVehicleCondition = 'good';
        $this->returnNotes = '';
        $this->returnKilometers = '';
        $this->returnBatteryLevel = '';
        $this->returnPhoto = null;
        $this->returnPhotoPreview = null;
    }

    public function render()
    {
        return view('livewire.operator-deliveries', [
            'pendingDeliveries' => $this->pendingDeliveries,
            'pendingReturns' => $this->pendingReturns,
        ])->layout('components.operator-layout');
    }
}
