<x-guest-layout>
    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <!-- Email Address -->
        <div>
            <x-input-label for="email" value="Correo Electrónico" class="text-gray-700 font-medium mb-2" />
            <x-text-input id="email"
                class="block w-full px-4 py-3 border border-gray-300 rounded-md focus:border-green-500 focus:ring-green-500"
                type="email" name="email" :value="old('email')" required autofocus autocomplete="username"
                placeholder="tu@email.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" value="Contraseña" class="text-gray-700 font-medium mb-2" />
            <x-text-input id="password"
                class="block w-full px-4 py-3 border border-gray-300 rounded-md focus:border-green-500 focus:ring-green-500"
                type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox"
                    class="rounded border-gray-300 text-green-500 shadow-sm focus:ring-green-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">Recuérdame</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm text-gray-600 hover:text-green-600" href="{{ route('password.request') }}">
                    ¿Olvidaste tu contraseña?
                </a>
            @endif
        </div>

        <!-- Submit Button -->
        <div class="space-y-4">
            <button type="submit"
                class="w-full bg-black hover:bg-gray-800 text-white font-medium py-3 px-4 rounded-md transition-colors duration-200">
                Iniciar Sesión
            </button>

            <div class="text-center text-sm text-gray-600">
                ¿No tienes cuenta?
                <a href="{{ route('register') }}" class="font-medium text-green-600 hover:text-green-700">
                    Regístrate
                </a>
            </div>
        </div>
    </form>
</x-guest-layout>