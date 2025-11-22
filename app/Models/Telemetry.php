<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Telemetry extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_id',
        'battery_level',
        'speed',
        'distance_traveled',
        'last_ping_at',
    ];

    protected function casts(): array
    {
        return [
            'last_ping_at' => 'datetime',
        ];
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
}
