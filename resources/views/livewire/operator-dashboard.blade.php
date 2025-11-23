<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Dashboard Operador</h1>
            @if(auth()->user()->station)
                <p class="mt-1 text-sm text-gray-600">
                    Estación: <span class="font-semibold">{{ auth()->user()->station->name }}</span>
                </p>
            @else
                <div class="mt-2 bg-red-50 border border-red-200 rounded-md p-3">
                    <p class="text-sm text-red-700">⚠️ No tienes una estación asignada. Contacta al administrador.</p>
                </div>
            @endif
        </div>

        @if(auth()->user()->station)
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-blue-600 font-medium">Reservas de Hoy</p>
                            <p class="text-3xl font-bold text-blue-700 mt-1">{{ $stats['today_total'] }}</p>
                        </div>
                        <div class="bg-blue-100 rounded-full p-3">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-yellow-600 font-medium">Entregas Pendientes</p>
                            <p class="text-3xl font-bold text-yellow-700 mt-1">{{ $stats['pending_deliveries'] }}</p>
                        </div>
                        <div class="bg-yellow-100 rounded-full p-3">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-orange-600 font-medium">Devoluciones Pendientes</p>
                            <p class="text-3xl font-bold text-orange-700 mt-1">{{ $stats['pending_returns'] }}</p>
                        </div>
                        <div class="bg-orange-100 rounded-full p-3">
                            <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-green-600 font-medium">Completadas Hoy</p>
                            <p class="text-3xl font-bold text-green-700 mt-1">{{ $stats['completed_today'] }}</p>
                        </div>
                        <div class="bg-green-100 rounded-full p-3">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- QR Scanner Section -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                <h2 class="text-xl font-bold text-gray-800 mb-4">
                    📱 Escanear Código QR / Placa
                </h2>

                <div class="flex flex-col md:flex-row gap-4 items-end">
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Tipo de Operación
                        </label>
                        <div class="flex gap-4">
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" wire:model.live="scanMode" value="delivery"
                                    class="h-4 w-4 text-blue-600 focus:ring-blue-500">
                                <span class="ml-2 text-sm text-gray-700">Entrega</span>
                            </label>
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" wire:model.live="scanMode" value="return"
                                    class="h-4 w-4 text-blue-600 focus:ring-blue-500">
                                <span class="ml-2 text-sm text-gray-700">Devolución</span>
                            </label>
                        </div>
                    </div>

                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Placa del Vehículo
                        </label>
                        <input type="text" wire:model="plateInput" wire:keydown.enter="scanPlate" placeholder="Ej: ABC123"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent uppercase"
                            autocomplete="off">
                        @error('plateInput')
                            <span class="text-xs text-red-500 mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <button wire:click="scanPlate"
                        class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md font-medium transition-colors flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                        </svg>
                        Escanear
                    </button>
                </div>
            </div>

            <!-- Tabs -->
            <div class="bg-white rounded-lg shadow-md">
                <div class="border-b border-gray-200">
                    <nav class="flex -mb-px">
                        <button @class([
                            'py-4 px-6 text-sm font-medium border-b-2 transition-colors',
                            'border-blue-500 text-blue-600' => $activeTab === 'deliveries',
                            'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' => $activeTab !== 'deliveries'
                        ])
                            wire:click="$set('activeTab', 'deliveries')">
                            Entregas Pendientes ({{ $stats['pending_deliveries'] }})
                        </button>
                        <button @class([
                            'py-4 px-6 text-sm font-medium border-b-2 transition-colors',
                            'border-blue-500 text-blue-600' => $activeTab === 'returns',
                            'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' => $activeTab !== 'returns'
                        ]) wire:click="$set('activeTab', 'returns')">
                            Devoluciones Pendientes ({{ $stats['pending_returns'] }})
                        </button>
                        <button @class([
                            'py-4 px-6 text-sm font-medium border-b-2 transition-colors',
                            'border-blue-500 text-blue-600' => $activeTab === 'today',
                            'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' => $activeTab !== 'today'
                        ]) wire:click="$set('activeTab', 'today')">
                            Reservas de Hoy ({{ $stats['today_total'] }})
                        </button>
                    </nav>
                </div>

                <div class="p-6">
                    @if($activeTab === 'deliveries')
                        <!-- Pending Deliveries -->
                        @forelse($pendingDeliveries as $reservation)
                            <div class="border border-gray-200 rounded-lg p-4 mb-3 hover:bg-gray-50 transition-colors">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-2">
                                            <h3 class="font-semibold text-gray-900">
                                                {{ $reservation->vehicle->brand }} {{ $reservation->vehicle->model }}
                                            </h3>
                                            <span
                                                class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-700 border border-blue-200 rounded">
                                                {{ $reservation->vehicle->plate }}
                                            </span>
                                        </div>
                                        <p class="text-sm text-gray-600">
                                            <span class="font-medium">Cliente:</span> {{ $reservation->user->name }}
                                        </p>
                                        <p class="text-sm text-gray-600">
                                            <span class="font-medium">Inicio:</span>
                                            {{ $reservation->start_date->format('d/m/Y H:i') }}
                                        </p>
                                    </div>
                                    <button wire:click="viewReservationDetails({{ $reservation->id }})"
                                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-md transition-colors">
                                        Procesar Entrega
                                    </button>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-12">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                </svg>
                                <p class="mt-2 text-sm text-gray-500">No hay entregas pendientes</p>
                            </div>
                        @endforelse

                    @elseif($activeTab === 'returns')
                        <!-- Pending Returns -->
                        @forelse($pendingReturns as $reservation)
                            <div class="border border-gray-200 rounded-lg p-4 mb-3 hover:bg-gray-50 transition-colors">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-2">
                                            <h3 class="font-semibold text-gray-900">
                                                {{ $reservation->vehicle->brand }} {{ $reservation->vehicle->model }}
                                            </h3>
                                            <span
                                                class="px-2 py-1 text-xs font-medium bg-green-100 text-green-700 border border-green-200 rounded">
                                                {{ $reservation->vehicle->plate }}
                                            </span>
                                        </div>
                                        <p class="text-sm text-gray-600">
                                            <span class="font-medium">Cliente:</span> {{ $reservation->user->name }}
                                        </p>
                                        <p class="text-sm text-gray-600">
                                            <span class="font-medium">Fin:</span> {{ $reservation->end_date->format('d/m/Y H:i') }}
                                        </p>
                                    </div>
                                    <button wire:click="viewReservationDetails({{ $reservation->id }})"
                                        class="px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white text-sm rounded-md transition-colors">
                                        Procesar Devolución
                                    </button>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-12">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                                </svg>
                                <p class="mt-2 text-sm text-gray-500">No hay devoluciones pendientes</p>
                            </div>
                        @endforelse

                    @else
                        <!-- Today's Reservations -->
                        @forelse($todayReservations as $reservation)
                            <div class="border border-gray-200 rounded-lg p-4 mb-3">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-2">
                                            <h3 class="font-semibold text-gray-900">
                                                {{ $reservation->vehicle->brand }} {{ $reservation->vehicle->model }}
                                            </h3>
                                            <span
                                                class="px-2 py-1 text-xs font-medium {{ $reservation->getStatusColorClass() }} border rounded">
                                                {{ $reservation->getStatusLabel() }}
                                            </span>
                                        </div>
                                        <p class="text-sm text-gray-600">
                                            <span class="font-medium">Cliente:</span> {{ $reservation->user->name }}
                                        </p>
                                        <p class="text-sm text-gray-600">
                                            <span class="font-medium">Período:</span>
                                            {{ $reservation->start_date->format('d/m H:i') }} -
                                            {{ $reservation->end_date->format('d/m H:i') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-12">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <p class="mt-2 text-sm text-gray-500">No hay reservas para hoy</p>
                            </div>
                        @endforelse
                    @endif
                </div>
            </div>
        @endif
    </div>

    <!-- Process Modal -->
    @if($showProcessModal && $selectedReservation)
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <h3 class="text-xl font-bold text-gray-900">
                            {{ $scanMode === 'delivery' ? 'Procesar Entrega' : 'Procesar Devolución' }}
                        </h3>
                        <button wire:click="closeModal" class="text-gray-400 hover:text-gray-500">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Reservation Details -->
                    <div class="bg-gray-50 rounded-lg p-4 mb-6">
                        <h4 class="font-semibold text-gray-900 mb-3">Detalles de la Reserva</h4>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="text-gray-600">Vehículo:</span>
                                <p class="font-medium">{{ $selectedReservation->vehicle->brand }}
                                    {{ $selectedReservation->vehicle->model }}</p>
                            </div>
                            <div>
                                <span class="text-gray-600">Placa:</span>
                                <p class="font-medium">{{ $selectedReservation->vehicle->plate }}</p>
                            </div>
                            <div>
                                <span class="text-gray-600">Cliente:</span>
                                <p class="font-medium">{{ $selectedReservation->user->name }}</p>
                            </div>
                            <div>
                                <span class="text-gray-600">Teléfono:</span>
                                <p class="font-medium">{{ $selectedReservation->user->phone ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Form -->
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Condición del Vehículo *
                            </label>
                            <div class="grid grid-cols-3 gap-3">
                                <label
                                    class="relative flex items-center justify-center p-3 border rounded-lg cursor-pointer {{ $vehicleCondition === 'good' ? 'border-green-500 bg-green-50' : 'border-gray-300' }}">
                                    <input type="radio" wire:model="vehicleCondition" value="good" class="sr-only">
                                    <span
                                        class="text-sm font-medium {{ $vehicleCondition === 'good' ? 'text-green-700' : 'text-gray-700' }}">
                                        ✓ Buena
                                    </span>
                                </label>
                                <label
                                    class="relative flex items-center justify-center p-3 border rounded-lg cursor-pointer {{ $vehicleCondition === 'fair' ? 'border-yellow-500 bg-yellow-50' : 'border-gray-300' }}">
                                    <input type="radio" wire:model="vehicleCondition" value="fair" class="sr-only">
                                    <span
                                        class="text-sm font-medium {{ $vehicleCondition === 'fair' ? 'text-yellow-700' : 'text-gray-700' }}">
                                        ⚠ Regular
                                    </span>
                                </label>
                                <label
                                    class="relative flex items-center justify-center p-3 border rounded-lg cursor-pointer {{ $vehicleCondition === 'poor' ? 'border-red-500 bg-red-50' : 'border-gray-300' }}">
                                    <input type="radio" wire:model="vehicleCondition" value="poor" class="sr-only">
                                    <span
                                        class="text-sm font-medium {{ $vehicleCondition === 'poor' ? 'text-red-700' : 'text-gray-700' }}">
                                        ✗ Mala
                                    </span>
                                </label>
                            </div>
                            @error('vehicleCondition')
                                <span class="text-xs text-red-500 mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Notas / Observaciones
                            </label>
                            <textarea wire:model="notes" rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Agregar observaciones sobre el estado del vehículo..."></textarea>
                            @error('notes')
                                <span class="text-xs text-red-500 mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="mt-6 flex justify-end gap-3">
                        <button wire:click="closeModal"
                            class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-colors">
                            Cancelar
                        </button>
                        @if($scanMode === 'delivery')
                            <button wire:click="processDelivery"
                                class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md font-medium transition-colors">
                                Confirmar Entrega
                            </button>
                        @else
                            <button wire:click="processReturn"
                                class="px-6 py-2 bg-orange-600 hover:bg-orange-700 text-white rounded-md font-medium transition-colors">
                                Confirmar Devolución
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Toast Notifications -->
    <script>
        document.addEventListener('livewire:initialized', () => {
            @this.on('scan-error', (event) => {
                Swal.fire({
                    title: 'Error',
                    text: event.message,
                    icon: 'error',
                    confirmButtonColor: '#3B82F6',
                });
            });

            @this.on('process-completed', (event) => {
                Swal.fire({
                    title: 'Éxito',
                    text: event.message,
                    icon: 'success',
                    confirmButtonColor: '#10B981',
                    timer: 2000,
                    showConfirmButton: false
                });
            });
        });
    </script>

    @script
    <script>
        $wire.activeTab = 'deliveries';
    </script>
    @endscript
</div>