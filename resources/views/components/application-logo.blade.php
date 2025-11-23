<svg {{ $attributes->merge(['class' => 'fill-current']) }} viewBox="0 0 200 50" xmlns="http://www.w3.org/2000/svg">
    <!-- Leaf/Eco Symbol -->
    <g transform="translate(10, 10)">
        <path d="M 15 5 Q 5 5, 5 15 Q 5 25, 15 25 Q 10 15, 15 5 Z" fill="currentColor" opacity="0.8" />
        <path d="M 15 5 Q 25 5, 25 15 Q 25 25, 15 25 Q 20 15, 15 5 Z" fill="currentColor" />
        <circle cx="15" cy="15" r="2" fill="white" />
    </g>

    <!-- Ecoflow Text -->
    <text x="50" y="32" font-family="Arial, sans-serif" font-size="24" font-weight="bold" fill="currentColor">
        Eco<tspan fill="#10B981">flow</tspan>
    </text>

    <!-- Flowing wave accent -->
    <path d="M 150 25 Q 160 20, 170 25 T 190 25" stroke="#10B981" stroke-width="2" fill="none" opacity="0.6" />
</svg>