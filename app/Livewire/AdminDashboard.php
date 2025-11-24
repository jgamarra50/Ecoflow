<?php

namespace App\Livewire;

use App\Models\Vehicle;
use App\Models\Reservation;
use App\Models\Telemetry;
use App\Models\Maintenance;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminDashboard extends Component
{
    // Métricas principales
    public function getTotalVehicles()
    {
        return Vehicle::count();
    }

    public function getActiveReservations()
    {
        return Reservation::where('status', 'active')->count();
    }

    public function getMaintenanceVehicles()
    {
        return Vehicle::where('status', 'maintenance')->count();
    }

    public function getMonthlyRevenue()
    {
        // Suma de reservas completadas este mes
        return Reservation::where('status', 'completed')
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('total_price');
    }

    // Datos para gráfico de Chart.js - Por modelo
    public function getReservationsByModel()
    {
        $data = Reservation::join('vehicles', 'reservations.vehicle_id', '=', 'vehicles.id')
            ->select('vehicles.model', DB::raw('count(*) as total'))
            ->groupBy('vehicles.model')
            ->get()
            ->pluck('total', 'model')
            ->toArray();

        return [
            'EcoMoto' => $data['EcoMoto'] ?? 0,
            'EcoMoto Pro' => $data['EcoMoto Pro'] ?? 0,
            'EcoScoot Lite' => $data['EcoScoot Lite'] ?? 0,
            'EcoScoot Max' => $data['EcoScoot Max'] ?? 0,
            'EcoBike One' => $data['EcoBike One'] ?? 0,
        ];
    }

    // Alertas - Vehículos con batería baja
    public function getLowBatteryVehicles()
    {
        return Vehicle::join('telemetries', 'vehicles.id', '=', 'telemetries.vehicle_id')
            ->select('vehicles.*', 'telemetries.battery_level')
            ->where('telemetries.battery_level', '<', 30)
            ->where('vehicles.status', 'available')
            ->get();
    }

    // Alertas - Vehículos que necesitan mantenimiento
    public function getPendingMaintenance()
    {
        return Maintenance::with('vehicle')
            ->whereIn('status', ['pending', 'assigned', 'in_progress'])
            ->latest()
            ->get();
    }

    // Últimas reservas
    public function getRecentReservations()
    {
        return Reservation::with(['user', 'vehicle'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
    }

    public function render()
    {
        return view('livewire.admin-dashboard', [
            'totalVehicles' => $this->getTotalVehicles(),
            'activeReservations' => $this->getActiveReservations(),
            'maintenanceVehicles' => $this->getMaintenanceVehicles(),
            'monthlyRevenue' => $this->getMonthlyRevenue(),
            'reservationsByModel' => $this->getReservationsByModel(),
            'lowBatteryVehicles' => $this->getLowBatteryVehicles(),
            'pendingMaintenance' => $this->getPendingMaintenance(),
            'recentReservations' => $this->getRecentReservations(),
        ])->layout('components.admin-layout');
    }
}
