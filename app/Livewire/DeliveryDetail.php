<?php

namespace App\Livewire;

use App\Models\Delivery;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class DeliveryDetail extends Component
{
    use WithFileUploads;

    public Delivery $delivery;
    public $notes = '';
    public $photo;
    public $signature;
   
    public function mount($id)
    {
        $this->delivery = Delivery::with(['reservation.user', 'reservation.vehicle', 'reservation.station'])
            ->where('delivery_person_id', Auth::id())
            ->findOrFail($id);
            
        $this->notes = $this->delivery->notes ?? '';
    }

    public function startDelivery()
    {
        if ($this->delivery->canStart()) {
            $this->delivery->start();
            $this->dispatch('delivery-started', message: '🚚 Entrega iniciada. ¡Buen viaje!');
        }
    }

    public function markAsArrived()
    {
        if ($this->delivery->status === 'in_transit') {
            $this->delivery->markAsArrived();
            $this->dispatch('delivery-arrived', message: '📍 Has llegado al destino');
        }
    }

    public function completeDelivery()
    {
        $this->validate([
            'signature' => 'required|string',
            'photo' => 'nullable|image|max:2048',
        ], [
            'signature.required' => 'La firma del cliente es obligatoria',
            'photo.image' => 'El archivo debe ser una imagen',
            'photo.max' => 'La imagen no debe superar 2MB',
        ]);

        $photoPath = null;
        if ($this->photo) {
            $photoPath = $this->photo->store('delivery-photos', 'public');
        }

        $completed = $this->delivery->complete([
            'notes' => $this->notes,
            'photo_proof' => $photoPath,
            'customer_signature' => $this->signature,
        ]);

        if ($completed) {
            // Update user stats
            $user = Auth::user();
            $user->increment('total_deliveries');
            
            $this->dispatch('delivery-completed', message: '✅ ¡Entrega completada exitosamente!');
            
            // Redirect after showing message
            $this->redirectRoute('repartidor.dashboard', navigate: true);
        }
    }

    public function cancelDelivery()
    {
        $this->delivery->cancel('Cancelada por repartidor: ' . $this->notes);
        $this->dispatch('delivery-cancelled', message: '❌ Entrega cancelada');
        $this->redirectRoute('repartidor.dashboard', navigate: true);
    }

    public function openNavigation()
    {
        $lat = $this->delivery->delivery_lat;
        $lng = $this->delivery->delivery_lng;
        
        // Open Google Maps or Waze
        $url = "https://www.google.com/maps/dir/?api=1&destination={$lat},{$lng}";
        
        $this->dispatch('open-navigation', url: $url);
    }

    public function render()
    {
        return view('livewire.delivery-detail')
            ->layout('components.delivery-layout');
    }
}
