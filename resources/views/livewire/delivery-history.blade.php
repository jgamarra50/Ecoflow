<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Historial de Entregas</h1>
            <p class="mt-2 text-gray-600">Revisa tus entregas completadas</p>
        </div>

        <!-- Summary Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl border-2 border-green-200 p-4">
                <p class="text-sm text-green-600 font-medium">Total Entregas</p>
                <p class="text-3xl font-bold text-green-900 mt-1">{{ $deliveries->total() }}</p>
            </div>
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl border-2 border-blue-200 p-4">
                <p class="text-sm text-blue-600 font-medium">Esta Semana</p>
                <p class="text-3xl font-bold text-blue-900 mt-1">
                    {{ \App\Models\Delivery::where('delivery_person_id', auth()->id())
                        ->where('status', 'delivered')
                        ->whereBetween('actual_delivery_time', [now()->startOfWeek(), now()->endOfWeek()])
                        ->count() }}
                </p>
            </div>
            <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl border-2 border-purple-200 p-4">
                <p class="text-sm text-purple-600 font-medium">Este Mes</p>
                <p class="text-3xl font-bold text-purple-900 mt-1">
                    {{ \App\Models\Delivery::where('delivery_person_id', auth()->id())
                        ->where('status', 'delivered')
                        ->whereMonth('actual_delivery_time', now()->month)
                        ->count() }}
                </p>
            </div>
            <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-xl border-2 border-yellow-200 p-4">
                <p class="text-sm text-yellow-600 font-medium">Ganancias Totales</p>
                <p class="text-2xl font-bold text-yellow-900 mt-1">
                    ${{ number_format(\App\Models\Delivery::where('delivery_person_id', auth()->id())
                        ->where('status', 'delivered')
                        ->sum('delivery_fee'), 0) }}
                </p>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-6">
            <div class="flex flex-col md:flex-row gap-4">
                <div class="flex-1">
                    <input type="text" 
                        wire:model.live.debounce.300ms="search"
                        placeholder="Buscar por nombre de cliente..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                </div>
                <div>
                    <select wire:model.live="dateFilter" 
                        class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                        <option value="all">Todas las Fechas</option>
                        <option value="today">Hoy</option>
                        <option value="week">Esta Semana</option>
                        <option value="month">Este Mes</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Deliveries Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vehículo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cliente</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dirección</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tarifa</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($deliveries as $delivery)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $delivery->actual_delivery_time->format('d/m/Y') }}</div>
                                    <div class="text-xs text-gray-500">{{ $delivery->actual_delivery_time->format('H:i') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-2xl">{{ $delivery->type === 'delivery' ? '📦' : '🔄' }}</span>
                                    <span class="text-sm text-gray-900 ml-1">{{ $delivery->getTypeLabel() }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center space-x-2">
                                        <span class="text-xl">{{ $delivery->reservation->vehicle->getTypeIcon() }}</span>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $delivery->reservation->vehicle->brand }} {{ $delivery->reservation->vehicle->model }}
                                            </div>
                                            <div class="text-xs text-gray-500">{{ $delivery->reservation->vehicle->license_plate }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">{{ $delivery->reservation->user->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $delivery->reservation->user->phone }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-600 max-w-xs">
                                        {{ Str::limit($delivery->delivery_address, 40) }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-semibold text-green-600">
                                        ${{ number_format($delivery->delivery_fee, 0) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="text-6xl mb-3">📦</div>
                                    <p class="text-gray-500">No se encontraron entregas</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($deliveries->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $deliveries->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
