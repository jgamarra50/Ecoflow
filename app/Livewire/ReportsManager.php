<?php

namespace App\Livewire;

use App\Models\Reservation;
use App\Models\Vehicle;
use App\Exports\ReservationsExport;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportsManager extends Component
{
    public $dateFrom;
    public $dateTo;
    public $selectedPeriod = 'month';

    public function mount()
    {
        // Default: este mes
        $this->dateFrom = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->dateTo = Carbon::now()->endOfMonth()->format('Y-m-d');
    }

    public function setPeriod($period)
    {
        $this->selectedPeriod = $period;
        
        switch ($period) {
            case 'today':
                $this->dateFrom = Carbon::today()->format('Y-m-d');
                $this->dateTo = Carbon::today()->format('Y-m-d');
                break;
            case 'week':
                $this->dateFrom = Carbon::now()->startOfWeek()->format('Y-m-d');
                $this->dateTo = Carbon::now()->endOfWeek()->format('Y-m-d');
                break;
            case 'month':
                $this->dateFrom = Carbon::now()->startOfMonth()->format('Y-m-d');
                $this->dateTo = Carbon::now()->endOfMonth()->format('Y-m-d');
                break;
            case 'quarter':
                $this->dateFrom = Carbon::now()->startOfQuarter()->format('Y-m-d');
                $this->dateTo = Carbon::now()->endOfQuarter()->format('Y-m-d');
                break;
            case 'year':
                $this->dateFrom = Carbon::now()->startOfYear()->format('Y-m-d');
                $this->dateTo = Carbon::now()->endOfYear()->format('Y-m-d');
                break;
        }
    }

    public function getGeneralStatsProperty()
    {
        $reservations = Reservation::whereBetween('created_at', [$this->dateFrom, $this->dateTo]);
        
        return [
            'total_reservations' => $reservations->count(),
            'total_revenue' => $reservations->where('status', 'completed')->sum('total_price'),
            'total_vehicles' => Vehicle::count(),
            'avg_rental_duration' => round($reservations->avg(DB::raw('DATEDIFF(end_date, start_date)')), 1) ?? 0,
        ];
    }

    public function getTopVehiclesProperty()
    {
        return Vehicle::withCount(['reservations' => function($query) {
                $query->whereBetween('created_at', [$this->dateFrom, $this->dateTo]);
            }])
            ->with(['reservations' => function($query) {
                $query->whereBetween('created_at', [$this->dateFrom, $this->dateTo])
                      ->where('status', 'completed');
            }])
            ->orderBy('reservations_count', 'desc')
            ->take(10)
            ->get()
            ->map(function($vehicle) {
                $vehicle->total_revenue = $vehicle->reservations->sum('total_price');
                return $vehicle;
            });
    }

    public function getRevenueByPeriodProperty()
    {
        return Reservation::where('status', 'completed')
            ->whereBetween('created_at', [$this->dateFrom, $this->dateTo])
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_price) as revenue')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }

    public function getReservationStatusStatsProperty()
    {
        $reservations = Reservation::whereBetween('created_at', [$this->dateFrom, $this->dateTo])
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get()
            ->pluck('total', 'status')
            ->toArray();

        return [
            'pending' => $reservations['pending'] ?? 0,
            'confirmed' => $reservations['confirmed'] ?? 0,
            'active' => $reservations['active'] ?? 0,
            'completed' => $reservations['completed'] ?? 0,
            'cancelled' => $reservations['cancelled'] ?? 0,
        ];
    }

    public function downloadExcel()
    {
        return Excel::download(
            new ReservationsExport($this->dateFrom, $this->dateTo),
            'reporte-reservas-' . date('Y-m-d') . '.xlsx'
        );
    }

    public function downloadPDF()
    {
        $pdf = Pdf::loadView('reports.pdf', [
            'generalStats' => $this->generalStats,
            'topVehicles' => $this->topVehicles,
            'statusStats' => $this->reservationStatusStats,
            'dateFrom' => $this->dateFrom,
            'dateTo' => $this->dateTo,
        ]);

        return $pdf->download('reporte-reservas-' . date('Y-m-d') . '.pdf');
    }

    public function render()
    {
        return view('livewire.reports-manager', [
            'generalStats' => $this->generalStats,
            'topVehicles' => $this->topVehicles,
            'revenueByPeriod' => $this->revenueByPeriod,
            'statusStats' => $this->reservationStatusStats,
        ])->layout('components.admin-layout');
    }
}
