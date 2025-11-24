<?php

namespace App\Livewire;

use App\Models\Delivery;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class DeliveryHistory extends Component
{
    public $search = '';
    public $dateFilter = 'all';

    public function render()
    {
        $query = Delivery::with(['reservation.user', 'reservation.vehicle'])
            ->where('delivery_person_id', Auth::id())
            ->where('status', 'delivered');

        if ($this->search) {
            $query->whereHas('reservation.user', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->dateFilter === 'today') {
            $query->whereDate('actual_delivery_time', today());
        } elseif ($this->dateFilter === 'week') {
            $query->whereBetween('actual_delivery_time', [now()->startOfWeek(), now()->endOfWeek()]);
        } elseif ($this->dateFilter === 'month') {
            $query->whereMonth('actual_delivery_time', now()->month);
        }

        $deliveries = $query->latest('actual_delivery_time')->paginate(20);

        return view('livewire.delivery-history', [
            'deliveries' => $deliveries,
        ])->layout('components.delivery-layout');
    }
}
