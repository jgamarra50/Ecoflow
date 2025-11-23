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

    public function telemetry()
    {
        return $this->hasOne(Telemetry::class)->latestOfMany();
    }

    public function latestTelemetry()
    {
        return $this->telemetry();
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
            'in_use' => 'En Uso',
            'charging' => 'Cargando',
            default => ucfirst($this->status),
        };
    }

    public function getStatusColorClass()
    {
        return match($this->status) {
            'available' => 'bg-green-100 text-green-800 border-green-300',
            'in_use' => 'bg-blue-100 text-blue-800 border-blue-300',
            'reserved' => 'bg-purple-100 text-purple-800 border-purple-300',
            'maintenance' => 'bg-yellow-100 text-yellow-800 border-yellow-300',
            'charging' => 'bg-cyan-100 text-cyan-800 border-cyan-300',
            'damaged' => 'bg-red-100 text-red-800 border-red-300',
            default => 'bg-gray-100 text-gray-800 border-gray-300',
        };
    }
}
