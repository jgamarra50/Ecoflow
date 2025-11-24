<div>
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-green-50 py-12">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8 text-center">
                <h1 class="text-3xl font-bold text-gray-900">💳 Finaliza tu Reserva</h1>
                <p class="mt-2 text-gray-600">Pago seguro y encriptado</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Payment Form - Left Side -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                        <!-- Payment Method Selector -->
                        <div class="flex border-b border-gray-200">
                            <button wire:click="setPaymentMethod('card')"
                                class="flex-1 py-4 px-6 text-center font-semibold transition-all {{ $paymentMethod === 'card' ? 'bg-green-500 text-white' : 'bg-gray-50 text-gray-600 hover:bg-gray-100' }}">
                                💳 Tarjeta de Crédito
                            </button>
                            <button wire:click="setPaymentMethod('pse')"
                                class="flex-1 py-4 px-6 text-center font-semibold transition-all {{ $paymentMethod === 'pse' ? 'bg-green-500 text-white' : 'bg-gray-50 text-gray-600 hover:bg-gray-100' }}">
                                🏦 PSE
                            </button>
                        </div>

                        <div class="p-8">
                            @if($paymentMethod === 'card')
                                <!-- Credit Card Form -->
                                <div class="space-y-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Número de Tarjeta</label>
                                        <div class="relative">
                                            <input type="text" wire:model.live="cardNumber" maxlength="16"
                                                placeholder="1234 5678 9012 3456"
                                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                                x-data="{}" 
                                                x-on:input="$el.value = $el.value.replace(/\s/g, '').replace(/(.{4})/g, '$1 ').trim()">
                                            <div class="absolute right-4 top-3 flex space-x-1">
                                                <img src="https://img.icons8.com/color/48/000000/visa.png" class="h-8" alt="Visa">
                                                <img src="https://img.icons8.com/color/48/000000/mastercard.png" class="h-8" alt="Mastercard">
                                            </div>
                                        </div>
                                        @error('cardNumber') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Nombre del Titular</label>
                                        <input type="text" wire:model="cardName"
                                            placeholder="JUAN PEREZ"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent uppercase">
                                        @error('cardName') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Fecha de Vencimiento</label>
                                            <input type="text" wire:model="expiryDate" maxlength="5"
                                                placeholder="MM/YY"
                                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                                x-data="{}"
                                                x-on:input="$el.value = $el.value.replace(/[^0-9]/g, '').replace(/^([0-9]{2})/, '$1/').substring(0, 5)">
                                            @error('expiryDate') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">CVV</label>
                                            <input type="password" wire:model="cvv" maxlength="4"
                                                placeholder="123"
                                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                            @error('cvv') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                        </div>
                                    </div>

                                    <!-- Security Badges -->
                                    <div class="flex items-center justify-center space-x-4 pt-4 border-t border-gray-200">
                                        <div class="flex items-center text-sm text-gray-600">
                                            <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                                            </svg>
                                            Pago Seguro SSL
                                        </div>
                                        <div class="flex items-center text-sm text-gray-600">
                                            <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                            Protección de Datos
                                        </div>
                                    </div>
                                </div>

                            @else
                                <!-- PSE Form -->
                                <div class="space-y-6">
                                    <!-- Bank Selection -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Selecciona tu Banco</label>
                                        <select wire:model="selectedBank"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                            <option value="">-- Selecciona --</option>
                                            <option value="bancolombia">Bancolombia</option>
                                            <option value="davivienda">Davivienda</option>
                                            <option value="banco_bogota">Banco de Bogotá</option>
                                            <option value="bbva">BBVA Colombia</option>
                                            <option value="banco_occidente">Banco de Occidente</option>
                                            <option value="banco_popular">Banco Popular</option>
                                            <option value="colpatria">Scotiabank Colpatria</option>
                                            <option value="av_villas">Banco AV Villas</option>
                                            <option value="banco_caja_social">Banco Caja Social</option>
                                            <option value="nequi">Nequi</option>
                                            <option value="daviplata">Daviplata</option>
                                        </select>
                                        @error('selectedBank') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                    </div>

                                    <!-- Person Type -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Tipo de Persona</label>
                                        <div class="grid grid-cols-2 gap-4">
                                            <button type="button" wire:click="$set('personType', 'natural')"
                                                class="py-3 px-4 border-2 rounded-lg font-medium transition-all {{ $personType === 'natural' ? 'border-green-500 bg-green-50 text-green-700' : 'border-gray-300 hover:border-gray-400' }}">
                                                👤 Persona Natural
                                            </button>
                                            <button type="button" wire:click="$set('personType', 'juridica')"
                                                class="py-3 px-4 border-2 rounded-lg font-medium transition-all {{ $personType === 'juridica' ? 'border-green-500 bg-green-50 text-green-700' : 'border-gray-300 hover:border-gray-400' }}">
                                                🏢 Persona Jurídica
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Document Type -->
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Tipo de Documento</label>
                                            <select wire:model="documentType"
                                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                                @if($personType === 'natural')
                                                    <option value="CC">Cédula de Ciudadanía</option>
                                                    <option value="CE">Cédula de Extranjería</option>
                                                    <option value="TI">Tarjeta de Identidad</option>
                                                    <option value="PPN">Pasaporte</option>
                                                @else
                                                    <option value="NIT">NIT</option>
                                                @endif
                                            </select>
                                            @error('documentType') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Número de Documento</label>
                                            <input type="text" wire:model="documentNumber"
                                                placeholder="1234567890"
                                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                            @error('documentNumber') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                        </div>
                                    </div>

                                    <!-- PSE Info -->
                                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                        <div class="flex">
                                            <svg class="w-5 h-5 text-blue-500 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                            </svg>
                                            <div class="text-sm text-blue-700">
                                                <p class="font-medium mb-1">Información PSE</p>
                                                <p>Serás redirigido al portal de tu banco para completar el pago de forma segura.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Submit Button -->
                            <div class="mt-8">
                                <button wire:click="processPayment" 
                                    wire:loading.attr="disabled"
                                    wire:loading.class="opacity-50 cursor-not-allowed"
                                    class="w-full bg-gradient-to-r from-green-500 to-teal-500 text-white font-bold py-4 px-6 rounded-lg hover:from-green-600 hover:to-teal-600 transition-all shadow-lg hover:shadow-xl flex items-center justify-center space-x-2">
                                    <span wire:loading.remove>
                                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                        </svg>
                                        Pagar ${{ number_format($totalPrice, 0, ',', '.') }} COP
                                    </span>
                                    <span wire:loading>
                                        <svg class="animate-spin h-5 w-5 inline mr-2" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        Procesando pago...
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Summary - Right Side -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-xl shadow-lg p-6 sticky top-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-6">Resumen del Pedido</h3>

                        <!-- Vehicle Info -->
                        <div class="mb-6 pb-6 border-b border-gray-200">
                            <div class="flex items-start space-x-4">
                                <div class="w-20 h-20 bg-gradient-to-br from-green-100 to-green-200 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <span class="text-4xl">{{ $vehicle->getTypeIcon() }}</span>
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-semibold text-gray-900">{{ $vehicle->brand }} {{ $vehicle->model }}</h4>
                                    <p class="text-sm text-gray-600 mt-1">Placa: {{ $vehicle->plate }}</p>
                                    @if($vehicle->station)
                                        <p class="text-xs text-gray-500 mt-1">📍 {{ $vehicle->station->name }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Reservation Details -->
                        <div class="space-y-3 mb-6">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Duración:</span>
                                <span class="font-semibold text-gray-900">{{ $days }} {{ $days == 1 ? 'día' : 'días' }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Precio por día:</span>
                                <span class="font-semibold text-gray-900">$50,000 COP</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Subtotal:</span>
                                <span class="font-semibold text-gray-900">${{ number_format($days * 50000, 0, ',', '.') }} COP</span>
                            </div>
                            
                            @if($reservationData['delivery_method'] === 'delivery')
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Entrega a domicilio:</span>
                                    <span class="font-semibold text-gray-900">$10,000 COP</span>
                                </div>
                            @endif

                            @if($reservationData['return_method'] === 'pickup')
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Recogida a domicilio:</span>
                                    <span class="font-semibold text-gray-900">$10,000 COP</span>
                                </div>
                            @endif
                        </div>

                        <!-- Total -->
                        <div class="pt-6 border-t-2 border-gray-300">
                            <div class="flex justify-between items-center">
                                <span class="text-lg font-bold text-gray-900">Total a Pagar:</span>
                                <span class="text-2xl font-bold text-green-600">${{ number_format($totalPrice, 0, ',', '.') }} COP</span>
                            </div>
                        </div>

                        <!-- Trust Badges -->
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <div class="text-xs text-center text-gray-500 space-y-2">
                                <p class="flex items-center justify-center">
                                    <svg class="w-4 h-4 text-green-500 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Pago 100% seguro
                                </p>
                                <p class="flex items-center justify-center">
                                    <svg class="w-4 h-4 text-green-500 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Encriptación SSL
                                </p>
                                <p class="flex items-center justify-center">
                                    <svg class="w-4 h-4 text-green-500 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Transacción protegida
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Optional: Add card number formatting and validation
        document.addEventListener('livewire:init', () => {
            Livewire.on('payment-success', (event) => {
                Swal.fire({
                    title: '¡Pago Exitoso!',
                    text: 'Tu reserva ha sido confirmada',
                    icon: 'success',
                    confirmButtonText: 'Ver Mi Reserva',
                    confirmButtonColor: '#10b981'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = `/reservations/${event.reservationId}`;
                    }
                });
            });
        });
    </script>
    @endpush
</div>
