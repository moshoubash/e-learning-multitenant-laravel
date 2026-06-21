@if($showAssignmentCreateModal)
    <div class="fixed inset-0 z-50 overflow-y-auto" x-data="{ show: true }" x-show="show">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-on-surface/60 transition-opacity" wire:click="closeAssignmentModal"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div class="inline-block overflow-hidden ltr:text-left rtl:text-right align-bottom transition-all transform bg-surface-container-lowest neo-border neo-radius sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4 max-h-[80vh] overflow-y-auto">
                    <h3 class="text-sm font-bold uppercase tracking-widest text-on-surface mb-4">{{ __('messages.Create New Assignment') }}</h3>
                    <form>
                        <div class="mb-4">
                            <label class="block mb-1 text-xs font-bold uppercase tracking-widest text-on-surface">{{ __('messages.Title') }}</label>
                            <input type="text" wire:model.lazy="assignmentCreateTitle"
                                class="w-full px-3 py-2 neo-border-sm neo-radius bg-surface-container-low text-on-surface text-sm focus:outline-none focus:ring-0 placeholder:text-secondary">
                            @error('assignmentCreateTitle') <span class="text-xs text-error mt-1 block font-bold">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-4">
                            <label class="block mb-1 text-xs font-bold uppercase tracking-widest text-on-surface">{{ __('messages.Description') }}</label>
                            <textarea wire:model.lazy="assignmentCreateDescription" rows="3"
                                class="w-full px-3 py-2 neo-border-sm neo-radius bg-surface-container-low text-on-surface text-sm focus:outline-none focus:ring-0 placeholder:text-secondary"></textarea>
                            @error('assignmentCreateDescription') <span class="text-xs text-error mt-1 block font-bold">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-4">
                            <label class="block mb-1 text-xs font-bold uppercase tracking-widest text-on-surface">{{ __('messages.Instructions') }}</label>
                            <textarea wire:model.lazy="assignmentCreateInstructions" rows="3"
                                class="w-full px-3 py-2 neo-border-sm neo-radius bg-surface-container-low text-on-surface text-sm focus:outline-none focus:ring-0 placeholder:text-secondary"
                                placeholder="{{ __('messages.Enter assignment instructions') }}"></textarea>
                            @error('assignmentCreateInstructions') <span class="text-xs text-error mt-1 block font-bold">{{ $message }}</span> @enderror
                        </div>
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block mb-1 text-xs font-bold uppercase tracking-widest text-on-surface">{{ __('messages.Due Date') }}</label>
                                <input type="datetime-local" wire:model.lazy="assignmentCreateDueDate"
                                    class="w-full px-3 py-2 neo-border-sm neo-radius bg-surface-container-low text-on-surface text-sm focus:outline-none focus:ring-0">
                                @error('assignmentCreateDueDate') <span class="text-xs text-error mt-1 block font-bold">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block mb-1 text-xs font-bold uppercase tracking-widest text-on-surface">{{ __('messages.Max Score') }}</label>
                                <input type="number" wire:model.lazy="assignmentCreateMaxScore" min="0"
                                    class="w-full px-3 py-2 neo-border-sm neo-radius bg-surface-container-low text-on-surface text-sm focus:outline-none focus:ring-0" value="100">
                                @error('assignmentCreateMaxScore') <span class="text-xs text-error mt-1 block font-bold">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block mb-1 text-xs font-bold uppercase tracking-widest text-on-surface">{{ __('messages.Allow Late') }}</label>
                                <select wire:model.lazy="assignmentCreateAllowLate"
                                    class="w-full px-3 py-2 neo-border-sm neo-radius bg-surface-container-low text-on-surface text-sm focus:outline-none focus:ring-0">
                                    <option value="1">{{ __('messages.Yes') }}</option>
                                    <option value="0">{{ __('messages.No') }}</option>
                                </select>
                                @error('assignmentCreateAllowLate') <span class="text-xs text-error mt-1 block font-bold">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block mb-1 text-xs font-bold uppercase tracking-widest text-on-surface">{{ __('messages.Status') }}</label>
                                <select wire:model.lazy="assignmentCreateStatus"
                                    class="w-full px-3 py-2 neo-border-sm neo-radius bg-surface-container-low text-on-surface text-sm focus:outline-none focus:ring-0">
                                    <option value="draft">{{ __('messages.Draft') }}</option>
                                    <option value="published">{{ __('messages.Published') }}</option>
                                    <option value="archived">{{ __('messages.Archived') }}</option>
                                </select>
                                @error('assignmentCreateStatus') <span class="text-xs text-error mt-1 block font-bold">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="block mb-1 text-xs font-bold uppercase tracking-widest text-on-surface">{{ __('messages.Order') }}</label>
                            <input type="number" wire:model.lazy="assignmentCreateOrder" min="0"
                                class="w-full px-3 py-2 neo-border-sm neo-radius bg-surface-container-low text-on-surface text-sm focus:outline-none focus:ring-0 placeholder:text-secondary">
                            @error('assignmentCreateOrder') <span class="text-xs text-error mt-1 block font-bold">{{ $message }}</span> @enderror
                        </div>
                        <div class="p-4 neo-border-sm neo-radius bg-surface-container-low mb-4">
                            <label class="block mb-2 text-xs font-bold uppercase tracking-widest text-on-surface">{{ __('messages.Attachments') }}</label>
                            <p class="mb-2 text-xs text-secondary">{{ __('messages.Upload files for students to reference') }}</p>
                            <input type="file" wire:model="assignmentCreateAttachments" multiple
                                class="w-full px-3 py-2 neo-border-sm neo-radius bg-surface-container-lowest text-on-surface text-sm file:neo-border-sm file:neo-radius file:bg-surface-container file:text-on-surface file:text-xs file:font-bold file:uppercase file:tracking-widest file:px-3 file:py-1 file:ltr:mr-3 file:rtl:ml-3 file:cursor-pointer focus:outline-none focus:ring-0">
                            @error('assignmentCreateAttachments.*') <span class="text-xs text-error mt-1 block font-bold">{{ $message }}</span> @enderror
                            @if(count($this->assignmentCreateAttachments ?? []) > 0)
                                <div class="mt-2 text-xs font-bold text-secondary">{{ count($this->assignmentCreateAttachments) }} {{ __('messages.file(s) selected') }}</div>
                            @endif
                        </div>
                    </form>
                </div>
                <div class="px-4 py-3 bg-surface-container-low sm:px-6 sm:flex sm:flex-row-reverse">
                    <button wire:click="storeAssignment" type="button"
                        class="inline-flex justify-center w-full px-4 py-2 neo-border neo-radius bg-primary-container text-on-primary-container text-xs font-bold uppercase tracking-widest hover:bg-on-surface hover:text-white transition-colors sm:ltr:ml-3 sm:rtl:mr-3 sm:w-auto">
                        {{ __('messages.Create') }}
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