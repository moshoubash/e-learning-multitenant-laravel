<div class="overflow-hidden bg-surface-container-lowest neo-border neo-radius">
    <div class="p-[24px] border-b-2 border-on-surface flex items-center justify-between">
        <h3 class="text-[18px] font-bold uppercase tracking-widest text-on-surface leading-none">{{ __('messages.Active Quizzes') }}</h3>
        <button wire:click="openQuizCreateModal()"
            class="px-4 py-2 text-xs font-bold tracking-widest uppercase transition-colors neo-border neo-radius bg-primary-container text-on-primary-container hover:bg-on-surface hover:text-white">
            <i class="fas fa-plus ltr:mr-2 rtl:ml-2"></i>
            {{ __('messages.Add Quiz') }}
        </button>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="border-b-2 bg-surface-container-low border-on-surface rtl:text-right">
                <tr>
                    <th class="w-8 p-4 text-[10px] font-bold uppercase tracking-widest text-secondary"></th>
                    <th class="p-4 text-[10px] font-bold uppercase tracking-widest text-secondary">{{ __('messages.Title') }}</th>
                    <th class="p-4 text-[10px] font-bold uppercase tracking-widest text-secondary">{{ __('messages.Section') }}</th>
                    <th class="p-4 text-[10px] font-bold uppercase tracking-widest text-secondary">{{ __('messages.Pass %') }}</th>
                    <th class="p-4 text-[10px] font-bold uppercase tracking-widest text-secondary">{{ __('messages.Re-attempts') }}</th>
                    <th class="p-4 text-[10px] font-bold uppercase tracking-widest text-secondary">{{ __('messages.Questions') }}</th>
                    <th class="p-4 text-[10px] font-bold uppercase tracking-widest text-secondary">{{ __('messages.Actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#E5E5E5] rtl:text-right">
                @foreach ($quizzes as $quiz)
                    <tr>
                        <td class="text-center rtl:pr-4 ltr:pl-4">
                            <button wire:click="toggleQuizExpand({{ $quiz->id }})" class="transition-colors text-secondary hover:text-on-primary-container">
                                <i class="fas {{ $quiz->questions && count($quiz->questions) > 0 ? ($expandedQuizzes && in_array($quiz->id, $expandedQuizzes) ? 'fa-chevron-up' : 'fa-chevron-down') : 'fa-minus' }}"></i>
                            </button>
                        </td>
                        <td class="p-4">
                            <div class="flex items-center">
                                <span class="text-sm font-bold text-on-surface">{{ $quiz->title }}</span>
                                @if($quiz->questions && count($quiz->questions) > 0)
                                    <span class="text-xs text-secondary ltr:ml-2 rtl:mr-2">({{ __('messages.questions') }} {{ count($quiz->questions) }})</span>
                                @endif
                            </div>
                        </td>
                        <td class="p-4 text-sm text-on-surface">
                            @if($quiz->section)
                                {{ $quiz->section->title }} - {{ $quiz->section->course->title ?? 'No Course' }}
                            @else
                                <span class="text-secondary">N/A</span>
                            @endif
                        </td>
                        <td class="p-4 text-sm font-bold text-on-surface">{{ $quiz->pass_percentage }}%</td>
                        <td class="p-4 text-sm text-on-surface">
                            @if($quiz->can_reattempt)
                                <span class="font-bold">{{ $quiz->max_attempts ?? 1 }} {{ __('messages.times') }}</span>
                            @else
                                <span class="text-secondary">{{ __('messages.Not allowed') }}</span>
                            @endif
                        </td>
                        <td class="p-4 text-sm font-bold text-on-surface">{{ $quiz->questions ? count($quiz->questions) : 0 }}</td>
                        <td class="p-4">
                            <div class="flex items-center gap-2">
                                <button wire:click="openAttemptsModal({{ $quiz->id }})"
                                    class="flex items-center justify-center w-8 h-8 transition-colors neo-border-sm neo-radius text-on-primary-container hover:bg-primary-container hover:text-on-primary-container" title="View Attempts">
                                    <i class="text-xs fas fa-users"></i>
                                </button>
                                <button wire:click="openQuestionCreateModal({{ $quiz->id }})"
                                    class="flex items-center justify-center w-8 h-8 transition-colors neo-border-sm neo-radius text-on-surface hover:bg-primary-container hover:text-on-primary-container" title="Add Question">
                                    <i class="text-xs fas fa-plus-circle"></i>
                                </button>
                                <button wire:click="openQuizEditModal({{ $quiz->id }})"
                                    class="flex items-center justify-center w-8 h-8 transition-colors neo-border-sm neo-radius text-on-surface hover:bg-primary-container hover:text-on-primary-container" title="Edit">
                                    <i class="text-xs fas fa-edit"></i>
                                </button>
                                <button wire:click="openQuizDeleteModal({{ $quiz->id }})"
                                    class="flex items-center justify-center w-8 h-8 transition-colors neo-border-sm neo-radius text-error hover:bg-error hover:text-white" title="Delete">
                                    <i class="text-xs fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @if($expandedQuizzes && in_array($quiz->id, $expandedQuizzes) && $quiz->questions && count($quiz->questions) > 0)
                        <tr>
                            <td colspan="12" class="p-0 bg-surface-container-low">
                                <div class="p-4">
                                    <div class="flex items-center justify-between mb-3">
                                        <h4 class="text-xs font-bold tracking-widest uppercase text-secondary">{{ __('messages.Questions & Options') }}</h4>
                                    </div>
                                    <div class="space-y-3">
                                        @foreach($quiz->questions->sortBy('order') as $question)
                                            @include('livewire.instructor.quizzes-components.tables.partials.question-row', ['question' => $question, 'quizId' => $quiz->id])
                                        @endforeach
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endif
                @endforeach
                @if(count($quizzes) == 0)
                    <tr><td colspan="12" class="p-8 text-sm text-center text-secondary">{{ __('messages.No quizzes found.') }}</td></tr>
                @endif
            </tbody>
        </table>
    </div>
    <div class="p-4 border-t-2 border-on-surface">
        {{ $quizzes->links() }}
    </div>
</div>
