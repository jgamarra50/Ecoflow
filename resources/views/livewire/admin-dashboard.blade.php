<div class="py-8 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Header --}}
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Dashboard de Administrador</h1>
            <p class="mt-2 text-gray-600">Resumen general del sistema de movilidad</p>
        </div>

        {{-- Metrics Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            {{-- Total Vehículos --}}
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Vehículos</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalVehicles }}</p>
                    </div>
                    <div class="bg-blue-100 rounded-full p-3">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Reservas Activas --}}
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Reservas Activas</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $activeReservations }}</p>
                    </div>
                    <div class="bg-green-100 rounded-full p-3">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- En Mantenimiento --}}
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-yellow-500">
                <div>
                    <p class="text-sm font-medium text-gray-600">En Mantenimiento</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $maintenanceVehicles }}</p>
                </div>
                <div class="flex items-center justify-between">
                    <div class="bg-yellow-100 rounded-full p-3 mt-2">
                        <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Ingresos del Mes --}}
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Ingresos del Mes</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">${{ number_format($monthlyRevenue, 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-purple-100 rounded-full p-3">
                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            {{-- Chart.js - Reservas por Modelo --}}
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Reservas por Modelo EcoFlow</h2>
                <canvas id="reservationsChart" class="w-full" style="max-height: 300px;"></canvas>
            </div>

            {{-- Alertas --}}
            <div class="space-y-4">
                {{-- Batería Baja --}}
                <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-red-500">
                    <h3 class="text-lg font-bold text-gray-900 mb-3 flex items-center">
                        <svg class="w-5 h-5 text-red-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                        Vehículos con Batería Baja
                    </h3>
                    @if($lowBatteryVehicles->count() > 0)
                        <div class="space-y-2">
                            @foreach($lowBatteryVehicles as $vehicle)
                                <div class="flex items-center justify-between p-3 bg-red-50 rounded-md">
                                    <div class="flex items-center">
                                        <span class="text-2xl mr-3">{{ $vehicle->getTypeIcon() }}</span>
                                        <div>
                                            <p class="font-medium text-gray-900">{{ $vehicle->brand }} {{ $vehicle->model }}</p>
                                            <p class="text-sm text-gray-600">{{ $vehicle->plate }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-lg font-bold text-red-600">{{ $vehicle->battery_level }}%</p>
                                        <p class="text-xs text-gray-500">Batería</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-sm">✅ Todos los vehículos tienen batería suficiente</p>
                    @endif
                </div>

                {{-- Mantenimientos Pendientes --}}
                <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-yellow-500">
                    <h3 class="text-lg font-bold text-gray-900 mb-3 flex items-center">
                        <svg class="w-5 h-5 text-yellow-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                        </svg>
                        Mantenimientos Pendientes
                    </h3>
                    @if($pendingMaintenance->count() > 0)
                        <div class="space-y-2">
                            @foreach($pendingMaintenance as $vehicle)
                                <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-md">
                                    <div class="flex items-center">
                                        <span class="text-2xl mr-3">{{ $vehicle->getTypeIcon() }}</span>
                                        <div>
                                            <p class="font-medium text-gray-900">{{ $vehicle->brand }} {{ $vehicle->model }}</p>
                                            <p class="text-sm text-gray-600">{{ $vehicle->plate }}</p>
                                        </div>
                                    </div>
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $vehicle->status === 'maintenance' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $vehicle->status === 'maintenance' ? 'Mantenimiento' : 'Dañado' }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-sm">✅ No hay vehículos pendientes de mantenimiento</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Últimas Reservas --}}
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-bold text-gray-900">Últimas Reservas</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usuario</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vehículo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fechas</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Precio</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($recentReservations as $reservation)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    #{{ str_pad($reservation->id, 4, '0', STR_PAD_LEFT) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $reservation->user->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $reservation->user->email }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <span class="text-2xl mr-2">{{ $reservation->vehicle->getTypeIcon() }}</span>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $reservation->vehicle->brand }} {{ $reservation->vehicle->model }}</div>
                                            <div class="text-sm text-gray-500">{{ $reservation->vehicle->plate }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <div>{{ $reservation->start_date->format('d/m/Y') }}</div>
                                    <div>{{ $reservation->end_date->format('d/m/Y') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $reservation->getStatusColorClass() }}">
                                        {{ $reservation->getStatusLabel() }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    ${{ number_format($reservation->total_price, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                    No hay reservas registradas aún
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Chart.js Script --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('reservationsChart').getContext('2d');
            const data = @json($reservationsByModel);
            
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['EcoMoto', 'EcoMoto Pro', 'EcoScoot Lite', 'EcoScoot Max', 'EcoBike One'],
                    datasets: [{
                        label: 'Número de Reservas',
                        data: [
                            data['EcoMoto'], 
                            data['EcoMoto Pro'], 
                            data['EcoScoot Lite'], 
                            data['EcoScoot Max'], 
                            data['EcoBike One']
                        ],
                        backgroundColor: [
                            'rgba(59, 130, 246, 0.8)',   // Blue - EcoMoto
                            'rgba(16, 185, 129, 0.8)',   // Green - EcoMoto Pro
                            'rgba(249, 115, 22, 0.8)',   // Orange - EcoScoot Lite
                            'rgba(168, 85, 247, 0.8)',   // Purple - EcoScoot Max
                            'rgba(236, 72, 153, 0.8)',   // Pink - EcoBike One
                        ],
                        borderColor: [
                            'rgba(59, 130, 246, 1)',
                            'rgba(16, 185, 129, 1)',
                            'rgba(249, 115, 22, 1)',
                            'rgba(168, 85, 247, 1)',
                            'rgba(236, 72, 153, 1)',
                        ],
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });
        });
    </script>
</div>
