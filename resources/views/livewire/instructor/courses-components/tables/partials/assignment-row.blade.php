<div class="px-4 py-2 border-t border-[#E5E5E5] bg-surface-container-low">
    <div class="flex items-center justify-between text-sm">
        <div class="flex items-center gap-2">
            <i class="text-xs fas fa-tasks text-on-surface ltr:mr-2 rtl:ml-2"></i>
            <span class="font-bold text-on-surface">{{ $assignment->title }}</span>
            <span class="text-xs text-secondary">({{ __('messages.Order') }}: {{ $assignment->order }})</span>
            @if($assignment->deleted_at)
                <span class="px-2 py-0.5 neo-border-sm neo-radius text-[10px] font-bold bg-error/10 text-error">{{ __('messages.Deleted') }}</span>
            @endif
        </div>
        <div class="flex items-center gap-2">
            <button wire:click="openAssignmentEditModal({{ $assignment->id }})"
                class="flex items-center justify-center transition-colors w-7 h-7 neo-border-sm neo-radius text-on-surface hover:bg-primary-container hover:text-on-primary-container" title="Edit Assignment">
                <i class="text-xs fas fa-edit"></i>
            </button>
            @if($assignment->deleted_at)
                <button wire:click="openAssignmentRestoreModal({{ $assignment->id }})"
                    class="flex items-center justify-center transition-colors w-7 h-7 neo-border-sm neo-radius text-on-surface hover:bg-primary-container hover:text-on-primary-container" title="Restore Assignment">
                    <i class="text-xs fas fa-undo"></i>
                </button>
            @else
                <button wire:click="openAssignmentDeleteModal({{ $assignment->id }})"
                    class="flex items-center justify-center transition-colors w-7 h-7 neo-border-sm neo-radius text-error hover:bg-error hover:text-white" title="Delete Assignment">
                    <i class="text-xs fas fa-trash"></i>
                </button>
            @endif
        </div>
    </div>
</div>
