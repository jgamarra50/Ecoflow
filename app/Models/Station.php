<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Station extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'city',
        'capacity',
        'lat',
        'lng',
    ];

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}
