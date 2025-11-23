<div class="py-8 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Header --}}
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Gestión de Mantenimientos</h1>
            <p class="mt-2 text-gray-600">Administra todos los mantenimientos de vehículos</p>
        </div>

        {{-- Filters --}}
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                {{-- Status Filter --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                    <select wire:model.live="statusFilter"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="all">Todos</option>
                        <option value="pending">Pendiente</option>
                        <option value="assigned">Asignado</option>
                        <option value="in_progress">En Progreso</option>
                        <option value="completed">Completado</option>
                        <option value="cancelled">Cancelado</option>
                    </select>
                </div>

                {{-- Technician Filter --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Técnico</label>
                    <select wire:model.live="technicianFilter"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="all">Todos</option>
                        @foreach($technicians as $tech)
                            <option value="{{ $tech->id }}">{{ $tech->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        {{-- Maintenances Table --}}
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vehículo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Título</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prioridad</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Técnico</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($maintenances as $maintenance)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <span class="text-2xl mr-2">{{ $maintenance->vehicle->getTypeIcon() }}</span>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $maintenance->vehicle->brand }}</div>
                                            <div class="text-sm text-gray-500">{{ $maintenance->vehicle->plate }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $maintenance->title }}</div>
                                    <div class="text-sm text-gray-500">{{ Str::limit($maintenance->description, 50) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full border {{ $maintenance->getPriorityColorClass() }}">
                                        {{ $maintenance->getPriorityLabel() }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div x-data="{ open: false }" class="relative">
                                        <button type="button" @click.prevent="open = !open" 
                                            class="px-3 py-1 inline-flex items-center text-xs leading-5 font-semibold rounded-full border {{ $maintenance->getStatusColorClass() }}">
                                            {{ $maintenance->getStatusLabel() }}
                                            <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </button>
                                        <div x-show="open" 
                                            @click.outside="open = false" 
                                            class="absolute left-0 mt-2 w-48 bg-white rounded-md shadow-xl z-50 border border-gray-200 py-1"
                                            style="display: none;">
                                            <div wire:click="changeStatus({{ $maintenance->id }}, 'pending')" 
                                                @click="open = false"
                                                class="px-4 py-2 text-sm text-gray-700 hover:bg-yellow-50 cursor-pointer">
                                                🟡 Pendiente
                                            </div>
                                            <div wire:click="changeStatus({{ $maintenance->id }}, 'in_progress')" 
                                                @click="open = false"
                                                class="px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 cursor-pointer">
                                                🔵 En Progreso
                                            </div>
                                            <div wire:click="changeStatus({{ $maintenance->id }}, 'completed')" 
                                                @click="open = false"
                                                class="px-4 py-2 text-sm text-gray-700 hover:bg-green-50 cursor-pointer">
                                                🟢 Completado
                                            </div>
                                            <div wire:click="changeStatus({{ $maintenance->id }}, 'cancelled')" 
                                                @click="open = false"
                                                class="px-4 py-2 text-sm text-gray-700 hover:bg-red-50 cursor-pointer">
                                                🔴 Cancelado
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    @if($maintenance->technician)
                                        <div class="flex items-center">
                                            <div class="h-8 w-8 bg-indigo-100 rounded-full flex items-center justify-center mr-2">
                                                <span class="text-indigo-600 font-semibold text-xs">{{ $maintenance->technician->getInitials() }}</span>
                                            </div>
                                            {{ $maintenance->technician->name }}
                                        </div>
                                    @else
                                        <span class="text-gray-400 italic">Sin asignar</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $maintenance->created_at->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-2">
                                        {{-- Assign Technician --}}
                                        @if($maintenance->isPending() || !$maintenance->technician)
                                            <button wire:click="assignTechnician({{ $maintenance->id }})"
                                                class="text-indigo-600 hover:text-indigo-900" title="Asignar técnico">
                                                <svg class="w-5 h-5"fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                                </svg>
                                            </button>
                                        @endif

                                        {{-- View History --}}
                                        <button wire:click="viewVehicleHistory({{ $maintenance->vehicle_id }})"
                                            class="text-blue-600 hover:text-blue-900" title="Ver historial">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
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
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        <p class="text-gray-500 text-lg font-medium">No se encontraron mantenimientos</p>
                                        <p class="text-gray-400 text-sm mt-1">Intenta ajustar los filtros</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($maintenances->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $maintenances->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- Assign Technician Modal --}}
    @if($showAssignModal && $selectedMaintenance)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" @click="$wire.closeAssignModal()"></div>

                <div class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form wire:submit.prevent="saveAssignment">
                        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">Asignar Técnico</h3>
                        </div>

                        <div class="px-6 py-6">
                            <div class="mb-4">
                                <p class="text-sm text-gray-600 mb-2">Mantenimiento:</p>
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <div class="flex items-center mb-2">
                                        <span class="text-2xl mr-2">{{ $selectedMaintenance->vehicle->getTypeIcon() }}</span>
                                        <div>
                                            <p class="font-medium text-gray-900">{{ $selectedMaintenance->vehicle->brand }} {{ $selectedMaintenance->vehicle->model }}</p>
                                            <p class="text-sm text-gray-500">{{ $selectedMaintenance->vehicle->plate }}</p>
                                        </div>
                                    </div>
                                    <p class="text-sm font-medium text-gray-700 mt-2">{{ $selectedMaintenance->title }}</p>
                                    <p class="text-sm text-gray-600">{{ $selectedMaintenance->description }}</p>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Seleccionar Técnico *</label>
                                <select wire:model="selectedTechnicianId" 
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Selecciona un técnico...</option>
                                    @foreach($technicians as $tech)
                                        <option value="{{ $tech->id }}">{{ $tech->name }} - {{ $tech->email }}</option>
                                    @endforeach
                                </select>
                                @error('selectedTechnicianId') 
                                    <span class="text-red-500 text-xs">{{ $message }}</span> 
                                @enderror
                            </div>
                        </div>

                        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end space-x-3">
                            <button type="button" @click="$wire.closeAssignModal()"
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                                Cancelar
                            </button>
                            <button type="submit"
                                class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-700">
                                Asignar Técnico
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    {{-- Vehicle History Modal --}}
    @if($showHistoryModal && $vehicleHistory->count() > 0)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" @click="$wire.closeHistoryModal()"></div>

                <div class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full max-h-[90vh] overflow-y-auto">
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200 flex justify-between items-center">
                        <h3 class="text-lg font-medium text-gray-900">Historial de Mantenimientos</h3>
                        <button @click="$wire.closeHistoryModal()" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="px-6 py-6">
                        <div class="space-y-4">
                            @foreach($vehicleHistory as $item)
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <div class="flex justify-between items-start mb-2">
                                        <div class="flex-1">
                                            <h4 class="font-medium text-gray-900">{{ $item->title }}</h4>
                                            <p class="text-sm text-gray-600 mt-1">{{ $item->description }}</p>
                                        </div>
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full border {{ $item->getStatusColorClass() }}">
                                            {{ $item->getStatusLabel() }}
                                        </span>
                                    </div>
                                    <div class="mt-3 grid grid-cols-2 gap-4 text-sm">
                                        <div>
                                            <p class="text-gray-500">Prioridad</p>
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full border {{ $item->getPriorityColorClass() }}">
                                                {{ $item->getPriorityLabel() }}
                                            </span>
                                        </div>
                                        <div>
                                            <p class="text-gray-500">Técnico</p>
                                            <p class="text-gray-900">{{ $item->technician?->name ?? 'Sin asignar' }}</p>
                                        </div>
                                        <div>
                                            <p class="text-gray-500">Fecha</p>
                                            <p class="text-gray-900">{{ $item->created_at->format('d/m/Y H:i') }}</p>
                                        </div>
                                        @if($item->cost)
                                            <div>
                                                <p class="text-gray-500">Costo</p>
                                                <p class="text-gray-900 font-medium">${{ number_format($item->cost, 0, ',', '.') }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                        <button @click="$wire.closeHistoryModal()"
                            class="w-full px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-700">
                            Cerrar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Notification Scripts --}}
    <script>
        document.addEventListener('livewire:initialized', () => {
            @this.on('maintenance-assigned', (event) => {
                Swal.fire({
                    title: '¡Técnico Asignado!',
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
        });
    </script>
</div>
