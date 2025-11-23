<?php

namespace App\Policies;

use App\Models\Maintenance;
use App\Models\User;

class MaintenancePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Admins, technicians, and operators can view maintenance tickets
        return in_array($user->role, ['admin', 'tecnico', 'operador']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Maintenance $maintenance): bool
    {
        // Admins can view all, technicians can view assigned tickets, operators can view all
        if ($user->role === 'admin' || $user->role === 'operador') {
            return true;
        }

        // Technicians can only view their assigned tickets
        if ($user->role === 'tecnico') {
            return $maintenance->assigned_to === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Admins, technicians, and operators can create maintenance tickets
        return in_array($user->role, ['admin', 'tecnico', 'operador']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Maintenance $maintenance): bool
    {
        // Admins can update all tickets
        if ($user->role === 'admin') {
            return true;
        }

        // Technicians can only update tickets assigned to them
        if ($user->role === 'tecnico') {
            return $maintenance->assigned_to === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Maintenance $maintenance): bool
    {
        // Only admins can delete maintenance tickets
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can assign the maintenance to a technician.
     */
    public function assign(User $user, Maintenance $maintenance): bool
    {
        // Only admins can assign tickets
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can complete the maintenance.
     */
    public function complete(User $user, Maintenance $maintenance): bool
    {
        // Admins can complete any ticket
        if ($user->role === 'admin') {
            return true;
        }

        // Technicians can complete their assigned tickets
        if ($user->role === 'tecnico') {
            return $maintenance->assigned_to === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Maintenance $maintenance): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Maintenance $maintenance): bool
    {
        return $user->role === 'admin';
    }
}
