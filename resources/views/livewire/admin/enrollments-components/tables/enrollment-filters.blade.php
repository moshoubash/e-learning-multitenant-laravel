<div class="p-[24px] border-b-2 border-on-surface bg-surface-container-low">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex-1 max-w-md">
            <div class="flex overflow-hidden neo-border-sm neo-radius bg-surface-container-lowest">
                <input type="text" wire:model.lazy="search" placeholder="{{ __('messages.Search by student or course...') }}"
                    class="flex-1 px-3 py-2 bg-transparent text-on-surface text-sm focus:outline-none focus:ring-0 placeholder:text-secondary ltr:border-r rtl:border-l border-on-surface/20">
                <button wire:click="$refresh"
                    class="px-3 py-2 bg-transparent text-secondary hover:text-on-surface transition-colors flex items-center justify-center shrink-0">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <select wire:model.lazy="statusFilter"
                class="px-3 py-2 neo-border-sm neo-radius bg-surface-container-lowest text-on-surface text-sm focus:outline-none focus:ring-0">
                <option value="">{{ __('messages.All Statuses') }}</option>
                <option value="active">{{ __('messages.Active') }}</option>
                <option value="completed">{{ __('messages.Completed') }}</option>
                <option value="pending">{{ __('messages.Pending') }}</option>
                <option value="cancelled">{{ __('messages.Cancelled') }}</option>
            </select>
        </div>
    </div>
</div>
