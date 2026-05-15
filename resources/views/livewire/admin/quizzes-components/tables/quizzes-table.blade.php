<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
    <div class="flex items-center justify-between">
        <div class="p-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-700">{{ __('Active Quizzes') }}</h3>
        </div>
        <button wire:click="openQuizCreateModal()"
            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
            <i class="fas fa-plus mr-2"></i>
            {{ __('Add Quiz') }}
        </button>
    </div>

    <table class="w-full text-left">
        <thead class="bg-gray-50">
            <tr>
                <th class="p-4 text-sm font-semibold text-gray-600 w-8"></th>
                <th class="p-4 text-sm font-semibold text-gray-600">{{ __("Title") }}</th>
                <th class="p-4 text-sm font-semibold text-gray-600">{{ __("Lesson") }}</th>
                <th class="p-4 text-sm font-semibold text-gray-600">{{ __("Pass %") }}</th>
                <th class="p-4 text-sm font-semibold text-gray-600">{{ __("Questions") }}</th>
                <th class="p-4 text-sm font-semibold text-gray-600">{{ __("Actions") }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($quizzes as $quiz)
                <tr class="border-t border-gray-200">
                    <td class="p-2 text-center">
                        <button wire:click="toggleQuizExpand({{ $quiz->id }})" class="text-gray-500 hover:text-gray-700">
                            <i
                                class="fas {{ $quiz->questions && count($quiz->questions) > 0 ? ($expandedQuizzes && in_array($quiz->id, $expandedQuizzes) ? 'fa-chevron-down' : 'fa-chevron-right') : 'fa-minus' }}"></i>
                        </button>
                    </td>
                    <td class="p-4">
                        <div class="flex items-center">
                            <span class="font-medium">{{ $quiz->title }}</span>
                            @if($quiz->questions && count($quiz->questions) > 0)
                                <span class="ml-2 text-xs text-gray-500">({{ count($quiz->questions) }}
                                    questions)</span>
                            @endif
                        </div>
                    </td>
                    <td class="p-4">
                        @if($quiz->lesson)
                            <span class="text-sm">{{ $quiz->lesson->title }}</span>
                        @else
                            <span class="text-gray-400">N/A</span>
                        @endif
                    </td>
                    <td class="p-4">{{ $quiz->pass_percentage }}%</td>
                    <td class="p-4">{{ $quiz->questions ? count($quiz->questions) : 0 }}</td>
                    <td class="p-4">
                        <div class="flex items-center">
                            <button wire:click="openQuizEditModal({{ $quiz->id }})"
                                class="text-blue-600 hover:text-blue-800 mr-3" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button wire:click="openQuizDeleteModal({{ $quiz->id }})"
                                class="text-red-600 hover:text-red-800" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                            <button wire:click="openQuestionCreateModal({{ $quiz->id }})"
                                class="text-green-600 hover:text-green-800 ml-3" title="Add Question">
                                <i class="fas fa-plus-circle"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @if($expandedQuizzes && in_array($quiz->id, $expandedQuizzes) && $quiz->questions && count($quiz->questions) > 0)
                    <tr>
                        <td colspan="6" class="p-0 bg-gray-50">
                            <div class="p-4">
                                <div class="flex justify-between items-center mb-3">
                                    <h4 class="text-sm font-semibold text-gray-600">{{ __('Questions & Options') }}</h4>
                                </div>
                                <div class="space-y-3">
                                    @foreach($quiz->questions->sortBy('order') as $question)
                                        @include('livewire.admin.quizzes-components.tables.partials.question-row', ['question' => $question, 'quizId' => $quiz->id])
                                    @endforeach
                                </div>
                            </div>
                        </td>
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>
    <div class="p-4">
        {{ $quizzes->links() }}
    </div>
</div>