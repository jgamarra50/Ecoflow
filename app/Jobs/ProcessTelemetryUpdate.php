<?php

namespace App\Jobs;

use App\Models\Telemetry;
use App\Models\Vehicle;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessTelemetryUpdate implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $vehicle;

    /**
     * Create a new job instance.
     */
    public function __construct(Vehicle $vehicle)
    {
        $this->vehicle = $vehicle;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Get latest telemetry or create default values
        $latestTelemetry = $this->vehicle->telemetries()->latest()->first();

        // Calculate new values with simulation
        $newBatteryLevel = $this->calculateBatteryLevel($latestTelemetry);
        $newLatitude = $this->calculateLatitude($latestTelemetry);
        $newLongitude = $this->calculateLongitude($latestTelemetry);
        $newKilometers = $this->calculateKilometers($latestTelemetry);

        // Create new telemetry record
        $telemetry = Telemetry::create([
            'vehicle_id' => $this->vehicle->id,
            'battery_level' => $newBatteryLevel,
            'latitude' => $newLatitude,
            'longitude' => $newLongitude,
            'kilometers' => $newKilometers,
        ]);

        Log::info("Telemetry updated for vehicle {$this->vehicle->id}", [
            'battery' => $newBatteryLevel,
            'location' => [$newLatitude, $newLongitude],
            'km' => $newKilometers,
        ]);
    }

    /**
     * Calculate new battery level (±5%)
     */
    private function calculateBatteryLevel($latestTelemetry): int
    {
        $currentLevel = $latestTelemetry ? $latestTelemetry->battery_level : 100;
        
        // For active/reserved vehicles, decrease battery more
        $change = $this->vehicle->status === 'reserved' 
            ? rand(-5, -1) // Decrease 1-5% if in use
            : rand(-2, 2); // Small fluctuation if idle
        
        $newLevel = $currentLevel + $change;
        
        // Clamp between 0 and 100
        return max(0, min(100, $newLevel));
    }

    /**
     * Calculate new latitude (±0.001°)
     */
    private function calculateLatitude($latestTelemetry): float
    {
        // Use station location as base if no telemetry
        $currentLat = $latestTelemetry 
            ? $latestTelemetry->latitude 
            : ($this->vehicle->station ? $this->vehicle->station->latitude : 7.1193);
        
        // Only move if vehicle is reserved/active
        if ($this->vehicle->status === 'reserved') {
            $change = (rand(-10, 10) / 10000); // ±0.001°
            return round($currentLat + $change, 6);
        }
        
        return $currentLat;
    }

    /**
     * Calculate new longitude (±0.001°)
     */
    private function calculateLongitude($latestTelemetry): float
    {
        // Use station location as base if no telemetry
        $currentLng = $latestTelemetry 
            ? $latestTelemetry->longitude 
            : ($this->vehicle->station ? $this->vehicle->station->longitude : -73.1227);
        
        // Only move if vehicle is reserved/active
        if ($this->vehicle->status === 'reserved') {
            $change = (rand(-10, 10) / 10000); // ±0.001°
            return round($currentLng + $change, 6);
        }
        
        return $currentLng;
    }

    /**
     * Calculate new kilometers (+0.5 km if moving)
     */
    private function calculateKilometers($latestTelemetry): float
    {
        $currentKm = $latestTelemetry ? $latestTelemetry->kilometers : 0;
        
        // Add distance if vehicle is reserved/active
        if ($this->vehicle->status === 'reserved') {
            $addition = rand(3, 7) / 10; // 0.3 to 0.7 km per update
            return round($currentKm + $addition, 1);
        }
        
        return $currentKm;
    }
}
