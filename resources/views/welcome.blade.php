<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ecoflow - Movilidad Eléctrica Sostenible</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800" rel="stylesheet" />

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="antialiased bg-white">
    <!-- Navigation -->
    <nav class="bg-white border-b border-gray-200 fixed w-full z-50 top-0">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <div class="flex items-center space-x-3">
                        <div
                            class="w-10 h-10 bg-gradient-to-br from-green-500 to-teal-500 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5" />
                            </svg>
                        </div>
                        <span class="text-2xl font-bold text-gray-900">EcoFlow</span>
                    </div>
                </div>

                <!-- Navigation Links -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#inicio"
                        class="text-gray-700 hover:text-green-600 font-medium transition-colors">Inicio</a>
                    <a href="#servicios"
                        class="text-gray-700 hover:text-green-600 font-medium transition-colors">Servicios</a>
                    <a href="#sedes" class="text-gray-700 hover:text-green-600 font-medium transition-colors">Sedes</a>
                    <a href="#contacto"
                        class="text-gray-700 hover:text-green-600 font-medium transition-colors">Contacto</a>
                </div>

                <!-- Auth Buttons -->
                <div class="flex items-center space-x-4">
                    @auth
                        <a href="{{ url('/dashboard') }}"
                            class="text-gray-700 hover:text-green-600 font-medium transition-colors">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}"
                            class="text-gray-700 hover:text-green-600 font-medium transition-colors">Iniciar sesión</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                                class="bg-gradient-to-r from-green-500 to-teal-500 text-white px-6 py-2 rounded-lg font-semibold hover:from-green-600 hover:to-teal-600 transition-all shadow-md hover:shadow-lg">
                                Registrarse
                            </a>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="inicio" class="pt-24 pb-16 bg-gradient-to-br from-green-50 via-white to-teal-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <!-- Text Content -->
                <div>
                    <h1 class="text-5xl lg:text-6xl font-bold text-gray-900 mb-6 leading-tight">
                        Movilidad Eléctrica
                        <span
                            class="bg-gradient-to-r from-green-500 to-teal-500 text-transparent bg-clip-text">Sostenible</span>
                    </h1>
                    <p class="text-xl text-gray-600 mb-8 leading-relaxed">
                        Somos una plataforma de movilidad sostenible que ofrece el alquiler de bicicletas, patinetas y
                        monopatines eléctricos en Bucaramanga y su área metropolitana, facilitando reservas, pagos y
                        ubicaciones en tiempo real.
                    </p>

                    <!-- Key Points -->
                    <div class="space-y-4 mb-8">
                        <div class="flex items-start space-x-3">
                            <div
                                class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <p class="text-gray-700">Solución al transporte público ineficiente en Bucaramanga</p>
                        </div>
                        <div class="flex items-start space-x-3">
                            <div
                                class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <p class="text-gray-700">Vehículos 100% eléctricos y ambientalmente sostenibles</p>
                        </div>
                        <div class="flex items-start space-x-3">
                            <div
                                class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <p class="text-gray-700">4 sedes estratégicas en el área metropolitana</p>
                        </div>
                    </div>

                    <!-- CTA Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="{{ route('register') }}"
                            class="bg-gradient-to-r from-green-500 to-teal-500 text-white px-8 py-4 rounded-lg font-bold hover:from-green-600 hover:to-teal-600 transition-all shadow-lg hover:shadow-xl text-center">
                            Comenzar Ahora
                        </a>
                        <a href="/map"
                            class="bg-white border-2 border-green-500 text-green-600 px-8 py-4 rounded-lg font-bold hover:bg-green-50 transition-all text-center">
                            Ver Mapa de Vehículos
                        </a>
                    </div>
                </div>

                <!-- Hero Image -->
                <div class="relative">
                    <div class="bg-gradient-to-br from-green-100 to-teal-100 rounded-3xl p-8 shadow-2xl">
                        <img src="C:/Users/juanp/.gemini/antigravity/brain/efc0db01-cc24-4ca5-ab60-bb67b92ea3b6/hero_illustration_1763949480751.png"
                            alt="Personas usando vehículos eléctricos" class="w-full h-auto rounded-2xl">
                    </div>
                    <!-- Floating Stats -->
                    <div class="absolute -bottom-6 -left-6 bg-white rounded-xl shadow-xl p-6 border-2 border-green-200">
                        <p class="text-3xl font-bold text-green-600">15+</p>
                        <p class="text-sm text-gray-600">Vehículos Disponibles</p>
                    </div>
                    <div class="absolute -top-6 -right-6 bg-white rounded-xl shadow-xl p-6 border-2 border-teal-200">
                        <p class="text-3xl font-bold text-teal-600">100%</p>
                        <p class="text-sm text-gray-600">Eléctricos</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="servicios" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">¿Por qué elegirnos?</h2>
                <p class="text-xl text-gray-600">La mejor opción para tu movilidad urbana</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div
                    class="bg-gradient-to-br from-green-50 to-green-100 rounded-2xl p-8 hover:shadow-xl transition-shadow">
                    <div class="w-16 h-16 bg-green-500 rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">100% Eléctrico</h3>
                    <p class="text-gray-600 leading-relaxed">Vehículos sostenibles que no generan emisiones
                        contaminantes. Cuida el planeta mientras te desplazas.</p>
                </div>

                <!-- Feature 2 -->
                <div
                    class="bg-gradient-to-br from-teal-50 to-teal-100 rounded-2xl p-8 hover:shadow-xl transition-shadow">
                    <div class="w-16 h-16 bg-teal-500 rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Disponibilidad 24/7</h3>
                    <p class="text-gray-600 leading-relaxed">Reserva y alquila vehículos en cualquier momento. Sistema
                        automatizado para tu comodidad.</p>
                </div>

                <!-- Feature 3 -->
                <div
                    class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-2xl p-8 hover:shadow-xl transition-shadow">
                    <div class="w-16 h-16 bg-blue-500 rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Rastreo en Tiempo Real</h3>
                    <p class="text-gray-600 leading-relaxed">Ubica vehículos disponibles y rastrea tu vehículo alquilado
                        en tiempo real desde tu dispositivo.</p>
                </div>

                <!-- Feature 4 -->
                <div
                    class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-2xl p-8 hover:shadow-xl transition-shadow">
                    <div class="w-16 h-16 bg-purple-500 rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Pagos Seguros</h3>
                    <p class="text-gray-600 leading-relaxed">Acepta múltiples métodos de pago con total seguridad.
                        Transacciones encriptadas y protegidas.</p>
                </div>

                <!-- Feature 5 -->
                <div
                    class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-2xl p-8 hover:shadow-xl transition-shadow">
                    <div class="w-16 h-16 bg-orange-500 rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Precios Accesibles</h3>
                    <p class="text-gray-600 leading-relaxed">Tarifas competitivas y transparentes. Paga solo por el
                        tiempo que uses el vehículo.</p>
                </div>

                <!-- Feature 6 -->
                <div
                    class="bg-gradient-to-br from-pink-50 to-pink-100 rounded-2xl p-8 hover:shadow-xl transition-shadow">
                    <div class="w-16 h-16 bg-pink-500 rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Soporte 24/7</h3>
                    <p class="text-gray-600 leading-relaxed">Equipo de atención al cliente disponible en todo momento
                        para resolver tus dudas.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Stations Section -->
    <section id="sedes" class="py-20 bg-gradient-to-br from-gray-50 to-green-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Nuestras Sedes</h2>
                <p class="text-xl text-gray-600">4 ubicaciones estratégicas en el área metropolitana</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Station 1 -->
                <div
                    class="bg-white rounded-2xl p-6 shadow-lg hover:shadow-xl transition-shadow border-t-4 border-green-500">
                    <div class="text-4xl mb-4">📍</div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Cabecera</h3>
                    <p class="text-gray-600 text-sm mb-4">Parque San Pío - Calle 45 #35-20</p>
                    <div class="flex items-center text-green-600 text-sm font-semibold">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd" />
                        </svg>
                        5 vehículos disponibles
                    </div>
                </div>

                <!-- Station 2 -->
                <div
                    class="bg-white rounded-2xl p-6 shadow-lg hover:shadow-xl transition-shadow border-t-4 border-teal-500">
                    <div class="text-4xl mb-4">📍</div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Floridablanca Centro</h3>
                    <p class="text-gray-600 text-sm mb-4">Parque Principal - Carrera 7 #10-15</p>
                    <div class="flex items-center text-teal-600 text-sm font-semibold">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd" />
                        </svg>
                        4 vehículos disponibles
                    </div>
                </div>

                <!-- Station 3 -->
                <div
                    class="bg-white rounded-2xl p-6 shadow-lg hover:shadow-xl transition-shadow border-t-4 border-blue-500">
                    <div class="text-4xl mb-4">📍</div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Girón</h3>
                    <p class="text-gray-600 text-sm mb-4">Parque Las Nieves - Calle 30 #25-42</p>
                    <div class="flex items-center text-blue-600 text-sm font-semibold">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd" />
                        </svg>
                        3 vehículos disponibles
                    </div>
                </div>

                <!-- Station 4 -->
                <div
                    class="bg-white rounded-2xl p-6 shadow-lg hover:shadow-xl transition-shadow border-t-4 border-purple-500">
                    <div class="text-4xl mb-4">📍</div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Piedecuesta</h3>
                    <p class="text-gray-600 text-sm mb-4">Centro Comercial - Carrera 5 #8-30</p>
                    <div class="flex items-center text-purple-600 text-sm font-semibold">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd" />
                        </svg>
                        3 vehículos disponibles
                    </div>
                </div>
            </div>

            <div class="mt-12 text-center">
                <a href="/map"
                    class="inline-flex items-center bg-gradient-to-r from-green-500 to-teal-500 text-white px-8 py-4 rounded-lg font-bold hover:from-green-600 hover:to-teal-600 transition-all shadow-lg hover:shadow-xl">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                    </svg>
                    Ver Mapa Interactivo
                </a>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-gradient-to-r from-green-500 to-teal-500">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-4xl font-bold text-white mb-6">¿Listo para comenzar?</h2>
            <p class="text-xl text-green-100 mb-8 max-w-2xl mx-auto">
                Únete a la revolución de la movilidad sostenible en Bucaramanga. Regístrate hoy y obtén tu primer viaje
                gratis.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('register') }}"
                    class="bg-white text-green-600 px-8 py-4 rounded-lg font-bold hover:bg-gray-100 transition-all shadow-lg inline-block">
                    Crear Cuenta Gratis
                </a>
                <a href="/map"
                    class="bg-transparent border-2 border-white text-white px-8 py-4 rounded-lg font-bold hover:bg-white hover:text-green-600 transition-all inline-block">
                    Explorar Vehículos
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer id="contacto" class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
                <!-- Company Info -->
                <div class="col-span-2">
                    <div class="flex items-center space-x-3 mb-4">
                        <div
                            class="w-10 h-10 bg-gradient-to-br from-green-500 to-teal-500 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5" />
                            </svg>
                        </div>
                        <span class="text-2xl font-bold">EcoFlow</span>
                    </div>
                    <p class="text-gray-400 mb-4">Movilidad eléctrica sostenible para un futuro mejor en Bucaramanga.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-green-500 transition-colors">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                            </svg>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-green-500 transition-colors">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z" />
                            </svg>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-green-500 transition-colors">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12 0C8.74 0 8.333.015 7.053.072 5.775.132 4.905.333 4.14.63c-.789.306-1.459.717-2.126 1.384S.935 3.35.63 4.14C.333 4.905.131 5.775.072 7.053.012 8.333 0 8.74 0 12s.015 3.667.072 4.947c.06 1.277.261 2.148.558 2.913.306.788.717 1.459 1.384 2.126.667.666 1.336 1.079 2.126 1.384.766.296 1.636.499 2.913.558C8.333 23.988 8.74 24 12 24s3.667-.015 4.947-.072c1.277-.06 2.148-.262 2.913-.558.788-.306 1.459-.718 2.126-1.384.666-.667 1.079-1.335 1.384-2.126.296-.765.499-1.636.558-2.913.06-1.28.072-1.687.072-4.947s-.015-3.667-.072-4.947c-.06-1.277-.262-2.149-.558-2.913-.306-.789-.718-1.459-1.384-2.126C21.319 1.347 20.651.935 19.86.63c-.765-.297-1.636-.499-2.913-.558C15.667.012 15.26 0 12 0zm0 2.16c3.203 0 3.585.016 4.85.071 1.17.055 1.805.249 2.227.415.562.217.96.477 1.382.896.419.42.679.819.896 1.381.164.422.36 1.057.413 2.227.057 1.266.07 1.646.07 4.85s-.015 3.585-.074 4.85c-.061 1.17-.256 1.805-.421 2.227-.224.562-.479.96-.899 1.382-.419.419-.824.679-1.38.896-.42.164-1.065.36-2.235.413-1.274.057-1.649.07-4.859.07-3.211 0-3.586-.015-4.859-.074-1.171-.061-1.816-.256-2.236-.421-.569-.224-.96-.479-1.379-.899-.421-.419-.69-.824-.9-1.38-.165-.42-.359-1.065-.42-2.235-.045-1.26-.061-1.649-.061-4.844 0-3.196.016-3.586.061-4.861.061-1.17.255-1.814.42-2.234.21-.57.479-.96.9-1.381.419-.419.81-.689 1.379-.898.42-.166 1.051-.361 2.221-.421 1.275-.045 1.65-.06 4.859-.06l.045.03zm0 3.678c-3.405 0-6.162 2.76-6.162 6.162 0 3.405 2.76 6.162 6.162 6.162 3.405 0 6.162-2.76 6.162-6.162 0-3.405-2.76-6.162-6.162-6.162zM12 16c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4zm7.846-10.405c0 .795-.646 1.44-1.44 1.44-.795 0-1.44-.646-1.44-1.44 0-.794.646-1.439 1.44-1.439.793-.001 1.44.645 1.44 1.439z" />
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div>
                    <h3 class="text-lg font-bold mb-4">Enlaces Rápidos</h3>
                    <ul class="space-y-2">
                        <li><a href="#inicio" class="text-gray-400 hover:text-green-500 transition-colors">Inicio</a>
                        </li>
                        <li><a href="#servicios"
                                class="text-gray-400 hover:text-green-500 transition-colors">Servicios</a></li>
                        <li><a href="#sedes" class="text-gray-400 hover:text-green-500 transition-colors">Sedes</a></li>
                        <li><a href="/map" class="text-gray-400 hover:text-green-500 transition-colors">Mapa</a></li>
                    </ul>
                </div>

                <!-- Contact -->
                <div>
                    <h3 class="text-lg font-bold mb-4">Contacto</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li>📍 Bucaramanga, Santander</li>
                        <li>📞 +57 300 123 4567</li>
                        <li>✉️ contacto@ecoflow.com</li>
                        <li>🕐 24/7 Disponible</li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-800 pt-8 text-center text-gray-400">
                <p>&copy; 2025 Ecoflow. Todos los derechos reservados. Hecho con 💚 en Bucaramanga.</p>
            </div>
        </div>
    </footer>

    <!-- Scroll to top button -->
    <button x-data="{ show: false }" @scroll.window="show = window.pageYOffset > 300" x-show="show"
        @click="window.scrollTo({top: 0, behavior: 'smooth'})"
        class="fixed bottom-8 right-8 bg-gradient-to-r from-green-500 to-teal-500 text-white p-4 rounded-full shadow-lg hover:from-green-600 hover:to-teal-600 transition-all z-40"
        x-transition>
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
        </svg>
    </button>
</body>

</html>