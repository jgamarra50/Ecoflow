<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TelemetryController extends Controller
{
    /**
     * Get telemetry data for a specific vehicle
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id): JsonResponse
    {
        $vehicle = Vehicle::with(['latestTelemetry', 'station'])
            ->findOrFail($id);

        $telemetry = $vehicle->latestTelemetry;

        if (!$telemetry) {
            return response()->json([
                'success' => false,
                'message' => 'No telemetry data available for this vehicle',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'vehicle_id' => $vehicle->id,
                'vehicle' => [
                    'brand' => $vehicle->brand,
                    'model' => $vehicle->model,
                    'plate' => $vehicle->plate,
                    'type' => $vehicle->type,
                    'status' => $vehicle->status,
                ],
                'telemetry' => [
                    'battery_level' => $telemetry->battery_level,
                    'latitude' => $telemetry->latitude,
                    'longitude' => $telemetry->longitude,
                    'kilometers' => $telemetry->kilometers,
                    'recorded_at' => $telemetry->created_at->toIso8601String(),
                ],
                'station' => $vehicle->station ? [
                    'id' => $vehicle->station->id,
                    'name' => $vehicle->station->name,
                    'latitude' => $vehicle->station->latitude,
                    'longitude' => $vehicle->station->longitude,
                ] : null,
            ],
            'timestamp' => now()->toIso8601String(),
        ]);
    }

    /**
     * Get telemetry history for a vehicle
     *
     * @param  int  $id
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function history($id, Request $request): JsonResponse
    {
        $vehicle = Vehicle::findOrFail($id);

        $limit = $request->input('limit', 10);
        $limit = min($limit, 100); // Max 100 records

        $telemetries = $vehicle->telemetries()
            ->latest()
            ->limit($limit)
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'vehicle_id' => $vehicle->id,
                'count' => $telemetries->count(),
                'telemetries' => $telemetries->map(function ($t) {
                    return [
                        'id' => $t->id,
                        'battery_level' => $t->battery_level,
                        'latitude' => $t->latitude,
                        'longitude' => $t->longitude,
                        'kilometers' => $t->kilometers,
                        'recorded_at' => $t->created_at->toIso8601String(),
                    ];
                }),
            ],
            'timestamp' => now()->toIso8601String(),
        ]);
    }

    /**
     * Get telemetry data for all vehicles
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): JsonResponse
    {
        $vehicles = Vehicle::with(['latestTelemetry', 'station'])
            ->where('status', '!=', 'maintenance')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $vehicles->map(function ($vehicle) {
                $telemetry = $vehicle->latestTelemetry;
                
                return [
                    'vehicle_id' => $vehicle->id,
                    'vehicle' => [
                        'brand' => $vehicle->brand,
                        'model' => $vehicle->model,
                        'plate' => $vehicle->plate,
                        'type' => $vehicle->type,
                        'status' => $vehicle->status,
                    ],
                    'telemetry' => $telemetry ? [
                        'battery_level' => $telemetry->battery_level,
                        'latitude' => $telemetry->latitude,
                        'longitude' => $telemetry->longitude,
                        'kilometers' => $telemetry->kilometers,
                        'recorded_at' => $telemetry->created_at->toIso8601String(),
                    ] : null,
                    'station' => $vehicle->station ? [
                        'name' => $vehicle->station->name,
                        'latitude' => $vehicle->station->latitude,
                        'longitude' => $vehicle->station->longitude,
                    ] : null,
                ];
            }),
            'count' => $vehicles->count(),
            'timestamp' => now()->toIso8601String(),
        ]);
    }
}
