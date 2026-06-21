@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-1 pt-1 border-b-2 border-primary-container text-sm font-medium leading-5 text-on-surface focus:outline-none focus:border-primary-container transition duration-150 ease-in-out'
            : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-secondary hover:text-on-surface hover:border-primary-container focus:outline-none focus:text-on-surface focus:border-primary-container transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
