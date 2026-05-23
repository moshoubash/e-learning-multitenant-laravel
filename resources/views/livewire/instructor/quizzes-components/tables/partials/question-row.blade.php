<div class="overflow-hidden bg-white border border-gray-200 rounded-lg">
    <div class="flex items-center justify-between px-4 py-3 bg-gray-100">
        <div class="flex items-center">
            <i class="@if(app()->getLocale() === 'ar') ml-2 @else mr-2 @endif text-purple-500 fas fa-question-circle"></i>
            <span class="font-medium text-gray-700">{{ $question->question }}</span>
            <span class="ml-2 px-2 py-0.5 bg-purple-100 text-purple-700 text-xs rounded">
                {{ str_replace('_', ' ', __('messages.' . $question->type)) }}
            </span>
            <span class="ml-2 text-xs text-gray-500">({{ __('messages.Order') }}: {{ $question->order }})</span>
        </div>
        <div class="flex items-center space-x-2">
            <button wire:click="openOptionCreateModal({{ $question->id }})"
                class="text-sm text-green-600 hover:text-green-800" title="Add Option">
                <i class="fas fa-plus-circle"></i>
            </button>
            <button wire:click="openQuestionEditModal({{ $question->id }})"
                class="text-sm text-blue-600 hover:text-blue-800" title="Edit">
                <i class="fas fa-edit"></i>
            </button>
            <button wire:click="openQuestionDeleteModal({{ $question->id }})"
                class="text-sm text-red-600 hover:text-red-800" title="Delete">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    </div>

    @if($question->options && count($question->options) > 0)
        <div class="divide-y divide-gray-100">
            @foreach($question->options as $option)
                <div class="flex items-center justify-between px-4 py-2 hover:bg-gray-50">
                    <div class="flex items-center">
                        <i class="fas @if($option->is_correct) fa-check-circle text-green-500 @else fa-circle text-gray-400 @endif @if(app()->getLocale() === 'ar') ml-3 @else mr-3 @endif"></i>
                        <span class="text-sm text-gray-700">{{ $option->option_text }}</span>
                        @if($option->is_correct)
                            <span class="@if(app()->getLocale() === 'ar') mr-2 @else ml-2 @endif px-2 py-0.5 bg-green-100 text-green-700 text-xs rounded">Correct</span>
                        @endif
                    </div>
                    <div class="flex items-center space-x-2">
                        <button wire:click="openOptionEditModal({{ $option->id }})"
                            class="text-sm text-blue-600 hover:text-blue-800" title="Edit">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button wire:click="openOptionDeleteModal({{ $option->id }})"
                            class="text-sm text-red-600 hover:text-red-800" title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="px-4 py-3 text-sm italic text-gray-500">
            No options yet. Click the + button to add an option.
        </div>
    @endif
</div>
