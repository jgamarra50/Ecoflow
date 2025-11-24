<div class="flex h-screen bg-gray-100">
    <!-- Sidebar Info -->
    <div class="w-96 bg-white shadow-lg overflow-y-auto">
        <!-- Header -->
        <div class="p-6 bg-gradient-to-r from-blue-600 to-indigo-600">
            <div class="flex items-center justify-between mb-2">
                <h1 class="text-xl font-bold text-white">Tracking en Vivo</h1>
                <a href="{{ route('dashboard') }}" class="text-blue-100 hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </a>
            </div>
            <p class="text-sm text-blue-100">Reserva #{{ $reservation->id }}</p>
        </div>

        <!-- Vehicle Info -->
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center space-x-4">
                <div class="text-5xl">{{ $reservation->vehicle->getTypeIcon() }}</div>
                <div class="flex-1">
                    <h3 class="font-bold text-lg text-gray-900">{{ $reservation->vehicle->brand }} {{ $reservation->vehicle->model }}</h3>
                    <p class="text-sm text-gray-600">{{ $reservation->vehicle->plate }}</p>
                </div>
            </div>
        </div>

        <!-- Status -->
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-sm font-semibold text-gray-700 mb-3">Estado Actual</h3>
            <div class="bg-gradient-to-br from-{{ $reservation->status === 'active' ? 'green' : 'blue' }}-50 to-{{ $reservation->status === 'active' ? 'emerald' : 'indigo' }}-50 border-2 border-{{ $reservation->status === 'active' ? 'green' : 'blue' }}-500 rounded-lg p-4">
                <div class="flex items-center">
                    @if($reservation->status === 'active')
                        <div class="w-3 h-3 bg-green-500 rounded-full mr-3 animate-pulse"></div>
                        <span class="font-semibold text-green-700">En Uso</span>
                    @elseif($reservation->status === 'confirmed')
                        <div class="w-3 h-3 bg-blue-500 rounded-full mr-3"></div>
                        <span class="font-semibold text-blue-700">Confirmada</span>
                    @else
                        <div class="w-3 h-3 bg-gray-400 rounded-full mr-3"></div>
                        <span class="font-semibold text-gray-700">{{ $reservation->getStatusLabel() }}</span>
                    @endif
                </div>
                <p class="text-xs text-gray-600 mt-2">Última actualización: {{ $lastUpdate }}</p>
            </div>
        </div>

        <!-- Time & Distance -->
        <div class="p-6 border-b border-gray-200">
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-blue-50 rounded-lg p-4">
                    <div class="text-center">
                        <svg class="w-8 h-8 mx-auto mb-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-xs text-gray-600">Tiempo</p>
                        <p class="text-sm font-bold text-gray-900 mt-1">{{ $estimatedTime }}</p>
                    </div>
                </div>
                <div class="bg-green-50 rounded-lg p-4">
                    <div class="text-center">
                        <svg class="w-8 h-8 mx-auto mb-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                        </svg>
                        <p class="text-xs text-gray-600">Distancia</p>
                        <p class="text-sm font-bold text-gray-900 mt-1">{{ $distance }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reservation Details -->
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-sm font-semibold text-gray-700 mb-3">Detalles de la Reserva</h3>
            <div class="space-y-3 text-sm">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-gray-400 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <div>
                        <p class="text-gray-600">Inicio</p>
                        <p class="font-medium text-gray-900">{{ $reservation->start_date->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-gray-400 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <div>
                        <p class="text-gray-600">Fin</p>
                        <p class="font-medium text-gray-900">{{ $reservation->end_date->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
                @if($reservation->station)
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-gray-400 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <div>
                            <p class="text-gray-600">Estación</p>
                            <p class="font-medium text-gray-900">{{ $reservation->station->name }}</p>
                        </div>
                    </div>
                @endif
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-gray-400 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div>
                        <p class="text-gray-600">Total</p>
                        <p class="font-medium text-gray-900">${{ number_format($reservation->total_price, 0) }} COP</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Battery Info -->
        @if($reservation->vehicle->latestTelemetry)
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-sm font-semibold text-gray-700 mb-3">Batería</h3>
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-2xl">
                            @if($reservation->vehicle->latestTelemetry->battery_level > 60)
                                🔋
                            @elseif($reservation->vehicle->latestTelemetry->battery_level > 30)
                                🪫
                            @else
                                ⚠️
                            @endif
                        </span>
                        <span class="text-2xl font-bold text-gray-900">{{ $reservation->vehicle->latestTelemetry->battery_level }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="bg-gradient-to-r from-green-400 to-green-600 h-3 rounded-full transition-all" 
                             style="width: {{ $reservation->vehicle->latestTelemetry->battery_level }}%"></div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Contact Support -->
        <div class="p-6">
            <button wire:click="contactSupport" 
                class="w-full px-4 py-3 bg-gradient-to-r from-orange-500 to-red-500 hover:from-orange-600 hover:to-red-600 text-white font-medium rounded-lg transition-all shadow-md hover:shadow-lg flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                Contactar Soporte
            </button>
            <p class="text-xs text-center text-gray-500 mt-2">¿Necesitas ayuda? Estamos aquí para ti</p>
        </div>
    </div>

    <!-- Map Container -->
    <div class="flex-1 relative" style="min-height: 100vh;">
        <div id="tracking-map" wire:ignore style="height: 100%; width: 100%; min-height: 100vh;"></div>
        
        <!-- Auto-refresh indicator -->
        <div class="absolute top-4 right-4 bg-white rounded-lg shadow-lg px-4 py-2 z-[1000]">
            <div class="flex items-center text-sm">
                <div class="w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse"></div>
                <span class="text-gray-700 font-medium">Actualización en tiempo real</span>
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
        border-radius: 12px;
    }
    .leaflet-popup-content {
        margin: 0;
        min-width: 200px;
    }
</style>
@endpush

@push('scripts')
<script>
    let trackingMap;
    let vehicleMarker;
    let stationMarker;
    
    function initTrackingMap() {
        if (typeof L === 'undefined') {
            console.error('Leaflet not loaded');
            return;
        }

        const mapEl = document.getElementById('tracking-map');
        if (!mapEl || mapEl._leaflet_id) return;

        console.log('Initializing tracking map...');
        
        const lat = @js($simulatedLat);
        const lng = @js($simulatedLng);
        
        trackingMap = L.map('tracking-map').setView([lat, lng], 15);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap',
            maxZoom: 19
        }).addTo(trackingMap);

        // Vehicle icon (larger)
        const vehicleIcon = L.divIcon({
            html: '<div style="font-size:42px">{{ $reservation->vehicle->getTypeIcon() }}</div>',
            className: 'custom-icon',
            iconSize: [42, 42],
            iconAnchor: [21, 42],
            popupAnchor: [0, -42]
        });

        // Station icon
        const stationIcon = L.divIcon({
            html: '<div style="font-size:32px">📍</div>',
            className: 'custom-icon',
            iconSize: [32, 32],
            iconAnchor: [16, 32],
            popupAnchor: [0, -32]
        });

        // Add vehicle marker
        vehicleMarker = L.marker([lat, lng], { icon: vehicleIcon })
            .addTo(trackingMap)
            .bindPopup('<div class="p-2"><h3 class="font-bold text-blue-700">Tu Vehículo</h3><p class="text-sm">{{ $reservation->vehicle->brand }} {{ $reservation->vehicle->model }}</p></div>');

        // Add station marker
        @if($reservation->station && $reservation->station->latitude && $reservation->station->longitude)
            stationMarker = L.marker([{{ $reservation->station->latitude }}, {{ $reservation->station->longitude }}], { icon: stationIcon })
                .addTo(trackingMap)
                .bindPopup('<div class="p-2"><h3 class="font-bold text-green-700">{{ $reservation->station->name }}</h3></div>');
        @endif

        setTimeout(() => trackingMap.invalidateSize(), 100);
    }

    function updateVehiclePosition() {
        @this.call('updateLocation').then(() => {
            const newLat = @this.get('simulatedLat');
            const newLng = @this.get('simulatedLng');
            
            if (vehicleMarker && newLat && newLng) {
                vehicleMarker.setLatLng([newLat, newLng]);
                console.log('Vehicle position updated');
            }
        });
    }

    // Initialize
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => setTimeout(initTrackingMap, 200));
    } else {
        setTimeout(initTrackingMap, 200);
    }

    // Auto-update position every 5 seconds
    setInterval(updateVehiclePosition, 5000);

    // Listen for support contact
    document.addEventListener('livewire:initialized', () => {
        Livewire.on('support-contacted', (event) => {
            Swal.fire({
                title: '¡Mensaje Enviado!',
                text: event.message,
                icon: 'success',
                confirmButtonColor: '#10B981'
            });
        });
    });
</script>
@endpush
