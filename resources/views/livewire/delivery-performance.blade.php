<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Mi Desempeño</h1>
            <p class="mt-2 text-gray-600">Estadísticas y métricas de tu rendimiento</p>
        </div>

        <!-- Period Selector -->
        <div class="mb-6">
            <div class="inline-flex rounded-lg border border-gray-300 bg-white p-1">
                <button wire:click="$set('period', 'week')" 
                    class="px-4 py-2 text-sm font-medium rounded-md {{ $period === 'week' ? 'bg-orange-500 text-white' : 'text-gray-700 hover:bg-gray-100' }} transition-colors">
                    Semana
                </button>
                <button wire:click="$set('period', 'month')" 
                    class="px-4 py-2 text-sm font-medium rounded-md {{ $period === 'month' ? 'bg-orange-500 text-white' : 'text-gray-700 hover:bg-gray-100' }} transition-colors">
                    Mes
                </button>
                <button wire:click="$set('period', 'year')" 
                    class="px-4 py-2 text-sm font-medium rounded-md {{ $period === 'year' ? 'bg-orange-500 text-white' : 'text-gray-700 hover:bg-gray-100' }} transition-colors">
                    Año
                </button>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Deliveries -->
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl shadow-sm border-2 border-blue-200 p-6">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-4xl">📦</span>
                    <div class="text-right">
                        <p class="text-sm font-medium text-blue-600">Entregas</p>
                        <p class="text-3xl font-bold text-blue-900">{{ $stats['total_deliveries'] }}</p>
                    </div>
                </div>
                <p class="text-xs text-blue-700">En el período seleccionado</p>
            </div>

            <!-- Total Earnings -->
            <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl shadow-sm border-2 border-green-200 p-6">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-4xl">💰</span>
                    <div class="text-right">
                        <p class="text-sm font-medium text-green-600">Ganancias</p>
                        <p class="text-2xl font-bold text-green-900">${{ number_format($stats['total_earnings'], 0) }}</p>
                    </div>
                </div>
                <p class="text-xs text-green-700">En el período seleccionado</p>
            </div>

            <!-- Average Per Day -->
            <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl shadow-sm border-2 border-purple-200 p-6">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-4xl">📊</span>
                    <div class="text-right">
                        <p class="text-sm font-medium text-purple-600">Promedio/Día</p>
                        <p class="text-3xl font-bold text-purple-900">{{ number_format($stats['average_per_day'], 1) }}</p>
                    </div>
                </div>
                <p class="text-xs text-purple-700">Entregas por día</p>
            </div>

            <!-- Completion Rate -->
            <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-xl shadow-sm border-2 border-yellow-200 p-6">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-4xl">✅</span>
                    <div class="text-right">
                        <p class="text-sm font-medium text-yellow-600">Tasa Éxito</p>
                        <p class="text-3xl font-bold text-yellow-900">{{ $stats['completion_rate'] }}%</p>
                    </div>
                </div>
                <p class="text-xs text-yellow-700">Entregas completadas</p>
            </div>
        </div>

        <!-- Overall Performance Stats -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <span class="text-2xl mr-2">⭐</span>
                    Estadísticas Generales
                </h3>
                <div class="space-y-4">
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                        <span class="text-gray-700">Total de Entregas:</span>
                        <span class="text-xl font-bold text-gray-900">{{ auth()->user()->total_deliveries ?? 0 }}</span>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                        <span class="text-gray-700">Calificación Promedio:</span>
                        <div class="flex items-center space-x-2">
                            <span class="text-xl font-bold text-yellow-600">{{ number_format(auth()->user()->average_rating ?? 0, 1) }}</span>
                            <span class="text-yellow-400 text-2xl">⭐</span>
                        </div>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                        <span class="text-gray-700">Horario de Trabajo:</span>
                        <span class="text-sm font-semibold text-gray-900">
                            {{ auth()->user()->shift_start ? \Carbon\Carbon::parse(auth()->user()->shift_start)->format('H:i') : 'N/A' }} - 
                            {{ auth()->user()->shift_end ? \Carbon\Carbon::parse(auth()->user()->shift_end)->format('H:i') : 'N/A' }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                        <span class="text-gray-700">Tipo de Vehículo:</span>
                        <span class="text-sm font-semibold text-gray-900 capitalize">
                            {{ auth()->user()->vehicle_type ?? 'N/A' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Daily Stats Chart -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <span class="text-2xl mr-2">📈</span>
                    Actividad de los Últimos 7 Días
                </h3>
                <div class="space-y-3">
                    @forelse($dailyStats as $dayStat)
                        <div class="flex items-center space-x-3">
                            <div class="text-xs text-gray-600 w-24">
                                {{ \Carbon\Carbon::parse($dayStat->date)->format('d M') }}
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center space-x-2">
                                    <div class="flex-1 bg-gray-200 rounded-full h-4 overflow-hidden">
                                        <div class="bg-gradient-to-r from-orange-500 to-red-500 h-full rounded-full transition-all" 
                                            style="width: {{ min(100, ($dayStat->count / max(1, $dailyStats->max('count'))) * 100) }}%">
                                        </div>
                                    </div>
                                    <span class="text-sm font-semibold text-gray-900 w-8">{{ $dayStat->count }}</span>
                                </div>
                            </div>
                            <div class="text-xs text-green-600 font-semibold w-20 text-right">
                                ${{ number_format($dayStat->earnings, 0) }}
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-gray-500">
                            <div class="text-5xl mb-2">📊</div>
                            <p class="text-sm">No hay datos para mostrar</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Performance Tips -->
        <div class="bg-gradient-to-r from-orange-50 to-red-50 rounded-xl border-2 border-orange-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <span class="text-2xl mr-2">💡</span>
                Consejos para Mejorar tu Desempeño
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white rounded-lg p-4 border border-orange-200">
                    <div class="text-3xl mb-2">⏰</div>
                    <h4 class="font-semibold text-gray-900 mb-1">Puntualidad</h4>
                    <p class="text-sm text-gray-600">Llega a tiempo a tus entregas para mantener alta calificación</p>
                </div>
                <div class="bg-white rounded-lg p-4 border border-orange-200">
                    <div class="text-3xl mb-2">😊</div>
                    <h4 class="font-semibold text-gray-900 mb-1">Servicio al Cliente</h4>
                    <p class="text-sm text-gray-600">Mantén una actitud profesional y amable con los clientes</p>
                </div>
                <div class="bg-white rounded-lg p-4 border border-orange-200">
                    <div class="text-3xl mb-2">📱</div>
                    <h4 class="font-semibold text-gray-900 mb-1">Comunicación</h4>
                    <p class="text-sm text-gray-600">Actualiza el estado de las entregas en tiempo real</p>
                </div>
            </div>
        </div>
    </div>
</div>
