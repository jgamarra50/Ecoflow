<?php

namespace App\Livewire;

use App\Models\Reservation;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class MyReservations extends Component
{
    public $statusFilter = 'all';
    public $selectedReservation = null;
    public $showModal = false;

    protected $listeners = ['reservationCancelled' => '$refresh'];

    public function mount()
    {
        // Component initialization
    }

    public function getReservationsProperty()
    {
        $query = Reservation::where('user_id', Auth::id())
            ->with(['vehicle.station', 'station'])
            ->orderBy('created_at', 'desc');

        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        return $query->get();
    }

    public function setStatusFilter($status)
    {
        $this->statusFilter = $status;
    }

    public function viewDetails($reservationId)
    {
        $this->selectedReservation = Reservation::with(['vehicle.station', 'station', 'user'])
            ->findOrFail($reservationId);
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->selectedReservation = null;
    }

    public function cancelReservation($reservationId)
    {
        $reservation = Reservation::findOrFail($reservationId);

        // Verify ownership
        if ($reservation->user_id !== Auth::id()) {
            $this->dispatch('show-error', message: 'No tienes permiso para cancelar esta reserva.');
            return;
        }

        // Check if can be cancelled
        if (!$reservation->canBeCancelled()) {
            $this->dispatch('show-error', message: 'Esta reserva no puede ser cancelada.');
            return;
        }

        // Cancel the reservation
        $reservation->update(['status' => 'cancelled']);

        // Close modal if open
        $this->closeModal();

        // Show success message
        $this->dispatch('reservation-cancelled', message: 'Reserva cancelada exitosamente.');
    }

    public function downloadReceipt($reservationId)
    {
        $reservation = Reservation::with(['vehicle', 'station', 'user'])
            ->findOrFail($reservationId);

        // Verify ownership
        if ($reservation->user_id !== Auth::id()) {
            return;
        }

        // Redirect to receipt view (will implement PDF later)
        return redirect()->route('reservations.receipt', $reservationId);
    }

    public function viewLocation($reservationId)
    {
        $reservation = Reservation::with('vehicle')->findOrFail($reservationId);

        // Verify ownership and status
        if ($reservation->user_id !== Auth::id() || !$reservation->isActive()) {
            return;
        }

        // For now, just show details modal
        // Later can redirect to map view
        $this->viewDetails($reservationId);
    }

    public function render()
    {
        return view('livewire.my-reservations')
            ->layout('components.client-layout');
    }
}
