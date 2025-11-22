<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VehicleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vehicles = [
            // Cañaveral Station (ID: 1) - 8 vehicles
            ['type' => 'scooter', 'brand' => 'Xiaomi', 'model' => 'Mi Electric Scooter Pro 2', 'plate' => 'ECO-001', 'status' => 'available', 'current_location_lat' => 7.0799, 'current_location_lng' => -73.0978, 'station_id' => 1],
            ['type' => 'scooter', 'brand' => 'Segway', 'model' => 'Ninebot Max', 'plate' => 'ECO-002', 'status' => 'available', 'current_location_lat' => 7.0799, 'current_location_lng' => -73.0978, 'station_id' => 1],
            ['type' => 'bicycle', 'brand' => 'Trek', 'model' => 'FX 3', 'plate' => 'ECO-003', 'status' => 'available', 'current_location_lat' => 7.0799, 'current_location_lng' => -73.0978, 'station_id' => 1],
            ['type' => 'bicycle', 'brand' => 'Giant', 'model' => 'Escape 2', 'plate' => 'ECO-004', 'status' => 'available', 'current_location_lat' => 7.0799, 'current_location_lng' => -73.0978, 'station_id' => 1],
            ['type' => 'skateboard', 'brand' => 'Boosted', 'model' => 'Mini X', 'plate' => 'ECO-005', 'status' => 'available', 'current_location_lat' => 7.0799, 'current_location_lng' => -73.0978, 'station_id' => 1],
            ['type' => 'scooter', 'brand' => 'Ninebot', 'model' => 'E45', 'plate' => 'ECO-006', 'status' => 'reserved', 'current_location_lat' => 7.0799, 'current_location_lng' => -73.0978, 'station_id' => 1],
            ['type' => 'bicycle', 'brand' => 'Specialized', 'model' => 'Sirrus X 3.0', 'plate' => 'ECO-007', 'status' => 'available', 'current_location_lat' => 7.0799, 'current_location_lng' => -73.0978, 'station_id' => 1],
            ['type' => 'scooter', 'brand' => 'Xiaomi', 'model' => 'Pro 3', 'plate' => 'ECO-008', 'status' => 'maintenance', 'current_location_lat' => 7.0799, 'current_location_lng' => -73.0978, 'station_id' => 1],
            
            // Cabecera Station (ID: 2) - 6 vehicles
            ['type' => 'scooter', 'brand' => 'Segway', 'model' => 'Kickscooter ES2', 'plate' => 'ECO-009', 'status' => 'available', 'current_location_lat' => 7.1197, 'current_location_lng' => -73.1227, 'station_id' => 2],
            ['type' => 'bicycle', 'brand' => 'Cannondale', 'model' => 'Quick CX 3', 'plate' => 'ECO-010', 'status' => 'available', 'current_location_lat' => 7.1197, 'current_location_lng' => -73.1227, 'station_id' => 2],
            ['type' => 'skateboard', 'brand' => 'Evolve', 'model' => 'GTR Bamboo', 'plate' => 'ECO-011', 'status' => 'available', 'current_location_lat' => 7.1197, 'current_location_lng' => -73.1227, 'station_id' => 2],
            ['type' => 'scooter', 'brand' => 'Ninebot', 'model' => 'Max G30', 'plate' => 'ECO-012', 'status' => 'available', 'current_location_lat' => 7.1197, 'current_location_lng' => -73.1227, 'station_id' => 2],
            ['type' => 'bicycle', 'brand' => 'Scott', 'model' => 'Sub Cross 50', 'plate' => 'ECO-013', 'status' => 'available', 'current_location_lat' => 7.1197, 'current_location_lng' => -73.1227, 'station_id' => 2],
            ['type' => 'scooter', 'brand' => 'Xiaomi', 'model' => 'Essential', 'plate' => 'ECO-014', 'status' => 'damaged', 'current_location_lat' => 7.1197, 'current_location_lng' => -73.1227, 'station_id' => 2],
            
            // Floridablanca Station (ID: 3) - 4 vehicles
            ['type' => 'bicycle', 'brand' => 'Merida', 'model' => 'Crossway 100', 'plate' => 'ECO-015', 'status' => 'available', 'current_location_lat' => 7.0621, 'current_location_lng' => -73.0868, 'station_id' => 3],
            ['type' => 'scooter', 'brand' => 'Segway', 'model' => 'P100S', 'plate' => 'ECO-016', 'status' => 'available', 'current_location_lat' => 7.0621, 'current_location_lng' => -73.0868, 'station_id' => 3],
            ['type' => 'skateboard', 'brand' => 'Meepo', 'model' => 'V4', 'plate' => 'ECO-017', 'status' => 'available', 'current_location_lat' => 7.0621, 'current_location_lng' => -73.0868, 'station_id' => 3],
            ['type' => 'bicycle', 'brand' => 'Kona', 'model' => 'Dew Plus', 'plate' => 'ECO-018', 'status' => 'available', 'current_location_lat' => 7.0621, 'current_location_lng' => -73.0868, 'station_id' => 3],
            
            // Piedecuesta Station (ID: 4) - 2 vehicles
            ['type' => 'scooter', 'brand' => 'Xiaomi', 'model' => 'M365', 'plate' => 'ECO-019', 'status' => 'available', 'current_location_lat' => 6.9733, 'current_location_lng' => -73.0507, 'station_id' => 4],
            ['type' => 'bicycle', 'brand' => 'Polygon', 'model' => 'Heist X5', 'plate' => 'ECO-020', 'status' => 'available', 'current_location_lat' => 6.9733, 'current_location_lng' => -73.0507, 'station_id' => 4],
        ];

        DB::table('vehicles')->insert($vehicles);
    }
}
