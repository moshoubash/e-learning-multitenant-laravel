@props(['value'])

<label {{ $attributes->merge(['class' => 'block text-sm font-bold uppercase tracking-wider text-on-surface']) }}>
    {{ $value ?? $slot }}
</label>
