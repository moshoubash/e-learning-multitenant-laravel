<div class="p-6 mb-6 overflow-hidden bg-white shadow-sm sm:rounded-lg">
    <div class="flex items-center justify-between">
        <div class="p-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-700">{{ __('messages.Active Quizzes') }}</h3>
        </div>
        <button wire:click="openQuizCreateModal()"
            class="px-4 py-2 text-sm font-medium text-white transition-colors bg-blue-600 rounded-lg hover:bg-blue-700">
            <i class="@rim('mr-2') fas fa-plus"></i>
            {{ __('messages.Add Quiz') }}
        </button>
    </div>

    <table class="w-full mx-0 mt-4 text-left">
        <thead class="bg-gray-50">
            <tr>
                <th class="w-8 p-4 text-sm font-semibold text-gray-600"></th>
                <th class="p-4 text-sm font-semibold text-gray-600">{{ __("messages.Title") }}</th>
                <th class="p-4 text-sm font-semibold text-gray-600">{{ __("messages.Section") }}</th>
                <th class="p-4 text-sm font-semibold text-gray-600">{{ __("messages.Pass %") }}</th>
                <th class="p-4 text-sm font-semibold text-gray-600">{{ __("messages.Questions") }}</th>
                <th class="p-4 text-sm font-semibold text-gray-600">{{ __("messages.Actions") }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($quizzes as $quiz)
                <tr class="border-t border-gray-200">
                    <td class="p-2 text-center">
                        <button wire:click="toggleQuizExpand({{ $quiz->id }})" class="text-gray-500 hover:text-gray-700">
                            @if(app()->getLocale() == 'ar')
                                <i class="fas {{ $quiz->questions && count($quiz->questions) > 0 ? ($expandedQuizzes && in_array($quiz->id, $expandedQuizzes) ? 'fa-chevron-down' : 'fa-chevron-left') : 'fa-minus' }}"></i>
                            @else
                                <i class="fas {{ $quiz->questions && count($quiz->questions) > 0 ? ($expandedQuizzes && in_array($quiz->id, $expandedQuizzes) ? 'fa-chevron-down' : 'fa-chevron-right') : 'fa-minus' }}"></i>
                            @endif
                        </button>
                    </td>
                    <td class="p-4">
                        <div class="flex items-center">
                            <span class="font-medium">{{ $quiz->title }}</span>
                            @if($quiz->questions && count($quiz->questions) > 0)
                                <span class="@if(app()->getLocale() == 'ar') mr-2 @else ml-2 @endif text-xs text-gray-500">
                                    ({{ __('messages.questions') }}
                                    {{ count($quiz->questions) }})
                                </span>
                            @endif
                        </div>
                    </td>
                    <td class="p-4">
                        @if($quiz->section)
                            <span class="text-sm">{{ $quiz->section->title }} -
                                {{ $quiz->section->course->title ?? 'No Course' }}</span>
                        @else
                            <span class="text-gray-400">N/A</span>
                        @endif
                    </td>
                    <td class="p-4">{{ $quiz->pass_percentage }}%</td>
                    <td class="p-4">{{ $quiz->questions ? count($quiz->questions) : 0 }}</td>
                    <td class="p-4">
                        <div class="flex items-center">
                            <button wire:click="openAttemptsModal({{ $quiz->id }})"
                                class="@rim('mr-2') text-purple-600 hover:text-purple-800" title="View Attempts">
                                <i class="fas fa-users"></i>
                            </button>
                            <button wire:click="openQuestionCreateModal({{ $quiz->id }})"
                                class="@rim('mr-2') text-green-600 hover:text-green-800" title="Add Question">
                                <i class="fas fa-plus-circle"></i>
                            </button>
                            <button wire:click="openQuizEditModal({{ $quiz->id }})"
                                class="@rim('mr-2') text-blue-600 hover:text-blue-800" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button wire:click="openQuizDeleteModal({{ $quiz->id }})"
                                class="text-red-600 hover:text-red-800" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @if($expandedQuizzes && in_array($quiz->id, $expandedQuizzes) && $quiz->questions && count($quiz->questions) > 0)
                    <tr>
                        <td colspan="6" class="p-0 bg-gray-50">
                            <div class="p-4">
                                <div class="flex items-center justify-between mb-3">
                                    <h4 class="text-sm font-semibold text-gray-600">{{ __('messages.Questions & Options') }}</h4>
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
                <td colspan="6" class="p-4 text-center text-gray-500">{{ __('messages.No quizzes found.') }}</td>
            @endif
        </tbody>
    </table>
    <div class="p-4">
        {{ $quizzes->links() }}
    </div>
</div>
