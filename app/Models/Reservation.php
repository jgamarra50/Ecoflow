<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'vehicle_id',
        'start_date',
        'end_date',
        'delivery_method',
        'delivery_address',
        'station_id',
        'status',
        'total_price',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'datetime',
            'end_date' => 'datetime',
            'total_price' => 'decimal:2',
        ];
    }

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function station()
    {
        return $this->belongsTo(Station::class);
    }

    // Helper methods
    public function isActive()
    {
        return $this->status === 'active';
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isConfirmed()
    {
        return $this->status === 'confirmed';
    }

    public function getStatusColorClass()
    {
        return match($this->status) {
            'active' => 'bg-green-100 text-green-700 border-green-300',
            'confirmed' => 'bg-blue-100 text-blue-700 border-blue-300',
            'pending' => 'bg-yellow-100 text-yellow-700 border-yellow-300',
            'completed' => 'bg-gray-100 text-gray-700 border-gray-300',
            'cancelled' => 'bg-red-100 text-red-700 border-red-300',
            default => 'bg-gray-100 text-gray-700 border-gray-300',
        };
    }

    public function getStatusLabel()
    {
        return match($this->status) {
            'active' => 'Activa',
            'confirmed' => 'Confirmada',
            'pending' => 'Pendiente',
            'completed' => 'Completada',
            'cancelled' => 'Cancelada',
            default => ucfirst($this->status),
        };
    }
}
