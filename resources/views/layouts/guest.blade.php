<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
        <!-- Logo -->
        <div class="mb-8">
            <a href="/" class="flex flex-col items-center">
                <!-- GO GREEN Style Logo -->
                <div class="flex items-center mb-2">
                    <svg class="w-12 h-12 text-green-500" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M17 8C8 10 5.9 16.17 3.82 21.34l1.89.66.95-2.3c.48.17.98.3 1.34.3C19 20 22 3 22 3c-1 2-8 2.25-13 3.25S2 11.5 2 13.5s1.75 3.75 1.75 3.75C7 8 17 8 17 8z" />
                    </svg>
                </div>
                <div class="text-center">
                    <h1 class="text-4xl font-bold text-green-500">GO</h1>
                    <h2 class="text-2xl font-bold text-green-600">GREEN</h2>
                </div>
            </a>
        </div>

        <!-- Content Card -->
        <div class="w-full sm:max-w-md px-8 py-8 bg-white shadow-lg overflow-hidden sm:rounded-lg">
            {{ $slot }}
        </div>
    </div>
</body>

</html>