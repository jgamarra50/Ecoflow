<?php

namespace App\Livewire;

use App\Models\Delivery;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class DeliveryList extends Component
{
    public $statusFilter = 'all';
    public $typeFilter = 'all';

    public function render()
    {
        $query = Delivery::with(['reservation.user', 'reservation.vehicle'])
            ->where('delivery_person_id', Auth::id());

        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        if ($this->typeFilter !== 'all') {
            $query->where('type', $this->typeFilter);
        }

        $deliveries = $query->orderBy('scheduled_time', 'desc')->get();

        return view('livewire.delivery-list', [
            'deliveries' => $deliveries,
        ])->layout('components.delivery-layout');
    }
}
