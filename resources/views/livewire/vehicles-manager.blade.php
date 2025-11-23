<div class="py-8 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Header --}}
        <div class="mb-8 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Gestión de Vehículos</h1>
                <p class="mt-2 text-gray-600">Administra todos los vehículos del sistema</p>
            </div>
            <button wire:click="createVehicle"
                class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-lg font-medium transition-colors flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Nuevo Vehículo
            </button>
        </div>

        {{-- Filters Bar --}}
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                {{-- Search --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Buscar</label>
                    <input type="text" wire:model.live="search" placeholder="Marca, modelo, placa..."
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                {{-- Type Filter --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tipo</label>
                    <select wire:model.live="typeFilter"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="all">Todos</option>
                        <option value="scooter">🛴 Scooters</option>
                        <option value="bicycle">🚴 Bicicletas</option>
                        <option value="skateboard">🛹 Skateboards</option>
                    </select>
                </div>

                {{-- Status Filter --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                    <select wire:model.live="statusFilter"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="all">Todos</option>
                        <option value="available">Disponible</option>
                        <option value="reserved">Reservado</option>
                        <option value="maintenance">Mantenimiento</option>
                        <option value="damaged">Dañado</option>
                    </select>
                </div>

                {{-- Station Filter --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Estación</label>
                    <select wire:model.live="stationFilter"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="all">Todas</option>
                        @foreach($stations as $station)
                            <option value="{{ $station->id }}">{{ $station->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Reset Filters Button --}}
            @if($search || $typeFilter !== 'all' || $statusFilter !== 'all' || $stationFilter !== 'all')
                <div class="mt-4">
                    <button wire:click="resetFilters"
                        class="text-sm text-gray-600 hover:text-gray-900 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Limpiar filtros
                    </button>
                </div>
            @endif
        </div>

        {{-- Table --}}
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Icono</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vehículo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Placa</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ubicación</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($vehicles as $vehicle)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-3xl">{{ $vehicle->getTypeIcon() }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                        {{ $vehicle->type === 'scooter' ? 'bg-blue-100 text-blue-800' : '' }}
                                        {{ $vehicle->type === 'bicycle' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $vehicle->type === 'skateboard' ? 'bg-orange-100 text-orange-800' : '' }}">
                                        {{ ucfirst($vehicle->type) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $vehicle->brand }}</div>
                                    <div class="text-sm text-gray-500">{{ $vehicle->model }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-mono font-bold text-gray-900">{{ $vehicle->plate }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full border
                                        {{ $vehicle->status === 'available' ? 'bg-green-100 text-green-800 border-green-300' : '' }}
                                        {{ $vehicle->status === 'reserved' ? 'bg-gray-100 text-gray-800 border-gray-300' : '' }}
                                        {{ $vehicle->status === 'maintenance' ? 'bg-yellow-100 text-yellow-800 border-yellow-300' : '' }}
                                        {{ $vehicle->status === 'damaged' ? 'bg-red-100 text-red-800 border-red-300' : '' }}">
                                        {{ $vehicle->getStatusLabel() }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        </svg>
                                        {{ $vehicle->station->name ?? 'Sin estación' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-2">
                                        {{-- Edit --}}
                                        <button wire:click="editVehicle({{ $vehicle->id }})"
                                            class="text-indigo-600 hover:text-indigo-900" title="Editar">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>

                                        {{-- Change Status --}}
                                        <div x-data="{ open: false }" class="relative">
                                            <button @click="open = !open" class="text-gray-600 hover:text-gray-900" title="Cambiar estado">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                                </svg>
                                            </button>
                                            <div x-show="open" @click.away="open = false" 
                                                class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-10 border border-gray-200">
                                                <button wire:click="changeStatus({{ $vehicle->id }}, 'available')" @click="open = false"
                                                    class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                    🟢 Disponible
                                                </button>
                                                <button wire:click="changeStatus({{ $vehicle->id }}, 'maintenance')" @click="open = false"
                                                    class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                    🟡 Mantenimiento
                                                </button>
                                                <button wire:click="changeStatus({{ $vehicle->id }}, 'damaged')" @click="open = false"
                                                    class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                    🔴 Dañado
                                                </button>
                                            </div>
                                        </div>

                                        {{-- View Location --}}
                                        <button wire:click="viewLocation({{ $vehicle->id }})"
                                            class="text-green-600 hover:text-green-900" title="Ver ubicación">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            </svg>
                                        </button>

                                        {{-- Delete --}}
                                        <button wire:click="confirmDelete({{ $vehicle->id }})"
                                            class="text-red-600 hover:text-red-900" title="Eliminar">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-16 h-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                        </svg>
                                        <p class="text-gray-500 text-lg font-medium">No se encontraron vehículos</p>
                                        <p class="text-gray-400 text-sm mt-1">Intenta ajustar los filtros o crea un nuevo vehículo</p>
                                        <button wire:click="createVehicle" 
                                            class="mt-4 bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm">
                                            Crear primer vehículo
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($vehicles->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $vehicles->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- Create/Edit Modal --}}
    @if($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" x-data="{ show: @entangle('showModal') }">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                {{-- Overlay --}}
                <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" @click="$wire.closeModal()"></div>

                {{-- Modal Content --}}
                <div class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form wire:submit.prevent="save">
                        {{-- Header --}}
                        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">
                                {{ $editMode ? 'Editar Vehículo' : 'Nuevo Vehículo' }}
                            </h3>
                        </div>

                        {{-- Body --}}
                        <div class="px-6 py-4 space-y-4">
                            {{-- Type --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tipo *</label>
                                <select wire:model="type" 
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="scooter">🛴 Scooter</option>
                                    <option value="bicycle">🚴 Bicicleta</option>
                                    <option value="skateboard">🛹 Skateboard</option>
                                </select>
                                @error('type') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            {{-- Brand --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Marca *</label>
                                <input type="text" wire:model="brand" 
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    placeholder="Ej: EcoFlow">
                                @error('brand') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            {{-- Model --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Modelo *</label>
                                <input type="text" wire:model="model" 
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    placeholder="Ej: EcoMoto Pro">
                                @error('model') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            {{-- Plate --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Placa *</label>
                                <input type="text" wire:model="plate" 
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    placeholder="Ej: ECO-001">
                                @error('plate') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            {{-- Status --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Estado *</label>
                                <select wire:model="status" 
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="available">Disponible</option>
                                    <option value="maintenance">Mantenimiento</option>
                                    <option value="damaged">Dañado</option>
                                    <option value="reserved">Reservado</option>
                                </select>
                                @error('status') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            {{-- Station --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Estación *</label>
                                <select wire:model="station_id" 
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Selecciona una estación...</option>
                                    @foreach($stations as $station)
                                        <option value="{{ $station->id }}">{{ $station->name }}</option>
                                    @endforeach
                                </select>
                                @error('station_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            {{-- Location --}}
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Latitud</label>
                                    <input type="number" step="0.000001" wire:model="current_location_lat" 
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        placeholder="7.0799">
                                    @error('current_location_lat') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Longitud</label>
                                    <input type="number" step="0.000001" wire:model="current_location_lng" 
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        placeholder="-73.0978">
                                    @error('current_location_lng') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Footer --}}
                        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end space-x-3">
                            <button type="button" @click="$wire.closeModal()"
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                                Cancelar
                            </button>
                            <button type="submit"
                                class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-700">
                                {{ $editMode ? 'Actualizar' : 'Crear' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    {{-- Location Modal --}}
    @if($showLocationModal && $selectedVehicle)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" @click="$wire.closeLocationModal()"></div>

                <div class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-md sm:w-full">
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Ubicación del Vehículo</h3>
                    </div>
                    <div class="px-6 py-6">
                        <div class="space-y-3">
                            <div class="flex items-center text-2xl">
                                <span class="mr-3">{{ $selectedVehicle->getTypeIcon() }}</span>
                                <div>
                                    <p class="font-medium text-gray-900">{{ $selectedVehicle->brand }} {{ $selectedVehicle->model }}</p>
                                    <p class="text-sm text-gray-500">{{ $selectedVehicle->plate }}</p>
                                </div>
                            </div>
                            <div class="border-t border-gray-200 pt-3">
                                <p class="text-sm font-medium text-gray-700 mb-2">📍 Estación:</p>
                                <p class="text-sm text-gray-900">{{ $selectedVehicle->station->name }}</p>
                                <p class="text-xs text-gray-500">{{ $selectedVehicle->station->address }}</p>
                            </div>
                            @if($selectedVehicle->current_location_lat && $selectedVehicle->current_location_lng)
                                <div class="border-t border-gray-200 pt-3">
                                    <p class="text-sm font-medium text-gray-700 mb-2">🗺️ Coordenadas GPS:</p>
                                    <div class="bg-gray-50 p-3 rounded-md">
                                        <p class="text-xs font-mono text-gray-900">Lat: {{ $selectedVehicle->current_location_lat }}</p>
                                        <p class="text-xs font-mono text-gray-900">Lng: {{ $selectedVehicle->current_location_lng }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                        <button @click="$wire.closeLocationModal()"
                            class="w-full px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-700">
                            Cerrar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Scripts for notifications and confirmations --}}
    <script>
        document.addEventListener('livewire:initialized', () => {
            @this.on('vehicle-saved', (event) => {
                Swal.fire({
                    title: '¡Éxito!',
                    text: event.message,
                    icon: 'success',
                    confirmButtonColor: '#4F46E5',
                    timer: 2000
                });
            });

            @this.on('status-changed', (event) => {
                Swal.fire({
                    title: '¡Actualizado!',
                    text: event.message,
                    icon: 'success',
                    confirmButtonColor: '#4F46E5',
                    timer: 2000
                });
            });

            @this.on('vehicle-deleted', (event) => {
                Swal.fire({
                    title: '¡Eliminado!',
                    text: event.message,
                    icon: 'success',
                    confirmButtonColor: '#4F46E5',
                    timer: 2000
                });
            });

            @this.on('confirm-delete', (event) => {
                Swal.fire({
                    title: '¿Eliminar vehículo?',
                    text: "Esta acción no se puede deshacer",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#EF4444',
                    cancelButtonColor: '#6B7280',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        @this.call('delete', event.vehicleId);
                    }
                });
            });
        });
    </script>
</div>
