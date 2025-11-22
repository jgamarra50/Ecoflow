<?php

namespace App\Livewire;

use App\Models\Reservation;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class ClientDashboard extends Component
{
    public $activeReservationsCount = 0;
    public $upcomingReservationsCount = 0;
    public $recentReservations;

    public function mount()
    {
        $this->loadDashboardData();
    }

    public function loadDashboardData()
    {
        $user = Auth::user();

        // Count active reservations
        $this->activeReservationsCount = Reservation::where('user_id', $user->id)
            ->where('status', 'active')
            ->count();

        // Count upcoming (confirmed) reservations
        $this->upcomingReservationsCount = Reservation::where('user_id', $user->id)
            ->where('status', 'confirmed')
            ->count();

        // Get recent reservations (last 5)
        $this->recentReservations = Reservation::where('user_id', $user->id)
            ->with(['vehicle', 'station'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
    }

    public function render()
    {
        return view('livewire.client-dashboard');
    }
}
