<x-client-layout>
    <div class="min-h-screen bg-gradient-to-br from-green-50 via-white to-teal-50 py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Success Animation -->
            <div class="text-center mb-8" x-data="{ show: false }" x-init="setTimeout(() => show = true, 100)">
                <div class="inline-block" x-show="show" x-transition:enter="transform transition ease-out duration-500"
                    x-transition:enter-start="scale-0 opacity-0" x-transition:enter-end="scale-100 opacity-100">
                    <div class="w-24 h-24 mx-auto bg-green-500 rounded-full flex items-center justify-center mb-6 shadow-xl">
                        <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                </div>
                
                <h1 class="text-4xl font-bold text-gray-900 mb-3">¡Pago Exitoso! 🎉</h1>
                <p class="text-xl text-gray-600">Comprobante de Pago</p>
            </div>

            <!-- Invoice/Receipt Card -->
            <div class="bg-white rounded-2xl shadow-2xl overflow-hidden mb-6" id="invoice">
                <!-- Header with Ecoflow Branding -->
                <div class="bg-gradient-to-r from-green-500 to-teal-500 px-8 py-8 text-white">
                    <div class="flex items-start justify-between">
                        <div>
                            <div class="flex items-center mb-4">
                                <div class="bg-white rounded-full p-3 mr-4">
                                    <svg class="w-8 h-8 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="text-3xl font-bold">Ecoflow</h2>
                                    <p class="text-green-100">Movilidad Eléctrica Sostenible</p>
                                </div>
                            </div>
                            <div class="text-sm space-y-1 text-green-50">
                                <p>📍 Bucaramanga, Santander</p>
                                <p>📞 +57 300 123 4567</p>
                                <p>✉️ contacto@ecoflow.com</p>
                                <p>🌐 www.ecoflow.com</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="bg-white bg-opacity-20 rounded-lg px-4 py-3 mb-3">
                                <p class="text-xs text-green-100">Comprobante</p>
                                <p class="text-2xl font-bold">#{{ str_pad($reservation->id, 6, '0', STR_PAD_LEFT) }}</p>
                            </div>
                            <p class="text-sm text-green-100">{{ $reservation->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="px-8 py-8">
                    <!-- Transaction Info -->
                    <div class="bg-gray-50 rounded-xl p-6 mb-8">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                            <svg class="w-6 h-6 text-green-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            Información de la Transacción
                        </h3>
                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <p class="text-sm text-gray-500">Estado del Pago</p>
                                <p class="text-lg font-semibold text-green-600">✓ Aprobado</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Método de Pago</p>
                                <p class="text-lg font-semibold text-gray-900">💳 Tarjeta de Crédito</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Referencia de Pago</p>
                                <p class="text-lg font-semibold text-gray-900">{{ strtoupper(substr(md5($reservation->id . $reservation->created_at), 0, 12)) }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Autorización</p>
                                <p class="text-lg font-semibold text-gray-900">{{ rand(100000, 999999) }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Client Info -->
                    <div class="mb-8 pb-8 border-b border-gray-200">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Cliente</h3>
                        <div class="bg-white border border-gray-200 rounded-lg p-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-500">Nombre</p>
                                    <p class="font-semibold text-gray-900">{{ $reservation->user->name }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Email</p>
                                    <p class="font-semibold text-gray-900">{{ $reservation->user->email }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Teléfono</p>
                                    <p class="font-semibold text-gray-900">{{ $reservation->user->phone ?? '+57 300 000 0000' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Documento</p>
                                    <p class="font-semibold text-gray-900">CC {{ $reservation->user->document ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Vehicle & Reservation Details -->
                    <div class="mb-8 pb-8 border-b border-gray-200">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">🚲 Detalles del Servicio</h3>
                        <div class="bg-gradient-to-br from-green-50 to-teal-50 rounded-xl p-6">
                            <div class="flex items-start space-x-6 mb-6">
                                <div class="w-24 h-24 bg-gradient-to-br from-green-200 to-green-300 rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg">
                                    <span class="text-5xl">{{ $reservation->vehicle->getTypeIcon() }}</span>
                                </div>
                                <div class="flex-1">
                                    <h4 class="text-2xl font-bold text-gray-900">{{ $reservation->vehicle->brand }} {{ $reservation->vehicle->model }}</h4>
                                    <div class="grid grid-cols-2 gap-3 mt-3 text-sm">
                                        <p class="text-gray-600"><span class="font-semibold">Placa:</span> {{ $reservation->vehicle->plate }}</p>
                                        <p class="text-gray-600"><span class="font-semibold">Tipo:</span> {{ ucfirst($reservation->vehicle->type) }}</p>
                                        <p class="text-gray-600"><span class="font-semibold">Color:</span> {{ $reservation->vehicle->color ?? 'Negro' }}</p>
                                        @if($reservation->station)
                                            <p class="text-gray-600"><span class="font-semibold">Estación:</span> {{ $reservation->station->name }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Dates -->
                            <div class="grid grid-cols-2 gap-4 bg-white rounded-lg p-4">
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">📅 Fecha de Inicio</p>
                                    <p class="font-bold text-gray-900">{{ \Carbon\Carbon::parse($reservation->start_date)->format('d/m/Y') }}</p>
                                    <p class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($reservation->start_date)->format('H:i') }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">📅 Fecha de Finalización</p>
                                    <p class="font-bold text-gray-900">{{ \Carbon\Carbon::parse($reservation->end_date)->format('d/m/Y') }}</p>
                                    <p class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($reservation->end_date)->format('H:i') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Price Breakdown -->
                    <div class="mb-8">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">💰 Desglose de Precios</h3>
                        <div class="space-y-3">
                            @php
                                $days = \Carbon\Carbon::parse($reservation->start_date)->diffInDays(\Carbon\Carbon::parse($reservation->end_date));
                                $pricePerDay = 50000;
                                $basePrice = $days * $pricePerDay;
                                $deliveryFee = $reservation->delivery_method === 'delivery' ? 10000 : 0;
                                $total = $reservation->total_price;
                            @endphp
                            
                            <div class="flex justify-between items-center py-2">
                                <span class="text-gray-600">Alquiler ({{ $days }} {{ $days == 1 ? 'día' : 'días' }} × $50,000)</span>
                                <span class="font-semibold text-gray-900">${{ number_format($basePrice, 0, ',', '.') }} COP</span>
                            </div>
                            
                            @if($deliveryFee > 0)
                                <div class="flex justify-between items-center py-2">
                                    <span class="text-gray-600">Servicio de entrega</span>
                                    <span class="font-semibold text-gray-900">${{ number_format($deliveryFee, 0, ',', '.') }} COP</span>
                                </div>
                            @endif

                            <div class="flex justify-between items-center py-4 border-t-2 border-gray-300 bg-green-50 rounded-lg px-4 mt-4">
                                <span class="text-xl font-bold text-gray-900">TOTAL PAGADO</span>
                                <span class="text-3xl font-bold text-green-600">${{ number_format($total, 0, ',', '.') }} COP</span>
                            </div>
                        </div>
                    </div>

                    <!-- Important Notes -->
                    <div class="bg-blue-50 border-l-4 border-blue-500 p-6 rounded-lg mb-8">
                        <h4 class="font-semibold text-blue-900 mb-3 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                            Información Importante
                        </h4>
                        <ul class="text-sm text-blue-900 space-y-2">
                            <li>✓ Hemos enviado una copia a tu correo electrónico</li>
                            <li>✓ Presenta este comprobante al recoger el vehículo</li>
                            <li>✓ Lleva tu documento de identidad original</li>
                            <li>✓ El vehículo debe devolverse con mínimo 20% de batería</li>
                        </ul>
                    </div>

                    <!-- Verification Code -->
                    <div class="flex items-center justify-between bg-gray-50 rounded-xl p-6">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Código de Verificación</p>
                            <p class="text-2xl font-mono font-bold text-gray-900">{{ strtoupper(substr(md5($reservation->id), 0, 8)) }}-{{ str_pad($reservation->id, 4, '0', STR_PAD_LEFT) }}</p>
                        </div>
                        <div class="w-24 h-24 bg-gray-200 rounded-lg flex items-center justify-center">
                            <svg class="w-20 h-20 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="bg-gray-100 px-8 py-6 text-center text-sm text-gray-600">
                    <p class="mb-2">Este documento es un comprobante de pago válido</p>
                    <p>Gracias por confiar en Ecoflow - Movilidad Sostenible 🌱</p>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
                <button onclick="window.print()"
                    class="bg-white border-2 border-gray-300 text-gray-700 font-bold py-4 px-6 rounded-lg hover:bg-gray-50 transition-all flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                    </svg>
                    Imprimir
                </button>
                
                <a href="{{ route('reservations.index') }}"
                    class="bg-gradient-to-r from-green-500 to-teal-500 text-white font-bold py-4 px-6 rounded-lg hover:from-green-600 hover:to-teal-600 transition-all flex items-center justify-center">
                    📋 Mis Reservas
                </a>

                <a href="{{ route('reservations.track', $reservation->id) }}"
                    class="bg-white border-2 border-green-500 text-green-600 font-bold py-4 px-6 rounded-lg hover:bg-green-50 transition-all flex items-center justify-center">
                    🗺️ Rastrear
                </a>
            </div>

            <!-- Support Info -->
            <div class="text-center text-sm text-gray-600">
                <p class="mb-2">¿Necesitas ayuda? Contáctanos</p>
                <p class="font-semibold text-green-600">📞 +57 300 123 4567 | ✉️ soporte@ecoflow.com</p>
            </div>
        </div>
    </div>

    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            #invoice, #invoice * {
                visibility: visible;
            }
            #invoice {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
        }
    </style>
</x-client-layout>