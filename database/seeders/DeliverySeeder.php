<?php

namespace Database\Seeders;

use App\Models\Delivery;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Database\Seeder;

class DeliverySeeder extends Seeder
{
    public function run(): void
    {
        // Get repartidores
        $repartidor1 = User::where('email', 'repartidor1@test.com')->first();
        $repartidor2 = User::where('email', 'repartidor2@test.com')->first();

        // Get reservations that need delivery
        $reservations = Reservation::where('delivery_method', 'delivery')
            ->orWhere('delivery_method', 'pickup')
            ->get();

        foreach ($reservations as $reservation) {
            // Create delivery for delivery_method = 'delivery'
            if ($reservation->delivery_method === 'delivery') {
                Delivery::create([
                    'reservation_id' => $reservation->id,
                    'delivery_person_id' => rand(0, 1) ? $repartidor1->id : $repartidor2->id,
                    'type' => 'delivery',
                    'status' => ['pending', 'assigned', 'in_transit', 'delivered'][rand(0, 3)],
                    'scheduled_time' => $reservation->start_date->subHours(2),
                    'actual_delivery_time' => rand(0, 1) ? $reservation->start_date->subHour() : null,
                    'delivery_address' => $reservation->delivery_address ?? 'Dirección de ejemplo',
                    'delivery_lat' => 7.0799 + (rand(-100, 100) / 10000),
                    'delivery_lng' => -73.0978 + (rand(-100, 100) / 10000),
                    'delivery_fee' => 10000,
                    'notes' => rand(0, 1) ? 'Entrega exitosa' : null,
                ]);
            }

            // Create pickup for delivery_method = 'pickup'
            if ($reservation->delivery_method === 'pickup') {
                Delivery::create([
                    'reservation_id' => $reservation->id,
                    'delivery_person_id' => rand(0, 1) ? $repartidor1->id : $repartidor2->id,
                    'type' => 'pickup',
                    'status' => ['pending', 'assigned'][rand(0, 1)],
                    'scheduled_time' => $reservation->end_date->addHours(1),
                    'delivery_address' => $reservation->delivery_address ?? 'Dirección de ejemplo',
                    'delivery_lat' => 7.0799 + (rand(-100, 100) / 10000),
                    'delivery_lng' => -73.0978 + (rand(-100, 100) / 10000),
                    'delivery_fee' => 10000,
                ]);
            }
        }

        // Create some standalone deliveries
        $additionalDeliveries = [
            [
                'reservation_id' => $reservations->first()->id,
                'delivery_person_id' => $repartidor1->id,
                'type' => 'delivery',
                'status' => 'assigned',
                'scheduled_time' => now()->addHours(2),
                'delivery_address' => 'Carrera 27 #45-30, Bucaramanga',
                'delivery_lat' => 7.1197,
                'delivery_lng' => -73.1227,
                'delivery_fee' => 10000,
            ],
            [
                'reservation_id' => $reservations->skip(1)->first()->id,
                'delivery_person_id' => $repartidor2->id,
                'type' => 'pickup',
                'status' => 'in_transit',
                'scheduled_time' => now()->addHour(),
                'delivery_address' => 'Calle 56 #34-21, Piedecuesta',
                'delivery_lat' => 7.0621,
                'delivery_lng' => -73.0868,
                'delivery_fee' => 10000,
            ],
        ];

        foreach ($additionalDeliveries as $deliveryData) {
            Delivery::create($deliveryData);
        }
    }
}
