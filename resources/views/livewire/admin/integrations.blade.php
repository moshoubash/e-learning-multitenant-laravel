<div>
    <header class="h-16 flex justify-between items-center px-[24px] bg-surface-container-lowest border-b-2 border-on-surface sticky top-0 z-40">
        <div>
            <h2 class="text-[24px] font-bold text-on-surface leading-none">{{ __('messages.Integrations') }}</h2>
            <p class="text-[12px] font-medium uppercase text-secondary mt-0.5 tracking-wider">{{ __('messages.Manage OAuth providers and API credentials') }}</p>
        </div>
        <div class="flex items-center gap-2">
            @livewire('shared.notification-bell')
            <button wire:click="openCreateModal"
                    @if(empty($this->availableProviders)) disabled title="{{ __('messages.All providers have been added') }}" @endif
                    class="px-4 py-2 text-xs font-bold tracking-widest uppercase transition-colors neo-border neo-radius bg-primary-container text-on-primary-container hover:bg-on-surface hover:text-white disabled:opacity-50 disabled:cursor-not-allowed">
                    <i class="fas fa-plus ltr:mr-2 rtl:ml-2"></i>
                    {{ __('messages.Add Integration') }}
                </button>
            </div>
        </header>

    <div class="p-[24px] max-w-[1400px] mx-auto space-y-6">
        @include('livewire.admin.integrations-components.tables.integrations-table')
    </div>

    @include('livewire.admin.integrations-components.modals.create-integration-modal')
    @include('livewire.admin.integrations-components.modals.edit-integration-modal')
    @include('livewire.admin.integrations-components.modals.delete-integration-modal')
</div>
