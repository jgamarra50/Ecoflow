<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $stations = [
            [
                'name' => 'Estación Cañaveral',
                'address' => 'Centro Comercial Cañaveral, Floridablanca',
                'city' => 'cañaveral',
                'capacity' => 30,
                'lat' => 7.0799,
                'lng' => -73.0978,
            ],
            [
                'name' => 'Estación Cabecera',
                'address' => 'Parque San Pío, Cabecera del Llano',
                'city' => 'cabecera',
                'capacity' => 25,
                'lat' => 7.1197,
                'lng' => -73.1227,
            ],
            [
                'name' => 'Estación Floridablanca Centro',
                'address' => 'Parque Principal de Floridablanca',
                'city' => 'floridablanca',
                'capacity' => 20,
                'lat' => 7.0621,
                'lng' => -73.0868,
            ],
            [
                'name' => 'Estación Piedecuesta',
                'address' => 'Parque Principal de Piedecuesta',
                'city' => 'piedecuesta',
                'capacity' => 15,
                'lat' => 6.9733,
                'lng' => -73.0507,
            ],
        ];

        DB::table('stations')->insert($stations);
    }
}
