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
});

require __DIR__.'/auth.php';
