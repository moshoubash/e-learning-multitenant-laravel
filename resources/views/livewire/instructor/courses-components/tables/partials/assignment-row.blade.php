<div class="px-4 py-2 border-t border-[#E5E5E5] bg-surface-container-low">
    <div class="flex items-center justify-between text-sm">
        <div class="flex items-center gap-2">
            <i class="fas fa-tasks text-on-surface ltr:mr-2 rtl:ml-2 text-xs"></i>
            <span class="font-bold text-on-surface">{{ $assignment->title }}</span>
            <span class="text-xs text-secondary">({{ __('messages.Order') }}: {{ $assignment->order }})</span>
            @if($assignment->deleted_at)
                <span class="px-2 py-0.5 neo-border-sm neo-radius text-[10px] font-bold bg-error/10 text-error">{{ __('messages.Deleted') }}</span>
            @endif
        </div>
        <div class="flex items-center gap-2">
            <button wire:click="openAssignmentEditModal({{ $assignment->id }})"
                class="w-7 h-7 neo-border-sm neo-radius flex items-center justify-center text-on-surface hover:bg-primary-container hover:text-on-primary-container transition-colors" title="Edit Assignment">
                <i class="fas fa-edit text-xs"></i>
            </button>
            @if($assignment->deleted_at)
                <button wire:click="openAssignmentRestoreModal({{ $assignment->id }})"
                    class="w-7 h-7 neo-border-sm neo-radius flex items-center justify-center text-on-surface hover:bg-primary-container hover:text-on-primary-container transition-colors" title="Restore Assignment">
                    <i class="fas fa-undo text-xs"></i>
                </button>
            @else
                <button wire:click="openAssignmentDeleteModal({{ $assignment->id }})"
                    class="w-7 h-7 neo-border-sm neo-radius flex items-center justify-center text-error hover:bg-error hover:text-white transition-colors" title="Delete Assignment">
                    <i class="fas fa-trash text-xs"></i>
                </button>
            @endif
        </div>
    </div>
</div>