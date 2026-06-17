@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'w-full px-3 py-2 text-on-surface bg-surface-container-low neo-border-sm neo-radius focus:outline-none']) }}>
