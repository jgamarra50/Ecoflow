<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        {{-- Header --}}
        <div class="mb-8">
            <h2 class="text-3xl font-bold text-gray-900">Mis Reservas</h2>
            <p class="mt-2 text-gray-600">Gestiona y revisa todas tus reservas de vehículos</p>
        </div>

        {{-- Status Filters --}}
        <div class="mb-6 flex flex-wrap gap-2">
            <button 
                wire:click="setStatusFilter('all')"
                class="px-4 py-2 rounded-full text-sm font-medium transition-colors {{ $statusFilter === 'all' ? 'bg-indigo-600 text-white shadow-md' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                Todas
            </button>
            <button 
                wire:click="setStatusFilter('pending')"
                class="px-4 py-2 rounded-full text-sm font-medium transition-colors {{ $statusFilter === 'pending' ? 'bg-yellow-500 text-white shadow-md' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                Pendientes
            </button>
            <button 
                wire:click="setStatusFilter('confirmed')"
                class="px-4 py-2 rounded-full text-sm font-medium transition-colors {{ $statusFilter === 'confirmed' ? 'bg-blue-600 text-white shadow-md' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                Confirmadas
            </button>
            <button 
                wire:click="setStatusFilter('active')"
                class="px-4 py-2 rounded-full text-sm font-medium transition-colors {{ $statusFilter === 'active' ? 'bg-green-600 text-white shadow-md' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                Activas
            </button>
            <button 
                wire:click="setStatusFilter('completed')"
                class="px-4 py-2 rounded-full text-sm font-medium transition-colors {{ $statusFilter === 'completed' ? 'bg-gray-700 text-white shadow-md' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                Completadas
            </button>
            <button 
                wire:click="setStatusFilter('cancelled')"
                class="px-4 py-2 rounded-full text-sm font-medium transition-colors {{ $statusFilter === 'cancelled' ? 'bg-red-600 text-white shadow-md' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                Canceladas
            </button>
        </div>

        {{-- Reservations Grid --}}
        @if($this->reservations->count() > 0)
            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                @foreach($this->reservations as $reservation)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                        {{-- Card Header --}}
                        <div class="bg-gradient-to-r from-indigo-500 to-purple-600 p-4 text-white">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <span class="text-4xl">{{ $reservation->vehicle->getTypeIcon() }}</span>
                                    <div>
                                        <h3 class="font-bold text-lg">{{ $reservation->vehicle->brand }}</h3>
                                        <p class="text-sm opacity-90">{{ $reservation->vehicle->model }}</p>
                                    </div>
                                </div>
                                <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $reservation->getStatusColorClass() }} bg-white">
                                    {{ $reservation->getStatusLabel() }}
                                </span>
                            </div>
                        </div>

                        {{-- Card Body --}}
                        <div class="p-4 space-y-3">
                            {{-- Dates --}}
                            <div class="flex items-start space-x-2 text-sm">
                                <svg class="w-5 h-5 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <div>
                                    <p class="font-medium text-gray-900">{{ $reservation->getFormattedDates()['start_short'] }} - {{ $reservation->getFormattedDates()['end_short'] }}</p>
                                    <p class="text-gray-500">{{ $reservation->getDurationInDays() }} día(s)</p>
                                </div>
                            </div>

                            {{-- Delivery Method --}}
                            <div class="flex items-center space-x-2 text-sm">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <div>
                                    <p class="text-gray-900">
                                        @if($reservation->delivery_method === 'pickup')
                                            Recogida en estación
                                        @else
                                            Entrega a domicilio
                                        @endif
                                    </p>
                                    @if($reservation->station)
                                        <p class="text-gray-500 text-xs">{{ $reservation->station->name }}</p>
                                    @endif
                                </div>
                            </div>

                            {{-- Price --}}
                            <div class="flex items-center justify-between pt-2 border-t border-gray-200">
                                <span class="text-sm font-medium text-gray-600">Total</span>
                                <span class="text-xl font-bold text-indigo-600">${{ number_format($reservation->total_price, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        {{-- Card Actions --}}
                        <div class="px-4 pb-4 flex gap-2">
                            {{-- View Details Button (Always) --}}
                            <button 
                                wire:click="viewDetails({{ $reservation->id }})"
                                class="flex-1 px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 transition-colors">
                                Ver Detalles
                            </button>

                            {{-- Status-specific actions --}}
                            @if($reservation->isPending() || $reservation->isConfirmed())
                                <button 
                                    wire:click="cancelReservation({{ $reservation->id }})"
                                    wire:confirm="¿Estás seguro de cancelar esta reserva?"
                                    class="px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700 transition-colors">
                                    Cancelar
                                </button>
                            @endif

                            @if($reservation->isActive())
                                <button 
                                    wire:click="viewLocation({{ $reservation->id }})"
                                    class="px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700 transition-colors">
                                    📍 Ubicación
                                </button>
                            @endif

                            @if($reservation->status === 'completed')
                                <button 
                                    wire:click="downloadReceipt({{ $reservation->id }})"
                                    class="px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-md hover:bg-gray-700 transition-colors">
                                    📄 Recibo
                                </button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            {{-- Empty State --}}
            <div class="text-center py-12 bg-white rounded-lg shadow">
                <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900">No tienes reservas</h3>
                <p class="mt-2 text-gray-500">
                    @if($statusFilter !== 'all')
                        No hay reservas {{ $statusFilter }}. Prueba con otro filtro.
                    @else
                        ¡Comienza a reservar vehículos para tu movilidad!
                    @endif
                </p>
                <div class="mt-6">
                    <a href="{{ route('reservations.new') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white font-medium rounded-md hover:bg-indigo-700 transition-colors">
                        Nueva Reserva
                    </a>
                </div>
            </div>
        @endif
    </div>

    {{-- Details Modal --}}
    @if($showModal && $selectedReservation)
        <div 
            x-data="{ show: @entangle('showModal') }"
            x-show="show"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 overflow-y-auto"
            style="display: none;">
            
            {{-- Backdrop --}}
            <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" wire:click="closeModal"></div>

            {{-- Modal Content --}}
            <div class="flex min-h-full items-center justify-center p-4">
                <div 
                    x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="relative bg-white rounded-lg shadow-xl max-w-2xl w-full">
                    
                    {{-- Modal Header --}}
                    <div class="bg-gradient-to-r from-indigo-500 to-purple-600 px-6 py-4 rounded-t-lg">
                        <div class="flex items-center justify-between">
                            <h3 class="text-xl font-bold text-white">Detalles de Reserva #{{ $selectedReservation->id }}</h3>
                            <button wire:click="closeModal" class="text-white hover:text-gray-200 transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                        <span class="inline-block mt-2 px-3 py-1 rounded-full text-sm font-semibold {{ $selectedReservation->getStatusColorClass() }} bg-white">
                            {{ $selectedReservation->getStatusLabel() }}
                        </span>
                    </div>

                    {{-- Modal Body --}}
                    <div class="px-6 py-4 max-h-96 overflow-y-auto">
                        {{-- Vehicle Info --}}
                        <div class="mb-6">
                            <h4 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
                                <span class="text-2xl mr-2">{{ $selectedReservation->vehicle->getTypeIcon() }}</span>
                                Información del Vehículo
                            </h4>
                            <div class="bg-gray-50 rounded-lg p-4 space-y-2">
                                <p><span class="font-medium">Tipo:</span> {{ ucfirst($selectedReservation->vehicle->type) }}</p>
                                <p><span class="font-medium">Marca:</span> {{ $selectedReservation->vehicle->brand }}</p>
                                <p><span class="font-medium">Modelo:</span> {{ $selectedReservation->vehicle->model }}</p>
                                <p><span class="font-medium">Placa:</span> {{ $selectedReservation->vehicle->plate }}</p>
                            </div>
                        </div>

                        {{-- Dates & Duration --}}
                        <div class="mb-6">
                            <h4 class="text-lg font-semibold text-gray-900 mb-3">Fechas</h4>
                            <div class="bg-gray-50 rounded-lg p-4 space-y-2">
                                <p><span class="font-medium">Inicio:</span> {{ $selectedReservation->getFormattedDates()['start'] }}</p>
                                <p><span class="font-medium">Fin:</span> {{ $selectedReservation->getFormattedDates()['end'] }}</p>
                                <p><span class="font-medium">Duración:</span> {{ $selectedReservation->getDurationInDays() }} día(s)</p>
                            </div>
                        </div>

                        {{-- Delivery Information --}}
                        <div class="mb-6">
                            <h4 class="text-lg font-semibold text-gray-900 mb-3">Información de Entrega</h4>
                            <div class="bg-gray-50 rounded-lg p-4 space-y-2">
                                <p><span class="font-medium">Método:</span> 
                                    @if($selectedReservation->delivery_method === 'pickup')
                                        Recogida en estación
                                    @else
                                        Entrega a domicilio
                                    @endif
                                </p>
                                @if($selectedReservation->station)
                                    <p><span class="font-medium">Estación:</span> {{ $selectedReservation->station->name }}</p>
                                    <p><span class="font-medium">Dirección:</span> {{ $selectedReservation->station->address }}, {{ $selectedReservation->station->city }}</p>
                                @endif
                                @if($selectedReservation->delivery_address)
                                    <p><span class="font-medium">Dirección de entrega:</span> {{ $selectedReservation->delivery_address }}</p>
                                @endif
                            </div>
                        </div>

                        {{-- Price Breakdown --}}
                        <div class="mb-4">
                            <h4 class="text-lg font-semibold text-gray-900 mb-3">Desglose de Precio</h4>
                            <div class="bg-gray-50 rounded-lg p-4 space-y-2">
                                @php $breakdown = $selectedReservation->getPriceBreakdown(); @endphp
                                <div class="flex justify-between">
                                    <span>Alquiler ({{ $breakdown['days'] }} día(s) × $50,000)</span>
                                    <span>${{ number_format($breakdown['base_price'], 0, ',', '.') }}</span>
                                </div>
                                @if($breakdown['delivery_fee'] > 0)
                                    <div class="flex justify-between">
                                        <span>Cargo por entrega</span>
                                        <span>${{ number_format($breakdown['delivery_fee'], 0, ',', '.') }}</span>
                                    </div>
                                @endif
                                <div class="flex justify-between pt-2 border-t border-gray-300 font-bold text-lg">
                                    <span>Total</span>
                                    <span class="text-indigo-600">${{ number_format($breakdown['total'], 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Modal Footer --}}
                    <div class="px-6 py-4 bg-gray-50 rounded-b-lg flex justify-end gap-3">
                        @if($selectedReservation->canBeCancelled())
                            <button 
                                wire:click="cancelReservation({{ $selectedReservation->id }})"
                                wire:confirm="¿Estás seguro de cancelar esta reserva?"
                                class="px-4 py-2 bg-red-600 text-white font-medium rounded-md hover:bg-red-700 transition-colors">
                                Cancelar Reserva
                            </button>
                        @endif
                        <button 
                            wire:click="closeModal"
                            class="px-4 py-2 bg-gray-600 text-white font-medium rounded-md hover:bg-gray-700 transition-colors">
                            Cerrar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Toast Notifications --}}
    <div x-data="{
        show: false,
        message: '',
        type: 'success'
    }"
    @reservation-cancelled.window="show = true; message = $event.detail.message; type = 'success'; setTimeout(() => show = false, 3000)"
    @show-error.window="show = true; message = $event.detail.message; type = 'error'; setTimeout(() => show = false, 3000)"
    x-show="show"
    x-transition:enter="transform ease-out duration-300 transition"
    x-transition:enter-start="translate-y-2 opacity-0"
    x-transition:enter-end="translate-y-0 opacity-100"
    x-transition:leave="transition ease-in duration-100"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed bottom-4 right-4 z-50"
    style="display: none;">
        <div :class="type === 'success' ? 'bg-green-500' : 'bg-red-500'" class="px-6 py-4 rounded-lg shadow-lg text-white">
            <p x-text="message"></p>
        </div>
    </div>
</div>
