<div>
    <header class="h-16 flex justify-between items-center px-[24px] bg-surface-container-lowest border-b-2 border-on-surface sticky top-0 z-40">
        <div class="flex items-center gap-4">
            <h2 class="text-[24px] font-bold text-on-surface leading-none">{{ $quiz->title ?? 'Quiz' }}</h2>
            <span class="px-3 py-1 neo-border-sm neo-radius text-[10px] font-bold uppercase tracking-widest bg-surface-container-high text-on-surface">
                {{ __('messages.questions') }} {{ $quiz->questions->count() ?? 0 }}
            </span>
            <span class="text-[10px] font-bold uppercase tracking-widest text-on-surface">
                {{ __('messages.Pass') }}: {{ $quiz->pass_percentage }}%
            </span>
        </div>
        <div class="flex items-center gap-2">
            @livewire('shared.notification-bell')
        </div>
    </header>

    <div class="p-[24px] max-w-[1400px] mx-auto">
        <div class="overflow-hidden bg-surface-container-lowest neo-border neo-radius">
        @if($quiz)
            @if(!$this->canTakeQuiz() && !$submitted)
                <div class="p-12 text-center">
                    <i class="mb-4 text-5xl text-primary-container fas {{ $this->attemptsExhausted() ? 'fa-times-circle' : 'fa-check-circle' }}"></i>
                    <h3 class="text-sm font-bold tracking-widest uppercase text-on-surface">
                        {{ $this->attemptsExhausted() ? __('messages.Maximum Attempts Reached') : __('messages.Quiz Already Passed!') }}
                    </h3>
                    <p class="mt-2 text-sm text-secondary">
                        {{ $this->attemptsExhausted()
                            ? __('messages.You have used all :max allowed attempts for this quiz.', ['max' => $quiz->max_attempts ?? 1])
                            : __('messages.You have already passed this quiz with a score of :score%', ['score' => $previousAttempt->score]) }}
                    </p>
                    @if($this->highestScore())
                        <p class="mt-1 text-sm font-bold text-on-surface">{{ __('messages.Highest Score') }}: {{ $this->highestScore() }}%</p>
                    @endif
                    <p class="mt-1 text-xs text-secondary">
                        {{ $this->attemptsExhausted()
                            ? __('messages.No more attempts allowed.')
                            : __('messages.Re-attempt is not allowed for this quiz.') }}
                    </p>
                    <a href="{{ route('tenant.student.course', $quiz->section->course->slug ?? 'courses') }}"
                        class="inline-flex items-center px-5 py-2 mt-6 text-xs font-bold tracking-widest uppercase transition-colors neo-border neo-radius bg-primary-container text-on-primary-container hover:bg-on-surface hover:text-white">
                        <i class="fas fa-arrow-left ltr:mr-2 rtl:ml-2"></i>
                        {{ __('messages.Back to Course') }}
                    </a>
                </div>
            @else
                @if($previousAttempt && !$submitted)
                    <div class="p-4 neo-border-sm neo-radius bg-primary-container/20 m-[24px]">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-xs font-bold tracking-widest uppercase text-on-surface">
                                    <i class="fas fa-info-circle ltr:mr-2 rtl:ml-2"></i>
                                    {{ __('messages.Previous Attempt') }}
                                </h3>
                                <p class="mt-1 text-sm text-on-surface">
                                    {{ __('messages.Your last score was :score%', ['score' => $previousAttempt->score]) }}
                                    @if($previousAttempt->passed)
                                        <span class="px-2 py-0.5 neo-border-sm neo-radius text-[10px] font-bold bg-primary-container text-on-primary-container ltr:ml-2 rtl:mr-2">{{ __('messages.Passed') }}</span>
                                    @else
                                        <span class="px-2 py-0.5 neo-border-sm neo-radius text-[10px] font-bold bg-error/20 text-error ltr:ml-2 rtl:mr-2">{{ __('messages.Not Passed') }}</span>
                                    @endif
                                </p>
                                <p class="mt-1 text-xs text-on-surface">
                                    <span class="font-bold">{{ __('messages.Highest Score') }}:</span> {{ $this->highestScore() }}%
                                </p>
                                <p class="mt-1 text-xs text-secondary">
                                    {{ __('messages.Attempt :count of :max', ['count' => $this->attemptCount(), 'max' => $quiz->max_attempts ?? 1]) }}
                                </p>
                            </div>
                        </div>
                    </div>

                    @if($this->canReattempt())
                        <div class="p-4 neo-border-sm neo-radius bg-surface-container-low m-[24px]">
                            <p class="text-xs text-secondary">
                                <i class="fas fa-exclamation-triangle text-primary-container ltr:mr-2 rtl:ml-2"></i>
                                {{ __('messages.You have a previous attempt with a score of :score%. You can retake the quiz to try for a better score.', ['score' => $previousAttempt->score]) }}
                            </p>
                        </div>
                    @endif
                @endif

                @if($submitted)
                    <div class="p-12 text-center">
                        @if($passed)
                            <i class="mb-4 text-5xl text-primary-container fas fa-trophy"></i>
                            <h3 class="text-sm font-bold tracking-widest uppercase text-on-surface">{{ __('messages.Congratulations!') }}</h3>
                            <p class="mt-2 text-sm text-secondary">{{ __('messages.You passed the quiz!') }}</p>
                        @else
                            <i class="mb-4 text-5xl text-primary-container fas fa-redo"></i>
                            <h3 class="text-sm font-bold tracking-widest uppercase text-on-surface">{{ __('messages.Almost There!') }}</h3>
                            <p class="mt-2 text-sm text-secondary">{{ __('messages.You need :percent% to pass', ['percent' => $quiz->pass_percentage]) }}</p>
                        @endif

                        <div class="inline-block px-8 py-4 my-6 neo-border neo-radius bg-surface-container-high">
                            <div class="text-4xl font-bold {{ $passed ? 'text-on-surface' : 'text-error' }}">
                                {{ $score }}%
                            </div>
                            <div class="mt-1 text-xs font-bold tracking-widest uppercase text-secondary">
                                {{ __('messages.Score') }}
                            </div>
                        </div>

                        @if($this->highestScore())
                            <div class="mb-6 text-sm text-on-surface">
                                <span class="font-bold">{{ __('messages.Highest Score') }}:</span> {{ $this->highestScore() }}%
                            </div>
                        @endif

                        <div class="flex justify-center gap-4">
                            @if($this->canReattempt() && $previousAttempt)
                                <button wire:click="resetQuiz"
                                    class="px-5 py-2 text-xs font-bold tracking-widest uppercase transition-colors neo-border neo-radius bg-primary-container text-on-primary-container hover:bg-on-surface hover:text-white">
                                    <i class="fas fa-redo ltr:mr-2 rtl:ml-2"></i>
                                    {{ __('messages.Try Again') }}
                                </button>
                            @endif
                            <a href="{{ route('tenant.student.course', $quiz->section->course->slug ?? 'courses') }}"
                                class="px-5 py-2 text-xs font-bold tracking-widest uppercase transition-colors neo-border neo-radius bg-surface-container text-on-surface hover:bg-on-surface hover:text-white">
                                <i class="fas fa-arrow-left ltr:mr-2 rtl:ml-2"></i>
                                {{ __('messages.Back to Course') }}
                            </a>
                        </div>
                    </div>
                @else
                    <div class="p-[24px]">
                        <div class="pb-4 mb-6 border-b-2 border-on-surface">
                            <h3 class="text-sm font-bold tracking-widest uppercase text-on-surface">{{ $quiz->title }}</h3>
                            <p class="mt-1 text-xs text-secondary">{{ __('messages.questions') }} {{ $quiz->questions->count() }} | {{ __('messages.Pass') }}: {{ $quiz->pass_percentage }}% | {{ __('messages.Attempt') }} {{ $this->attemptCount() + 1 }} {{ __('messages.of') }} {{ $quiz->max_attempts ?? 1 }}</p>
                        </div>

                        @foreach($quiz->questions->sortBy('order') as $questionIndex => $question)
                            <div class="p-4 mb-6 neo-border-sm neo-radius bg-surface-container-low">
                                <div class="flex items-start mb-4">
                                    <span class="inline-flex items-center justify-center w-8 h-8 text-xs font-bold neo-border-sm neo-radius bg-primary-container text-on-primary-container ltr:mr-3 rtl:ml-3 shrink-0">
                                        {{ $questionIndex + 1 }}
                                    </span>
                                    <h4 class="flex-1 text-sm font-bold text-on-surface" dir="auto">{{ $question->question }}</h4>
                                </div>

                                <div class="space-y-2 ltr:ml-11 rtl:mr-11">
                                    @if($question->type === 'multiple')
                                        @foreach($question->options as $option)
                                            <label class="flex items-center p-3 neo-border-sm neo-radius cursor-pointer hover:bg-surface-container-high transition-colors
                                                {{ $this->isOptionSelected($question->id, $option->id) ? 'bg-primary-container border-primary-container' : 'bg-surface-container-lowest' }}">
                                                <div class="relative flex items-center justify-center w-4 h-4 neo-border-sm neo-radius bg-surface-container-low ltr:mr-3 rtl:ml-3
                                                    {{ $this->isOptionSelected($question->id, $option->id) ? 'bg-on-surface' : '' }}">
                                                    @if($this->isOptionSelected($question->id, $option->id))
                                                        <i class="fas fa-check text-[10px] text-white"></i>
                                                    @endif
                                                </div>
                                                <input type="checkbox" class="sr-only"
                                                    value="{{ $option->id }}"
                                                    wire:click.stop="selectOption({{ $question->id }}, {{ $option->id }}, true)"
                                                    @if($this->isOptionSelected($question->id, $option->id)) checked @endif>
                                                <span class="text-sm text-on-surface" dir="auto">{{ $option->option_text }}</span>
                                            </label>
                                        @endforeach
                                    @else
                                        @foreach($question->options as $option)
                                            <label class="flex items-center p-3 neo-border-sm neo-radius cursor-pointer hover:bg-surface-container-high transition-colors
                                                {{ $this->isOptionSelected($question->id, $option->id) ? 'bg-primary-container border-primary-container' : 'bg-surface-container-lowest' }}"
                                                wire:click="selectOption({{ $question->id }}, {{ $option->id }})">
                                                <div class="relative flex items-center justify-center w-4 h-4 neo-border-sm neo-radius bg-surface-container-low ltr:mr-3 rtl:ml-3
                                                    {{ $this->isOptionSelected($question->id, $option->id) ? 'bg-on-surface' : '' }}">
                                                    @if($this->isOptionSelected($question->id, $option->id))
                                                        <div class="w-2 h-2 bg-white neo-radius"></div>
                                                    @endif
                                                </div>
                                                <input type="radio" class="sr-only" name="question_{{ $question->id }}"
                                                    @if($this->isOptionSelected($question->id, $option->id)) checked @endif
                                                    wire:click="selectOption({{ $question->id }}, {{ $option->id }})">
                                                <span class="text-sm text-on-surface" dir="auto">{{ $option->option_text }}</span>
                                            </label>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        @endforeach

                        <div class="pt-4 mt-6 border-t-2 border-on-surface">
                            <button wire:click="submitQuiz"
                                class="w-full py-3 text-xs font-bold tracking-widest uppercase transition-colors neo-border neo-radius bg-primary-container text-on-primary-container hover:bg-on-surface hover:text-white">
                                <i class="fas fa-check ltr:mr-2 rtl:ml-2"></i>
                                {{ __('messages.Submit Quiz') }}
                            </button>
                        </div>
                    </div>
                @endif
            @endif
        @else
            <div class="p-12 text-center">
                <i class="mb-4 text-5xl text-secondary fas fa-exclamation-triangle"></i>
                <h3 class="text-sm font-bold tracking-widest uppercase text-on-surface">{{ __('messages.Quiz not found') }}</h3>
                <p class="mt-2 text-sm text-secondary">{{ __('messages.The quiz you\'re looking for doesn\'t exist.') }}</p>
            </div>
        @endif
    </div>
    </div>
</div>
