@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('messages.Pagination Navigation') }}" class="flex gap-2 items-center justify-between">

        @if ($paginator->onFirstPage())
            <span class="inline-flex items-center px-4 py-2 text-sm font-bold text-secondary bg-surface-container-low neo-border-sm neo-radius cursor-not-allowed">
                {!! __('messages.pagination.previous') !!}
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center px-4 py-2 text-sm font-bold text-on-surface bg-surface-container-lowest neo-border-sm neo-radius hover:bg-surface-container-high transition-colors duration-150">
                {!! __('messages.pagination.previous') !!}
            </a>
        @endif

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center px-4 py-2 text-sm font-bold text-on-surface bg-surface-container-lowest neo-border-sm neo-radius hover:bg-surface-container-high transition-colors duration-150">
                {!! __('messages.pagination.next') !!}
            </a>
        @else
            <span class="inline-flex items-center px-4 py-2 text-sm font-bold text-secondary bg-surface-container-low neo-border-sm neo-radius cursor-not-allowed">
                {!! __('messages.pagination.next') !!}
            </span>
        @endif

    </nav>
@endif
