<x-guest-layout>
    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" value="Nombre Completo" class="text-gray-700 font-medium mb-2" />
            <x-text-input id="name"
                class="block w-full px-4 py-3 border border-gray-300 rounded-md focus:border-green-500 focus:ring-green-500"
                type="text" name="name" :value="old('name')" required autofocus autocomplete="name"
                placeholder="Juan Pérez" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div>
            <x-input-label for="email" value="Correo Electrónico" class="text-gray-700 font-medium mb-2" />
            <x-text-input id="email"
                class="block w-full px-4 py-3 border border-gray-300 rounded-md focus:border-green-500 focus:ring-green-500"
                type="email" name="email" :value="old('email')" required autocomplete="username"
                placeholder="tu@email.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Phone -->
        <div>
            <x-input-label for="phone" value="Teléfono" class="text-gray-700 font-medium mb-2" />
            <x-text-input id="phone"
                class="block w-full px-4 py-3 border border-gray-300 rounded-md focus:border-green-500 focus:ring-green-500"
                type="tel" name="phone" :value="old('phone')" required autocomplete="tel" placeholder="3001234567" />
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" value="Contraseña" class="text-gray-700 font-medium mb-2" />
            <x-text-input id="password"
                class="block w-full px-4 py-3 border border-gray-300 rounded-md focus:border-green-500 focus:ring-green-500"
                type="password" name="password" required autocomplete="new-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div>
            <x-input-label for="password_confirmation" value="Confirmar Contraseña"
                class="text-gray-700 font-medium mb-2" />
            <x-text-input id="password_confirmation"
                class="block w-full px-4 py-3 border border-gray-300 rounded-md focus:border-green-500 focus:ring-green-500"
                type="password" name="password_confirmation" required autocomplete="new-password"
                placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Hidden role field -->
        <input type="hidden" name="role" value="cliente">

        <!-- Submit Button -->
        <div class="space-y-4 pt-2">
            <button type="submit"
                class="w-full bg-black hover:bg-gray-800 text-white font-medium py-3 px-4 rounded-md transition-colors duration-200">
                Registrarse
            </button>

            <div class="text-center text-sm text-gray-600">
                ¿Ya tienes cuenta?
                <a href="{{ route('login') }}" class="font-medium text-green-600 hover:text-green-700">
                    Inicia sesión
                </a>
            </div>
        </div>
    </form>
</x-guest-layout>