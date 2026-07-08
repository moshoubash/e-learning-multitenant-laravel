<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center gap-2 px-4 py-2 text-xs font-bold tracking-widest uppercase transition-colors neo-border neo-radius bg-error text-white hover:bg-on-surface']) }}>
    {{ $slot }}
</button>
