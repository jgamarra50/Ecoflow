<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TelemetrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $telemetries = [];
        
        // Create telemetry data for all 20 vehicles
        for ($i = 1; $i <= 20; $i++) {
            $telemetries[] = [
                'vehicle_id' => $i,
                'battery_level' => rand(20, 100), // Random battery between 20-100%
                'speed' => rand(0, 25), // Random speed 0-25 km/h
                'distance_traveled' => rand(0, 50), // Random distance 0-50 km
                'last_ping_at' => Carbon::now()->subMinutes(rand(1, 30)),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('telemetries')->insert($telemetries);
    }
}
