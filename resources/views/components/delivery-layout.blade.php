<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Repartidor</title>

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    @stack('styles')
</head>

<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen">
        <!-- Navigation -->
        <nav class="bg-gradient-to-r from-orange-600 to-red-600 shadow-lg">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <!-- Logo -->
                        <div class="flex-shrink-0 flex items-center">
                            <span class="text-2xl font-bold text-white">🚚 EcoFlow</span>
                            <span class="ml-2 px-2 py-1 text-xs bg-white text-orange-600 rounded-full font-semibold">Repartidor</span>
                        </div>

                        <!-- Navigation Links -->
                        <div class="hidden sm:ml-10 sm:flex sm:space-x-4">
                            <a href="{{ route('repartidor.dashboard') }}"
                                class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('repartidor.dashboard') ? 'bg-orange-700 text-white' : 'text-white hover:bg-orange-500' }}">
                                📊 Dashboard
                            </a>
                            <a href="{{ route('repartidor.deliveries') }}"
                                class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('repartidor.deliveries') ? 'bg-orange-700 text-white' : 'text-white hover:bg-orange-500' }}">
                                📦 Mis Entregas
                            </a>
                            <a href="{{ route('repartidor.history') }}"
                                class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('repartidor.history') ? 'bg-orange-700 text-white' : 'text-white hover:bg-orange-500' }}">
                                📜 Historial
                            </a>
                            <a href="{{ route('repartidor.performance') }}"
                                class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('repartidor.performance') ? 'bg-orange-700 text-white' : 'text-white hover:bg-orange-500' }}">
                                📈 Desempeño
                            </a>
                        </div>
                    </div>

                    <!-- User Menu -->
                    <div class="flex items-center space-x-4">
                        <!-- Availability Toggle -->
                        <div class="flex items-center space-x-2">
                            <span class="text-white text-sm">{{ Auth::user()->is_available ? 'Disponible' : 'No disponible' }}</span>
                            <button wire:click="toggleAvailability" 
                                class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors {{ Auth::user()->is_available ? 'bg-green-500' : 'bg-gray-400' }}">
                                <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform {{ Auth::user()->is_available ? 'translate-x-6' : 'translate-x-1' }}"></span>
                            </button>
                        </div>

                        <!-- User Dropdown -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open"
                                class="flex items-center space-x-2 text-white hover:text-orange-100">
                                <div class="w-8 h-8 bg-orange-700 rounded-full flex items-center justify-center font-bold">
                                    {{ Auth::user()->getInitials() }}
                                </div>
                                <span class="hidden md:block text-sm font-medium">{{ Auth::user()->name }}</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <div x-show="open" @click.away="open = false" x-cloak
                                class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                                <a href="{{ route('profile.edit') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    👤 Mi Perfil
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        🚪 Cerrar Sesión
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </div>

    <!-- Toast Notifications -->
    <x-toast-notifications />

    @stack('scripts')
</body>

</html>
