@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'w-full px-4 py-3 text-on-surface bg-surface-container-lowest rounded-neo font-medium border-2 border-on-surface transition-all focus:outline-none focus:shadow-[0_0_0_3px_var(--color-primary-container)]']) }}>
