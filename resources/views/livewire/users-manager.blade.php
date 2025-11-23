<div class="py-8 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Header --}}
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Gestión de Usuarios</h1>
            <p class="mt-2 text-gray-600">Administra todos los usuarios del sistema - Total: {{ $users->total() }}</p>
        </div>

        {{-- Role Filter Tabs --}}
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex flex-wrap gap-2">
                <button wire:click="$set('roleFilter', 'all')"
                    class="px-4 py-2 rounded-lg font-medium transition-colors {{ $roleFilter === 'all' ? 'bg-indigo-600 text-white shadow-md' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Todos
                </button>
                <button wire:click="$set('roleFilter', 'admin')"
                    class="px-4 py-2 rounded-lg font-medium transition-colors {{ $roleFilter === 'admin' ? 'bg-purple-600 text-white shadow-md' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Administradores
                </button>
                <button wire:click="$set('roleFilter', 'operador')"
                    class="px-4 py-2 rounded-lg font-medium transition-colors {{ $roleFilter === 'operador' ? 'bg-blue-600 text-white shadow-md' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Operadores
                </button>
                <button wire:click="$set('roleFilter', 'tecnico')"
                    class="px-4 py-2 rounded-lg font-medium transition-colors {{ $roleFilter === 'tecnico' ? 'bg-orange-600 text-white shadow-md' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Técnicos
                </button>
                <button wire:click="$set('roleFilter', 'cliente')"
                    class="px-4 py-2 rounded-lg font-medium transition-colors {{ $roleFilter === 'cliente' ? 'bg-green-600 text-white shadow-md' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Clientes
                </button>
            </div>
        </div>

        {{-- Users Table --}}
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usuario</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Teléfono</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rol</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reservas</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($users as $user)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 bg-indigo-100 rounded-full flex items-center justify-center">
                                            <span class="text-indigo-600 font-semibold text-sm">{{ $user->getInitials() }}</span>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $user->email }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500">{{ $user->phone ?? 'N/A' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $roleName = $user->roles->first()?->name ?? 'cliente';
                                        $roleColors = [
                                            'admin' => 'bg-purple-100 text-purple-800 border-purple-300',
                                            'operador' => 'bg-blue-100 text-blue-800 border-blue-300',
                                            'tecnico' => 'bg-orange-100 text-orange-800 border-orange-300',
                                            'cliente' => 'bg-green-100 text-green-800 border-green-300',
                                        ];
                                    @endphp
                                    <div x-data="{ open: false }" class="relative">
                                        <button type="button" @click.prevent="open = !open" 
                                            class="px-3 py-1 inline-flex items-center text-xs leading-5 font-semibold rounded-full border {{ $roleColors[$roleName] ?? 'bg-gray-100 text-gray-800 border-gray-300' }}">
                                            {{ ucfirst($roleName) }}
                                            <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </button>
                                        <div x-show="open" 
                                            @click.outside="open = false" 
                                            class="absolute left-0 mt-2 w-40 bg-white rounded-md shadow-xl z-50 border border-gray-200 py-1"
                                            style="display: none;">
                                            <div wire:click="changeRole({{ $user->id }}, 'admin')" 
                                                @click="open = false"
                                                class="px-4 py-2 text-sm text-gray-700 hover:bg-purple-50 cursor-pointer">
                                                👑 Admin
                                            </div>
                                            <div wire:click="changeRole({{ $user->id }}, 'operador')" 
                                                @click="open = false"
                                                class="px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 cursor-pointer">
                                                👨‍💼 Operador
                                            </div>
                                            <div wire:click="changeRole({{ $user->id }}, 'tecnico')" 
                                                @click="open = false"
                                                class="px-4 py-2 text-sm text-gray-700 hover:bg-orange-50 cursor-pointer">
                                                🔧 Técnico
                                            </div>
                                            <div wire:click="changeRole({{ $user->id }}, 'cliente')" 
                                                @click="open = false"
                                                class="px-4 py-2 text-sm text-gray-700 hover:bg-green-50 cursor-pointer">
                                                👤 Cliente
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" wire:click="toggleStatus({{ $user->id }})" 
                                                {{ $user->is_active ? 'checked' : '' }} class="sr-only peer">
                                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600"></div>
                                        </label>
                                        <span class="ml-2 text-xs {{ $user->is_active ? 'text-green-700' : 'text-red-700' }}">
                                            {{ $user->getStatusLabel() }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $user->reservations->count() }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button wire:click="viewDetails({{ $user->id }})"
                                        class="text-indigo-600 hover:text-indigo-900" title="Ver detalles">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-16 h-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                        </svg>
                                        <p class="text-gray-500 text-lg font-medium">No se encontraron usuarios</p>
                                        <p class="text-gray-400 text-sm mt-1">Intenta ajustar los filtros</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($users->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- Details Modal --}}
    @if($showDetailsModal && $selectedUser)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" @click="$wire.closeDetailsModal()"></div>

                <div class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full max-h-[90vh] overflow-y-auto">
                    {{-- Header --}}
                    <div class="px-6 py-4 bg-gradient-to-r from-indigo-600 to-purple-600 text-white">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-xl font-bold">Detalles del Usuario</h3>
                            </div>
                            <button @click="$wire.closeDetailsModal()" class="text-white hover:text-gray-200">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- User Info --}}
                    <div class="px-6 py-6">
                        <div class="flex items-center mb-6">
                            <div class="flex-shrink-0 h-20 w-20 bg-indigo-100 rounded-full flex items-center justify-center">
                                <span class="text-indigo-600 font-bold text-2xl">{{ $selectedUser->getInitials() }}</span>
                            </div>
                            <div class="ml-6">
                                <h4 class="text-2xl font-bold text-gray-900">{{ $selectedUser->name }}</h4>
                                <p class="text-gray-500 flex items-center mt-1">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                    {{ $selectedUser->email }}
                                </p>
                                @if($selectedUser->phone)
                                    <p class="text-gray-500 flex items-center mt-1">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                        </svg>
                                        {{ $selectedUser->phone }}
                                    </p>
                                @endif
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-6">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-sm text-gray-500">Rol</p>
                                <p class="text-lg font-semibold text-gray-900 mt-1">{{ ucfirst($selectedUser->roles->first()?->name ?? 'cliente') }}</p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-sm text-gray-500">Estado</p>
                                <p class="text-lg font-semibold {{ $selectedUser->is_active ? 'text-green-600' : 'text-red-600' }} mt-1">
                                    {{ $selectedUser->getStatusLabel() }}
                                </p>
                            </div>
                        </div>

                        {{-- Stats --}}
                        @if($userStats)
                            <div class="border-t border-gray-200 pt-6 mb-6">
                                <h5 class="text-lg font-bold text-gray-900 mb-4">Estadísticas de Reservas</h5>
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                    <div class="text-center p-4 bg-indigo-50 rounded-lg">
                                        <p class="text-3xl font-bold text-indigo-600">{{ $userStats['total'] }}</p>
                                        <p class="text-sm text-gray-600 mt-1">Total</p>
                                    </div>
                                    <div class="text-center p-4 bg-green-50 rounded-lg">
                                        <p class="text-3xl font-bold text-green-600">{{ $userStats['active'] }}</p>
                                        <p class="text-sm text-gray-600 mt-1">Activas</p>
                                    </div>
                                    <div class="text-center p-4 bg-blue-50 rounded-lg">
                                        <p class="text-3xl font-bold text-blue-600">{{ $userStats['completed'] }}</p>
                                        <p class="text-sm text-gray-600 mt-1">Completadas</p>
                                    </div>
                                    <div class="text-center p-4 bg-red-50 rounded-lg">
                                        <p class="text-3xl font-bold text-red-600">{{ $userStats['cancelled'] }}</p>
                                        <p class="text-sm text-gray-600 mt-1">Canceladas</p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- Reservations History --}}
                        <div class="border-t border-gray-200 pt-6">
                            <h5 class="text-lg font-bold text-gray-900 mb-4">Historial de Reservas</h5>
                            @if($selectedUser->reservations->count() > 0)
                                <div class="space-y-3 max-h-96 overflow-y-auto">
                                    @foreach($selectedUser->reservations->take(10) as $reservation)
                                        <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors">
                                            <div class="flex justify-between items-start">
                                                <div class="flex-1">
                                                    <div class="flex items-center">
                                                        <span class="text-2xl mr-2">{{ $reservation->vehicle->getTypeIcon() }}</span>
                                                        <div>
                                                            <p class="font-medium text-gray-900">{{ $reservation->vehicle->brand }} {{ $reservation->vehicle->model }}</p>
                                                            <p class="text-sm text-gray-500">Placa: {{ $reservation->vehicle->plate }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="mt-2 text-sm text-gray-600">
                                                        <p>📅 {{ $reservation->start_date->format('d/m/Y') }} - {{ $reservation->end_date->format('d/m/Y') }}</p>
                                                        <p class="mt-1">💰 ${{ number_format($reservation->total_price, 0, ',', '.') }}</p>
                                                    </div>
                                                </div>
                                                <div>
                                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full border {{ $reservation->getStatusColorClass() }}">
                                                        {{ $reservation->getStatusLabel() }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8 text-gray-500">
                                    <svg class="w-12 h-12 mx-auto mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                    <p>No tiene reservas registradas</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Footer --}}
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                        <button @click="$wire.closeDetailsModal()"
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
            @this.on('role-changed', (event) => {
                Swal.fire({
                    title: '¡Éxito!',
                    text: event.message,
                    icon: 'success',
                    confirmButtonColor: '#4F46E5',
                    timer: 2000
                });
            });

            @this.on('role-change-error', (event) => {
                Swal.fire({
                    title: 'Error',
                    text: event.message,
                    icon: 'error',
                    confirmButtonColor: '#EF4444'
                });
            });

            @this.on('status-toggled', (event) => {
                Swal.fire({
                    title: '¡Actualizado!',
                    text: event.message,
                    icon: 'success',
                    confirmButtonColor: '#4F46E5',
                    timer: 2000
                });
            });

            @this.on('toggle-error', (event) => {
                Swal.fire({
                    title: 'Error',
                    text: event.message,
                    icon: 'error',
                    confirmButtonColor: '#EF4444'
                });
            });
        });
    </script>
</div>
