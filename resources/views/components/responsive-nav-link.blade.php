@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-primary-container text-start text-base font-medium text-on-surface bg-surface-container-lowest focus:outline-none focus:text-on-surface focus:bg-surface-container-low focus:border-primary-container transition duration-150 ease-in-out'
            : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-secondary hover:text-on-surface hover:bg-surface-container-low hover:border-primary-container focus:outline-none focus:text-on-surface focus:bg-surface-container-low focus:border-primary-container transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
