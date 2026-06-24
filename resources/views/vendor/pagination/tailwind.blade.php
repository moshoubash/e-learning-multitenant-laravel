@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('messages.Pagination Navigation') }}">

        <div class="flex gap-2 items-center justify-between sm:hidden">

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

        </div>

        <div class="hidden sm:flex-1 sm:flex sm:gap-2 sm:items-center sm:justify-between">

            <div>
                <p class="text-sm text-secondary">
                    {!! __('messages.Showing') !!}
                    @if ($paginator->firstItem())
                        <span class="font-bold text-on-surface">{{ $paginator->firstItem() }}</span>
                        {!! __('messages.to') !!}
                        <span class="font-bold text-on-surface">{{ $paginator->lastItem() }}</span>
                    @else
                        {{ $paginator->count() }}
                    @endif
                    {!! __('messages.of') !!}
                    <span class="font-bold text-on-surface">{{ $paginator->total() }}</span>
                    {!! __('messages.results') !!}
                </p>
            </div>

            <div>
                <span class="inline-flex ltr:flex-row rtl:flex-row-reverse neo-border neo-radius overflow-hidden divide-x-2">

                    @if ($paginator->onFirstPage())
                        <span aria-disabled="true" aria-label="{{ __('messages.pagination.previous') }}">
                            <span class="inline-flex items-center px-2 py-2 text-sm font-medium text-secondary bg-surface-container-low cursor-not-allowed" aria-hidden="true">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </span>
                        </span>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center px-2 py-2 text-sm font-medium text-on-surface bg-surface-container-lowest hover:bg-surface-container-high transition-colors duration-150" aria-label="{{ __('messages.pagination.previous') }}">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    @endif

                    @foreach ($elements as $element)
                        @if (is_string($element))
                            <span aria-disabled="true">
                                <span class="inline-flex items-center px-4 py-2 text-sm font-bold text-secondary bg-surface-container-low">{{ $element }}</span>
                            </span>
                        @endif

                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <span aria-current="page">
                                        <span class="inline-flex items-center px-4 py-2 text-sm font-bold text-on-primary-container bg-primary-container">{{ $page }}</span>
                                    </span>
                                @else
                                    <a href="{{ $url }}" class="inline-flex items-center px-4 py-2 text-sm font-bold text-on-surface bg-surface-container-lowest hover:bg-surface-container-high transition-colors duration-150" aria-label="{{ __('messages.Go to page :page', ['page' => $page]) }}">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center px-2 py-2 text-sm font-medium text-on-surface bg-surface-container-lowest hover:bg-surface-container-high transition-colors duration-150" aria-label="{{ __('messages.pagination.next') }}">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    @else
                        <span aria-disabled="true" aria-label="{{ __('messages.pagination.next') }}">
                            <span class="inline-flex items-center px-2 py-2 text-sm font-medium text-secondary bg-surface-container-low cursor-not-allowed" aria-hidden="true">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                </svg>
                            </span>
                        </span>
                    @endif
                </span>
            </div>
        </div>
    </nav>
@endif
