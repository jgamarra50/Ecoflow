<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('repartidor.dashboard') }}" class="inline-flex items-center text-orange-600 hover:text-orange-700">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Volver al Dashboard
            </a>
        </div>

        <!-- Header -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
            <div class="flex items-start justify-between">
                <div class="flex items-center space-x-4">
                    <div class="text-6xl">{{ $delivery->reservation->vehicle->getTypeIcon() }}</div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">
                            {{ $delivery->reservation->vehicle->brand }} {{ $delivery->reservation->vehicle->model }}
                        </h1>
                        <p class="text-gray-600">Placa: {{ $delivery->reservation->vehicle->plate }}</p>
                        <p class="text-sm text-gray-500 mt-1">
                            {{ $delivery->getTypeLabel() }} - ID: #{{ $delivery->id }}
                        </p>
                    </div>
                </div>
                <span class="px-4 py-2 text-sm font-semibold rounded-full {{ $delivery->getStatusColorClass() }}">
                    {{ $delivery->getStatusLabel() }}
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Cliente Info -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Información del Cliente</h3>
                    <div class="space-y-3">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                <span class="text-blue-600 font-bold">
                                    {{ substr($delivery->reservation->user->name, 0, 1) }}
                                </span>
                            </div>
                            <div>
                                <p class="font-semibold">{{ $delivery->reservation->user->name }}</p>
                                <p class="text-sm text-gray-500">Cliente</p>
                            </div>
                        </div>
                        <div class="flex items-center text-gray-600">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            <span>{{ $delivery->reservation->user->phone }}</span>
                        </div>
                        <div class="flex items-start text-gray-600">
                            <svg class="w-5 h-5 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span>{{ $delivery->delivery_address }}</span>
                        </div>
                    </div>
                </div>

                <!-- Delivery Timeline -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Estado de la Entrega</h3>
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <div class="w-32 text-sm text-gray-600">Programada:</div>
                            <div class="font-medium">{{ $delivery->scheduled_time->format('d/m/Y H:i') }}</div>
                        </div>
                        @if($delivery->actual_delivery_time)
                        <div class="flex items-center">
                            <div class="w-32 text-sm text-gray-600">Entregada:</div>
                            <div class="font-medium text-green-600">{{ $delivery->actual_delivery_time->format('d/m/Y H:i') }}</div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Notes -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Notas</h3>
                    <textarea wire:model="notes" rows="4" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                        placeholder="Agrega notas sobre la entrega..."></textarea>
                </div>

                <!-- Completion Section -->
                @if($delivery->canComplete())
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Completar Entrega</h3>
                    
                    <!-- Photo Upload -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Foto de Confirmación</label>
                        <input type="file" wire:model="photo" accept="image/*" 
                            class="block w-full text-sm text-gray-500
                            file:mr-4 file:py-2 file:px-4
                            file:rounded-full file:border-0
                            file:text-sm file:font-semibold
                            file:bg-orange-50 file:text-orange-700
                            hover:file:bg-orange-100"/>
                        @error('photo') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Signature Pad -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Firma del Cliente *</label>
                        <div id="signature-pad" class="border-2 border-gray-300 rounded-lg">
                            <canvas width="600" height="200" class="w-full"></canvas>
                        </div>
                        <button type="button" onclick="clearSignature()" 
                            class="mt-2 text-sm text-gray-600 hover:text-gray-900">
                            🗑️ Limpiar Firma
                        </button>
                        @error('signature') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <button wire:click="completeDelivery" wire:loading.attr="disabled"
                        class="w-full py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition-colors">
                        ✅ Completar Entrega
                    </button>
                </div>
                @endif
            </div>

            <!-- Sidebar Actions -->
            <div class="space-y-6">
                <!-- Map -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Ubicación</h3>
                    <div id="delivery-map" class="h-64 rounded-lg"></div>
                    <button wire:click="openNavigation"
                        class="w-full mt-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">
                        🗺️ Abrir en Google Maps
                    </button>
                </div>

                <!-- Actions -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Acciones</h3>
                    <div class="space-y-3">
                        @if($delivery->canStart())
                        <button wire:click="startDelivery"
                            class="w-full py-2 bg-orange-600 hover:bg-orange-700 text-white rounded-lg">
                            🚚 Iniciar Entrega
                        </button>
                        @endif

                        @if($delivery->status === 'in_transit')
                        <button wire:click="markAsArrived"
                            class="w-full py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg">
                            📍 Marcar Llegada
                        </button>
                        @endif

                        <a href="tel:{{ $delivery->reservation->user->phone }}"
                            class="block w-full py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-center">
                            📞 Llamar Cliente
                        </a>

                        @if($delivery->canCancel())
                        <button wire:click="cancelDelivery" wire:confirm="¿Estás seguro de cancelar esta entrega?"
                            class="w-full py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg">
                            ❌ Cancelar Entrega
                        </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>
<script>
    let map;
    let signaturePad;

    // Initialize signature pad when DOM is ready
    document.addEventListener('DOMContentLoaded', function() {
        initializeSignaturePad();
        initializeMap();
    });

    function initializeSignaturePad() {
        const canvas = document.querySelector('#signature-pad canvas');
        
        if (canvas) {
            signaturePad = new SignaturePad(canvas, {
                backgroundColor: 'rgb(255, 255, 255)',
                penColor: 'rgb(0, 0, 0)'
            });
            
            // Save signature when changed
            signaturePad.addEventListener("endStroke", () => {
                @this.set('signature', signaturePad.toDataURL());
            });
        }
    }

    function clearSignature() {
        if (signaturePad) {
            signaturePad.clear();
            @this.set('signature', null);
        }
    }

    // Initialize map
    function initializeMap() {
        const mapEl = document.getElementById('delivery-map');
        if (mapEl && typeof L !== 'undefined') {
            const lat = {{ $delivery->delivery_lat ?? 7.0799 }};
            const lng = {{ $delivery->delivery_lng ?? -73.0978 }};
            
            map = L.map('delivery-map').setView([lat, lng], 15);
            
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap'
            }).addTo(map);
            
            L.marker([lat, lng]).addTo(map)
                .bindPopup('<b>Destino de Entrega</b>').openPopup();
        }
    }

    // Listen for events
    document.addEventListener('livewire:initialized', () => {
        Livewire.on('delivery-started', (event) => {
            Swal.fire({
                icon: 'success',
                title: event.message,
                timer: 2000
            });
        });

        Livewire.on('delivery-arrived', (event) => {
            Swal.fire({
                icon: 'info',
                title: event.message,
                timer: 2000
            });
        });

        Livewire.on('delivery-completed', (event) => {
            Swal.fire({
                icon: 'success',
                title: event.message,
                timer: 3000
            });
        });

        Livewire.on('open-navigation', (event) => {
            window.open(event.url, '_blank');
        });
    });
</script>
@endpush
