<?php

namespace App\Livewire;

use App\Models\Delivery;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class DeliveryPerformance extends Component
{
    public $period = 'month'; // week, month, year

    public function getStatsProperty()
    {
        $user = Auth::user();
        
        $startDate = match($this->period) {
            'week' => now()->startOfWeek(),
            'month' => now()->startOfMonth(),
            'year' => now()->startOfYear(),
            default => now()->startOfMonth(),
        };

        $deliveries = Delivery::where('delivery_person_id', $user->id)
            ->where('status', 'delivered')
            ->where('actual_delivery_time', '>=', $startDate)
            ->get();

        return [
            'total_deliveries' => $deliveries->count(),
            'total_earnings' => $deliveries->sum('delivery_fee'),
            'average_per_day' => $deliveries->count() / max(1, $startDate->diffInDays(now())),
            'completion_rate' => $this->getCompletionRate(),
        ];
    }

    public function getCompletionRate()
    {
        $user = Auth::user();
        
        $total = Delivery::where('delivery_person_id', $user->id)->count();
        $completed = Delivery::where('delivery_person_id', $user->id)
            ->where('status', 'delivered')
            ->count();

        return $total > 0 ? round(($completed / $total) * 100, 1) : 0;
    }

    public function getDailyStatsProperty()
    {
        $user = Auth::user();
        
        return Delivery::where('delivery_person_id', $user->id)
            ->where('status', 'delivered')
            ->where('actual_delivery_time', '>=', now()->subDays(7))
            ->select(
                DB::raw('DATE(actual_delivery_time) as date'),
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(delivery_fee) as earnings')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }

    public function render()
    {
        return view('livewire.delivery-performance', [
            'stats' => $this->stats,
            'dailyStats' => $this->dailyStats,
        ])->layout('components.delivery-layout');
    }
}
