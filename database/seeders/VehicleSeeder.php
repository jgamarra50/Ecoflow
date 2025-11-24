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
            
            // Motos Eléctricas - Distributed across stations
            ['type' => 'motorcycle electric', 'brand' => 'EcoFlow', 'model' => 'EcoMoto Thunder', 'plate' => 'ECO-006', 'status' => 'available', 'current_location_lat' => 7.0799, 'current_location_lng' => -73.0978, 'station_id' => 1],
            ['type' => 'motorcycle electric', 'brand' => 'EcoFlow', 'model' => 'SpeedRider Pro', 'plate' => 'ECO-007', 'status' => 'available', 'current_location_lat' => 7.1197, 'current_location_lng' => -73.1227, 'station_id' => 2],
            ['type' => 'motorcycle electric', 'brand' => 'EcoFlow', 'model' => 'CityBike Electric', 'plate' => 'ECO-008', 'status' => 'available', 'current_location_lat' => 7.0621, 'current_location_lng' => -73.0868, 'station_id' => 3],
        ];

        // Asignar imagen aleatoria a cada vehículo según su tipo
        foreach ($vehicles as &$vehicle) {
            $vehicle['image'] = \App\Models\Vehicle::getRandomImageByType($vehicle['type']);
        }

        DB::table('vehicles')->insert($vehicles);
    }
}
