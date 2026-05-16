<div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
    <div class="flex items-center justify-between px-4 py-3 bg-gray-100">
        <div class="flex items-center">
            <i class="fas fa-question-circle text-purple-500 mr-2"></i>
            <span class="font-medium text-gray-700">{{ $question->question }}</span>
            <span class="ml-2 px-2 py-0.5 bg-purple-100 text-purple-700 text-xs rounded">
                {{ str_replace('_', ' ', ucfirst($question->type)) }}
            </span>
            <span class="ml-2 text-xs text-gray-500">(Order: {{ $question->order }})</span>
        </div>
        <div class="flex items-center space-x-2">
            <button wire:click="openOptionCreateModal({{ $question->id }})"
                class="text-green-600 hover:text-green-800 text-sm" title="Add Option">
                <i class="fas fa-plus-circle"></i>
            </button>
            <button wire:click="openQuestionEditModal({{ $question->id }})"
                class="text-blue-600 hover:text-blue-800 text-sm" title="Edit">
                <i class="fas fa-edit"></i>
            </button>
            <button wire:click="openQuestionDeleteModal({{ $question->id }})"
                class="text-red-600 hover:text-red-800 text-sm" title="Delete">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    </div>

    @if($question->options && count($question->options) > 0)
        <div class="divide-y divide-gray-100">
            @foreach($question->options as $option)
                <div class="flex items-center justify-between px-4 py-2 hover:bg-gray-50">
                    <div class="flex items-center">
                        <i
                            class="fas @if($option->is_correct) fa-check-circle text-green-500 @else fa-circle text-gray-400 @endif mr-3"></i>
                        <span class="text-sm text-gray-700">{{ $option->option_text }}</span>
                        @if($option->is_correct)
                            <span class="ml-2 px-2 py-0.5 bg-green-100 text-green-700 text-xs rounded">Correct</span>
                        @endif
                    </div>
                    <div class="flex items-center space-x-2">
                        <button wire:click="openOptionEditModal({{ $option->id }})"
                            class="text-blue-600 hover:text-blue-800 text-sm" title="Edit">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button wire:click="openOptionDeleteModal({{ $option->id }})"
                            class="text-red-600 hover:text-red-800 text-sm" title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="px-4 py-3 text-sm text-gray-500 italic">
            No options yet. Click the + button to add an option.
        </div>
    @endif
</div>