<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Vehicle;

class VehiclePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // All authenticated users can view vehicles
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Vehicle $vehicle): bool
    {
        // All authenticated users can view individual vehicles
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Only admins can create vehicles
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Vehicle $vehicle): bool
    {
        // Only admins and technicians can update vehicles
        return in_array($user->role, ['admin', 'tecnico']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Vehicle $vehicle): bool
    {
        // Only admins can delete vehicles
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can perform maintenance.
     */
    public function performMaintenance(User $user, Vehicle $vehicle): bool
    {
        // Technicians and admins can perform maintenance
        return in_array($user->role, ['admin', 'tecnico']);
    }

    /**
     * Determine whether the user can change vehicle status.
     */
    public function changeStatus(User $user, Vehicle $vehicle): bool
    {
        // Admins, technicians, and operators can change status
        return in_array($user->role, ['admin', 'tecnico', 'operador']);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Vehicle $vehicle): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Vehicle $vehicle): bool
    {
        return $user->role === 'admin';
    }
}
