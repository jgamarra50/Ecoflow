<div class="py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Reportar Problema</h1>
            @if(auth()->user()->station)
                <p class="mt-1 text-sm text-gray-600">
                    Estación: <span class="font-semibold">{{ auth()->user()->station->name }}</span>
                </p>
            @else
                <div class="mt-2 bg-red-50 border border-red-200 rounded-md p-3">
                    <p class="text-sm text-red-700">⚠️ No tienes una estación asignada. Contacta al administrador.</p>
                </div>
            @endif
        </div>

        @if(auth()->user()->station)
            <!-- Report Form -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <form wire:submit.prevent="submitReport" class="space-y-6">
                    <!-- Vehicle Selection -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Vehículo *
                        </label>
                        <select wire:model="vehicleId"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Selecciona un vehículo</option>
                            @foreach($vehicles as $vehicle)
                                <option value="{{ $vehicle->id }}">
                                    {{ $vehicle->brand }} {{ $vehicle->model }} - {{ $vehicle->plate }}
                                    ({{ ucfirst($vehicle->status) }})
                                </option>
                            @endforeach
                        </select>
                        @error('vehicleId')
                            <span class="text-xs text-red-500 mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Issue Type -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Tipo de Problema *
                        </label>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                            <label
                                class="relative flex items-center p-3 border rounded-lg cursor-pointer {{ $issueType === 'battery' ? 'border-blue-500 bg-blue-50' : 'border-gray-300 hover:border-gray-400' }}">
                                <input type="radio" wire:model="issueType" value="battery" class="sr-only">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 mr-2 {{ $issueType === 'battery' ? 'text-blue-600' : 'text-gray-400' }}"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                    </svg>
                                    <span
                                        class="text-sm font-medium {{ $issueType === 'battery' ? 'text-blue-700' : 'text-gray-700' }}">Batería</span>
                                </div>
                            </label>

                            <label
                                class="relative flex items-center p-3 border rounded-lg cursor-pointer {{ $issueType === 'brakes' ? 'border-blue-500 bg-blue-50' : 'border-gray-300 hover:border-gray-400' }}">
                                <input type="radio" wire:model="issueType" value="brakes" class="sr-only">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 mr-2 {{ $issueType === 'brakes' ? 'text-blue-600' : 'text-gray-400' }}"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                    <span
                                        class="text-sm font-medium {{ $issueType === 'brakes' ? 'text-blue-700' : 'text-gray-700' }}">Frenos</span>
                                </div>
                            </label>

                            <label
                                class="relative flex items-center p-3 border rounded-lg cursor-pointer {{ $issueType === 'lights' ? 'border-blue-500 bg-blue-50' : 'border-gray-300 hover:border-gray-400' }}">
                                <input type="radio" wire:model="issueType" value="lights" class="sr-only">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 mr-2 {{ $issueType === 'lights' ? 'text-blue-600' : 'text-gray-400' }}"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                                    </svg>
                                    <span
                                        class="text-sm font-medium {{ $issueType === 'lights' ? 'text-blue-700' : 'text-gray-700' }}">Luces</span>
                                </div>
                            </label>

                            <label
                                class="relative flex items-center p-3 border rounded-lg cursor-pointer {{ $issueType === 'structure' ? 'border-blue-500 bg-blue-50' : 'border-gray-300 hover:border-gray-400' }}">
                                <input type="radio" wire:model="issueType" value="structure" class="sr-only">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 mr-2 {{ $issueType === 'structure' ? 'text-blue-600' : 'text-gray-400' }}"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                    <span
                                        class="text-sm font-medium {{ $issueType === 'structure' ? 'text-blue-700' : 'text-gray-700' }}">Estructura</span>
                                </div>
                            </label>

                            <label
                                class="relative flex items-center p-3 border rounded-lg cursor-pointer {{ $issueType === 'other' ? 'border-blue-500 bg-blue-50' : 'border-gray-300 hover:border-gray-400' }}">
                                <input type="radio" wire:model="issueType" value="other" class="sr-only">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 mr-2 {{ $issueType === 'other' ? 'text-blue-600' : 'text-gray-400' }}"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                    <span
                                        class="text-sm font-medium {{ $issueType === 'other' ? 'text-blue-700' : 'text-gray-700' }}">Otro</span>
                                </div>
                            </label>
                        </div>
                        @error('issueType')
                            <span class="text-xs text-red-500 mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Priority -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Prioridad *
                        </label>
                        <div class="grid grid-cols-4 gap-3">
                            <label
                                class="relative flex items-center justify-center p-3 border rounded-lg cursor-pointer {{ $priority === 'low' ? 'border-gray-500 bg-gray-50' : 'border-gray-300' }}">
                                <input type="radio" wire:model="priority" value="low" class="sr-only">
                                <span
                                    class="text-sm font-medium {{ $priority === 'low' ? 'text-gray-700' : 'text-gray-500' }}">
                                    Baja
                                </span>
                            </label>
                            <label
                                class="relative flex items-center justify-center p-3 border rounded-lg cursor-pointer {{ $priority === 'medium' ? 'border-blue-500 bg-blue-50' : 'border-gray-300' }}">
                                <input type="radio" wire:model="priority" value="medium" class="sr-only">
                                <span
                                    class="text-sm font-medium {{ $priority === 'medium' ? 'text-blue-700' : 'text-gray-500' }}">
                                    Media
                                </span>
                            </label>
                            <label
                                class="relative flex items-center justify-center p-3 border rounded-lg cursor-pointer {{ $priority === 'high' ? 'border-orange-500 bg-orange-50' : 'border-gray-300' }}">
                                <input type="radio" wire:model="priority" value="high" class="sr-only">
                                <span
                                    class="text-sm font-medium {{ $priority === 'high' ? 'text-orange-700' : 'text-gray-500' }}">
                                    Alta
                                </span>
                            </label>
                            <label
                                class="relative flex items-center justify-center p-3 border rounded-lg cursor-pointer {{ $priority === 'urgent' ? 'border-red-500 bg-red-50' : 'border-gray-300' }}">
                                <input type="radio" wire:model="priority" value="urgent" class="sr-only">
                                <span
                                    class="text-sm font-medium {{ $priority === 'urgent' ? 'text-red-700' : 'text-gray-500' }}">
                                    Urgente
                                </span>
                            </label>
                        </div>
                        <p class="mt-2 text-xs text-gray-500">
                            ℹ️ Prioridad Alta/Urgente cambiará el estado del vehículo a "Mantenimiento"
                        </p>
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Descripción del Problema *
                        </label>
                        <textarea wire:model="description" rows="5"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Describe detalladamente el problema que has observado en el vehículo..."></textarea>
                        <div class="flex justify-between mt-1">
                            @error('description')
                                <span class="text-xs text-red-500">{{ $message }}</span>
                            @else
                                <span class="text-xs text-gray-500">Mínimo 10 caracteres</span>
                            @enderror
                            <span class="text-xs text-gray-500">{{ strlen($description) }}/1000</span>
                        </div>
                    </div>

                    <!-- Photo Upload -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Foto del Problema (Opcional)
                        </label>
                        <div class="flex items-start gap-4">
                            <label class="flex-1 cursor-pointer">
                                <div
                                    class="border-2 border-dashed border-gray-300 rounded-lg p-6 hover:border-blue-400 transition-colors">
                                    <div class="text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <p class="mt-2 text-sm text-gray-600">Haz clic para subir una foto</p>
                                        <p class="text-xs text-gray-500">PNG, JPG hasta 2MB</p>
                                    </div>
                                </div>
                                <input type="file" wire:model="photo" accept="image/*" class="hidden">
                            </label>
                            @if($photoPreview)
                                <div class="relative">
                                    <img src="{{ $photoPreview }}"
                                        class="h-32 w-32 object-cover rounded-lg border-2 border-gray-300">
                                    <button type="button" wire:click="$set('photo', null)"
                                        class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1.5 hover:bg-red-600 shadow-lg">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            @endif
                        </div>
                        @error('photo')
                            <span class="text-xs text-red-500 mt-1">{{ $message }}</span>
                        @enderror
                        <div wire:loading wire:target="photo" class="text-sm text-blue-600 mt-2">
                            <svg class="inline w-4 h-4 animate-spin mr-2" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            Subiendo foto...
                        </div>
                    </div>

                    <!-- Info Box -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex">
                            <svg class="w-5 h-5 text-blue-600 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div class="text-sm text-blue-700">
                                <p class="font-medium">Al enviar este reporte:</p>
                                <ul class="list-disc list-inside mt-1 space-y-1">
                                    <li>Se creará automáticamente un ticket de mantenimiento</li>
                                    <li>El administrador recibirá una notificación</li>
                                    <li>Si la prioridad es Alta/Urgente, el vehículo se marcará como "En Mantenimiento"</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end gap-3 pt-4 border-t">
                        <button type="button" wire:click="$set('vehicleId', '')"
                            class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-colors">
                            Limpiar
                        </button>
                        <button type="submit"
                            class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md font-medium transition-colors flex items-center gap-2"
                            wire:loading.attr="disabled" wire:target="submitReport">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                            </svg>
                            <span wire:loading.remove wire:target="submitReport">Enviar Reporte</span>
                            <span wire:loading wire:target="submitReport">Enviando...</span>
                        </button>
                    </div>
                </form>
            </div>
        @endif
    </div>

    <!-- Toast Notifications -->
    <script>
        document.addEventListener('livewire:initialized', () => {
            @this.on('report-submitted', (event) => {
                Swal.fire({
                    title: 'Reporte Enviado',
                    text: event.message,
                    icon: 'success',
                    confirmButtonColor: '#10B981',
                    confirmButtonText: 'Aceptar'
                });
            });
        });
    </script>
</div>