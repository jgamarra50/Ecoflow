<div class="py-8 bg-gradient-to-br from-amber-50 to-orange-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Header --}}
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Panel de Técnico</h1>
            <p class="mt-2 text-gray-600">Gestiona tus mantenimientos asignados</p>
        </div>

        {{-- Stats Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-orange-500">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-orange-100 rounded-md p-3">
                        <svg class="h-6 w-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <div class="ml-5">
                        <p class="text-sm font-medium text-gray-500">Total Asignados</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['total'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-blue-100 rounded-md p-3">
                        <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <div class="ml-5">
                        <p class="text-sm font-medium text-gray-500">En Progreso</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['in_progress'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-green-100 rounded-md p-3">
                        <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-5">
                        <p class="text-sm font-medium text-gray-500">Completados Hoy</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['completed_today'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-yellow-500">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-yellow-100 rounded-md p-3">
                        <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-5">
                        <p class="text-sm font-medium text-gray-500">Pendientes</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['pending'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filter Tabs --}}
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex flex-wrap gap-2">
                <button wire:click="$set('statusFilter', 'all')"
                    class="px-4 py-2 rounded-lg font-medium transition-colors {{ $statusFilter === 'all' ? 'bg-orange-600 text-white shadow-md' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Todos
                </button>
                <button wire:click="$set('statusFilter', 'pending')"
                    class="px-4 py-2 rounded-lg font-medium transition-colors {{ $statusFilter === 'pending' ? 'bg-yellow-600 text-white shadow-md' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Pendientes
                </button>
                <button wire:click="$set('statusFilter', 'assigned')"
                    class="px-4 py-2 rounded-lg font-medium transition-colors {{ $statusFilter === 'assigned' ? 'bg-purple-600 text-white shadow-md' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Asignados
                </button>
                <button wire:click="$set('statusFilter', 'in_progress')"
                    class="px-4 py-2 rounded-lg font-medium transition-colors {{ $statusFilter === 'in_progress' ? 'bg-blue-600 text-white shadow-md' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    En Progreso
                </button>
                <button wire:click="$set('statusFilter', 'completed')"
                    class="px-4 py-2 rounded-lg font-medium transition-colors {{ $statusFilter === 'completed' ? 'bg-green-600 text-white shadow-md' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Completados
                </button>
            </div>
        </div>

        {{-- Maintenances Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($maintenances as $maintenance)
                <div class="bg-white rounded-lg shadow-md hover:shadow-xl transition-shadow overflow-hidden">
                    <div class="p-6">
                        {{-- Header with Priority --}}
                        <div class="flex justify-between items-start mb-4">
                            <div class="flex items-center">
                                <span class="text-3xl mr-3">{{ $maintenance->vehicle->getTypeIcon() }}</span>
                                <div>
                                    <h3 class="font-bold text-gray-900">{{ $maintenance->vehicle->brand }}</h3>
                                    <p class="text-sm text-gray-500">{{ $maintenance->vehicle->model }}</p>
                                    <p class="text-xs text-gray-400">{{ $maintenance->vehicle->plate }}</p>
                                </div>
                            </div>
                            <span class="px-3 py-1 text-xs font-semibold rounded-full border {{ $maintenance->getPriorityColorClass() }}">
                                {{ $maintenance->getPriorityLabel() }}
                            </span>
                        </div>

                        {{-- Title & Description --}}
                        <div class="mb-4">
                            <h4 class="font-semibold text-gray-900 mb-2">{{ $maintenance->title }}</h4>
                            <p class="text-sm text-gray-600">{{ Str::limit($maintenance->description, 80) }}</p>
                        </div>

                        {{-- Status & Date --}}
                        <div class="flex justify-between items-center mb-4">
                            <span class="px-3 py-1 text-xs font-semibold rounded-full border {{ $maintenance->getStatusColorClass() }}">
                                {{ $maintenance->getStatusLabel() }}
                            </span>
                            <span class="text-xs text-gray-500">
                                {{ $maintenance->created_at->format('d/m/Y') }}
                            </span>
                        </div>

                        {{-- Action Button --}}
                        <button type="button" wire:click="viewDetails({{ $maintenance->id }})"
                            class="w-full bg-orange-600 hover:bg-orange-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                            Ver Detalles
                        </button>
                    </div>
                </div>
            @empty
                <div class="col-span-full">
                    <div class="bg-white rounded-lg shadow-md p-12 text-center">
                        <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        <p class="text-gray-500 text-lg font-medium">No tienes mantenimientos {{ $statusFilter !== 'all' ? 'en este estado' : 'asignados' }}</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    {{-- Details Modal --}}
    @if($showDetailsModal && $selectedMaintenance)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" wire:click="closeDetailsModal"></div>

                <div class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                    {{-- Header --}}
                    <div class="px-6 py-4 bg-gradient-to-r from-orange-600 to-amber-600 text-white">
                        <div class="flex justify-between items-center">
                            <h3 class="text-xl font-bold">Detalles del Mantenimiento</h3>
                            <button wire:click="closeDetailsModal" type="button" class="text-white hover:text-gray-200">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="px-6 py-6">
                        {{-- Vehicle Info --}}
                        <div class="mb-6">
                            <h4 class="text-lg font-bold text-gray-900 mb-3">Información del Vehículo</h4>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <div class="flex items-center mb-2">
                                    <span class="text-3xl mr-3">{{ $selectedMaintenance->vehicle->getTypeIcon() }}</span>
                                    <div>
                                        <p class="font-bold text-gray-900">{{ $selectedMaintenance->vehicle->brand }} {{ $selectedMaintenance->vehicle->model }}</p>
                                        <p class="text-sm text-gray-500">Placa: {{ $selectedMaintenance->vehicle->plate }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Problem Details --}}
                        <div class="mb-6">
                            <h4 class="text-lg font-bold text-gray-900 mb-3">Problema Reportado</h4>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="font-semibold text-gray-900 mb-2">{{ $selectedMaintenance->title }}</p>
                                <p class="text-sm text-gray-700">{{ $selectedMaintenance->description }}</p>
                                <div class="mt-3 flex items-center gap-3">
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full border {{ $selectedMaintenance->getPriorityColorClass() }}">
                                        {{ $selectedMaintenance->getPriorityLabel() }}
                                    </span>
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full border {{ $selectedMaintenance->getStatusColorClass() }}">
                                        {{ $selectedMaintenance->getStatusLabel() }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- Timeline --}}
                        <div class="mb-6">
                            <h4 class="text-lg font-bold text-gray-900 mb-3">Timeline</h4>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Asignado:</span>
                                    <span class="font-medium text-gray-900">{{ $selectedMaintenance->assigned_at?->format('d/m/Y H:i') ?? 'N/A' }}</span>
                                </div>
                                @if($selectedMaintenance->started_at)
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Iniciado:</span>
                                        <span class="font-medium text-gray-900">{{ $selectedMaintenance->started_at->format('d/m/Y H:i') }}</span>
                                    </div>
                                @endif
                                @if($selectedMaintenance->completed_at)
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Completado:</span>
                                        <span class="font-medium text-gray-900">{{ $selectedMaintenance->completed_at->format('d/m/Y H:i') }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Notes --}}
                        <div class="mb-6">
                            <label class="block text-lg font-bold text-gray-900 mb-3">Notas del Trabajo</label>
                            <textarea wire:model="notes" rows="4"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500"
                                placeholder="Escribe aquí las notas sobre el trabajo realizado..."></textarea>
                            @error('notes') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    {{-- Footer Actions --}}
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-between gap-3">
                        <div class="flex gap-2">
                            @if($selectedMaintenance->status === 'pending' || $selectedMaintenance->status === 'assigned')
                                <button wire:click="updateStatus('in_progress')" type="button"
                                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                                    🔵 Iniciar Trabajo
                                </button>
                            @endif
                            <button wire:click="saveNotes" type="button"
                                class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors">
                                💾 Guardar Notas
                            </button>
                        </div>
                        <div class="flex gap-2">
                            @if($selectedMaintenance->status !== 'completed')
                                <button wire:click="markAsCompleted" type="button"
                                    class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors">
                                    ✅ Marcar Completado
                                </button>
                            @endif
                            <button wire:click="closeDetailsModal" type="button"
                                class="px-4 py-2 bg-gray-700 hover:bg-gray-800 text-white font-medium rounded-lg transition-colors">
                                Cerrar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Notification Scripts --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('livewire:initialized', () => {
            @this.on('status-updated', (event) => {
                Swal.fire({
                    title: '¡Actualizado!',
                    text: event.message,
                    icon: 'success',
                    confirmButtonColor: '#ea580c',
                    timer: 2000
                });
            });

            @this.on('notes-saved', (event) => {
                Swal.fire({
                    title: '¡Guardado!',
                    text: event.message,
                    icon: 'success',
                    confirmButtonColor: '#ea580c',
                    timer: 2000
                });
            });

            @this.on('maintenance-completed', (event) => {
                Swal.fire({
                    title: '¡Completado!',
                    text: event.message,
                    icon: 'success',
                    confirmButtonColor: '#16a34a',
                    timer: 2000
                });
            });
        });
    </script>
</div>
