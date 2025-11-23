<div>
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-4 bg-gradient-to-r from-green-600 to-emerald-600 border-b border-green-500">
            <h3 class="text-lg font-semibold text-white">🗺️ Mapa de Vehículos Disponibles</h3>
            <p class="text-sm text-green-50 mt-1">Encuentra vehículos y estaciones cercanas en Bucaramanga</p>
        </div>

        <div id="vehicle-map" wire:ignore style="height: 500px; width: 100%;"></div>

        <div class="p-4 bg-gray-50 border-t border-gray-200">
            <h4 class="text-sm font-semibold text-gray-700 mb-2">Leyenda:</h4>
            <div class="flex flex-wrap gap-4 text-sm">
                <div class="flex items-center">
                    <span class="text-2xl mr-2">📍</span>
                    <span class="text-gray-600">Estaciones</span>
                </div>
                <div class="flex items-center">
                    <span class="text-2xl mr-2">🛴</span>
                    <span class="text-gray-600">Scooters</span>
                </div>
                <div class="flex items-center">
                    <span class="text-2xl mr-2">🛹</span>
                    <span class="text-gray-600">Skates</span>
                </div>
                <div class="flex items-center">
                    <span class="text-2xl mr-2">🚲</span>
                    <span class="text-gray-600">Bicicletas</span>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
    <style>
        .custom-icon {
            background: none;
            border: none;
        }

        .leaflet-popup-content-wrapper {
            border-radius: 8px;
        }

        .leaflet-popup-content {
            margin: 0;
            min-width: 200px;
        }
    </style>
@endpush

@push('scripts')
    <script>
        function initializeMap() {
            if (typeof L === 'undefined') {
                console.error('Leaflet not loaded');
                return;
            }

            const mapEl = document.getElementById('vehicle-map');
            if (!mapEl) {
                console.error('Map element not found');
                return;
            }

            if (mapEl._leaflet_id) {
                console.log('Map already initialized');
                return;
            }

            console.log('Initializing map...');

            const map = L.map('vehicle-map').setView([7.1193, -73.1227], 13);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap',
                maxZoom: 19
            }).addTo(map);

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

            let count = 0;

            @foreach($stations as $station)
                @if($station->latitude && $station->longitude)
                    L.marker([{{ $station->latitude }}, {{ $station->longitude }}], { icon: stationIcon })
                        .addTo(map)
                        .bindPopup('<div class="p-2"><h3 class="font-bold text-green-700">{{ $station->name }}</h3><p class="text-sm">{{ $station->address }}</p></div>');
                    count++;
                @endif
            @endforeach

            @foreach($vehicles as $vehicle)
                @if($vehicle->station && $vehicle->station->latitude && $vehicle->station->longitude)
                    (function () {
                        const lat = {{ $vehicle->station->latitude }} + (Math.random() - 0.5) * 0.002;
                        const lng = {{ $vehicle->station->longitude }} + (Math.random() - 0.5) * 0.002;
                        const icon = vehicleIcons['{{ $vehicle->type }}'] || vehicleIcons.scooter;

                        L.marker([lat, lng], { icon: icon }).addTo(map)
                            .bindPopup('<div class="p-2"><h3 class="font-bold text-blue-700">{{ $vehicle->brand }} {{ $vehicle->model }}</h3><p class="text-sm">{{ $vehicle->plate }}</p><p class="text-xs text-green-600 mt-2">✓ Disponible</p></div>');
                        count++;
                    })();
                @endif
            @endforeach

            console.log('Map initialized with ' + count + ' markers');

            if (count > 0) {
                const bounds = [];
                @foreach($stations as $station)
                    @if($station->latitude && $station->longitude)
                        bounds.push([{{ $station->latitude }}, {{ $station->longitude }}]);
                    @endif
                @endforeach

                if (bounds.length > 0) {
                    map.fitBounds(bounds, { padding: [50, 50] });
                }
            }

            setTimeout(function () { map.invalidateSize(); }, 100);
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', function () {
                setTimeout(initializeMap, 200);
            });
        } else {
            setTimeout(initializeMap, 200);
        }
    </script>
@endpush