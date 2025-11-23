<div class="flex h-screen bg-gray-100">
    <!-- Sidebar Filters -->
    <div class="w-80 bg-white shadow-lg overflow-y-auto">
        <!-- Header -->
        <div class="p-6 bg-gradient-to-r from-blue-600 to-indigo-600">
            <h1 class="text-2xl font-bold text-white">🗺️ Mapa EcoFlow</h1>
            <p class="text-sm text-blue-100 mt-1">Encuentra tu vehículo ideal</p>
        </div>

        <!-- Stats -->
        <div class="p-4 border-b border-gray-200">
            <h3 class="text-sm font-semibold text-gray-700 mb-3">Vehículos Disponibles</h3>
            <div class="grid grid-cols-2 gap-2 text-sm">
                <div
                    class="text-center p-3 rounded-lg {{ $vehicleTypeFilter === 'all' ? 'bg-blue-100 border-2 border-blue-500' : 'bg-gray-50' }}">
                    <div class="text-2xl font-bold text-gray-800">{{ $stats['total'] }}</div>
                    <div class="text-xs text-gray-600">Total</div>
                </div>
                <div class="text-center p-3 rounded-lg bg-gray-50">
                    <div class="text-lg">🛴</div>
                    <div class="font-semibold">{{ $stats['scooter'] }}</div>
                </div>
                <div class="text-center p-3 rounded-lg bg-gray-50">
                    <div class="text-lg">🛹</div>
                    <div class="font-semibold">{{ $stats['skateboard'] }}</div>
                </div>
                <div class="text-center p-3 rounded-lg bg-gray-50">
                    <div class="text-lg">🚲</div>
                    <div class="font-semibold">{{ $stats['bicycle'] }}</div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="p-4 border-b border-gray-200">
            <h3 class="text-sm font-semibold text-gray-700 mb-3">Filtrar por Tipo</h3>
            <div class="space-y-2">
                <button wire:click="setVehicleFilter('all')"
                    class="w-full text-left px-4 py-3 rounded-lg transition-colors {{ $vehicleTypeFilter === 'all' ? 'bg-blue-600 text-white' : 'bg-gray-100 hover:bg-gray-200 text-gray-700' }}">
                    <span class="font-medium">Todos</span>
                    <span class="float-right">{{ $stats['total'] }}</span>
                </button>
                <button wire:click="setVehicleFilter('scooter')"
                    class="w-full text-left px-4 py-3 rounded-lg transition-colors {{ $vehicleTypeFilter === 'scooter' ? 'bg-blue-600 text-white' : 'bg-gray-100 hover:bg-gray-200 text-gray-700' }}">
                    <span class="font-medium">🛴 Scooters</span>
                    <span class="float-right">{{ $stats['scooter'] }}</span>
                </button>
                <button wire:click="setVehicleFilter('skateboard')"
                    class="w-full text-left px-4 py-3 rounded-lg transition-colors {{ $vehicleTypeFilter === 'skateboard' ? 'bg-blue-600 text-white' : 'bg-gray-100 hover:bg-gray-200 text-gray-700' }}">
                    <span class="font-medium">🛹 Skateboards</span>
                    <span class="float-right">{{ $stats['skateboard'] }}</span>
                </button>
                <button wire:click="setVehicleFilter('bicycle')"
                    class="w-full text-left px-4 py-3 rounded-lg transition-colors {{ $vehicleTypeFilter === 'bicycle' ? 'bg-blue-600 text-white' : 'bg-gray-100 hover:bg-gray-200 text-gray-700' }}">
                    <span class="font-medium">🚲 Bicicletas</span>
                    <span class="float-right">{{ $stats['bicycle'] }}</span>
                </button>
            </div>
        </div>

        <!-- Toggle Stations -->
        <div class="p-4 border-b border-gray-200">
            <label class="flex items-center justify-between cursor-pointer">
                <div>
                    <span class="text-sm font-semibold text-gray-700">Mostrar Estaciones</span>
                    <p class="text-xs text-gray-500">📍 Puntos de recogida</p>
                </div>
                <div class="relative">
                    <input type="checkbox" wire:model.live="showStations" class="sr-only">
                    <div
                        class="w-14 h-8 rounded-full {{ $showStations ? 'bg-blue-600' : 'bg-gray-300' }} transition-colors">
                    </div>
                    <div
                        class="absolute left-1 top-1 w-6 h-6 bg-white rounded-full transition-transform {{ $showStations ? 'transform translate-x-6' : '' }}">
                    </div>
                </div>
            </label>
        </div>

        <!-- Auto Refresh Info -->
        <div class="p-4">
            <div class="bg-green-50 border border-green-200 rounded-lg p-3">
                <div class="flex items-center text-sm text-green-800">
                    <svg class="w-4 h-4 mr-2 animate-pulse" fill="currentColor" viewBox="0 0 20 20">
                        <circle cx="10" cy="10" r="8" />
                    </svg>
                    <span>Actualización automática activa</span>
                </div>
                <p class="text-xs text-green-600 mt-1">El mapa se actualiza cada 30 segundos</p>
            </div>
        </div>

        <!-- CTA Button -->
        <div class="p-4">
            <a href="{{ route('register') }}"
                class="block w-full px-4 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-center font-medium rounded-lg transition-all shadow-md hover:shadow-lg">
                Crear Cuenta Gratis
            </a>
            <a href="{{ route('login') }}"
                class="block w-full mt-2 px-4 py-3 border-2 border-blue-600 text-blue-600 hover:bg-blue-50 text-center font-medium rounded-lg transition-colors">
                Iniciar Sesión
            </a>
        </div>
    </div>

    <!-- Map Container -->
    <div class="flex-1 relative">
        <div id="public-map" wire:ignore style="height: 100%; width: 100%;"></div>
    </div>
