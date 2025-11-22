<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vehicle;
use App\Models\Telemetry;
use Carbon\Carbon;

class TelemetrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vehicles = Vehicle::all();
        
        foreach ($vehicles as $vehicle) {
            Telemetry::create([
                'vehicle_id' => $vehicle->id,
                'battery_level' => rand(70, 100), // Random battery between 70-100%
                'speed' => 0, // Vehicle is stationary
                'distance_traveled' => rand(5, 50), // Random distance 5-50 km
                'last_ping_at' => Carbon::now()->subMinutes(rand(1, 15)),
            ]);
        }
    }
}
