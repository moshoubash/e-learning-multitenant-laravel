<div>
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center">
            <a href="{{ route('tenant.student.course', $quiz->section->course->slug ?? 'courses') }}"
                class="@if(app()->getLocale() === 'ar') ml-4 @else mr-4 @endif text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    @if(app()->getLocale() === 'ar')
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7"/>
                    @else
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7"/>
                    @endif
                </svg>
            </a>
            <h1 class="text-2xl font-bold text-gray-900">{{ $quiz->title ?? 'Quiz' }}</h1>
        </div>
        <div class="text-sm text-gray-500">
            {{ __('messages.questions') }}
            {{ $quiz->questions->count() ?? 0 }}
            <span class="@if(app()->getLocale() === 'ar') mr-2 @else ml-2 @endif">
                {{ __('messages.Pass') }}: {{ $quiz->pass_percentage }}%
            </span>
        </div>
    </div>

    <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
        @if($quiz)
            @if(!$this->canTakeQuiz() && !$submitted)
                <!-- Cannot Reattempt Message -->
                <div class="p-8" style="display: flex; flex-direction: column; align-items: center;">
                    <i class="mb-4 text-6xl text-green-500 fas fa-check-circle"></i>
                    <h3 class="text-2xl font-bold text-green-600">{{ __('messages.Quiz Already Passed!') }}</h3>
                    <p class="mt-2 text-gray-600">{{ __('messages.You have already passed this quiz with a score of :score%', ['score' => $previousAttempt->score]) }}</p>
                    <p class="mt-1 text-sm text-gray-500">{{ __('messages.Re-attempt is not allowed for this quiz.') }}</p>
                    <div class="mt-6">
                        <a href="{{ route('tenant.student.course', $quiz->section->course->slug ?? 'courses') }}"
                            class="inline-flex items-center px-6 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                            <i class="@if(app()->getLocale() === 'ar') ml-2 @else mr-2 @endif fas fa-arrow-left"></i>
                            {{ __('messages.Back to Course') }}
                        </a>
                    </div>
                </div>
            @elseif($previousAttempt && !$submitted)
                <!-- Previous Attempt Info -->
                <div class="p-6 border-b border-blue-200 bg-blue-50">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="font-medium text-blue-800">
                                <i class="@if(app()->getLocale() === 'ar') ml-2 @else mr-2 @endif fas fa-info-circle"></i>
                                {{ __('messages.Previous Attempt') }}
                            </h3>
                            <p class="mt-1 text-sm text-blue-600">
                                {{ __('messages.Your last score was :score%', ['score' => $previousAttempt->score]) }}
                                @if($previousAttempt->passed)
                                    <span class="text-green-600">({{ __('messages.Passed') }})</span>
                                @else
                                    <span class="text-red-600">({{ __('messages.Not Passed') }})</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            @if($submitted)
                <!-- Results -->
                <div class="p-8 text-center">
                    <div class="mb-6">
                        @if($passed)
                            <i class="mb-4 text-6xl text-yellow-500 fas fa-trophy"></i>
                            <h3 class="text-2xl font-bold text-green-600">{{ __('messages.Congratulations!') }}</h3>
                            <p class="mt-2 text-gray-600">{{ __('messages.You passed the quiz!') }}</p>
                        @else
                            <i class="mb-4 text-6xl text-blue-500 fas fa-redo"></i>
                            <h3 class="text-2xl font-bold text-orange-600">{{ __('messages.Almost There!') }}</h3>
                            <p class="mt-2 text-gray-600">{{ __('messages.You need :percent% to pass', ['percent' => $quiz->pass_percentage]) }}</p>
                        @endif
                    </div>

                    <div class="inline-block px-8 py-4 mb-6 bg-gray-100 rounded-lg">
                        <div class="text-4xl font-bold {{ $passed ? 'text-green-600' : 'text-orange-600' }}">
                            {{ $score }}%
                        </div>
                        <div class="mt-1 text-sm text-gray-500">
                            {{ __('messages.Score') }}: {{ $score }}%
                        </div>
                    </div>

                    <div class="flex justify-center @if(app()->getLocale() === 'ar') gap-4 @endif space-x-4">
                        @if($this->canReattempt() && $previousAttempt)
                            <button wire:click="resetQuiz" class="px-6 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                                <i class="@if(app()->getLocale() === 'ar') ml-2 @else mr-2 @endif fas fa-redo"></i>
                                {{ __('messages.Try Again') }}
                            </button>
                        @endif

                        <a href="{{ route('tenant.student.course', $quiz->section->course->slug ?? 'courses') }}"
                            class="px-6 py-2 text-white bg-gray-600 rounded-lg hover:bg-gray-700">
                            <i class="@if(app()->getLocale() === 'ar') ml-2 @else mr-2 @endif fas fa-arrow-left"></i>
                            {{ __('messages.Back to Course') }}
                        </a>
                    </div>
                </div>
            @else
                <!-- Quiz Questions -->
                @if(!$this->canReattempt() && $previousAttempt)
                    <div class="p-4 mx-6 my-4 text-sm text-yellow-700 bg-yellow-100 border border-yellow-300 rounded-lg">
                        <i class="@if(app()->getLocale() === 'ar') ml-2 @else mr-2 @endif fas fa-exclamation-triangle"></i>
                        {{ __('messages.You have a previous attempt with a score of :score%. You can retake the quiz to try for a better score.', ['score' => $previousAttempt->score]) }}
                    </div>
                @else
                    <div class="p-6">
                        <div class="pb-4 mb-6 border-b">
                            <h3 class="text-lg font-semibold text-gray-800">{{ $quiz->title }}</h3>
                            <p class="text-sm text-gray-500">{{ __('messages.questions') }} {{ $quiz->questions->count() }} • {{ __('messages.Pass') }}:
                                {{ $quiz->pass_percentage }}%
                            </p>
                        </div>

                        @foreach($quiz->questions->sortBy('order') as $questionIndex => $question)
                            <div class="p-4 mb-8 rounded-lg bg-gray-50">
                                <div class="flex items-start mb-4">
                                    <span
                                        class="inline-flex items-center justify-center w-8 h-8 @if(app()->getLocale() === 'ar') ml-3 @else mr-3 @endif text-sm font-medium text-white bg-blue-600 rounded-full">
                                        {{ $questionIndex + 1 }}
                                    </span>
                                    <h4 class="flex-1 font-medium text-gray-800">{{ $question->question }}</h4>
                                </div>

                                <div class="space-y-2 ml-11">
                                    @if($question->type === 'multiple')
                                        @foreach($question->options as $option)
                                            <label
                                                class="flex items-center p-3 transition-colors bg-white border border-gray-200 rounded-lg cursor-pointer hover:bg-blue-50 {{ $this->isOptionSelected($question->id, $option->id) ? 'bg-blue-50 border-blue-300' : '' }}">
                                                <input type="checkbox" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                                                    value="{{ $option->id }}"
                                                    wire:click.stop="selectOption({{ $question->id }}, {{ $option->id }}, true)"
                                                    @if($this->isOptionSelected($question->id, $option->id)) checked @endif>
                                                <span class="@if(app()->getLocale() === 'ar') mr-3 @else ml-3 @endif text-gray-700">{{ $option->option_text }}</span>
                                            </label>
                                        @endforeach
                                    @else
                                        @foreach($question->options as $option)
                                            <label
                                                class="flex items-center p-3 rounded-lg cursor-pointer {{ $this->isOptionSelected($question->id, $option->id) ? 'bg-blue-100 border-blue-400' : 'bg-white border-gray-200' }} border hover:bg-blue-50 transition-colors"
                                                wire:click="selectOption({{ $question->id }}, {{ $option->id }})">
                                                <input type="radio" class="w-4 h-4 text-blue-600" name="question_{{ $question->id }}"
                                                    @if($this->isOptionSelected($question->id, $option->id)) checked @endif
                                                    wire:click="selectOption({{ $question->id }}, {{ $option->id }})">
                                                <span class="@if(app()->getLocale() === 'ar') mr-3 @else ml-3 @endif text-gray-700">{{ $option->option_text }}</span>
                                            </label>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        @endforeach

                        <div class="pt-4 mt-6 border-t">
                            <button wire:click="submitQuiz"
                                class="w-full py-3 font-medium text-white transition-colors bg-green-600 rounded-lg hover:bg-green-700">
                                <i class="@if(app()->getLocale() === 'ar') mr-3 @else ml-3 @endif fas fa-check"></i>
                                {{ __('messages.Submit Quiz') }}
                            </button>
                        </div>
                    </div>
                @endif
            @endif
        @else
            <div class="p-12 text-center">
                <i class="mb-4 text-6xl text-gray-300 fas fa-exclamation-triangle"></i>
                <h3 class="text-lg font-medium text-gray-700">{{ __('messages.Quiz not found') }}</h3>
                <p class="mt-2 text-gray-500">{{ __('messages.The quiz you\'re looking for doesn\'t exist.') }}</p>
            </div>
        @endif
    </div>
</div>
