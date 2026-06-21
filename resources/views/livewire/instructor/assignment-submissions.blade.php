<div>
    <header class="h-16 flex justify-between items-center px-[24px] bg-surface-container-lowest border-b-2 border-on-surface sticky top-0 z-40">
        <div>
            <h2 class="text-[24px] font-bold text-on-surface leading-none tracking-[0.08em]">{{ __('messages.Assignment Submissions') }}</h2>
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

        {{-- Submissions Table --}}
        <div class="overflow-hidden bg-surface-container-lowest neo-border neo-radius">
            <div class="overflow-x-auto">
                <table class="w-full text-left rtl:text-right">
                    <thead class="border-b-2 bg-surface-container-low border-on-surface ">
                        <tr>
                            <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-secondary">{{ __('messages.Student') }}</th>
                            <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-secondary">{{ __('messages.Assignment') }}</th>
                            <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-secondary">{{ __('messages.Submitted') }}</th>
                            <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-secondary">{{ __('messages.Status') }}</th>
                            <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-secondary">{{ __('messages.Score') }}</th>
                            <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-secondary">{{ __('messages.Graded By') }}</th>
                            <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-secondary">{{ __('messages.Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#E5E5E5]">
                        @forelse($this->submissions as $submission)
                            <tr class="transition-colors hover:bg-surface-container-low">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="flex items-center justify-center w-10 h-10 neo-border-sm neo-radius bg-primary-container shrink-0">
                                            <span class="text-sm font-bold text-on-primary-container">{{ substr($submission->student->name ?? 'N/A', 0, 1) }}</span>
                                        </div>
                                        <div class="ltr:ml-4 rtl:mr-4">
                                            <div class="text-sm font-bold text-on-surface">{{ $submission->student->name ?? 'Unknown' }}</div>
                                            <div class="text-xs text-secondary">{{ $submission->student->email ?? '' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-bold text-on-surface">{{ $submission->assignment->title ?? 'N/A' }}</div>
                                    <div class="text-xs text-secondary">{{ __('messages.Max Score') }}: {{ $submission->assignment->max_score ?? 100 }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-on-surface">{{ $submission->submitted_at ? $submission->submitted_at->format('M d, Y H:i') : 'N/A' }}</div>
                                    @if($submission->submitted_at && $submission->assignment->due_date && $submission->submitted_at->gt($submission->assignment->due_date))
                                        <div class="mt-1 text-xs font-bold text-error">
                                            <i class="fas fa-exclamation-triangle"></i>
                                            {{ __('messages.Late Submission') }}
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($submission->graded_at)
                                        <span class="inline-flex px-2 py-1 neo-border-sm neo-radius text-[10px] font-bold bg-primary-container text-on-primary-container">
                                            {{ __('messages.Graded') }}
                                        </span>
                                    @else
                                        <span class="inline-flex px-2 py-1 neo-border-sm neo-radius text-[10px] font-bold bg-surface-container-high text-on-surface">
                                            {{ __('messages.Pending') }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($submission->score !== null)
                                        <span class="text-sm font-bold text-on-surface">{{ $submission->score }}/{{ $submission->assignment->max_score ?? 100 }}</span>
                                    @else
                                        <span class="text-sm text-secondary">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-on-surface">{{ $submission->gradedBy->name ?? '-' }}</div>
                                    @if($submission->graded_at)
                                        <div class="text-xs text-secondary">{{ $submission->graded_at->format('M d, Y') }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <button wire:click="openGradingModal({{ $submission->id }})"
                                        class="px-3 py-1 neo-border-sm neo-radius text-[10px] font-bold uppercase tracking-widest bg-primary-container text-on-primary-container hover:bg-on-surface hover:text-white transition-colors">
                                        <i class="fas fa-edit ltr:mr-1 rtl:ml-1"></i>
                                        {{ $submission->graded_at ? __('messages.View/Edit Grade') : __('messages.Grade') }}
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12">
                                    <div class="text-center text-secondary">
                                        <i class="text-4xl fas fa-inbox"></i>
                                        <p class="mt-2 text-sm font-bold">{{ __('messages.No submissions found') }}</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($this->submissions->hasPages())
                <div class="px-6 py-4 border-t-2 border-on-surface">
                    {{ $this->submissions->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- Grading Modal --}}
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
</div>
