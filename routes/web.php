<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Public Map Route (Rate limited)
Route::middleware('throttle:60,1')->group(function () {
    Route::get('/map', \App\Livewire\PublicMap::class)->name('public.map');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard con redirección por rol
    Route::get('/dashboard', function () {
        $user = auth()->user();
        
        // Redirigir según el rol del usuario
        if ($user->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->hasRole('operador')) {
            return redirect()->route('operator.dashboard');
        } elseif ($user->hasRole('tecnico')) {
            return redirect()->route('technician.dashboard');
        } else {
            // Por defecto, cliente
            return view('dashboard');
        }
    })->name('dashboard');
    
    Route::get('/reservations/new', function () {
        return view('reservations.new');
    })->name('reservations.new');

    Route::get('/reservations', \App\Livewire\MyReservations::class)
        ->name('reservations.index');
    
    
    // Reservation Tracking Route (Protected by ownership)
    Route::middleware('reservation.owner')->group(function () {
        Route::get('/reservations/{id}/track', \App\Livewire\ReservationTracking::class)->name('reservations.track');
    });

    Route::get('/reservations/{reservation}/receipt', function ($reservationId) {
        $reservation = \App\Models\Reservation::with(['vehicle', 'station', 'user'])
            ->findOrFail($reservationId);
        
        // Verify ownership
        if ($reservation->user_id !== auth()->id()) {
            abort(403);
        }
        
        return view('reservations.receipt', compact('reservation'));
    })->name('reservations.receipt');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin Routes
Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', \App\Livewire\AdminDashboard::class)->name('admin.dashboard');
    Route::get('/vehicles', \App\Livewire\VehiclesManager::class)->name('admin.vehicles');
    Route::get('/users', \App\Livewire\UsersManager::class)->name('admin.users');
    Route::get('/maintenances', \App\Livewire\MaintenancesManager::class)->name('admin.maintenances');
    Route::get('/reports', \App\Livewire\ReportsManager::class)->name('admin.reports');
    Route::get('/telemetry', \App\Livewire\AdminTelemetry::class)->name('admin.telemetry');
});

// Technician Routes
Route::middleware(['auth', 'verified', 'role:tecnico'])->prefix('technician')->group(function () {
    Route::get('/dashboard', \App\Livewire\TechnicianDashboard::class)->name('technician.dashboard');
    Route::get('/vehicles', \App\Livewire\TechnicianVehicles::class)->name('technician.vehicles');
    Route::get('/history', \App\Livewire\TechnicianHistory::class)->name('technician.history');
});

// Operator Routes
Route::middleware(['auth', 'verified', 'role:operador'])->prefix('operator')->group(function () {
    Route::get('/dashboard', \App\Livewire\OperatorDashboard::class)->name('operator.dashboard');
    Route::get('/deliveries', \App\Livewire\OperatorDeliveries::class)->name('operator.deliveries');
    Route::get('/reports', \App\Livewire\OperatorReports::class)->name('operator.reports');
});

require __DIR__.'/auth.php';
