<div>
    <header class="h-16 flex justify-between items-center px-[24px] bg-surface-container-lowest border-b-2 border-on-surface sticky top-0 z-40">
        <div>
            <h2 class="text-[24px] font-bold text-on-surface leading-none">{{ __('messages.Leaderboard Monitor') }}</h2>
            <p class="text-[12px] font-medium uppercase text-secondary mt-0.5 tracking-wider">{{ __('messages.Monitor student rankings and points') }}</p>
        </div>
        <div class="flex items-center gap-2">
            @livewire('shared.notification-bell')
        </div>
    </header>

    <div class="p-[24px] max-w-[1400px] mx-auto space-y-6">
        @include('livewire.admin.leaderboard-monitor-components.tables.stats-cards')

        <div>
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="{{ __('messages.Search by name or email...') }}"
                   class="w-full neo-border neo-radius px-4 py-3 bg-surface-container-lowest text-on-surface text-sm font-bold placeholder:text-secondary focus:outline-none focus:ring-2 focus:ring-primary">
        </div>

        @include('livewire.admin.leaderboard-monitor-components.tables.leaderboard-table')
    </div>
</div>