</div>

@push('styles')
    <style>
        .custom-icon {
            background: none;
            border: none;
        }

        .leaflet-popup-content-wrapper {
            border-radius: 12px;
        }

        .leaflet-popup-content {
            margin: 0;
            min-width: 220px;
        }
    </style>
@endpush

@push('scripts')
    <script>
        let publicMap;
        let stationMarkers = [];
        let vehicleMarkers = [];

        function initPublicMap() {
            if (typeof L === 'undefined') {
                console.error('Leaflet not loaded');
                return;
            }

            const mapEl = document.getElementById('public-map');
            if (!mapEl || mapEl._leaflet_id) return;

            console.log('Initializing public map...');

            publicMap = L.map('public-map').setView([7.1193, -73.1227], 13);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap',
                maxZoom: 19
            }).addTo(publicMap);

            updateMapMarkers();
        }

        function updateMapMarkers() {
            if (!publicMap) return;

            // Clear existing markers
            stationMarkers.forEach(m => publicMap.removeLayer(m));
            vehicleMarkers.forEach(m => publicMap.removeLayer(m));
            stationMarkers = [];
            vehicleMarkers = [];

            const stationIcon = L.divIcon({
                html: '<div style="font-size:32px">📍</div>',
                className: 'custom-icon',
                iconSize: [32, 32],
                iconAnchor: [16, 32],
                popupAnchor: [0, -32]
            });

            const vehicleIcons = {
                scooter: L.divIcon({ html: '<div style="font-size:28px">🛴</div>', className: 'custom-icon', iconSize: [28, 28], iconAnchor: [14, 28], popupAnchor: [0, -28] }),
                skateboard: L.divIcon({ html: '<div style="font-size:28px">🛹</div>', className: 'custom-icon', iconSize: [28, 28], iconAnchor: [14, 28], popupAnchor: [0, -28] }),
                bicycle: L.divIcon({ html: '<div style="font-size:28px">🚲</div>', className: 'custom-icon', iconSize: [28, 28], iconAnchor: [14, 28], popupAnchor: [0, -28] })
            };

            const showStations = @js($showStations);

            // Add station markers
            if (showStations) {
                @foreach($stations as $station)
                    @if($station->latitude && $station->longitude)
                        const stationMarker = L.marker([{{ $station->latitude }}, {{ $station->longitude }}], { icon: stationIcon })
                            .addTo(publicMap)
                            .bindPopup('<div class="p-3"><h3 class="font-bold text-green-700 text-base">📍 {{ $station->name }}</h3><p class="text-sm text-gray-600 mt-1">{{ $station->address }}</p><p class="text-xs text-gray-500 mt-2">Capacidad: {{ $station->capacity }} vehículos</p></div>');
                        stationMarkers.push(stationMarker);
                    @endif
                @endforeach
            }

            // Add vehicle markers
            @foreach($vehicles as $vehicle)
                @if($vehicle->station && $vehicle->station->latitude && $vehicle->station->longitude)
                    (function () {
                        const lat = {{ $vehicle->station->latitude }} + (Math.random() - 0.5) * 0.002;
                        const lng = {{ $vehicle->station->longitude }} + (Math.random() - 0.5) * 0.002;
                        const icon = vehicleIcons['{{ $vehicle->type }}'] || vehicleIcons.scooter;

                        const batteryLevel = {{ $vehicle->latestTelemetry ? $vehicle->latestTelemetry->battery_level : 'null' }};
                        const batteryColor = batteryLevel > 60 ? 'green' : (batteryLevel > 30 ? 'yellow' : 'red');
                        const batteryIcon = batteryLevel > 60 ? '🔋' : (batteryLevel > 30 ? '🪫' : '⚠️');

                        const typeEmoji = '{{ $vehicle->type }}' === 'scooter' ? '🛴' : ('{{ $vehicle->type }}' === 'skateboard' ? '🛹' : '🚲');
                        const typeName = '{{ $vehicle->type }}' === 'scooter' ? 'Scooter' : ('{{ $vehicle->type }}' === 'skateboard' ? 'Skateboard' : 'Bicicleta');

                        const popup = `
                                <div class="p-3">
                                    <div class="flex items-start justify-between mb-2">
                                        <div>
                                            <h3 class="font-bold text-blue-700 text-base">{{ $vehicle->brand }} {{ $vehicle->model }}</h3>
                                            <p class="text-sm text-gray-600">${typeEmoji} ${typeName}</p>
                                        </div>
                                        ${batteryLevel ? `<div class="text-right"><div class="text-2xl">${batteryIcon}</div><div class="text-xs font-medium text-${batteryColor}-600">${batteryLevel}%</div></div>` : ''}
                                    </div>
                                    <div class="border-t border-gray-200 pt-2 mt-2 space-y-1">
                                        <p class="text-xs text-gray-600"><span class="font-medium">Placa:</span> {{ $vehicle->plate }}</p>
                                        <p class="text-xs text-gray-600"><span class="font-medium">Ubicación:</span> {{ $vehicle->station->name }}</p>
                                        <p class="text-xs text-green-600 font-medium mt-2">✓ Disponible ahora</p>
                                    </div>
                                    <a href="{{ route('register') }}" class="mt-3 block w-full text-center px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                                        Reservar Ahora
                                    </a>
                                </div>
                            `;

                        const vehicleMarker = L.marker([lat, lng], { icon: icon })
                            .addTo(publicMap)
                            .bindPopup(popup, { maxWidth: 280 });
                        vehicleMarkers.push(vehicleMarker);
                    })();
                @endif
            @endforeach

            console.log('Map updated: ' + vehicleMarkers.length + ' vehicles, ' + stationMarkers.length + ' stations');

            setTimeout(() => publicMap.invalidateSize(), 100);
        }

        // Initialize map
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => setTimeout(initPublicMap, 200));
        } else {
            setTimeout(initPublicMap, 200);
        }

        // Listen for Livewire updates
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('updateMap', () => {
                setTimeout(updateMapMarkers, 100);
            });
        });

        // Watch for filter changes
        Livewire.hook('morph.updated', ({ el, component }) => {
            if (publicMap) {
                setTimeout(updateMapMarkers, 100);
            }
        });

        // Auto-refresh every 30 seconds
        setInterval(() => {
            console.log('Auto-refreshing map data...');
            Livewire.dispatch('refreshMap');
        }, 30000);
    </script>
@endpush