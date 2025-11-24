<?php

namespace App\Livewire;

use App\Models\Reservation;
use App\Models\Vehicle;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CheckoutPayment extends Component
{
    public $paymentMethod = 'card'; // 'card' or 'pse'
    
    // Card payment fields
    public $cardNumber = '';
    public $cardName = '';
    public $expiryDate = '';
    public $cvv = '';
    
    // PSE fields
    public $selectedBank = '';
    public $personType = 'natural'; // 'natural' or 'juridica'
    public $documentType = 'CC';
    public $documentNumber = '';
    
    // Reservation data
    public $reservationData;
    public $vehicle;
    public $totalPrice;
    public $days;
    
    // Processing state
    public $isProcessing = false;

    public function mount()
    {
        // Get reservation data from session
        $this->reservationData = session('reservation_data');
        
        if (!$this->reservationData) {
            return redirect()->route('reservations.new')
                ->with('error', 'No hay datos de reserva. Por favor completa el formulario nuevamente.');
        }
        
        $this->vehicle = Vehicle::with('station')->find($this->reservationData['vehicle_id']);
        $this->totalPrice = $this->reservationData['total_price'];
        $this->days = $this->reservationData['days'];
    }

    public function setPaymentMethod($method)
    {
        $this->paymentMethod = $method;
    }

    public function processPayment()
    {
        $this->isProcessing = true;
        
        // Validate based on payment method
        if ($this->paymentMethod === 'card') {
            // Limpiar espacios del número de tarjeta antes de validar
            $this->cardNumber = str_replace(' ', '', $this->cardNumber);
            
            $this->validate([
                'cardNumber' => ['required', 'digits:16'],
                'cardName' => 'required|string|min:3',
                'expiryDate' => ['required', 'size:5', function($attribute, $value, $fail) {
                    if (!preg_match('/^(0[1-9]|1[0-2])\/[0-9]{2}$/', $value)) {
                        $fail('Formato inválido. Usa MM/YY');
                    }
                }],
                'cvv' => ['required', 'digits_between:3,4'],
            ], [
                'cardNumber.required' => 'Ingresa el número de tarjeta',
                'cardNumber.digits' => 'El número de tarjeta debe tener 16 dígitos',
                'cardName.required' => 'Ingresa el nombre del titular',
                'expiryDate.required' => 'Ingresa la fecha de vencimiento',
                'expiryDate.size' => 'La fecha debe tener formato MM/YY',
                'cvv.required' => 'Ingresa el código CVV',
                'cvv.digits_between' => 'El CVV debe tener 3 o 4 dígitos',
            ]);
        } else {
            $this->validate([
                'selectedBank' => 'required',
                'documentType' => 'required',
                'documentNumber' => 'required|numeric',
            ], [
                'selectedBank.required' => 'Selecciona tu banco',
                'documentType.required' => 'Selecciona el tipo de documento',
                'documentNumber.required' => 'Ingresa tu número de documento',
                'documentNumber.numeric' => 'El documento debe ser numérico',
            ]);
        }

        // Simulate payment processing (2 seconds)
        sleep(2);
        
        // Create the reservation
        $reservation = Reservation::create([
            'user_id' => $this->reservationData['user_id'],
            'vehicle_id' => $this->reservationData['vehicle_id'],
            'start_date' => $this->reservationData['start_date'],
            'end_date' => $this->reservationData['end_date'],
            'delivery_method' => $this->reservationData['delivery_method'],
            'delivery_address' => $this->reservationData['delivery_address'],
            'station_id' => $this->reservationData['station_id'],
            'status' => 'confirmed', // Confirmed after payment
            'total_price' => $this->reservationData['total_price'],
        ]);

        // Clear session data
        session()->forget('reservation_data');
        
        // Dispatch success event
        $this->dispatch('payment-success', reservationId: $reservation->id);
        
        // Redirect to success page
        return redirect()->route('checkout.success', $reservation->id);
    }

    public function render()
    {
        return view('livewire.checkout-payment')
            ->layout('components.client-layout');
    }
}
