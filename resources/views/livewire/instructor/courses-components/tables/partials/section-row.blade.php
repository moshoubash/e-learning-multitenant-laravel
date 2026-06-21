<div class="overflow-hidden neo-border-sm neo-radius bg-surface-container-lowest">
    <div class="flex items-center justify-between px-4 py-3 bg-surface-container">
        <div class="flex items-center">
            <i class="text-xs fas fa-folder text-secondary ltr:mr-2 rtl:ml-2"></i>
            <span class="text-sm font-bold text-on-surface">{{ $section->title }}</span>
            <span class="text-xs text-secondary ltr:ml-2 rtl:mr-2">({{ __('messages.Order') }}: {{ $section->order }})</span>
            @if($section->deleted_at)
                <span class="px-2 py-0.5 neo-border-sm neo-radius text-[10px] font-bold bg-error/10 text-error ltr:ml-2 rtl:mr-2">{{ __('messages.Deleted') }}</span>
            @endif
        </div>
        <div class="flex items-center gap-2">
            @if(!$section->quiz)
                <button wire:click="openQuizCreateModal({{ $section->id }})"
                    class="flex items-center justify-center transition-colors w-7 h-7 neo-border-sm neo-radius text-on-surface hover:bg-primary-container hover:text-on-primary-container" title="Add Quiz">
                    <i class="text-xs fas fa-clipboard-list"></i>
                </button>
            @endif
            <button wire:click="openAssignmentCreateModal({{ $section->id }})"
                class="flex items-center justify-center transition-colors w-7 h-7 neo-border-sm neo-radius text-on-surface hover:bg-primary-container hover:text-on-primary-container" title="Add Assignment">
                <i class="text-xs fas fa-tasks"></i>
            </button>
            <button wire:click="openLessonCreateModal({{ $section->id }})"
                class="flex items-center justify-center transition-colors w-7 h-7 neo-border-sm neo-radius text-on-surface hover:bg-primary-container hover:text-on-primary-container" title="Add Lesson">
                <i class="text-xs fas fa-plus-circle"></i>
            </button>
            <button wire:click="openSectionEditModal({{ $section->id }})"
                class="flex items-center justify-center transition-colors w-7 h-7 neo-border-sm neo-radius text-on-surface hover:bg-primary-container hover:text-on-primary-container" title="Edit">
                <i class="text-xs fas fa-edit"></i>
            </button>
            @if($section->deleted_at)
                <button wire:click="openSectionRestoreModal({{ $section->id }})"
                    class="flex items-center justify-center transition-colors w-7 h-7 neo-border-sm neo-radius text-on-surface hover:bg-primary-container hover:text-on-primary-container" title="Restore">
                    <i class="text-xs fas fa-undo"></i>
                </button>
            @else
                <button wire:click="openSectionDeleteModal({{ $section->id }})"
                    class="flex items-center justify-center transition-colors w-7 h-7 neo-border-sm neo-radius text-error hover:bg-error hover:text-white" title="Delete">
                    <i class="text-xs fas fa-trash"></i>
                </button>
            @endif
        </div>
    </div>

    @if($section->quiz)
        <div class="px-4 py-2 border-b-2 border-on-surface/10 bg-primary-container/20">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <i class="text-xs fas fa-clipboard-list text-on-surface ltr:mr-2 rtl:ml-2"></i>
                    <span class="text-sm font-bold text-on-surface">{{ $section->quiz->title }}</span>
                    <span class="text-xs text-secondary ltr:ml-2 rtl:mr-2">({{ __('messages.questions') }} {{ $section->quiz->questions->count() ?? 0 }})</span>
                    <span class="text-xs font-bold text-on-surface ltr:ml-2 rtl:mr-2">{{ __('messages.Pass') }}: {{ $section->quiz->pass_percentage }}%</span>
                    @if($section->quiz->can_reattempt)
                        <span class="text-xs font-bold text-on-surface ltr:ml-2 rtl:mr-2">{{ __('messages.Re-attempts') }}: {{ $section->quiz->max_attempts ?? 1 }}</span>
                    @else
                        <span class="text-xs text-secondary ltr:ml-2 rtl:mr-2">{{ __('messages.No re-attempt') }}</span>
                    @endif
                    @if($section->quiz->deleted_at)
                        <span class="px-2 py-0.5 neo-border-sm neo-radius text-[10px] font-bold bg-error/10 text-error ltr:ml-2 rtl:mr-2">{{ __('messages.Deleted') }}</span>
                    @endif
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('tenant.instructor.quizzes') }}" class="flex items-center justify-center transition-colors w-7 h-7 neo-border-sm neo-radius text-on-surface hover:bg-primary-container" title="Manage Questions">
                        <i class="text-xs fas fa-external-link-alt"></i>
                    </a>
                    <button wire:click="openQuizEditModal({{ $section->quiz->id }})"
                        class="flex items-center justify-center transition-colors w-7 h-7 neo-border-sm neo-radius text-on-surface hover:bg-primary-container hover:text-on-primary-container" title="Edit Quiz">
                        <i class="text-xs fas fa-edit"></i>
                    </button>
                    @if(!$section->quiz->deleted_at)
                        <button wire:click="openQuizDeleteModal({{ $section->quiz->id }})"
                            class="flex items-center justify-center transition-colors w-7 h-7 neo-border-sm neo-radius text-error hover:bg-error hover:text-white" title="Delete Quiz">
                            <i class="text-xs fas fa-trash"></i>
                        </button>
                    @endif
                </div>
            </div>
        </div>
    @endif

    @if($section->lessons && count($section->lessons) > 0)
        <div class="divide-y divide-[#E5E5E5]">
            @foreach($section->lessons->sortBy('order') as $lesson)
                <div class="flex items-center justify-between px-4 py-2 transition-colors hover:bg-surface-container-high">
                    <div class="flex items-center">
                        <i class="fas {{ $lesson->type === 'video' ? 'fa-play-circle text-on-surface' : ($lesson->type === 'text' ? 'fa-file-alt' : ($lesson->type === 'quiz' ? 'fa-list-check' : 'fa-file-text')) }} text-secondary ltr:mr-3 rtl:ml-3 text-xs"></i>
                        <span class="text-sm text-on-surface">{{ $lesson->title }}</span>
                        @if($lesson->duration_seconds)
                            <span class="text-xs font-bold text-secondary ltr:ml-2 rtl:mr-2">{{ gmdate('i:s', $lesson->duration_seconds) }}</span>
                        @endif
                        @if($lesson->deleted_at)
                            <span class="px-2 py-0.5 neo-border-sm neo-radius text-[10px] font-bold bg-error/10 text-error ltr:ml-2 rtl:mr-2">{{ __('messages.Deleted') }}</span>
                        @endif
                    </div>
                    <div class="flex items-center gap-2">
                        <button wire:click="openLessonEditModal({{ $lesson->id }})"
                            class="flex items-center justify-center transition-colors w-7 h-7 neo-border-sm neo-radius text-on-surface hover:bg-primary-container hover:text-on-primary-container" title="Edit">
                            <i class="text-xs fas fa-edit"></i>
                        </button>
                        @if($lesson->deleted_at)
                            <button wire:click="openLessonRestoreModal({{ $lesson->id }})"
                                class="flex items-center justify-center transition-colors w-7 h-7 neo-border-sm neo-radius text-on-surface hover:bg-primary-container hover:text-on-primary-container" title="Restore">
                                <i class="text-xs fas fa-undo"></i>
                            </button>
                        @else
                            <button wire:click="openLessonDeleteModal({{ $lesson->id }})"
                                class="flex items-center justify-center transition-colors w-7 h-7 neo-border-sm neo-radius text-error hover:bg-error hover:text-white" title="Delete">
                                <i class="text-xs fas fa-trash"></i>
                            </button>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    @if($section->assignments && count($section->assignments) > 0)
        <div class="divide-y divide-[#E5E5E5]">
            @foreach($section->assignments->sortBy('order') as $assignment)
                @include('livewire.instructor.courses-components.tables.partials.assignment-row', ['assignment' => $assignment])
            @endforeach
        </div>
    @endif

    @if(!($section->lessons && count($section->lessons) > 0) && !($section->assignments && count($section->assignments) > 0))
        <div class="px-4 py-3 text-xs italic text-secondary">
            {{ __('messages.No lessons yet. Click the + button to add a lesson.') }}
        </div>
    @endif
</div>
