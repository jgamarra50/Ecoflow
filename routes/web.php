<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    
    Route::get('/reservations/new', function () {
        return view('reservations.new');
    })->name('reservations.new');

    Route::get('/reservations', \App\Livewire\MyReservations::class)
        ->name('reservations.index');
    
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
});

// Technician Routes
Route::middleware(['auth', 'verified', 'role:tecnico'])->prefix('technician')->group(function () {
    Route::get('/dashboard', \App\Livewire\TechnicianDashboard::class)->name('technician.dashboard');
    Route::get('/vehicles', \App\Livewire\TechnicianVehicles::class)->name('technician.vehicles');
    Route::get('/history', \App\Livewire\TechnicianHistory::class)->name('technician.history');
});

require __DIR__.'/auth.php';
