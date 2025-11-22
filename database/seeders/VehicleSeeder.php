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
            // Cañaveral Station (ID: 1) - 2 vehicles
            ['type' => 'scooter', 'brand' => 'EcoFlow', 'model' => 'EcoMoto', 'plate' => 'ECO-001', 'status' => 'available', 'current_location_lat' => 7.0799, 'current_location_lng' => -73.0978, 'station_id' => 1],
            ['type' => 'scooter', 'brand' => 'EcoFlow', 'model' => 'EcoScoot Lite', 'plate' => 'ECO-002', 'status' => 'available', 'current_location_lat' => 7.0799, 'current_location_lng' => -73.0978, 'station_id' => 1],
            
            // Cabecera Station (ID: 2) - 2 vehicles
            ['type' => 'scooter', 'brand' => 'EcoFlow', 'model' => 'EcoMoto Pro', 'plate' => 'ECO-003', 'status' => 'available', 'current_location_lat' => 7.1197, 'current_location_lng' => -73.1227, 'station_id' => 2],
            ['type' => 'bicycle', 'brand' => 'EcoFlow', 'model' => 'EcoBike One', 'plate' => 'ECO-004', 'status' => 'available', 'current_location_lat' => 7.1197, 'current_location_lng' => -73.1227, 'station_id' => 2],
            
            // Floridablanca Station (ID: 3) - 1 vehicle
            ['type' => 'scooter', 'brand' => 'EcoFlow', 'model' => 'EcoScoot Max', 'plate' => 'ECO-005', 'status' => 'available', 'current_location_lat' => 7.0621, 'current_location_lng' => -73.0868, 'station_id' => 3],
        ];

        DB::table('vehicles')->insert($vehicles);
    }
}
