<x-slot name="header">
    <div class="flex justify-between items-center">
        <div class="flex items-center">
            <a href="{{ route('tenant.student.course', $quiz->section->course->slug ?? 'courses') }}"
                class="text-gray-500 hover:text-gray-700 mr-4">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $quiz->title ?? 'Quiz' }}
            </h2>
        </div>
        <div class="text-sm text-gray-500">
            {{ $quiz->questions->count() ?? 0 }} questions
            <span class="ml-2">Pass: {{ $quiz->pass_percentage }}%</span>
        </div>
    </div>
</x-slot>

<div class="max-w-4xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        @if($quiz)
            @if($previousAttempt && !$submitted)
                <!-- Previous Attempt Info -->
                <div class="p-6 bg-blue-50 border-b border-blue-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-blue-800 font-medium">
                                <i class="fas fa-info-circle mr-2"></i>
                                Previous Attempt
                            </h3>
                            <p class="text-blue-600 text-sm mt-1">
                                Your last score: {{ $previousAttempt->score }}%
                                @if($previousAttempt->passed)
                                    <span class="text-green-600">(Passed)</span>
                                @else
                                    <span class="text-red-600">(Not passed)</span>
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
                            <i class="fas fa-trophy text-6xl text-yellow-500 mb-4"></i>
                            <h3 class="text-2xl font-bold text-green-600">Congratulations!</h3>
                            <p class="text-gray-600 mt-2">You passed the quiz!</p>
                        @else
                            <i class="fas fa-redo text-6xl text-blue-500 mb-4"></i>
                            <h3 class="text-2xl font-bold text-orange-600">Almost There!</h3>
                            <p class="text-gray-600 mt-2">You need {{ $quiz->pass_percentage }}% to pass.</p>
                        @endif
                    </div>

                    <div class="inline-block px-8 py-4 bg-gray-100 rounded-lg mb-6">
                        <div class="text-4xl font-bold {{ $passed ? 'text-green-600' : 'text-orange-600' }}">
                            {{ $score }}%
                        </div>
                        <div class="text-sm text-gray-500 mt-1">
                            Score: {{ $score }}%
                        </div>
                    </div>

                    <div class="flex justify-center space-x-4">
                        <button wire:click="resetQuiz" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">
                            <i class="fas fa-redo mr-2"></i>
                            Try Again
                        </button>
                        <a href="{{ route('tenant.student.course', $quiz->section->course->slug ?? 'courses') }}"
                            class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Back to Course
                        </a>
                    </div>
                </div>
            @else
                <!-- Quiz Questions -->
                <div class="p-6">
                    <div class="mb-6 pb-4 border-b">
                        <h3 class="text-lg font-semibold text-gray-800">{{ $quiz->title }}</h3>
                        <p class="text-sm text-gray-500">{{ $quiz->questions->count() }} questions • Pass:
                            {{ $quiz->pass_percentage }}%
                        </p>
                    </div>

                    @foreach($quiz->questions->sortBy('order') as $questionIndex => $question)
                        <div class="mb-8 p-4 bg-gray-50 rounded-lg">
                            <div class="flex items-start mb-4">
                                <span
                                    class="inline-flex items-center justify-center w-8 h-8 bg-blue-600 text-white rounded-full text-sm font-medium mr-3">
                                    {{ $questionIndex + 1 }}
                                </span>
                                <h4 class="text-gray-800 font-medium flex-1">{{ $question->question_text }}</h4>
                            </div>

                            <div class="space-y-2 ml-11">
                                @foreach($question->options as $option)
                                    <label
                                        class="flex items-center p-3 rounded-lg cursor-pointer {{ $this->isOptionSelected($question->id, $option->id) ? 'bg-blue-100 border-blue-400' : 'bg-white border-gray-200' }} border hover:bg-blue-50 transition-colors"
                                        wire:click="selectOption({{ $question->id }}, {{ $option->id }})">
                                        <input type="radio" class="w-4 h-4 text-blue-600" name="question_{{ $question->id }}"
                                            @if($this->isOptionSelected($question->id, $option->id)) checked @endif
                                            wire:click="selectOption({{ $question->id }}, {{ $option->id }})">
                                        <span class="ml-3 text-gray-700">{{ $option->option_text }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach

                    <div class="mt-6 pt-4 border-t">
                        <button wire:click="submitQuiz"
                            class="w-full bg-green-600 hover:bg-green-700 text-white py-3 rounded-lg font-medium transition-colors">
                            <i class="fas fa-check mr-2"></i>
                            Submit Quiz
                        </button>
                    </div>
                </div>
            @endif
        @else
            <div class="p-12 text-center">
                <i class="fas fa-exclamation-triangle text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-700">Quiz not found</h3>
                <p class="text-gray-500 mt-2">The quiz you're looking for doesn't exist.</p>
            </div>
        @endif
    </div>
</div>