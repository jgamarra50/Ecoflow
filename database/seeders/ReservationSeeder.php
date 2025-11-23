<?php

namespace Database\Seeders;

use App\Models\Reservation;
use App\Models\Vehicle;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ReservationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cliente1 = User::where('email', 'cliente1@test.com')->first();
        $cliente2 = User::where('email', 'cliente2@test.com')->first();
        
        $vehicles = Vehicle::all();

        $reservations = [
            // Reservas activas
            [
                'user_id' => $cliente1->id,
                'vehicle_id' => $vehicles[0]->id,
                'start_date' => Carbon::now()->subDays(1),
                'end_date' => Carbon::now()->addDays(2),
                'delivery_method' => 'pickup',
                'station_id' => $vehicles[0]->station_id,
                'status' => 'active',
                'total_price' => 150000,
            ],
            [
                'user_id' => $cliente2->id,
                'vehicle_id' => $vehicles[1]->id,
                'start_date' => Carbon::now()->subHours(12),
                'end_date' => Carbon::now()->addDays(1),
                'delivery_method' => 'delivery',
                'delivery_address' => 'Calle 45 #23-10, Bucaramanga',
                'station_id' => $vehicles[1]->station_id,
                'status' => 'active',
                'total_price' => 120000,
            ],

            // Reservas completadas este mes
            [
                'user_id' => $cliente1->id,
                'vehicle_id' => $vehicles[2]->id,
                'start_date' => Carbon::now()->subDays(10),
                'end_date' => Carbon::now()->subDays(7),
                'delivery_method' => 'pickup',
                'station_id' => $vehicles[2]->station_id,
                'status' => 'completed',
                'total_price' => 200000,
            ],
            [
                'user_id' => $cliente2->id,
                'vehicle_id' => $vehicles[0]->id,
                'start_date' => Carbon::now()->subDays(15),
                'end_date' => Carbon::now()->subDays(12),
                'delivery_method' => 'delivery',
                'delivery_address' => 'Carrera 27 #52-12, Floridablanca',
                'station_id' => $vehicles[0]->station_id,
                'status' => 'completed',
                'total_price' => 180000,
            ],
            [
                'user_id' => $cliente1->id,
                'vehicle_id' => $vehicles[3]->id,
                'start_date' => Carbon::now()->subDays(5),
                'end_date' => Carbon::now()->subDays(3),
                'delivery_method' => 'pickup',
                'station_id' => $vehicles[3]->station_id,
                'status' => 'completed',
                'total_price' => 100000,
            ],

            // Reservas pendientes/confirmadas
            [
                'user_id' => $cliente2->id,
                'vehicle_id' => $vehicles[4]->id,
                'start_date' => Carbon::now()->addDays(2),
                'end_date' => Carbon::now()->addDays(5),
                'delivery_method' => 'delivery',
                'delivery_address' => 'Calle 56 #34-21, Piedecuesta',
                'station_id' => $vehicles[4]->station_id,
                'status' => 'confirmed',
                'total_price' => 190000,
            ],
            [
                'user_id' => $cliente1->id,
                'vehicle_id' => $vehicles[2]->id,
                'start_date' => Carbon::now()->addDays(7),
                'end_date' => Carbon::now()->addDays(9),
                'delivery_method' => 'pickup',
                'station_id' => $vehicles[2]->station_id,
                'status' => 'pending',
                'total_price' => 130000,
            ],
        ];

        foreach ($reservations as $reservation) {
            Reservation::create($reservation);
        }
    }
}
