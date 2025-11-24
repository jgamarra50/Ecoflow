<?php

namespace App\Livewire;

use App\Models\Delivery;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class DeliveryDashboard extends Component
{
    public $availabilityStatus = true;

    public function mount()
    {
        $this->availabilityStatus = Auth::user()->is_available ?? false;
    }

    public function toggleAvailability()
    {
        $user = Auth::user();
        $user->is_available = !$user->is_available;
        $user->save();
        
        $this->availabilityStatus = $user->is_available;
        
        $message = $user->is_available 
            ? '✅ Ahora estás disponible para recibir entregas' 
            : '⏸️ Has pausado tu disponibilidad';
            
        $this->dispatch('availability-changed', message: $message);
    }

    public function getStatsProperty()
    {
        $user = Auth::user();
        $today = now()->startOfDay();

        return [
            'pending' => Delivery::where('delivery_person_id', $user->id)
                ->where('status', 'assigned')
                ->count(),
            'in_transit' => Delivery::where('delivery_person_id', $user->id)
                ->where('status', 'in_transit')
                ->count(),
            'completed_today' => Delivery::where('delivery_person_id', $user->id)
                ->where('status', 'delivered')
                ->where('actual_delivery_time', '>=', $today)
                ->count(),
            'total_deliveries' => $user->total_deliveries ?? 0,
            'average_rating' => $user->average_rating ?? 0,
            'earnings_today' => Delivery::where('delivery_person_id', $user->id)
                ->where('status', 'delivered')
                ->where('actual_delivery_time', '>=', $today)
                ->sum('delivery_fee'),
        ];
    }

    public function getPendingDeliveriesProperty()
    {
        return Delivery::with(['reservation.user', 'reservation.vehicle'])
            ->where('delivery_person_id', Auth::id())
            ->whereIn('status', ['assigned', 'in_transit', 'arrived'])
            ->orderBy('scheduled_time')
            ->get();
    }

    public function getRecentDeliveriesProperty()
    {
        return Delivery::with(['reservation.user', 'reservation.vehicle'])
            ->where('delivery_person_id', Auth::id())
            ->where('status', 'delivered')
            ->latest('actual_delivery_time')
            ->take(5)
            ->get();
    }

    public function render()
    {
        return view('livewire.delivery-dashboard', [
            'stats' => $this->stats,
            'pendingDeliveries' => $this->pendingDeliveries,
            'recentDeliveries' => $this->recentDeliveries,
        ])->layout('components.delivery-layout');
    }
}
