<div class="px-4 py-2 border-t border-gray-100 bg-gray-50">
    <div class="flex items-center justify-between text-sm text-gray-700">
        <div class="flex items-center gap-2">
            <i class="text-indigo-500 fas fa-tasks"></i>
            <span>{{ $assignment->title }}</span>
            <span class="text-xs text-gray-400">({{ __('messages.Order') }}: {{ $assignment->order }})</span>
            @if($assignment->deleted_at)
                <span class="px-2 py-0.5 bg-red-100 text-red-600 rounded text-xs">Deleted</span>
            @endif
        </div>
        <div class="flex items-center gap-2">
            <button wire:click="openAssignmentEditModal({{ $assignment->id }})"
                class="text-sm text-blue-600 hover:text-blue-800" title="Edit Assignment">
                <i class="fas fa-edit"></i>
            </button>
            @if($assignment->deleted_at)
                <button wire:click="openAssignmentRestoreModal({{ $assignment->id }})"
                    class="text-sm text-green-600 hover:text-green-800" title="Restore Assignment">
                    <i class="fas fa-undo"></i>
                </button>
            @else
                <button wire:click="openAssignmentDeleteModal({{ $assignment->id }})"
                    class="text-sm text-red-600 hover:text-red-800" title="Delete Assignment">
                    <i class="fas fa-trash"></i>
                </button>
            @endif
        </div>
    </div>
</div>
