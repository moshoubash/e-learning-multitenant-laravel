@if($showAssignmentDeleteModal && $deletingAssignment)
    <div class="fixed inset-0 z-50 overflow-y-auto" x-data="{ show: true }" x-show="show">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-on-surface/60 transition-opacity" wire:click="closeAssignmentModal"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div class="inline-block overflow-hidden ltr:text-left rtl:text-right align-bottom transition-all transform bg-surface-container-lowest neo-border neo-radius sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-sm font-bold uppercase tracking-widest text-on-surface mb-4">{{ __('messages.Delete Assignment') }}</h3>
                    <p class="text-sm text-secondary">{{ __('messages.Are you sure you want to delete this assignment? This action can be restored later.') }}</p>
                    <div class="mt-4 p-4 neo-border-sm neo-radius bg-surface-container-low">
                        <p class="font-bold text-on-surface">{{ $deletingAssignment->title }}</p>
                        <p class="text-sm text-secondary mt-1">{{ Str::limit($deletingAssignment->description, 120) }}</p>
                    </div>
                </div>
                <div class="px-4 py-3 bg-surface-container-low sm:px-6 sm:flex sm:flex-row-reverse">
                    <button wire:click="softDeleteAssignment" type="button"
                        class="inline-flex justify-center w-full px-4 py-2 neo-border neo-radius bg-error text-white text-xs font-bold uppercase tracking-widest hover:bg-on-surface transition-colors sm:ltr:ml-3 sm:rtl:mr-3 sm:w-auto">
                        {{ __('messages.Delete') }}
                    </button>
                    <button wire:click="closeAssignmentModal" type="button"
                        class="inline-flex justify-center w-full px-4 py-2 mt-3 neo-border-sm neo-radius text-xs font-bold text-on-surface bg-surface-container hover:bg-on-surface hover:text-white transition-colors sm:mt-0 sm:ltr:ml-3 sm:rtl:mr-3 sm:w-auto">
                        {{ __('messages.Cancel') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif