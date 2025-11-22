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

    public function canBeCancelled()
    {
        return in_array($this->status, ['pending', 'confirmed']);
    }

    public function getDurationInDays()
    {
        return max(1, $this->start_date->diffInDays($this->end_date));
    }

    public function getFormattedDates()
    {
        return [
            'start' => $this->start_date->format('d M Y, H:i'),
            'end' => $this->end_date->format('d M Y, H:i'),
            'start_short' => $this->start_date->format('d/m/Y'),
            'end_short' => $this->end_date->format('d/m/Y'),
        ];
    }

    public function getPriceBreakdown()
    {
        $days = $this->getDurationInDays();
        $basePrice = $days * 50000; // $50,000 COP per day
        $deliveryFee = ($this->delivery_method === 'delivery') ? 10000 : 0;
        $pickupFee = 0; // Could add return pickup fee logic here
        
        return [
            'days' => $days,
            'base_price' => $basePrice,
            'delivery_fee' => $deliveryFee,
            'pickup_fee' => $pickupFee,
            'total' => $this->total_price,
        ];
    }
}
