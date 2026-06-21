<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-4 py-2 bg-surface-container text-on-primary-container neo-border-sm neo-radius font-bold text-sm hover:bg-surface-container-high transition-colors duration-150']) }}>
    {{ $slot }}
</button>
