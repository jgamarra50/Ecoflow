<div class="py-8 bg-gradient-to-br from-amber-50 to-orange-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Header --}}
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Vehículos y Telemetría</h1>
            <p class="mt-2 text-gray-600">Monitorea el estado de todos los vehículos</p>
        </div>

        {{-- Search and Filters --}}
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Buscar</label>
                    <input type="text" wire:model.live="search" 
                        placeholder="Buscar por marca, modelo o placa..."
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                    <select wire:model.live="statusFilter"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500">
                        <option value="all">Todos</option>
                        <option value="available">Disponible</option>
                        <option value="in_use">En Uso</option>
                        <option value="maintenance">Mantenimiento</option>
                        <option value="charging">Cargando</option>
                        <option value="damaged">Dañado</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- Vehicles Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($vehicles as $vehicle)
                <div class="bg-white rounded-lg shadow-md hover:shadow-xl transition-shadow overflow-hidden">
                    {{-- Header with Status --}}
                    <div class="p-4 bg-gradient-to-r from-gray-50 to-gray-100 border-b">
                        <div class="flex justify-between items-start">
                            <div class="flex items-center">
                                <span class="text-3xl mr-3">{{ $vehicle->getTypeIcon() }}</span>
                                <div>
                                    <h3 class="font-bold text-gray-900">{{ $vehicle->brand }}</h3>
                                    <p class="text-sm text-gray-600">{{ $vehicle->model }}</p>
                                    <p class="text-xs text-gray-500">{{ $vehicle->plate }}</p>
                                </div>
                            </div>
                            <span class="px-3 py-1 text-xs font-semibold rounded-full border {{ $vehicle->getStatusColorClass() }}">
                                {{ $vehicle->getStatusLabel() }}
                            </span>
                        </div>
                    </div>

                    {{-- Telemetry Indicators --}}
                    @if($vehicle->telemetry)
                        <div class="p-4 space-y-3">
                            {{-- Battery Level --}}
                            <div>
                                <div class="flex justify-between items-center mb-1">
                                    <span class="text-xs font-medium text-gray-700">🔋 Batería</span>
                                    <span class="text-xs font-bold {{ $vehicle->telemetry->battery_level < 20 ? 'text-red-600' : ($vehicle->telemetry->battery_level < 50 ? 'text-yellow-600' : 'text-green-600') }}">
                                        {{ $vehicle->telemetry->battery_level }}%
                                    </span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="h-2 rounded-full {{ $vehicle->telemetry->battery_level < 20 ? 'bg-red-500' : ($vehicle->telemetry->battery_level < 50 ? 'bg-yellow-500' : 'bg-green-500') }}"
                                        style="width: {{ $vehicle->telemetry->battery_level }}%"></div>
                                </div>
                            </div>

                            {{-- Last Ping --}}
                            <div class="flex justify-between items-center">
                                <span class="text-xs font-medium text-gray-700">📡 Último ping</span>
                                <span class="text-xs text-gray-600">
                                    {{ $vehicle->telemetry->last_ping ? $vehicle->telemetry->last_ping->diffForHumans() : 'N/A' }}
                                </span>
                            </div>

                            {{-- Mileage --}}
                            <div class="flex justify-between items-center">
                                <span class="text-xs font-medium text-gray-700">🛣️ Kilometraje</span>
                                <span class="text-xs font-bold text-gray-900">
                                    {{ number_format($vehicle->telemetry->mileage, 0, ',', '.') }} km
                                </span>
                            </div>

                            {{-- Location --}}
                            <div class="flex justify-between items-center">
                                <span class="text-xs font-medium text-gray-700">📍 Ubicación</span>
                                <span class="text-xs text-gray-600">{{ $vehicle->station->name ?? 'N/A' }}</span>
                            </div>

                            {{-- GPS Coordinates --}}
                            @if($vehicle->telemetry->latitude && $vehicle->telemetry->longitude)
                                <div class="flex justify-between items-center">
                                    <span class="text-xs font-medium text-gray-700">🗺️ GPS</span>
                                    <span class="text-xs text-gray-500">
                                        {{ number_format($vehicle->telemetry->latitude, 6) }}, {{ number_format($vehicle->telemetry->longitude, 6) }}
                                    </span>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="p-4">
                            <p class="text-sm text-gray-500 text-center">Sin datos de telemetría</p>
                        </div>
                    @endif

                    {{-- Actions --}}
                    <div class="p-4 bg-gray-50 border-t space-y-2">
                        <div class="grid grid-cols-2 gap-2">
                            <button type="button" wire:click="viewHistory({{ $vehicle->id }})"
                                class="px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                                📋 Historial
                            </button>
                            <button type="button" wire:click="openReportModal({{ $vehicle->id }})"
                                class="px-3 py-2 bg-orange-600 hover:bg-orange-700 text-white text-sm font-medium rounded-lg transition-colors">
                                ⚠️ Reportar
                            </button>
                        </div>

                        {{-- Quick Status Update --}}
                        <div x-data="{ open: false }" class="relative">
                            <button type="button" @click.prevent="open = !open"
                                class="w-full px-3 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors">
                                Cambiar Estado
                            </button>
                            <div x-show="open" @click.outside="open = false"
                                class="absolute bottom-full left-0 right-0 mb-2 bg-white rounded-md shadow-xl z-50 border border-gray-200 py-1"
                                style="display: none;">
                                <div wire:click="updateVehicleStatus({{ $vehicle->id }}, 'available')" @click="open = false"
                                    class="px-4 py-2 text-sm text-gray-700 hover:bg-green-50 cursor-pointer">
                                    🟢 Disponible
                                </div>
                                <div wire:click="updateVehicleStatus({{ $vehicle->id }}, 'maintenance')" @click="open = false"
                                    class="px-4 py-2 text-sm text-gray-700 hover:bg-yellow-50 cursor-pointer">
                                    🟡 Mantenimiento
                                </div>
                                <div wire:click="updateVehicleStatus({{ $vehicle->id }}, 'charging')" @click="open = false"
                                    class="px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 cursor-pointer">
                                    🔵 Cargando
                                </div>
                                <div wire:click="updateVehicleStatus({{ $vehicle->id }}, 'damaged')" @click="open = false"
                                    class="px-4 py-2 text-sm text-gray-700 hover:bg-red-50 cursor-pointer">
                                    🔴 Dañado
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full">
                    <div class="bg-white rounded-lg shadow-md p-12 text-center">
                        <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-gray-500 text-lg font-medium">No se encontraron vehículos</p>
                    </div>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if($vehicles->hasPages())
            <div class="mt-6">
                {{ $vehicles->links() }}
            </div>
        @endif
    </div>

    {{-- History Modal --}}
    @if($showHistoryModal && $selectedVehicle)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" wire:click="closeHistoryModal"></div>

                <div class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full max-h-[90vh] overflow-y-auto">
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200 flex justify-between items-center">
                        <h3 class="text-lg font-bold text-gray-900">Historial de Mantenimientos</h3>
                        <button type="button" wire:click="closeHistoryModal" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="px-6 py-4">
                        <div class="mb-4 bg-gray-50 p-4 rounded-lg">
                            <div class="flex items-center">
                                <span class="text-3xl mr-3">{{ $selectedVehicle->getTypeIcon() }}</span>
                                <div>
                                    <p class="font-bold text-gray-900">{{ $selectedVehicle->brand }} {{ $selectedVehicle->model }}</p>
                                    <p class="text-sm text-gray-500">{{ $selectedVehicle->plate }}</p>
                                </div>
                            </div>
                        </div>

                        @if($maintenanceHistory->count() > 0)
                            <div class="space-y-3">
                                @foreach($maintenanceHistory as $item)
                                    <div class="border border-gray-200 rounded-lg p-4">
                                        <div class="flex justify-between items-start mb-2">
                                            <div>
                                                <h4 class="font-medium text-gray-900">{{ $item->title }}</h4>
                                                <p class="text-sm text-gray-600 mt-1">{{ $item->description }}</p>
                                            </div>
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full border {{ $item->getStatusColorClass() }}">
                                                {{ $item->getStatusLabel() }}
                                            </span>
                                        </div>
                                        <div class="mt-3 grid grid-cols-2 gap-2 text-sm">
                                            <div>
                                                <p class="text-gray-500">Técnico</p>
                                                <p class="text-gray-900">{{ $item->technician?->name ?? 'N/A' }}</p>
                                            </div>
                                            <div>
                                                <p class="text-gray-500">Fecha</p>
                                                <p class="text-gray-900">{{ $item->created_at->format('d/m/Y') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-center text-gray-500 py-8">No hay historial de mantenimientos</p>
                        @endif
                    </div>

                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                        <button type="button" wire:click="closeHistoryModal"
                            class="w-full px-4 py-2 bg-gray-700 hover:bg-gray-800 text-white font-medium rounded-lg transition-colors">
                            Cerrar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Report Modal --}}
    @if($showReportModal && $selectedVehicle)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" wire:click="closeReportModal"></div>

                <div class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form wire:submit.prevent="createReport">
                        <div class="px-6 py-4 bg-gradient-to-r from-orange-600 to-amber-600 text-white">
                            <h3 class="text-xl font-bold">Crear Reporte de Mantenimiento</h3>
                        </div>

                        <div class="px-6 py-6 space-y-4">
                            {{-- Vehicle Info --}}
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <div class="flex items-center">
                                    <span class="text-3xl mr-3">{{ $selectedVehicle->getTypeIcon() }}</span>
                                    <div>
                                        <p class="font-bold text-gray-900">{{ $selectedVehicle->brand }} {{ $selectedVehicle->model }}</p>
                                        <p class="text-sm text-gray-500">{{ $selectedVehicle->plate }}</p>
                                    </div>
                                </div>
                            </div>

                            {{-- Title --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Título *</label>
                                <input type="text" wire:model="title"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500"
                                    placeholder="Ej: Falla en frenos">
                                @error('title') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            {{-- Description --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Descripción del Problema *</label>
                                <textarea wire:model="description" rows="4"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500"
                                    placeholder="Describe detalladamente el problema encontrado..."></textarea>
                                @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            {{-- Priority --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Prioridad *</label>
                                <select wire:model="priority"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500">
                                    <option value="low">Baja</option>
                                    <option value="medium">Media</option>
                                    <option value="high">Alta</option>
                                    <option value="urgent">Urgente</option>
                                </select>
                                @error('priority') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            {{-- New Status --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nuevo Estado del Vehículo *</label>
                                <select wire:model="newStatus"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500">
                                    <option value="available">Disponible</option>
                                    <option value="maintenance">Mantenimiento</option>
                                    <option value="charging">Cargando</option>
                                    <option value="damaged">Dañado</option>
                                </select>
                                @error('newStatus') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end gap-3">
                            <button type="button" wire:click="closeReportModal"
                                class="px-4 py-2 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium rounded-lg transition-colors">
                                Cancelar
                            </button>
                            <button type="submit"
                                class="px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white font-medium rounded-lg transition-colors">
                                Crear Reporte
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    {{-- Notification Scripts --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('livewire:initialized', () => {
            @this.on('maintenance-created', (event) => {
                Swal.fire({
                    title: '¡Reporte Creado!',
                    text: event.message,
                    icon: 'success',
                    confirmButtonColor: '#ea580c',
                    timer: 2000
                });
            });

            @this.on('status-updated', (event) => {
                Swal.fire({
                    title: '¡Actualizado!',
                    text: event.message,
                    icon: 'success',
                    confirmButtonColor: '#ea580c',
                    timer: 2000
                });
            });
        });
    </script>
</div>
