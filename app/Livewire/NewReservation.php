<?php

namespace App\Livewire;

use App\Models\Vehicle;
use App\Models\Station;
use App\Models\Reservation;
use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class NewReservation extends Component
{
    // Wizard state
    public $currentStep = 1;
    public $totalSteps = 4;

    // Step 1: Vehicle Selection
    public $selectedVehicleId = null;
    public $typeFilter = 'all';
    public $vehicles = [];

    // Step 2: Dates & Delivery
    public $startDate = null;
    public $endDate = null;
    public $deliveryMethod = 'pickup'; // 'pickup' or 'delivery'
    public $pickupStationId = null;
    public $deliveryAddress = null;

    // Step 3: Return Method
    public $returnMethod = 'return'; // 'return' or 'pickup'
    public $returnStationId = null;
    public $returnAddress = null;

    // Step 4: Confirmation
    public $totalPrice = 0;
    public $days = 0;

    // Data
    public $stations = [];

    public function mount()
    {
        $this->loadVehicles();
        $this->stations = Station::all();
        
        // Default dates (tomorrow)
        $this->startDate = Carbon::tomorrow()->format('Y-m-d\TH:i');
        $this->endDate = Carbon::tomorrow()->addDays(1)->format('Y-m-d\TH:i');
    }

    public function loadVehicles()
    {
        $query = Vehicle::with(['station', 'telemetries' => function($q) {
            $q->latest()->limit(1);
        }])->where('status', 'available');

        if ($this->typeFilter !== 'all') {
            $query->where('type', $this->typeFilter);
        }

        $this->vehicles = $query->get();
    }

    public function selectVehicle($vehicleId)
    {
        $this->selectedVehicleId = $vehicleId;
    }

    public function setTypeFilter($type)
    {
        $this->typeFilter = $type;
        $this->loadVehicles();
    }

    public function nextStep()
    {
        $this->validateStep();
        
        if ($this->currentStep === 3) {
            $this->calculatePrice();
        }

        if ($this->currentStep < $this->totalSteps) {
            $this->currentStep++;
        }
    }

    public function previousStep()
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
    }

    public function validateStep()
    {
        if ($this->currentStep === 1) {
            if (!$this->selectedVehicleId) {
                session()->flash('error', 'Por favor selecciona un vehículo.');
                throw new \Exception('Validation failed');
            }
        }
        elseif ($this->currentStep === 2) {
            $this->validate([
                'startDate' => 'required|date|after:now',
                'endDate' => 'required|date|after:startDate',
                'deliveryMethod' => 'required|in:pickup,delivery',
                'pickupStationId' => 'required_if:deliveryMethod,pickup',
                'deliveryAddress' => 'required_if:deliveryMethod,delivery',
            ], [
                'startDate.required' => 'La fecha de inicio es requerida.',
                'endDate.after' => 'La fecha de fin debe ser posterior a la de inicio.',
                'pickupStationId.required_if' => 'Selecciona una estación de recogida.',
                'deliveryAddress.required_if' => 'Ingresa la dirección de entrega.',
            ]);

            if (!$this->checkAvailability()) {
                session()->flash('error', 'El vehículo no está disponible en esas fechas.');
                throw new \Exception('Validation failed');
            }
        }
        elseif ($this->currentStep === 3) {
            $this->validate([
                'returnMethod' => 'required|in:return,pickup',
                'returnStationId' => 'required_if:returnMethod,return',
                'returnAddress' => 'required_if:returnMethod,pickup',
            ], [
                'returnStationId.required_if' => 'Selecciona una estación de devolución.',
                'returnAddress.required_if' => 'Ingresa la dirección de recogida.',
            ]);
        }
    }

    public function checkAvailability()
    {
        // Parse dates to ensure proper format for database query
        $startDate = Carbon::parse($this->startDate)->format('Y-m-d H:i:s');
        $endDate = Carbon::parse($this->endDate)->format('Y-m-d H:i:s');
        
        $conflicts = Reservation::where('vehicle_id', $this->selectedVehicleId)
            ->whereIn('status', ['active', 'confirmed'])
            ->where(function($q) use ($startDate, $endDate) {
                $q->whereBetween('start_date', [$startDate, $endDate])
                  ->orWhereBetween('end_date', [$startDate, $endDate])
                  ->orWhere(function($q) use ($startDate, $endDate) {
                      $q->where('start_date', '<=', $startDate)
                        ->where('end_date', '>=', $endDate);
                  });
            })
            ->exists();

        return !$conflicts;
    }

    public function calculatePrice()
    {
        $start = Carbon::parse($this->startDate);
        $end = Carbon::parse($this->endDate);
        
        // Calculate days (minimum 1 day)
        $this->days = max(1, $start->diffInDays($end));
        
        // Price calculation: $50,000 COP per day
        $pricePerDay = 50000;
        $this->totalPrice = $this->days * $pricePerDay;

        // Add delivery/pickup fees if applicable (optional logic)
        if ($this->deliveryMethod === 'delivery') {
            $this->totalPrice += 10000; // Delivery fee
        }
        if ($this->returnMethod === 'pickup') {
            $this->totalPrice += 10000; // Pickup fee
        }
    }

    public function submitReservation()
    {
        // Debug log
        \Log::info('submitReservation called');
        
        // Store reservation data in session for checkout
        session([
            'reservation_data' => [
                'user_id' => Auth::id(),
                'vehicle_id' => $this->selectedVehicleId,
                'start_date' => $this->startDate,
                'end_date' => $this->endDate,
                'delivery_method' => $this->deliveryMethod,
                'delivery_address' => $this->deliveryMethod === 'delivery' ? $this->deliveryAddress : null,
                'station_id' => $this->deliveryMethod === 'pickup' ? $this->pickupStationId : null,
                'return_method' => $this->returnMethod,
                'return_address' => $this->returnMethod === 'pickup' ? $this->returnAddress : null,
                'return_station_id' => $this->returnMethod === 'return' ? $this->returnStationId : null,
                'total_price' => $this->totalPrice,
                'days' => $this->days,
            ]
        ]);

        // Flash success message
        session()->flash('checkout_ready', true);
        
        // Use JavaScript redirect
        $this->js('window.location.href = "' . route('checkout.payment') . '"');
    }

    public function getSelectedVehicleProperty()
    {
        return Vehicle::find($this->selectedVehicleId);
    }

    public function getPickupStationProperty()
    {
        return Station::find($this->pickupStationId);
    }

    public function getReturnStationProperty()
    {
        return Station::find($this->returnStationId);
    }

    public function render()
    {
        return view('livewire.new-reservation');
    }
}
