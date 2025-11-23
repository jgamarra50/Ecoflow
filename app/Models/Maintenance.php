<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Maintenance extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_id',
        'technician_id',
        'created_by',
        'title',
        'description',
        'status',
        'priority',
        'cost',
        'assigned_at',
        'started_at',
        'completed_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'assigned_at' => 'datetime',
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
            'cost' => 'decimal:2',
        ];
    }

    // Relationships
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function technician()
    {
        return $this->belongsTo(User::class, 'technician_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Helper methods
    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function canBeAssigned()
    {
        return in_array($this->status, ['pending', 'assigned']);
    }

    public function getStatusLabel()
    {
        return match($this->status) {
            'pending' => 'Pendiente',
            'assigned' => 'Asignado',
            'in_progress' => 'En Progreso',
            'completed' => 'Completado',
            'cancelled' => 'Cancelado',
            default => ucfirst($this->status),
        };
    }

    public function getStatusColorClass()
    {
        return match($this->status) {
            'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-300',
            'assigned' => 'bg-blue-100 text-blue-800 border-blue-300',
            'in_progress' => 'bg-indigo-100 text-indigo-800 border-indigo-300',
            'completed' => 'bg-green-100 text-green-800 border-green-300',
            'cancelled' => 'bg-red-100 text-red-800 border-red-300',
            default => 'bg-gray-100 text-gray-800 border-gray-300',
        };
    }

    public function getPriorityLabel()
    {
        return match($this->priority) {
            'low' => 'Baja',
            'medium' => 'Media',
            'high' => 'Alta',
            'urgent' => 'Urgente',
            default => ucfirst($this->priority),
        };
    }

    public function getPriorityColorClass()
    {
        return match($this->priority) {
            'low' => 'bg-gray-100 text-gray-800 border-gray-300',
            'medium' => 'bg-blue-100 text-blue-800 border-blue-300',
            'high' => 'bg-orange-100 text-orange-800 border-orange-300',
            'urgent' => 'bg-red-100 text-red-800 border-red-300',
            default => 'bg-gray-100 text-gray-800 border-gray-300',
        };
    }
}
