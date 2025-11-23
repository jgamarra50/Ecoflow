<?php

namespace App\Policies;

use App\Models\Reservation;
use App\Models\User;

class ReservationPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // All authenticated users can view their own reservations
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Reservation $reservation): bool
    {
        // User can view if they own the reservation or are admin/operator
        return $user->id === $reservation->user_id || 
               in_array($user->role, ['admin', 'operador']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Only clients can create reservations
        return $user->role === 'cliente';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Reservation $reservation): bool
    {
        // Only admins and operators can update reservations
        return in_array($user->role, ['admin', 'operador']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Reservation $reservation): bool
    {
        // Only admins can delete reservations
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can cancel the model.
     */
    public function cancel(User $user, Reservation $reservation): bool
    {
        // User can cancel their own reservation if it's not already cancelled or completed
        return $user->id === $reservation->user_id &&
               !in_array($reservation->status, ['cancelled', 'completed']);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Reservation $reservation): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Reservation $reservation): bool
    {
        return $user->role === 'admin';
    }
}
