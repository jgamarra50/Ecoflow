<?php

namespace App\Livewire;

use App\Models\Reservation;
use Livewire\Component;
use Illuminate\Support\Facades\Gate;

class ReservationTracking extends Component
{
    public $reservationId;
    public $reservation;
    public $simulatedLat;
    public $simulatedLng;
    public $lastUpdate;

    public function mount($id)
    {
        $this->reservationId = $id;
        $this->reservation = Reservation::with(['vehicle.station', 'vehicle.latestTelemetry', 'user'])
            ->findOrFail($id);

        // Authorization check
        if (auth()->id() !== $this->reservation->user_id) {
            abort(403, 'No tienes permiso para ver esta reserva.');
        }

        // Initialize simulated location (station location + small offset)
        if ($this->reservation->station) {
            $this->simulatedLat = $this->reservation->station->latitude;
            $this->simulatedLng = $this->reservation->station->longitude;
        }

        $this->lastUpdate = now()->toDateTimeString();
    }

    public function updateLocation()
    {
        // Simulate vehicle movement (small random movement)
        if ($this->reservation->status === 'active') {
            $this->simulatedLat += (rand(-10, 10) / 100000); // ~1-10 meters
            $this->simulatedLng += (rand(-10, 10) / 100000);
            $this->lastUpdate = now()->toDateTimeString();
        }
    }

    public function getEstimatedTimeProperty()
    {
        if (!$this->reservation->end_date) return null;
        
        $now = now();
        $endDate = $this->reservation->end_date;
        
        if ($endDate->isPast()) {
            return 'Finalizada';
        }
        
        $diff = $now->diffInMinutes($endDate);
        
        if ($diff < 60) {
            return $diff . ' minutos restantes';
        } else {
            $hours = floor($diff / 60);
            $minutes = $diff % 60;
            return $hours . 'h ' . $minutes . 'm restantes';
        }
    }

    public function getDistanceProperty()
    {
        // Simulate distance traveled based on time elapsed
        if ($this->reservation->status === 'active' && $this->reservation->delivered_at) {
            $minutesElapsed = now()->diffInMinutes($this->reservation->delivered_at);
            $kmTraveled = round($minutesElapsed * 0.15, 1); // ~9 km/h average
            return $kmTraveled . ' km recorridos';
        }
        
        return '0 km';
    }

    public function contactSupport()
    {
        $this->dispatch('support-contacted', 
            message: '¡Mensaje enviado! Nuestro equipo de soporte te contactará pronto.'
        );
    }

    public function render()
    {
        return view('livewire.reservation-tracking', [
            'estimatedTime' => $this->estimatedTime,
            'distance' => $this->distance,
        ])->layout('components.client-layout');
    }
}
