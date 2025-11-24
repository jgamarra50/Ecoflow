<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Dashboard de Entregas</h1>
            <p class="mt-2 text-gray-600">Gestiona tus entregas y revisa tu desempeño</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Pendientes -->
            <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-xl shadow-sm border-2 border-yellow-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-yellow-600">Pendientes</p>
                        <p class="text-3xl font-bold text-yellow-900 mt-2">{{ $stats['pending'] }}</p>
                    </div>
                    <div class="text-5xl">📦</div>
                </div>
            </div>

            <!-- En Camino -->
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl shadow-sm border-2 border-blue-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-blue-600">En Camino</p>
                        <p class="text-3xl font-bold text-blue-900 mt-2">{{ $stats['in_transit'] }}</p>
                    </div>
                    <div class="text-5xl">🚚</div>
                </div>
            </div>

            <!-- Completadas Hoy -->
            <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl shadow-sm border-2 border-green-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-green-600">Completadas Hoy</p>
                        <p class="text-3xl font-bold text-green-900 mt-2">{{ $stats['completed_today'] }}</p>
                    </div>
                    <div class="text-5xl">✅</div>
                </div>
            </div>

            <!-- Ganancias Hoy -->
            <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl shadow-sm border-2 border-purple-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-purple-600">Ganancias Hoy</p>
                        <p class="text-2xl font-bold text-purple-900 mt-2">${{ number_format($stats['earnings_today'], 0) }}</p>
                    </div>
                    <div class="text-5xl">💰</div>
                </div>
            </div>
        </div>

        <!-- Performance Summary -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Tu Desempeño</h3>
                    <span class="text-3xl">⭐</span>
                </div>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Total Entregas:</span>
                        <span class="font-bold text-gray-900">{{ $stats['total_deliveries'] }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Calificación:</span>
                        <span class="font-bold text-gray-900">{{ number_format($stats['average_rating'], 1) }} /5.0</span>
                    </div>
                    <div class="mt-2">
                        <div class="flex items-center space-x-1">
                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i <= floor($stats['average_rating']))
                                    <span class="text-yellow-400 text-xl">⭐</span>
                                @else
                                    <span class="text-gray-300 text-xl">⭐</span>
                                @endif
                            @endfor
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Acciones Rápidas</h3>
                <div class="grid grid-cols-2 gap-4">
                    <a href="{{ route('repartidor.deliveries') }}" 
                        class="flex items-center justify-center space-x-2 px-4 py-3 bg-orange-500 hover:bg-orange-600 text-white rounded-lg transition-colors">
                        <span class="text-2xl">📦</span>
                        <span class="font-medium">Ver Entregas</span>
                    </a>
                    <a href="{{ route('repartidor.history') }}" 
                        class="flex items-center justify-center space-x-2 px-4 py-3 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-colors">
                        <span class="text-2xl">📜</span>
                        <span class="font-medium">Historial</span>
                    </a>
                    <button wire:click="toggleAvailability" 
                        class="flex items-center justify-center space-x-2 px-4 py-3 {{ $availabilityStatus ? 'bg-green-500 hover:bg-green-600' : 'bg-gray-500 hover:bg-gray-600' }} text-white rounded-lg transition-colors">
                        <span class="text-2xl">{{ $availabilityStatus ? '✅' : '⏸️' }}</span>
                        <span class="font-medium">{{ $availabilityStatus ? 'Disponible' : 'No Disponible' }}</span>
                    </button>
                    <a href="{{ route('repartidor.performance') }}" 
                        class="flex items-center justify-center space-x-2 px-4 py-3 bg-purple-500 hover:bg-purple-600 text-white rounded-lg transition-colors">
                        <span class="text-2xl">📈</span>
                        <span class="font-medium">Desempeño</span>
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Pending Deliveries -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Entregas Pendientes</h3>
                </div>
                <div class="p-6">
                    @forelse($pendingDeliveries as $delivery)
                        <div class="mb-4 last:mb-0 p-4 border-2 {{ $delivery->status === 'in_transit' ? 'border-blue-300 bg-blue-50' : 'border-gray-200 bg-gray-50' }} rounded-lg">
                            <div class="flex items-start justify-between mb-2">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-2 mb-1">
                                        <span class="text-2xl">{{ $delivery->reservation->vehicle->getTypeIcon() }}</span>
                                        <span class="font-semibold text-gray-900">
                                            {{ $delivery->reservation->vehicle->brand }} {{ $delivery->reservation->vehicle->model }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-600">Cliente: {{ $delivery->reservation->user->name }}</p>
                                    <p class="text-sm text-gray-600">📍 {{Str::limit($delivery->delivery_address, 40) }}</p>
                                </div>
                                <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $delivery->getStatusColorClass() }}">
                                    {{ $delivery->getStatusLabel() }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between mt-3">
                                <span class="text-xs text-gray-500">
                                    🕐 {{ $delivery->scheduled_time->format('d/m H:i') }}
                                </span>
                                <a href="{{ route('repartidor.delivery', $delivery->id) }}" 
                                    class="px-3 py-1 bg-orange-500 hover:bg-orange-600 text-white text-sm rounded-md transition-colors">
                                    Ver Detalles →
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-gray-500">
                            <div class="text-6xl mb-3">📭</div>
                            <p>No tienes entregas pendientes</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Recent Completions -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Entregas Recientes</h3>
                </div>
                <div class="p-6">
                    @forelse($recentDeliveries as $delivery)
                        <div class="mb-4 last:mb-0 p-4 bg-green-50 border-2 border-green-200 rounded-lg">
                            <div class="flex items-start justify-between mb-2">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-2 mb-1">
                                        <span class="text-2xl">{{ $delivery->reservation->vehicle->getTypeIcon() }}</span>
                                        <span class="font-semibold text-gray-900">
                                            {{ $delivery->reservation->vehicle->brand }} {{ $delivery->reservation->vehicle->model }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-600">Cliente: {{ $delivery->reservation->user->name }}</p>
                                </div>
                                <span class="text-2xl">✅</span>
                            </div>
                            <div class="flex items-center justify-between mt-2">
                                <span class="text-xs text-gray-500">
                                    🕐 {{ $delivery->actual_delivery_time->format('d/m H:i') }}
                                </span>
                                <span class="text-sm font-semibold text-green-600">
                                    +${{ number_format($delivery->delivery_fee, 0) }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-gray-500">
                            <div class="text-6xl mb-3">📦</div>
                            <p>Aún no has completado entregas</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('livewire:initialized', () => {
        Livewire.on('availability-changed', (event) => {
            Swal.fire({
                icon: 'success',
                title: event.message,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });
        });
    });
</script>
@endpush
