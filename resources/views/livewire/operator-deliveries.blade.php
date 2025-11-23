<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Gestión de Entregas y Devoluciones</h1>
            @if(auth()->user()->station)
                <p class="mt-1 text-sm text-gray-600">
                    Estación: <span class="font-semibold">{{ auth()->user()->station->name }}</span>
                </p>
            @endif
        </div>

        <!-- Tabs -->
        <div class="bg-white rounded-lg shadow-md mb-6">
            <div class="border-b border-gray-200">
                <nav class="flex -mb-px">
                    <button @class([
                        'py-4 px-6 text-sm font-medium border-b-2 transition-colors',
                        'border-blue-500 text-blue-600' => $activeTab === 'deliveries',
                        'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' => $activeTab !== 'deliveries'
                    ])
                        wire:click="$set('activeTab', 'deliveries')">
                        📦 Entregas Pendientes ({{ $pendingDeliveries->count() }})
                    </button>
                    <button @class([
                        'py-4 px-6 text-sm font-medium border-b-2 transition-colors',
                        'border-blue-500 text-blue-600' => $activeTab === 'returns',
                        'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' => $activeTab !== 'returns'
                    ]) wire:click="$set('activeTab', 'returns')">
                        🔄 Devoluciones Pendientes ({{ $pendingReturns->count() }})
                    </button>
                </nav>
            </div>
        </div>

        @if($activeTab === 'deliveries')
            <!-- Pending Deliveries List -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @forelse($pendingDeliveries as $reservation)
                    <div class="bg-white rounded-lg shadow-md p-5 hover:shadow-lg transition-shadow border border-gray-200">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <h3 class="font-bold text-lg text-gray-900">
                                    {{ $reservation->vehicle->brand }} {{ $reservation->vehicle->model }}
                                </h3>
                                <span
                                    class="inline-block px-2 py-1 text-xs font-medium bg-blue-100 text-blue-700 border border-blue-200 rounded mt-1">
                                    {{ $reservation->vehicle->plate }}
                                </span>
                            </div>
                            <span
                                class="px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-700 border border-yellow-200 rounded">
                                Confirmada
                            </span>
                        </div>

                        <div class="space-y-2 text-sm text-gray-600 mb-4">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <span class="font-medium">Cliente:</span>
                                <span class="ml-1">{{ $reservation->user->name }}</span>
                            </div>
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span class="font-medium">Inicio:</span>
                                <span class="ml-1">{{ $reservation->start_date->format('d/m/Y H:i') }}</span>
                            </div>
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                                <span class="font-medium">Teléfono:</span>
                                <span class="ml-1">{{ $reservation->user->phone ?? 'N/A' }}</span>
                            </div>
                        </div>

                        <button wire:click="openDeliveryModal({{ $reservation->id }})"
                            class="w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md font-medium transition-colors flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                            </svg>
                            Procesar Entrega
                        </button>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12 bg-white rounded-lg shadow-md">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                        </svg>
                        <p class="mt-2 text-sm text-gray-500">No hay entregas pendientes</p>
                    </div>
                @endforelse
            </div>

        @else
            <!-- Pending Returns List -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @forelse($pendingReturns as $reservation)
                    <div class="bg-white rounded-lg shadow-md p-5 hover:shadow-lg transition-shadow border border-gray-200">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <h3 class="font-bold text-lg text-gray-900">
                                    {{ $reservation->vehicle->brand }} {{ $reservation->vehicle->model }}
                                </h3>
                                <span
                                    class="inline-block px-2 py-1 text-xs font-medium bg-green-100 text-green-700 border border-green-200 rounded mt-1">
                                    {{ $reservation->vehicle->plate }}
                                </span>
                            </div>
                            <span
                                class="px-2 py-1 text-xs font-medium bg-green-100 text-green-700 border border-green-200 rounded">
                                Activa
                            </span>
                        </div>

                        <div class="space-y-2 text-sm text-gray-600 mb-4">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <span class="font-medium">Cliente:</span>
                                <span class="ml-1">{{ $reservation->user->name }}</span>
                            </div>
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span class="font-medium">Fin:</span>
                                <span class="ml-1">{{ $reservation->end_date->format('d/m/Y H:i') }}</span>
                            </div>
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                                <span class="font-medium">Teléfono:</span>
                                <span class="ml-1">{{ $reservation->user->phone ?? 'N/A' }}</span>
                            </div>
                        </div>

                        <button wire:click="openReturnModal({{ $reservation->id }})"
                            class="w-full px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white rounded-md font-medium transition-colors flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            Procesar Devolución
                        </button>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12 bg-white rounded-lg shadow-md">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                        </svg>
                        <p class="mt-2 text-sm text-gray-500">No hay devoluciones pendientes</p>
                    </div>
                @endforelse
            </div>
        @endif
    </div>

    <!-- Delivery Modal -->
    @if($showDeliveryModal && $selectedDeliveryReservation)
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <h3 class="text-xl font-bold text-gray-900">Procesar Entrega</h3>
                        <button wire:click="closeDeliveryModal" class="text-gray-400 hover:text-gray-500">
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
                                <p class="font-medium">{{ $selectedDeliveryReservation->vehicle->brand }}
                                    {{ $selectedDeliveryReservation->vehicle->model }}</p>
                            </div>
                            <div>
                                <span class="text-gray-600">Placa:</span>
                                <p class="font-medium">{{ $selectedDeliveryReservation->vehicle->plate }}</p>
                            </div>
                            <div>
                                <span class="text-gray-600">Cliente:</span>
                                <p class="font-medium">{{ $selectedDeliveryReservation->user->name }}</p>
                            </div>
                            <div>
                                <span class="text-gray-600">Teléfono:</span>
                                <p class="font-medium">{{ $selectedDeliveryReservation->user->phone ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Delivery Form -->
                    <form wire:submit.prevent="processDelivery" class="space-y-5">
                        <!-- Vehicle Condition -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Condición del Vehículo *
                            </label>
                            <div class="grid grid-cols-3 gap-3">
                                <label
                                    class="relative flex items-center justify-center p-3 border rounded-lg cursor-pointer {{ $deliveryVehicleCondition === 'good' ? 'border-green-500 bg-green-50' : 'border-gray-300' }}">
                                    <input type="radio" wire:model="deliveryVehicleCondition" value="good" class="sr-only">
                                    <span
                                        class="text-sm font-medium {{ $deliveryVehicleCondition === 'good' ? 'text-green-700' : 'text-gray-700' }}">
                                        ✓ Buena
                                    </span>
                                </label>
                                <label
                                    class="relative flex items-center justify-center p-3 border rounded-lg cursor-pointer {{ $deliveryVehicleCondition === 'fair' ? 'border-yellow-500 bg-yellow-50' : 'border-gray-300' }}">
                                    <input type="radio" wire:model="deliveryVehicleCondition" value="fair" class="sr-only">
                                    <span
                                        class="text-sm font-medium {{ $deliveryVehicleCondition === 'fair' ? 'text-yellow-700' : 'text-gray-700' }}">
                                        ⚠ Regular
                                    </span>
                                </label>
                                <label
                                    class="relative flex items-center justify-center p-3 border rounded-lg cursor-pointer {{ $deliveryVehicleCondition === 'poor' ? 'border-red-500 bg-red-50' : 'border-gray-300' }}">
                                    <input type="radio" wire:model="deliveryVehicleCondition" value="poor" class="sr-only">
                                    <span
                                        class="text-sm font-medium {{ $deliveryVehicleCondition === 'poor' ? 'text-red-700' : 'text-gray-700' }}">
                                        ✗ Mala
                                    </span>
                                </label>
                            </div>
                            @error('deliveryVehicleCondition') <span class="text-xs text-red-500 mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Documents Verification -->
                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" wire:model="documentsVerified"
                                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <span class="ml-2 text-sm font-medium text-gray-700">
                                    He verificado los documentos del cliente (identificación, licencia) *
                                </span>
                            </label>
                            @error('documentsVerified') <span class="text-xs text-red-500 mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Photo Upload -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Foto del Vehículo (Opcional)
                            </label>
                            <div class="flex items-center gap-4">
                                <label class="flex-1 cursor-pointer">
                                    <div
                                        class="border-2 border-dashed border-gray-300 rounded-lg p-4 hover:border-blue-400 transition-colors">
                                        <div class="text-center">
                                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            <p class="mt-1 text-sm text-gray-600">Haz clic para subir una foto</p>
                                        </div>
                                    </div>
                                    <input type="file" wire:model="deliveryPhoto" accept="image/*" class="hidden">
                                </label>
                                @if($deliveryPhotoPreview)
                                    <div class="relative">
                                        <img src="{{ $deliveryPhotoPreview }}"
                                            class="h-24 w-24 object-cover rounded-lg border border-gray-300">
                                        <button type="button" wire:click="$set('deliveryPhoto', null)"
                                            class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                @endif
                            </div>
                            @error('deliveryPhoto') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                            <div wire:loading wire:target="deliveryPhoto" class="text-sm text-blue-600 mt-2">Subiendo
                                foto...</div>
                        </div>

                        <!-- Notes -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Notas / Observaciones
                            </label>
                            <textarea wire:model="deliveryNotes" rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Observaciones sobre el vehículo o la entrega..."></textarea>
                            @error('deliveryNotes') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- Actions -->
                        <div class="flex justify-end gap-3 pt-4 border-t">
                            <button type="button" wire:click="closeDeliveryModal"
                                class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-colors">
                                Cancelar
                            </button>
                            <button type="submit"
                                class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md font-medium transition-colors flex items-center gap-2"
                                wire:loading.attr="disabled">
                                <span wire:loading.remove wire:target="processDelivery">Confirmar Entrega</span>
                                <span wire:loading wire:target="processDelivery">Procesando...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Return Modal -->
    @if($showReturnModal && $selectedReturnReservation)
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <h3 class="text-xl font-bold text-gray-900">Procesar Devolución</h3>
                        <button wire:click="closeReturnModal" class="text-gray-400 hover:text-gray-500">
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
                                <p class="font-medium">{{ $selectedReturnReservation->vehicle->brand }}
                                    {{ $selectedReturnReservation->vehicle->model }}</p>
                            </div>
                            <div>
                                <span class="text-gray-600">Placa:</span>
                                <p class="font-medium">{{ $selectedReturnReservation->vehicle->plate }}</p>
                            </div>
                            <div>
                                <span class="text-gray-600">Cliente:</span>
                                <p class="font-medium">{{ $selectedReturnReservation->user->name }}</p>
                            </div>
                            <div>
                                <span class="text-gray-600">Teléfono:</span>
                                <p class="font-medium">{{ $selectedReturnReservation->user->phone ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Return Form -->
                    <form wire:submit.prevent="processReturn" class="space-y-5">
                        <!-- Vehicle Condition -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Condición del Vehículo *
                            </label>
                            <div class="grid grid-cols-3 gap-3">
                                <label
                                    class="relative flex items-center justify-center p-3 border rounded-lg cursor-pointer {{ $returnVehicleCondition === 'good' ? 'border-green-500 bg-green-50' : 'border-gray-300' }}">
                                    <input type="radio" wire:model="returnVehicleCondition" value="good" class="sr-only">
                                    <span
                                        class="text-sm font-medium {{ $returnVehicleCondition === 'good' ? 'text-green-700' : 'text-gray-700' }}">
                                        ✓ Buena
                                    </span>
                                </label>
                                <label
                                    class="relative flex items-center justify-center p-3 border rounded-lg cursor-pointer {{ $returnVehicleCondition === 'fair' ? 'border-yellow-500 bg-yellow-50' : 'border-gray-300' }}">
                                    <input type="radio" wire:model="returnVehicleCondition" value="fair" class="sr-only">
                                    <span
                                        class="text-sm font-medium {{ $returnVehicleCondition === 'fair' ? 'text-yellow-700' : 'text-gray-700' }}">
                                        ⚠ Regular
                                    </span>
                                </label>
                                <label
                                    class="relative flex items-center justify-center p-3 border rounded-lg cursor-pointer {{ $returnVehicleCondition === 'poor' ? 'border-red-500 bg-red-50' : 'border-gray-300' }}">
                                    <input type="radio" wire:model="returnVehicleCondition" value="poor" class="sr-only">
                                    <span
                                        class="text-sm font-medium {{ $returnVehicleCondition === 'poor' ? 'text-red-700' : 'text-gray-700' }}">
                                        ✗ Mala
                                    </span>
                                </label>
                            </div>
                            @error('returnVehicleCondition') <span class="text-xs text-red-500 mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Kilometers and Battery -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Kilometraje *
                                </label>
                                <input type="number" wire:model="returnKilometers" min="0"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="0">
                                @error('returnKilometers') <span class="text-xs text-red-500 mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Nivel de Batería (%) *
                                </label>
                                <input type="number" wire:model="returnBatteryLevel" min="0" max="100"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="0-100">
                                @error('returnBatteryLevel') <span class="text-xs text-red-500 mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Photo Upload -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Foto del Vehículo (Opcional)
                            </label>
                            <div class="flex items-center gap-4">
                                <label class="flex-1 cursor-pointer">
                                    <div
                                        class="border-2 border-dashed border-gray-300 rounded-lg p-4 hover:border-blue-400 transition-colors">
                                        <div class="text-center">
                                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            <p class="mt-1 text-sm text-gray-600">Haz clic para subir una foto</p>
                                        </div>
                                    </div>
                                    <input type="file" wire:model="returnPhoto" accept="image/*" class="hidden">
                                </label>
                                @if($returnPhotoPreview)
                                    <div class="relative">
                                        <img src="{{ $returnPhotoPreview }}"
                                            class="h-24 w-24 object-cover rounded-lg border border-gray-300">
                                        <button type="button" wire:click="$set('returnPhoto', null)"
                                            class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                @endif
                            </div>
                            @error('returnPhoto') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                            <div wire:loading wire:target="returnPhoto" class="text-sm text-blue-600 mt-2">Subiendo foto...
                            </div>
                        </div>

                        <!-- Notes -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Notas / Observaciones
                            </label>
                            <textarea wire:model="returnNotes" rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Observaciones sobre el estado del vehículo..."></textarea>
                            @error('returnNotes') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- Actions -->
                        <div class="flex justify-end gap-3 pt-4 border-t">
                            <button type="button" wire:click="closeReturnModal"
                                class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-colors">
                                Cancelar
                            </button>
                            <button type="submit"
                                class="px-6 py-2 bg-orange-600 hover:bg-orange-700 text-white rounded-md font-medium transition-colors flex items-center gap-2"
                                wire:loading.attr="disabled">
                                <span wire:loading.remove wire:target="processReturn">Confirmar Devolución</span>
                                <span wire:loading wire:target="processReturn">Procesando...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Toast Notifications -->
    <script>
        document.addEventListener('livewire:initialized', () => {
            @this.on('delivery-processed', (event) => {
                Swal.fire({
                    title: 'Éxito',
                    text: event.message,
                    icon: 'success',
                    confirmButtonColor: '#10B981',
                    timer: 2000,
                    showConfirmButton: false
                });
            });

            @this.on('return-processed', (event) => {
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
</div>