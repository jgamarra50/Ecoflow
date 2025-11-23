<div wire:poll.10s class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900">🛰️ Telemetría en Tiempo Real</h1>
            <p class="text-gray-600 mt-1">Monitoreo de vehículos - Actualización automática cada 10 segundos</p>
        </div>

        <!-- Low Battery Alerts -->
        @if(count($lowBatteryAlerts) > 0)
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
                <div class="flex items-center mb-2">
                    <svg class="w-5 h-5 text-red-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                            clip-rule="evenodd" />
                    </svg>
                    <h3 class="text-red-800 font-bold">⚠️ Alertas de Batería Baja ({{ count($lowBatteryAlerts) }})</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-2 mt-3">
                    @foreach($lowBatteryAlerts as $alert)
                        <div class="bg-white border border-red-200 rounded p-3 text-sm">
                            <p class="font-semibold text-gray-900">{{ $alert['name'] }}</p>
                            <p class="text-gray-600">{{ $alert['plate'] }}</p>
                            <p class="text-red-600 font-bold">🪫 {{ $alert['battery'] }}%</p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Vehicles Grid -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
            <div class="p-4 bg-gradient-to-r from-indigo-600 to-blue-600 border-b">
                <h2 class="text-lg font-bold text-white">Vehículos Activos</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Vehículo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Batería</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ubicación</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Distancia</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($vehicles as $vehicle)
                                            <tr class="hover:bg-gray-50 transition-colors">
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center">
                                                        <span class="text-2xl mr-3">{{ $vehicle->getTypeIcon() }}</span>
                                                        <div>
                                                            <div class="text-sm font-medium text-gray-900">{{ $vehicle->brand }}
                                                                {{ $vehicle->model }}</div>
                                                            <div class="text-sm text-gray-500">{{ $vehicle->plate }}</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span
                                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                            {{ $vehicle->status === 'reserved' ? 'bg-green-100 text-green-800' :
                            ($vehicle->status === 'available' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">
                                                        {{ ucfirst($vehicle->status) }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    @if($vehicle->latestTelemetry)
                                                                            <div class="flex items-center">
                                                                                <span class="text-2xl mr-2">
                                                                                    @if($vehicle->latestTelemetry->battery_level > 60)
                                                                                        🔋
                                                                                    @elseif($vehicle->latestTelemetry->battery_level > 30)
                                                                                        🪫
                                                                                    @else
                                                                                        ⚠️
                                                                                    @endif
                                                                                </span>
                                                                                <div class="flex-1">
                                                                                    <div
                                                                                        class="text-sm font-medium 
                                                                                                {{ $vehicle->latestTelemetry->battery_level > 60 ? 'text-green-600' :
                                                        ($vehicle->latestTelemetry->battery_level > 30 ? 'text-yellow-600' : 'text-red-600') }}">
                                                                                        {{ $vehicle->latestTelemetry->battery_level }}%
                                                                                    </div>
                                                                                    <div class="w-24 bg-gray-200 rounded-full h-2 mt-1">
                                                                                        <div class="h-2 rounded-full {{ $vehicle->latestTelemetry->battery_level > 60 ? 'bg-green-500' :
                                                        ($vehicle->latestTelemetry->battery_level > 30 ? 'bg-yellow-500' : 'bg-red-500') }}"
                                                                                            style="width: {{ $vehicle->latestTelemetry->battery_level }}%"></div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                    @else
                                                        <span class="text-gray-400 text-sm">Sin datos</span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    @if($vehicle->latestTelemetry)
                                                        <div class="text-sm text-gray-900">
                                                            {{ number_format($vehicle->latestTelemetry->latitude, 4) }},
                                                            {{ number_format($vehicle->latestTelemetry->longitude, 4) }}
                                                        </div>
                                                    @else
                                                        <span class="text-gray-400 text-sm">-</span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    @if($vehicle->latestTelemetry)
                                                        <div class="text-sm font-medium text-gray-900">
                                                            {{ $vehicle->latestTelemetry->kilometers }} km</div>
                                                    @else
                                                        <span class="text-gray-400 text-sm">-</span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                    <button wire:click="selectVehicle({{ $vehicle->id }})"
                                                        class="text-indigo-600 hover:text-indigo-900 font-medium">
                                                        Ver Historial
                                                    </button>
                                                </td>
                                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Battery History Chart -->
        @if($selectedVehicleId && $batteryHistory)
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="p-4 bg-gradient-to-r from-purple-600 to-indigo-600 border-b">
                    <h2 class="text-lg font-bold text-white">📊 Historial de Batería - Vehículo #{{ $selectedVehicleId }}
                    </h2>
                </div>
                <div class="p-6">
                    <canvas id="batteryChart" height="80"></canvas>
                </div>
            </div>
        @endif
    </div>

    <!-- Auto-refresh indicator -->
    <div wire:loading class="fixed bottom-4 right-4 bg-indigo-600 text-white px-4 py-2 rounded-lg shadow-lg">
        <div class="flex items-center">
            <svg class="animate-spin h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor"
                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                </path>
            </svg>
            Actualizando...
        </div>
    </div>
</div>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        let batteryChart;

        function initBatteryChart() {
            const ctx = document.getElementById('batteryChart');
            if (!ctx) return;

            const batteryData = @js($batteryHistory);

            if (batteryChart) {
                batteryChart.destroy();
            }

            batteryChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: batteryData.labels,
                    datasets: [{
                        label: 'Nivel de Batería (%)',
                        data: batteryData.data,
                        borderColor: 'rgb(79, 70, 229)',
                        backgroundColor: 'rgba(79, 70, 229, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                        },
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    return 'Batería: ' + context.parsed.y + '%';
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100,
                            ticks: {
                                callback: function (value) {
                                    return value + '%';
                                }
                            }
                        }
                    }
                }
            });
        }

        // Initialize chart when component loads
        document.addEventListener('livewire:initialized', () => {
            Livewire.hook('morph.updated', ({ el, component }) => {
                setTimeout(initBatteryChart, 100);
            });
        });

        // Initialize on page load
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => setTimeout(initBatteryChart, 200));
        } else {
            setTimeout(initBatteryChart, 200);
        }
    </script>
@endpush