@if($showGradingModal && $this->gradingSubmission)
    <div class="fixed inset-0 z-50 overflow-y-auto" x-data="{ show: true }" x-show="show">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-on-surface/60" wire:click="closeGradingModal"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div class="inline-block overflow-hidden ltr:text-left rtl:text-right align-bottom transition-all transform bg-surface-container-lowest neo-border neo-radius sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="px-4 pt-5 pb-4 bg-surface-container-lowest sm:p-6 sm:pb-4">
                    <h3 class="mb-4 text-sm font-bold tracking-widest uppercase text-on-surface">{{ __('messages.Grade Submission') }}</h3>

                    <div class="p-4 mb-6 neo-border-sm neo-radius bg-surface-container-low">
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="text-xs font-bold tracking-widest uppercase text-secondary">{{ __('messages.Student') }}:</span>
                                <span class="font-bold text-on-surface ltr:ml-2 rtl:mr-2">{{ $this->gradingSubmission->student->name ?? 'N/A' }}</span>
                            </div>
                            <div>
                                <span class="text-xs font-bold tracking-widest uppercase text-secondary">{{ __('messages.Assignment') }}:</span>
                                <span class="font-bold text-on-surface ltr:ml-2 rtl:mr-2">{{ $this->gradingSubmission->assignment->title ?? 'N/A' }}</span>
                            </div>
                            <div>
                                <span class="text-xs font-bold tracking-widest uppercase text-secondary">{{ __('messages.Submitted') }}:</span>
                                <span class="font-bold text-on-surface ltr:ml-2 rtl:mr-2">{{ $this->gradingSubmission->submitted_at ? $this->gradingSubmission->submitted_at->format('M d, Y H:i') : 'N/A' }}</span>
                            </div>
                            <div>
                                <span class="text-xs font-bold tracking-widest uppercase text-secondary">{{ __('messages.Max Score') }}:</span>
                                <span class="font-bold text-on-surface ltr:ml-2 rtl:mr-2">{{ $this->gradingSubmission->assignment->max_score ?? 100 }}</span>
                            </div>
                        </div>
                        @if($this->gradingSubmission->content)
                            <div class="mt-4">
                                <span class="text-xs font-bold tracking-widest uppercase text-secondary">{{ __('messages.Submission Content') }}:</span>
                                <div class="p-3 mt-1 overflow-y-auto text-sm neo-border-sm neo-radius bg-surface-container-lowest text-on-surface max-h-32">
                                    {{ $this->gradingSubmission->content }}
                                </div>
                            </div>
                        @endif
                        @if($this->gradingSubmission->file_path)
                            <div class="mt-3">
                                <a href="{{ $this->gradingSubmission->file_path }}" target="_blank"
                                    class="inline-flex items-center text-xs font-bold transition-colors text-on-surface hover:text-primary-container">
                                    <i class="fas fa-paperclip ltr:mr-1 rtl:ml-1"></i>
                                    {{ __('messages.View Attached File') }}
                                </a>
                            </div>
                        @endif
                    </div>

                    <form wire:submit="submitGrade">
                        <div class="mb-4">
                            <label class="block mb-2 text-xs font-bold tracking-widest uppercase text-on-surface">
                                {{ __('messages.Score') }} ({{ $gradingSubmission->assignment->max_score ?? 100 }} {{ __('messages.max') }})
                            </label>
                            <input type="number" wire:model.live="gradeScore" min="0"
                                max="{{ $gradingSubmission->assignment->max_score ?? 100 }}"
                                class="w-full px-3 py-2 text-sm neo-border-sm neo-radius bg-surface-container-low text-on-surface focus:outline-none focus:ring-0 placeholder:text-secondary">
                            @error('gradeScore') <span class="block mt-1 text-xs font-bold text-error">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-4">
                            <label class="block mb-2 text-xs font-bold tracking-widest uppercase text-on-surface">{{ __('messages.Feedback') }}</label>
                            <textarea wire:model="gradeFeedback" rows="4"
                                class="w-full px-3 py-2 text-sm neo-border-sm neo-radius bg-surface-container-low text-on-surface focus:outline-none focus:ring-0 placeholder:text-secondary"
                                placeholder="{{ __('messages.Enter feedback for the student...') }}"></textarea>
                        </div>
                    </form>
                </div>
                <div class="px-4 py-3 bg-surface-container-low sm:px-6 sm:flex sm:flex-row-reverse">
                    <button wire:click="submitGrade" type="button"
                        class="inline-flex justify-center w-full px-4 py-2 text-xs font-bold tracking-widest uppercase transition-colors neo-border neo-radius bg-primary-container text-on-primary-container hover:bg-on-surface hover:text-white sm:ltr:ml-3 sm:rtl:mr-3 sm:w-auto">
                        {{ __('messages.Submit Grade') }}
                    </button>
                    <button wire:click="closeGradingModal" type="button"
                        class="inline-flex justify-center w-full px-4 py-2 mt-3 text-xs font-bold transition-colors neo-border-sm neo-radius text-on-surface bg-surface-container hover:bg-on-surface hover:text-white sm:mt-0 sm:ltr:ml-3 sm:rtl:mr-3 sm:w-auto">
                        {{ __('messages.Cancel') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif
