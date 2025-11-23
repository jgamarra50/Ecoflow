<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'brand',
        'model',
        'plate',
        'status',
        'current_location_lat',
        'current_location_lng',
        'station_id',
    ];

    protected function casts(): array
    {
        return [
            'current_location_lat' => 'decimal:7',
            'current_location_lng' => 'decimal:7',
        ];
    }

    // Relationships
    public function station()
    {
        return $this->belongsTo(Station::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function telemetries()
    {
        return $this->hasMany(Telemetry::class);
    }

    // Helper methods
    public function isAvailable()
    {
        return $this->status === 'available';
    }

    public function getTypeIcon()
    {
        return match($this->type) {
            'scooter' => '🛴',
            'bicycle' => '🚴',
            'skateboard' => '🛹',
            default => '🚲',
        };
    }

    public function getStatusLabel()
    {
        return match($this->status) {
            'available' => 'Disponible',
            'reserved' => 'Reservado',
            'maintenance' => 'Mantenimiento',
            'damaged' => 'Dañado',
            default => ucfirst($this->status),
        };
    }
}
