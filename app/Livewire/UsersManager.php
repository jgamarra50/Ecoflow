<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class UsersManager extends Component
{
    use WithPagination;

    // Filtros
    public $roleFilter = 'all';

    // Modal de detalles
    public $showDetailsModal = false;
    public $selectedUserId;

    protected $listeners = ['roleChanged']

;

    public function updatingRoleFilter()
    {
        $this->resetPage();
    }

    public function getUsersProperty()
    {
        $query = User::with('roles');

        // Filtro por rol
        if ($this->roleFilter !== 'all') {
            $query->whereHas('roles', function($q) {
                $q->where('name', $this->roleFilter);
            });
        }

        return $query->latest()->paginate(10);
    }

    public function getSelectedUserProperty()
    {
        if (!$this->selectedUserId) {
            return null;
        }

        return User::with(['reservations.vehicle', 'reservations.station', 'roles'])
            ->find($this->selectedUserId);
    }

    public function getUserStatsProperty()
    {
        if (!$this->selectedUser) {
            return null;
        }

        $reservations = $this->selectedUser->reservations;

        return [
            'total' => $reservations->count(),
            'active' => $reservations->where('status', 'active')->count(),
            'completed' => $reservations->where('status', 'completed')->count(),
            'cancelled' => $reservations->where('status', 'cancelled')->count(),
            'pending' => $reservations->where('status', 'pending')->count(),
        ];
    }

    public function viewDetails($userId)
    {
        $this->selectedUserId = $userId;
        $this->showDetailsModal = true;
    }

    public function changeRole($userId, $newRole)
    {
        $user = User::findOrFail($userId);
        
        // No puede cambiar su propio rol
        if ($user->id === auth()->id()) {
            $this->dispatch('role-change-error', message: 'No puedes cambiar tu propio rol');
            return;
        }

        // Sincronizar rol
        $user->syncRoles([$newRole]);
        $user->update(['role' => $newRole]);

        $this->dispatch('role-changed', message: 'Rol actualizado exitosamente');
    }

    public function toggleStatus($userId)
    {
        $user = User::findOrFail($userId);

        // No puede desactivarse a sí mismo
        if ($user->id === auth()->id()) {
            $this->dispatch('toggle-error', message: 'No puedes desactivarte a ti mismo');
            return;
        }

        $user->update(['is_active' => !$user->is_active]);

        $statusText = $user->is_active ? 'activado' : 'desactivado';
        $this->dispatch('status-toggled', message: "Usuario {$statusText} exitosamente");
    }

    public function closeDetailsModal()
    {
        $this->showDetailsModal = false;
        $this->selectedUserId = null;
    }

    public function render()
    {
        return view('livewire.users-manager', [
            'users' => $this->users,
            'selectedUser' => $this->selectedUser,
            'userStats' => $this->userStats,
        ])->layout('components.admin-layout');
    }
}
