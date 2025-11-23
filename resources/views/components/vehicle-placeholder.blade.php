@props(['type' => 'scooter', 'size' => 'md'])

@php
    $sizes = [
        'sm' => 'w-16 h-16',
        'md' => 'w-24 h-24',
        'lg' => 'w-32 h-32',
        'xl' => 'w-48 h-48',
    ];

    $icons = [
        'scooter' => '🛴',
        'skateboard' => '🛹',
        'bicycle' => '🚲',
    ];

    $colors = [
        'scooter' => 'from-blue-100 to-blue-200',
        'skateboard' => 'from-purple-100 to-purple-200',
        'bicycle' => 'from-green-100 to-green-200',
    ];
@endphp

<div {{ $attributes->merge(['class' => 'relative rounded-lg bg-gradient-to-br ' . $colors[$type] . ' flex items-center justify-center ' . $sizes[$size]]) }}>
    <span class="text-5xl">{{ $icons[$type] }}</span>
    <div class="absolute inset-0 bg-white bg-opacity-10 rounded-lg"></div>
</div>