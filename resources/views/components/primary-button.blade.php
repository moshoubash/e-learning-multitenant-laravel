<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-primary-container text-on-primary-container neo-border-sm neo-radius font-bold text-sm hover:bg-on-surface hover:text-white transition-colors duration-150']) }}>
    {{ $slot }}
</button>
