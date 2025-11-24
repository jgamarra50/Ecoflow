<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header with Filters -->
        <div class="mb-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Mis Entregas</h1>
                    <p class="mt-2 text-gray-600">Gestiona tus entregas asignadas</p>
                </div>
                
                <!-- Filters -->
                <div class="flex flex-wrap gap-3">
                    <select wire:model.live="statusFilter" 
                        class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                        <option value="all">Todos los Estados</option>
                        <option value="assigned">Asignadas</option>
                        <option value="in_transit">En Camino</option>
                        <option value="arrived">Llegadas</option>
                    </select>
                    
                    <select wire:model.live="typeFilter" 
                        class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                        <option value="all">Todos los Tipos</option>
                        <option value="delivery">Entregas</option>
                        <option value="pickup">Recogidas</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Deliveries Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($deliveries as $delivery)
                <div class="bg-white rounded-xl shadow-sm border-2 {{ $delivery->status === 'in_transit' ? 'border-blue-400' : 'border-gray-200' }} hover:shadow-lg transition-shadow p-6">
                    <!-- Header -->
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center space-x-2">
                            <span class="text-3xl">{{ $delivery->type === 'delivery' ? '📦' : '🔄' }}</span>
                            <div>
                                <h3 class="font-semibold text-gray-900">{{ $delivery->getTypeLabel() }}</h3>
                                <p class="text-xs text-gray-500">#{{ $delivery->id }}</p>
                            </div>
                        </div>
                        <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $delivery->getStatusColorClass() }}">
                            {{ $delivery->getStatusLabel() }}
                        </span>
                    </div>

                    <!-- Vehicle Info -->
                    <div class="mb-4 p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center space-x-2 mb-2">
                            <span class="text-2xl">{{ $delivery->reservation->vehicle->getTypeIcon() }}</span>
                            <div class="flex-1">
                                <p class="font-semibold text-gray-900">
                                    {{ $delivery->reservation->vehicle->brand }} {{ $delivery->reservation->vehicle->model }}
                                </p>
                                <p class="text-xs text-gray-600">{{ $delivery->reservation->vehicle->license_plate }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Customer Info -->
                    <div class="mb-4">
                        <p class="text-sm text-gray-600 mb-1">
                            <span class="font-medium">Cliente:</span> {{ $delivery->reservation->user->name }}
                        </p>
                        <p class="text-sm text-gray-600 mb-1">
                            <span class="font-medium">📞</span> {{ $delivery->reservation->user->phone }}
                        </p>
                        <p class="text-sm text-gray-600">
                            <span class="font-medium">📍</span> {{ Str::limit($delivery->delivery_address, 40) }}
                        </p>
                    </div>

                    <!-- Time -->
                    <div class="mb-4 p-3 bg-orange-50 border border-orange-200 rounded-lg">
                        <p class="text-sm font-medium text-orange-800">
                            🕐 {{ $delivery->scheduled_time->format('d/m/Y H:i') }}
                        </p>
                    </div>

                    <!-- Fee -->
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-sm text-gray-600">Tarifa:</span>
                        <span class="text-lg font-bold text-green-600">${{ number_format($delivery->delivery_fee, 0) }}</span>
                    </div>

                    <!-- Action Button -->
                    <a href="{{ route('repartidor.delivery', $delivery->id) }}" 
                        class="block w-full text-center px-4 py-3 bg-gradient-to-r from-orange-500 to-red-500 hover:from-orange-600 hover:to-red-600 text-white font-semibold rounded-lg transition-all transform hover:scale-105">
                        Ver Detalles →
                    </a>
                </div>
            @empty
                <div class="col-span-full">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
                        <div class="text-8xl mb-4">📭</div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">No hay entregas</h3>
                        <p class="text-gray-600">No tienes entregas con los filtros seleccionados</p>
                    </div>
                </div>
            @endforelse
        </div>

        @if($deliveries->count() > 0)
            <div class="mt-6 text-center text-sm text-gray-600">
                Mostrando {{ $deliveries->count() }} {{ $deliveries->count() === 1 ? 'entrega' : 'entregas' }}
            </div>
        @endif
    </div>
</div>
