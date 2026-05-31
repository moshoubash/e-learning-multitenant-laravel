{{-- Status Pill --}}
@props([
    'variant' => 'default', // success, warning, danger, info, default
    'size' => 'sm'
])

@php
$colorMap = [
    'success' => 'bg-green-100 text-green-800',
    'warning' => 'bg-amber-100 text-amber-800',
    'danger' => 'bg-red-100 text-red-800',
    'info' => 'bg-blue-100 text-blue-800',
    'default' => 'bg-gray-100 text-gray-700',
    'live' => 'bg-red-50 text-red-600 border border-red-200',
    'completed' => 'bg-green-50 text-green-700 border border-green-200',
];

$sizeMap = [
    'xs' => 'px-2 py-0.5 text-[10px]',
    'sm' => 'px-2.5 py-0.5 text-xs',
    'md' => 'px-3 py-1 text-sm',
];

$classes = ($colorMap[$variant] ?? $colorMap['default']) . ' ' . ($sizeMap[$size] ?? $sizeMap['sm']);
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center font-medium rounded-full ' . $classes]) }}>
    {{ $slot }}
</span>
