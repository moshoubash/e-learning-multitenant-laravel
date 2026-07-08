<div>
    <header class="h-16 flex justify-between items-center px-[24px] bg-surface-container-lowest border-b-2 border-on-surface sticky top-0 z-40">
        <div>
            <h2 class="text-[24px] font-bold text-on-surface leading-none">{{ __('messages.Log Tracking') }}</h2>
            <p class="text-[12px] font-medium uppercase text-secondary mt-0.5 tracking-wider">{{ __('messages.Monitor application and security log files') }}</p>
        </div>
        <div class="flex items-center gap-2">
            @livewire('shared.notification-bell')
        </div>
    </header>

    <div class="p-[24px] max-w-[1400px] mx-auto space-y-6">
        @include('livewire.admin.logs-components.tables.logs-table')
    </div>

    @include('livewire.admin.logs-components.modals.view-log-modal')
    @include('livewire.admin.logs-components.modals.delete-log-modal')
</div>
