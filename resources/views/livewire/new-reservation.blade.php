<div class="py-12 bg-gray-100 min-h-screen">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Nueva Reserva</h1>
            <p class="mt-2 text-gray-600">Reserva tu vehículo en {{ $totalSteps }} simples pasos</p>
        </div>

        <!-- Progress Bar -->
        <div class="mb-8 bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between mb-4">
                @for($i = 1; $i <= $totalSteps; $i++)
                    <div class="flex items-center {{ $i < $totalSteps ? 'flex-1' : '' }}">
                        <div
                            class="flex items-center justify-center w-10 h-10 rounded-full {{ $currentStep >= $i ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-600' }} font-bold">
                            {{ $i }}
                        </div>
                        @if($i < $totalSteps)
                            <div class="flex-1 h-1 mx-2 {{ $currentStep > $i ? 'bg-green-500' : 'bg-gray-200' }}"></div>
                        @endif
                    </div>
                @endfor
            </div>
            <div class="flex justify-between text-sm">
                <span class="{{ $currentStep >= 1 ? 'text-green-600 font-medium' : 'text-gray-500' }}">Vehículo</span>
                <span class="{{ $currentStep >= 2 ? 'text-green-600 font-medium' : 'text-gray-500' }}">Fechas</span>
                <span class="{{ $currentStep >= 3 ? 'text-green-600 font-medium' : 'text-gray-500' }}">Entrega</span>
                <span class="{{ $currentStep >= 4 ? 'text-green-600 font-medium' : 'text-gray-500' }}">Confirmar</span>
            </div>
        </div>

        <!-- Error Message -->
        @if(session('error'))
            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        <!-- Step Content -->
        <div class="bg-white rounded-lg shadow-md p-6">
            @if($currentStep === 1)
                <!-- STEP 1: Vehicle Selection -->
                <div>
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">Selecciona tu Vehículo</h2>

                    <!-- Type Filters -->
                    <div class="flex flex-wrap gap-3 mb-6">
                        <button wire:click="setTypeFilter('all')"
                            class="px-4 py-2 rounded-md font-medium transition-colors {{ $typeFilter === 'all' ? 'bg-black text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            Todos
                        </button>
                        <button wire:click="setTypeFilter('scooter')"
                            class="px-4 py-2 rounded-md font-medium transition-colors {{ $typeFilter === 'scooter' ? 'bg-black text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            🛴 Scooters
                        </button>
                        <button wire:click="setTypeFilter('bicycle')"
                            class="px-4 py-2 rounded-md font-medium transition-colors {{ $typeFilter === 'bicycle' ? 'bg-black text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            🚴 Bicicletas
                        </button>
                        <button wire:click="setTypeFilter('skateboard')"
                            class="px-4 py-2 rounded-md font-medium transition-colors {{ $typeFilter === 'skateboard' ? 'bg-black text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            🛹 Skateboards
                        </button>
                    </div>

                    <!-- Vehicles Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @forelse($vehicles as $vehicle)
                            <div wire:click="selectVehicle({{ $vehicle->id }})"
                                class="relative border-2 rounded-lg p-4 cursor-pointer transition-all {{ $selectedVehicleId === $vehicle->id ? 'border-green-500 bg-green-50' : 'border-gray-200 hover:border-gray-300 hover:shadow-md' }}">

                                <!-- Selection Indicator -->
                                @if($selectedVehicleId === $vehicle->id)
                                    <div
                                        class="absolute top-3 right-3 w-6 h-6 bg-green-500 rounded-full flex items-center justify-center pointer-events-none">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                    </div>
                                @endif

                                <!-- Vehicle Image Placeholder -->
                                <div
                                    class="mb-4 bg-gradient-to-br from-green-100 to-green-200 rounded-lg h-40 flex items-center justify-center pointer-events-none">
                                    <span class="text-6xl">{{ $vehicle->getTypeIcon() }}</span>
                                </div>

                                <!-- Vehicle Info -->
                                <div class="space-y-2 pointer-events-none">
                                    <h3 class="text-lg font-bold text-gray-800">{{ $vehicle->brand }} {{ $vehicle->model }}</h3>
                                    <p class="text-sm text-gray-600">Placa: {{ $vehicle->plate }}</p>

                                    <!-- Battery Level -->
                                    @if($vehicle->telemetries->isNotEmpty())
                                        @php $telemetry = $vehicle->telemetries->first(); @endphp
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-2 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    d="M15.67 4H14V2h-4v2H8.33C7.6 4 7 4.6 7 5.33v15.33C7 21.4 7.6 22 8.33 22h7.33c.74 0 1.34-.6 1.34-1.33V5.33C17 4.6 16.4 4 15.67 4z" />
                                            </svg>
                                            <div class="flex-1">
                                                <div class="flex items-center">
                                                    <div class="flex-1 bg-gray-200 rounded-full h-2 mr-2">
                                                        <div class="bg-green-500 h-2 rounded-full"
                                                            style="width: {{ $telemetry->battery_level }}%"></div>
                                                    </div>
                                                    <span
                                                        class="text-xs font-medium text-gray-700">{{ $telemetry->battery_level }}%</span>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Location -->
                                    @if($vehicle->station)
                                        <div class="flex items-center text-sm text-gray-600">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            <span>{{ $vehicle->station->name }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full text-center py-12">
                                <p class="text-gray-500">No hay vehículos disponibles en esta categoría.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

            @elseif($currentStep === 2)
                <!-- STEP 2: Dates & Delivery -->
                <div>
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">Fechas y Entrega</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <!-- Dates -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Fecha de Inicio</label>
                            <input type="datetime-local" wire:model="startDate"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                            @error('startDate') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Fecha de Fin</label>
                            <input type="datetime-local" wire:model="endDate"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                            @error('endDate') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="border-t border-gray-200 pt-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Método de Entrega (Inicio)</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            <!-- Pickup Option -->
                            <div wire:click="$set('deliveryMethod', 'pickup')"
                                class="relative border rounded-lg p-4 cursor-pointer hover:bg-gray-50 {{ $deliveryMethod === 'pickup' ? 'border-green-500 bg-green-50 ring-1 ring-green-500' : 'border-gray-200' }}">
                                <div class="flex items-center">
                                    <input type="radio" checked="{{ $deliveryMethod === 'pickup' ? 'checked' : '' }}"
                                        class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 pointer-events-none">
                                    <div class="ml-3">
                                        <span class="block text-sm font-medium text-gray-900">Recoger en Sede</span>
                                        <span class="block text-sm text-gray-500">Ve a una de nuestras estaciones</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Delivery Option -->
                            <div wire:click="$set('deliveryMethod', 'delivery')"
                                class="relative border rounded-lg p-4 cursor-pointer hover:bg-gray-50 {{ $deliveryMethod === 'delivery' ? 'border-green-500 bg-green-50 ring-1 ring-green-500' : 'border-gray-200' }}">
                                <div class="flex items-center">
                                    <input type="radio" checked="{{ $deliveryMethod === 'delivery' ? 'checked' : '' }}"
                                        class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 pointer-events-none">
                                    <div class="ml-3">
                                        <span class="block text-sm font-medium text-gray-900">Entrega a Domicilio</span>
                                        <span class="block text-sm text-gray-500">Te llevamos el vehículo</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Conditional Fields -->
                        @if($deliveryMethod === 'pickup')
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Selecciona la Estación</label>
                                <select wire:model="pickupStationId"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                                    <option value="">Selecciona una estación...</option>
                                    @foreach($stations as $station)
                                        <option value="{{ $station->id }}">{{ $station->name }} - {{ $station->address }}</option>
                                    @endforeach
                                </select>
                                @error('pickupStationId') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        @else
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Dirección de Entrega</label>
                                <input type="text" wire:model="deliveryAddress" placeholder="Ej: Calle 123 #45-67, Bucaramanga"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                                @error('deliveryAddress') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        @endif
                    </div>
                </div>

            @elseif($currentStep === 3)
                <!-- STEP 3: Return Method -->
                <div>
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">Devolución del Vehículo</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <!-- Return at Station Option -->
                        <div wire:click="$set('returnMethod', 'return')"
                            class="relative border rounded-lg p-4 cursor-pointer hover:bg-gray-50 {{ $returnMethod === 'return' ? 'border-green-500 bg-green-50 ring-1 ring-green-500' : 'border-gray-200' }}">
                            <div class="flex items-center">
                                <input type="radio" checked="{{ $returnMethod === 'return' ? 'checked' : '' }}"
                                    class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 pointer-events-none">
                                <div class="ml-3">
                                    <span class="block text-sm font-medium text-gray-900">Devolver en Sede</span>
                                    <span class="block text-sm text-gray-500">Lleva el vehículo a una estación</span>
                                </div>
                            </div>
                        </div>

                        <!-- Home Pickup Option -->
                        <div wire:click="$set('returnMethod', 'pickup')"
                            class="relative border rounded-lg p-4 cursor-pointer hover:bg-gray-50 {{ $returnMethod === 'pickup' ? 'border-green-500 bg-green-50 ring-1 ring-green-500' : 'border-gray-200' }}">
                            <div class="flex items-center">
                                <input type="radio" checked="{{ $returnMethod === 'pickup' ? 'checked' : '' }}"
                                    class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 pointer-events-none">
                                <div class="ml-3">
                                    <span class="block text-sm font-medium text-gray-900">Recoger a Domicilio</span>
                                    <span class="block text-sm text-gray-500">Vamos por el vehículo</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Conditional Fields -->
                    @if($returnMethod === 'return')
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Estación de Devolución</label>
                            <select wire:model="returnStationId"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                                <option value="">Selecciona una estación...</option>
                                @foreach($stations as $station)
                                    <option value="{{ $station->id }}">{{ $station->name }} - {{ $station->address }}</option>
                                @endforeach
                            </select>
                            @error('returnStationId') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    @else
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Dirección de Recogida</label>
                            <input type="text" wire:model="returnAddress" placeholder="Ej: Calle 123 #45-67, Bucaramanga"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                            @error('returnAddress') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    @endif
                </div>

            @else
                <!-- STEP 4: Confirmation -->
                <div>
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">Confirmar Reserva</h2>

                    <div class="bg-gray-50 rounded-lg p-6 border border-gray-200 mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Resumen</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Vehicle Info -->
                            <div>
                                <p class="text-sm text-gray-500">Vehículo</p>
                                <p class="font-medium text-gray-900">{{ $this->selectedVehicle->brand }}
                                    {{ $this->selectedVehicle->model }}
                                </p>
                                <p class="text-sm text-gray-500">{{ $this->selectedVehicle->type }}</p>
                            </div>

                            <!-- Dates -->
                            <div>
                                <p class="text-sm text-gray-500">Fechas</p>
                                <p class="font-medium text-gray-900">
                                    {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y H:i') }} <br>
                                    {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y H:i') }}
                                </p>
                                <p class="text-sm text-green-600 font-medium">{{ $days }} días</p>
                            </div>

                            <!-- Delivery -->
                            <div>
                                <p class="text-sm text-gray-500">Entrega</p>
                                <p class="font-medium text-gray-900">
                                    {{ $deliveryMethod === 'pickup' ? 'Recoger en Sede' : 'Domicilio' }}
                                </p>
                                <p class="text-sm text-gray-600">
                                    {{ $deliveryMethod === 'pickup' ? ($this->pickupStation->name ?? '') : $deliveryAddress }}
                                </p>
                            </div>

                            <!-- Return -->
                            <div>
                                <p class="text-sm text-gray-500">Devolución</p>
                                <p class="font-medium text-gray-900">
                                    {{ $returnMethod === 'return' ? 'Devolver en Sede' : 'Recogida' }}
                                </p>
                                <p class="text-sm text-gray-600">
                                    {{ $returnMethod === 'return' ? ($this->returnStation->name ?? '') : $returnAddress }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Price Breakdown -->
                    <div class="border-t border-gray-200 pt-6">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-gray-600">Alquiler ({{ $days }} días)</span>
                            <span class="font-medium">${{ number_format($days * 50000, 0) }}</span>
                        </div>
                        @if($deliveryMethod === 'delivery')
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-gray-600">Domicilio de Entrega</span>
                                <span class="font-medium">$10,000</span>
                            </div>
                        @endif
                        @if($returnMethod === 'pickup')
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-gray-600">Domicilio de Recogida</span>
                                <span class="font-medium">$10,000</span>
                            </div>
                        @endif

                        <div class="flex justify-between items-center mt-4 pt-4 border-t border-gray-200">
                            <span class="text-xl font-bold text-gray-800">Total a Pagar</span>
                            <span class="text-2xl font-bold text-green-600">${{ number_format($totalPrice, 0) }} COP</span>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Navigation Buttons -->
        <div class="mt-6 flex justify-between">
            <button wire:click="previousStep" @if($currentStep === 1) disabled @endif
                class="px-6 py-3 rounded-md font-medium transition-colors {{ $currentStep === 1 ? 'bg-gray-200 text-gray-400 cursor-not-allowed' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                ← Anterior
            </button>

            @if($currentStep < $totalSteps)
                <button wire:click="nextStep"
                    class="px-6 py-3 bg-black hover:bg-gray-800 text-white rounded-md font-medium transition-colors">
                    Siguiente →
                </button>
            @else
                <button x-data @click="$wire.submitReservation()"
                    class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white rounded-md font-medium transition-colors">
                    Confirmar Reserva
                </button>
            @endif
        </div>
    </div>

    <script>
        document.addEventListener('livewire:initialized', () => {
            // Listen for redirect event
            Livewire.on('redirect-to-checkout', () => {
                window.location.href = "{{ route('checkout.payment') }}";
            });

            @this.on('reservation-created', (event) => {
                Swal.fire({
                    title: '¡Reserva Creada!',
                    text: 'Tu reserva ha sido registrada exitosamente.',
                    icon: 'success',
                    confirmButtonColor: '#10B981',
                    confirmButtonText: 'Ir a Mis Reservas'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "{{ route('dashboard') }}";
                    }
                });
            });
        });
    </script>
</div>