@if($showAssignmentEditModal && $editingAssignment)
    <div class="fixed inset-0 z-50 overflow-y-auto" x-data="{ show: true }" x-show="show">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-on-surface/60 transition-opacity" wire:click="closeAssignmentModal"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div class="inline-block overflow-hidden ltr:text-left rtl:text-right align-bottom transition-all transform bg-surface-container-lowest neo-border neo-radius sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4 max-h-[80vh] overflow-y-auto">
                    <h3 class="text-sm font-bold uppercase tracking-widest text-on-surface mb-4">{{ __('messages.Edit Assignment') }}: {{ $editingAssignment->title }}</h3>
                    <form>
                        <div class="mb-4">
                            <label class="block mb-1 text-xs font-bold uppercase tracking-widest text-on-surface">{{ __('messages.Title') }}</label>
                            <input type="text" wire:model.lazy="assignmentEditTitle"
                                class="w-full px-3 py-2 neo-border-sm neo-radius bg-surface-container-low text-on-surface text-sm focus:outline-none focus:ring-0 placeholder:text-secondary">
                            @error('assignmentEditTitle') <span class="text-xs text-error mt-1 block font-bold">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-4">
                            <label class="block mb-1 text-xs font-bold uppercase tracking-widest text-on-surface">{{ __('messages.Description') }}</label>
                            <textarea wire:model.lazy="assignmentEditDescription" rows="3"
                                class="w-full px-3 py-2 neo-border-sm neo-radius bg-surface-container-low text-on-surface text-sm focus:outline-none focus:ring-0 placeholder:text-secondary"></textarea>
                            @error('assignmentEditDescription') <span class="text-xs text-error mt-1 block font-bold">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-4">
                            <label class="block mb-1 text-xs font-bold uppercase tracking-widest text-on-surface">{{ __('messages.Instructions') }}</label>
                            <textarea wire:model.lazy="assignmentEditInstructions" rows="3"
                                class="w-full px-3 py-2 neo-border-sm neo-radius bg-surface-container-low text-on-surface text-sm focus:outline-none focus:ring-0 placeholder:text-secondary"
                                placeholder="{{ __('messages.Enter assignment instructions') }}"></textarea>
                            @error('assignmentEditInstructions') <span class="text-xs text-error mt-1 block font-bold">{{ $message }}</span> @enderror
                        </div>
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block mb-1 text-xs font-bold uppercase tracking-widest text-on-surface">{{ __('messages.Due Date') }}</label>
                                <input type="datetime-local" wire:model.lazy="assignmentEditDueDate"
                                    class="w-full px-3 py-2 neo-border-sm neo-radius bg-surface-container-low text-on-surface text-sm focus:outline-none focus:ring-0">
                                @error('assignmentEditDueDate') <span class="text-xs text-error mt-1 block font-bold">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block mb-1 text-xs font-bold uppercase tracking-widest text-on-surface">{{ __('messages.Max Score') }}</label>
                                <input type="number" wire:model.lazy="assignmentEditMaxScore" min="0"
                                    class="w-full px-3 py-2 neo-border-sm neo-radius bg-surface-container-low text-on-surface text-sm focus:outline-none focus:ring-0">
                                @error('assignmentEditMaxScore') <span class="text-xs text-error mt-1 block font-bold">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block mb-1 text-xs font-bold uppercase tracking-widest text-on-surface">{{ __('messages.Allow Late') }}</label>
                                <select wire:model.lazy="assignmentEditAllowLate"
                                    class="w-full px-3 py-2 neo-border-sm neo-radius bg-surface-container-low text-on-surface text-sm focus:outline-none focus:ring-0">
                                    <option value="1">{{ __('messages.Yes') }}</option>
                                    <option value="0">{{ __('messages.No') }}</option>
                                </select>
                                @error('assignmentEditAllowLate') <span class="text-xs text-error mt-1 block font-bold">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block mb-1 text-xs font-bold uppercase tracking-widest text-on-surface">{{ __('messages.Status') }}</label>
                                <select wire:model.lazy="assignmentEditStatus"
                                    class="w-full px-3 py-2 neo-border-sm neo-radius bg-surface-container-low text-on-surface text-sm focus:outline-none focus:ring-0">
                                    <option value="draft">{{ __('messages.Draft') }}</option>
                                    <option value="published">{{ __('messages.Published') }}</option>
                                    <option value="archived">{{ __('messages.Archived') }}</option>
                                </select>
                                @error('assignmentEditStatus') <span class="text-xs text-error mt-1 block font-bold">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="block mb-1 text-xs font-bold uppercase tracking-widest text-on-surface">{{ __('messages.Order') }}</label>
                            <input type="number" wire:model.lazy="assignmentEditOrder" min="0"
                                class="w-full px-3 py-2 neo-border-sm neo-radius bg-surface-container-low text-on-surface text-sm focus:outline-none focus:ring-0 placeholder:text-secondary">
                            @error('assignmentEditOrder') <span class="text-xs text-error mt-1 block font-bold">{{ $message }}</span> @enderror
                        </div>
                        <div class="p-4 neo-border-sm neo-radius bg-surface-container-low mb-4">
                            <label class="block mb-2 text-xs font-bold uppercase tracking-widest text-on-surface">{{ __('messages.Attachments') }}</label>
                            <p class="mb-2 text-xs text-secondary">{{ __('messages.Upload additional files for students to reference') }}</p>
                            <input type="file" wire:model="assignmentEditAttachments" multiple
                                class="w-full px-3 py-2 neo-border-sm neo-radius bg-surface-container-lowest text-on-surface text-sm file:neo-border-sm file:neo-radius file:bg-surface-container file:text-on-surface file:text-xs file:font-bold file:uppercase file:tracking-widest file:px-3 file:py-1 file:ltr:mr-3 file:rtl:ml-3 file:cursor-pointer focus:outline-none focus:ring-0">
                            @error('assignmentEditAttachments.*') <span class="text-xs text-error mt-1 block font-bold">{{ $message }}</span> @enderror
                            @if($editingAssignment && $editingAssignment->attachments->count() > 0)
                                <div class="mt-4">
                                    <h4 class="mb-2 text-xs font-bold uppercase tracking-widest text-on-surface">{{ __('messages.Current Attachments') }}</h4>
                                    <ul class="space-y-2">
                                        @foreach($editingAssignment->attachments as $attachment)
                                            <li class="flex items-center justify-between p-2 neo-border-sm neo-radius bg-surface-container-lowest">
                                                <div class="flex items-center">
                                                    <i class="fas fa-file text-secondary ltr:mr-2 rtl:ml-2 text-xs"></i>
                                                    <span class="text-sm text-on-surface">{{ $attachment->file_name }}</span>
                                                    <span class="text-xs text-secondary ltr:ml-2 rtl:mr-2">({{ number_format($attachment->size / 1024, 1) }} KB)</span>
                                                </div>
                                                <button type="button" wire:click="removeAttachment({{ $attachment->id }})"
                                                    class="text-error hover:text-on-surface transition-colors text-xs">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                        @if($editingAssignment && $editingAssignment->submissions->count() > 0)
                            <div class="p-4 neo-border-sm neo-radius bg-surface-container-low mb-4">
                                <h4 class="mb-2 text-xs font-bold uppercase tracking-widest text-on-surface">{{ __('messages.Submissions') }} ({{ $editingAssignment->submissions->count() }})</h4>
                                <ul class="space-y-2 overflow-y-auto max-h-48">
                                    @foreach($editingAssignment->submissions as $submission)
                                        <li class="p-2 neo-border-sm neo-radius bg-surface-container-lowest">
                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <span class="text-sm font-bold text-on-surface">{{ $submission->student->name ?? 'Unknown' }}</span>
                                                    <span class="text-xs text-secondary ltr:ml-2 rtl:mr-2">{{ $submission->submitted_at ? $submission->submitted_at->format('Y-m-d H:i') : 'N/A' }}</span>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <span class="px-2 py-0.5 neo-border-sm neo-radius text-[10px] font-bold {{ $submission->status === 'graded' ? 'bg-primary-container text-on-primary-container' : 'bg-surface-container-high text-on-surface' }}">
                                                        {{ ucfirst($submission->status) }}
                                                    </span>
                                                    @if($submission->score !== null)
                                                        <span class="text-sm font-bold text-on-surface">{{ $submission->score }}/{{ $editingAssignment->max_score ?? 100 }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </form>
                </div>
                <div class="px-4 py-3 bg-surface-container-low sm:px-6 sm:flex sm:flex-row-reverse">
                    <button wire:click="updateAssignment" type="button"
                        class="inline-flex justify-center w-full px-4 py-2 neo-border neo-radius bg-primary-container text-on-surface text-xs font-bold uppercase tracking-widest hover:bg-on-surface hover:text-white transition-colors sm:ltr:ml-3 sm:rtl:mr-3 sm:w-auto">
                        {{ __('messages.Update') }}
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