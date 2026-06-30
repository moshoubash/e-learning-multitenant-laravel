@if($showEditQuizModal && $editingQuiz)
    <div class="fixed inset-0 z-50 overflow-y-auto" x-data="{ show: true }" x-show="show">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-on-surface/60" wire:click="closeModals"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div class="inline-block overflow-hidden align-bottom transition-all transform ltr:text-left rtl:text-right bg-surface-container-lowest neo-border neo-radius sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4 max-h-[90vh] overflow-y-auto">
                    <h3 class="mb-4 text-sm font-bold tracking-widest uppercase text-on-surface">{{ __('messages.Edit Quiz') }}: {{ $editingQuiz->title }}</h3>

                    <!-- Quiz Details Form -->
                    <div class="p-4 mb-6 bg-surface-container-low neo-border-sm neo-radius">
                        <h4 class="mb-3 text-xs font-bold tracking-widest uppercase text-on-surface">{{ __('messages.Quiz Details') }}</h4>
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                            <div>
                                <label class="block mb-1 text-xs font-bold tracking-widest uppercase text-on-surface">{{ __('messages.Title') }}</label>
                                <input type="text" wire:model.lazy="editTitle"
                                    class="w-full px-3 py-2 text-sm neo-border-sm neo-radius bg-surface-container-lowest text-on-surface focus:outline-none focus:ring-0 placeholder:text-secondary">
                                @error('editTitle') <span class="block mt-1 text-xs font-bold text-error">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block mb-1 text-xs font-bold tracking-widest uppercase text-on-surface">{{ __('messages.Section') }}</label>
                                <select wire:model.lazy="editSectionId"
                                    class="w-full px-3 py-2 text-sm neo-border-sm neo-radius bg-surface-container-lowest text-on-surface focus:outline-none focus:ring-0">
                                    <option value="">Select Section</option>
                                    @foreach($sections as $section)
                                        <option value="{{ $section->id }}">{{ $section->course?->title }} - {{ $section->title }}</option>
                                    @endforeach
                                </select>
                                @error('editSectionId') <span class="block mt-1 text-xs font-bold text-error">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block mb-1 text-xs font-bold tracking-widest uppercase text-on-surface">{{ __('messages.Pass Percentage') }}</label>
                                <input type="number" wire:model.lazy="editPassPercentage" min="1" max="100"
                                    class="w-full px-3 py-2 text-sm neo-border-sm neo-radius bg-surface-container-lowest text-on-surface focus:outline-none focus:ring-0 placeholder:text-secondary">
                                @error('editPassPercentage') <span class="block mt-1 text-xs font-bold text-error">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <button wire:click="updateQuiz"
                            class="px-4 py-2 mt-3 text-xs font-bold tracking-widest uppercase transition-colors neo-border neo-radius bg-primary-container text-on-primary-container hover:bg-on-surface hover:text-white">
                            {{ __('messages.Update Quiz') }}
                        </button>
                    </div>

                    <!-- Questions Section -->
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-xs font-bold tracking-widest uppercase text-on-surface">{{ __('messages.Questions') }} ({{ $editingQuiz->questions->count() }})</h4>
                        <button wire:click="openQuestionCreateModal({{ $editingQuiz->id }})"
                            class="px-3 py-1.5 neo-border-sm neo-radius text-[10px] font-bold bg-primary-container text-on-primary-container hover:bg-on-surface hover:text-white transition-colors">
                            <i class="fas fa-plus ltr:mr-1 rtl:ml-1"></i> {{ __('messages.Add Question') }}
                        </button>
                    </div>

                    @forelse($editingQuiz->questions->sortBy('order') as $question)
                        <div class="p-4 mb-4 neo-border-sm neo-radius bg-surface-container-lowest">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex items-center gap-2">
                                    <span class="text-[10px] font-bold bg-surface-container text-secondary px-2 py-1 neo-border-sm">#{{ $question->order }}</span>
                                    <span class="text-[10px] font-bold bg-primary-container/30 text-on-primary-container px-2 py-1 neo-border-sm">{{ ucfirst($question->type) }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <button wire:click="openQuestionEditModal({{ $question->id }})"
                                        class="flex items-center justify-center w-8 h-8 transition-colors neo-border-sm neo-radius text-primary-container hover:bg-primary-container hover:text-on-primary-container">
                                        <i class="text-xs fas fa-edit"></i>
                                    </button>
                                    <button wire:click="openOptionCreateModal({{ $question->id }})"
                                        class="flex items-center justify-center w-8 h-8 transition-colors neo-border-sm neo-radius text-primary-container hover:bg-primary-container hover:text-on-primary-container" title="Add Option">
                                        <i class="text-xs fas fa-plus"></i>
                                    </button>
                                    <button wire:click="openQuestionDeleteModal({{ $question->id }})"
                                        class="flex items-center justify-center w-8 h-8 transition-colors neo-border-sm neo-radius text-error hover:bg-error hover:text-white">
                                        <i class="text-xs fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                            <p class="mb-3 text-sm font-bold text-on-surface" dir="auto">{{ $question->question }}</p>

                            <div class="space-y-2 ltr:ml-4 rtl:mr-4">
                                @forelse($question->options as $option)
                                    <div class="flex items-center justify-between p-2 bg-surface-container-low neo-border-sm neo-radius">
                                        <div class="flex items-center">
                                            @if($option->is_correct)
                                                <span class="flex items-center justify-center w-5 h-5 text-xs text-white bg-green-600 ltr:mr-2 rtl:ml-2 neo-border-sm">
                                                    <i class="fas fa-check"></i>
                                                </span>
                                            @else
                                                <span class="flex items-center justify-center w-5 h-5 text-xs ltr:mr-2 rtl:ml-2 neo-border-sm"></span>
                                            @endif
                                            <span class="{{ $option->is_correct ? 'font-bold' : 'text-on-surface' }} text-sm" dir="auto">
                                                {{ $option->option_text }}
                                            </span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <button wire:click="openOptionEditModal({{ $option->id }})"
                                                class="flex items-center justify-center w-8 h-8 transition-colors neo-border-sm neo-radius text-primary-container hover:bg-primary-container hover:text-on-primary-container">
                                                <i class="text-xs fas fa-edit"></i>
                                            </button>
                                            <button wire:click="openOptionDeleteModal({{ $option->id }})"
                                                class="flex items-center justify-center w-8 h-8 transition-colors neo-border-sm neo-radius text-error hover:bg-error hover:text-white">
                                                <i class="text-xs fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-xs italic text-secondary">No options yet.</p>
                                @endforelse
                            </div>
                        </div>
                    @empty
                        <div class="py-8 text-center border-dashed neo-border-sm neo-radius">
                            <p class="text-sm text-secondary">{{ __('messages.No questions yet.') }}</p>
                        </div>
                    @endforelse
                </div>
                <div class="px-4 py-3 bg-surface-container-low sm:px-6 sm:flex sm:flex-row-reverse">
                    <button wire:click="closeModals" type="button"
                        class="inline-flex justify-center w-full px-4 py-2 text-xs font-bold tracking-widest uppercase transition-colors neo-border neo-radius bg-surface-container text-on-primary-container hover:bg-on-surface hover:text-white sm:w-auto">
                        {{ __('messages.Close') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif
