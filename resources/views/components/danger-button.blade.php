<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-error text-white neo-border-sm neo-radius font-bold text-sm hover:bg-on-surface transition-colors duration-150']) }}>
    {{ $slot }}
</button>
