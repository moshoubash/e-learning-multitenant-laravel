<div class="overflow-hidden neo-border-sm neo-radius bg-surface-container-lowest">
    <div class="flex items-center justify-between px-4 py-3 bg-surface-container">
        <div class="flex items-center">
            <i class="text-xs fas fa-question-circle text-on-surface ltr:mr-2 rtl:ml-2"></i>
            <span class="text-sm font-bold text-on-surface">{{ $question->question }}</span>
            <span class="px-2 py-0.5 neo-border-sm neo-radius text-[10px] font-bold bg-primary-container/30 text-on-primary-container ltr:ml-2 rtl:mr-2">
                {{ str_replace('_', ' ', __('messages.' . $question->type)) }}
            </span>
            <span class="text-xs text-secondary ltr:ml-2 rtl:mr-2">({{ __('messages.Order') }}: {{ $question->order }})</span>
        </div>
        <div class="flex items-center gap-2">
            @if($question->type !== 'true_false')
                <button wire:click="openOptionCreateModal({{ $question->id }})"
                    class="flex items-center justify-center transition-colors w-7 h-7 neo-border-sm neo-radius text-on-surface hover:bg-primary-container hover:text-on-primary-container" title="Add Option">
                    <i class="text-xs fas fa-plus-circle"></i>
                </button>
            @endif
            <button wire:click="openQuestionEditModal({{ $question->id }})"
                class="flex items-center justify-center transition-colors w-7 h-7 neo-border-sm neo-radius text-on-surface hover:bg-primary-container hover:text-on-primary-container" title="Edit">
                <i class="text-xs fas fa-edit"></i>
            </button>
            <button wire:click="openQuestionDeleteModal({{ $question->id }})"
                class="flex items-center justify-center transition-colors w-7 h-7 neo-border-sm neo-radius text-error hover:bg-error hover:text-white" title="Delete">
                <i class="text-xs fas fa-trash"></i>
            </button>
        </div>
    </div>

    @if($question->options && count($question->options) > 0)
        <div class="divide-y divide-[#E5E5E5]">
            @foreach($question->options as $option)
                <div class="flex items-center justify-between px-4 py-2 transition-colors hover:bg-surface-container-high">
                    <div class="flex items-center">
                        <i class="fas {{ $option->is_correct ? 'fa-check-circle text-primary-container' : 'fa-circle text-secondary' }} ltr:mr-3 rtl:ml-3 text-xs"></i>
                        <span class="text-sm text-on-surface">{{ $option->option_text }}</span>
                        @if($option->is_correct)
                            <span class="px-2 py-0.5 neo-border-sm neo-radius text-[10px] font-bold bg-primary-container text-on-primary-container ltr:ml-2 rtl:mr-2">{{ __('messages.Correct') }}</span>
                        @endif
                    </div>
                    <div class="flex items-center gap-2">
                        <button wire:click="openOptionEditModal({{ $option->id }})"
                            class="flex items-center justify-center transition-colors w-7 h-7 neo-border-sm neo-radius text-on-surface hover:bg-primary-container hover:text-on-primary-container" title="Edit">
                            <i class="text-xs fas fa-edit"></i>
                        </button>
                        <button wire:click="openOptionDeleteModal({{ $option->id }})"
                            class="flex items-center justify-center transition-colors w-7 h-7 neo-border-sm neo-radius text-error hover:bg-error hover:text-white" title="Delete">
                            <i class="text-xs fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="px-4 py-3 text-xs italic text-secondary">
            {{ __('messages.No options yet. Click the + button to add an option.') }}
        </div>
    @endif
</div>
