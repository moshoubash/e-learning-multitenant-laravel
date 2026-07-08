<div>
    <header class="h-16 flex justify-between items-center px-[24px] bg-surface-container-lowest border-b-2 border-on-surface sticky top-0 z-40">
        <div>
            <h2 class="text-[24px] font-bold text-on-surface leading-none">{{ __('messages.Assignment Submissions') }}</h2>
            <p class="text-[12px] font-medium uppercase text-secondary mt-0.5 tracking-wider">{{ __('messages.Review and grade student submissions') }}</p>
        </div>
        <div class="flex items-center gap-2">
            @livewire('shared.notification-bell')
        </div>
    </header>

    <div class="p-[24px] max-w-[1400px] mx-auto space-y-6">
        {{-- Filter Tabs --}}
        <div class="flex flex-wrap gap-2 border-b-2 border-on-surface">
            <button wire:click="setFilterStatus('all')"
                class="px-4 py-2 text-xs font-bold uppercase tracking-widest transition-colors border-b-2 -mb-[2px] {{ $filterStatus === 'all' ? 'border-on-surface text-on-surface' : 'border-transparent text-secondary hover:text-on-surface' }}">
                {{ __('messages.All Submissions') }}
            </button>
            <button wire:click="setFilterStatus('pending')"
                class="px-4 py-2 text-xs font-bold uppercase tracking-widest transition-colors border-b-2 -mb-[2px] {{ $filterStatus === 'pending' ? 'border-on-surface text-on-surface' : 'border-transparent text-secondary hover:text-on-surface' }}">
                {{ __('messages.Pending Review') }}
            </button>
            <button wire:click="setFilterStatus('graded')"
                class="px-4 py-2 text-xs font-bold uppercase tracking-widest transition-colors border-b-2 -mb-[2px] {{ $filterStatus === 'graded' ? 'border-on-surface text-on-surface' : 'border-transparent text-secondary hover:text-on-surface' }}">
                {{ __('messages.Graded') }}
            </button>
        </div>

        @include('livewire.instructor.assignment-submissions-components.tables.submissions-table')
    </div>

    @include('livewire.instructor.assignment-submissions-components.modals.grading-modal')
</div>
