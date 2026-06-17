<div class="neo-border-sm neo-radius overflow-hidden bg-surface-container-lowest">
    <div class="flex items-center justify-between px-4 py-3 bg-surface-container">
        <div class="flex items-center">
            <i class="fas fa-question-circle text-on-surface ltr:mr-2 rtl:ml-2 text-xs"></i>
            <span class="font-bold text-sm text-on-surface">{{ $question->question }}</span>
            <span class="px-2 py-0.5 neo-border-sm neo-radius text-[10px] font-bold bg-primary-container/30 text-on-surface ltr:ml-2 rtl:mr-2">
                {{ str_replace('_', ' ', __('messages.' . $question->type)) }}
            </span>
            <span class="text-xs text-secondary ltr:ml-2 rtl:mr-2">({{ __('messages.Order') }}: {{ $question->order }})</span>
        </div>
        <div class="flex items-center gap-2">
            <button wire:click="openOptionCreateModal({{ $question->id }})"
                class="w-7 h-7 neo-border-sm neo-radius flex items-center justify-center text-on-surface hover:bg-primary-container hover:text-on-primary-container transition-colors" title="Add Option">
                <i class="fas fa-plus-circle text-xs"></i>
            </button>
            <button wire:click="openQuestionEditModal({{ $question->id }})"
                class="w-7 h-7 neo-border-sm neo-radius flex items-center justify-center text-on-surface hover:bg-primary-container hover:text-on-primary-container transition-colors" title="Edit">
                <i class="fas fa-edit text-xs"></i>
            </button>
            <button wire:click="openQuestionDeleteModal({{ $question->id }})"
                class="w-7 h-7 neo-border-sm neo-radius flex items-center justify-center text-error hover:bg-error hover:text-white transition-colors" title="Delete">
                <i class="fas fa-trash text-xs"></i>
            </button>
        </div>
    </div>

    @if($question->options && count($question->options) > 0)
        <div class="divide-y divide-[#E5E5E5]">
            @foreach($question->options as $option)
                <div class="flex items-center justify-between px-4 py-2 hover:bg-surface-container-high transition-colors">
                    <div class="flex items-center">
                        <i class="fas {{ $option->is_correct ? 'fa-check-circle text-primary-container' : 'fa-circle text-secondary' }} ltr:mr-3 rtl:ml-3 text-xs"></i>
                        <span class="text-sm text-on-surface">{{ $option->option_text }}</span>
                        @if($option->is_correct)
                            <span class="px-2 py-0.5 neo-border-sm neo-radius text-[10px] font-bold bg-primary-container text-on-primary-container ltr:ml-2 rtl:mr-2">{{ __('messages.Correct') }}</span>
                        @endif
                    </div>
                    <div class="flex items-center gap-2">
                        <button wire:click="openOptionEditModal({{ $option->id }})"
                            class="w-7 h-7 neo-border-sm neo-radius flex items-center justify-center text-on-surface hover:bg-primary-container hover:text-on-primary-container transition-colors" title="Edit">
                            <i class="fas fa-edit text-xs"></i>
                        </button>
                        <button wire:click="openOptionDeleteModal({{ $option->id }})"
                            class="w-7 h-7 neo-border-sm neo-radius flex items-center justify-center text-error hover:bg-error hover:text-white transition-colors" title="Delete">
                            <i class="fas fa-trash text-xs"></i>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="px-4 py-3 text-xs text-secondary italic">
            {{ __('messages.No options yet. Click the + button to add an option.') }}
        </div>
    @endif
</div>