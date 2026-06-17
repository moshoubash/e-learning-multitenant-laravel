<div class="neo-border-sm neo-radius overflow-hidden bg-surface-container-lowest">
    <div class="flex items-center justify-between px-4 py-3 bg-surface-container">
        <div class="flex items-center">
            <i class="fas fa-folder text-secondary ltr:mr-2 rtl:ml-2 text-xs"></i>
            <span class="font-bold text-sm text-on-surface">{{ $section->title }}</span>
            <span class="text-xs text-secondary ltr:ml-2 rtl:mr-2">({{ __('messages.Order') }}: {{ $section->order }})</span>
            @if($section->deleted_at)
                <span class="px-2 py-0.5 neo-border-sm neo-radius text-[10px] font-bold bg-error/10 text-error ltr:ml-2 rtl:mr-2">{{ __('messages.Deleted') }}</span>
            @endif
        </div>
        <div class="flex items-center gap-2">
            @if(!$section->quiz)
                <button wire:click="openQuizCreateModal({{ $section->id }})"
                    class="w-7 h-7 neo-border-sm neo-radius flex items-center justify-center text-on-surface hover:bg-primary-container hover:text-on-primary-container transition-colors" title="Add Quiz">
                    <i class="fas fa-clipboard-list text-xs"></i>
                </button>
            @endif
            <button wire:click="openAssignmentCreateModal({{ $section->id }})"
                class="w-7 h-7 neo-border-sm neo-radius flex items-center justify-center text-on-surface hover:bg-primary-container hover:text-on-primary-container transition-colors" title="Add Assignment">
                <i class="fas fa-tasks text-xs"></i>
            </button>
            <button wire:click="openLessonCreateModal({{ $section->id }})"
                class="w-7 h-7 neo-border-sm neo-radius flex items-center justify-center text-on-surface hover:bg-primary-container hover:text-on-primary-container transition-colors" title="Add Lesson">
                <i class="fas fa-plus-circle text-xs"></i>
            </button>
            <button wire:click="openSectionEditModal({{ $section->id }})"
                class="w-7 h-7 neo-border-sm neo-radius flex items-center justify-center text-on-surface hover:bg-primary-container hover:text-on-primary-container transition-colors" title="Edit">
                <i class="fas fa-edit text-xs"></i>
            </button>
            @if($section->deleted_at)
                <button wire:click="openSectionRestoreModal({{ $section->id }})"
                    class="w-7 h-7 neo-border-sm neo-radius flex items-center justify-center text-on-surface hover:bg-primary-container hover:text-on-primary-container transition-colors" title="Restore">
                    <i class="fas fa-undo text-xs"></i>
                </button>
            @else
                <button wire:click="openSectionDeleteModal({{ $section->id }})"
                    class="w-7 h-7 neo-border-sm neo-radius flex items-center justify-center text-error hover:bg-error hover:text-white transition-colors" title="Delete">
                    <i class="fas fa-trash text-xs"></i>
                </button>
            @endif
        </div>
    </div>

    @if($section->quiz)
        <div class="px-4 py-2 border-b-2 border-on-surface/10 bg-primary-container/20">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <i class="fas fa-clipboard-list text-on-surface ltr:mr-2 rtl:ml-2 text-xs"></i>
                    <span class="font-bold text-sm text-on-surface">{{ $section->quiz->title }}</span>
                    <span class="text-xs text-secondary ltr:ml-2 rtl:mr-2">({{ __('messages.questions') }} {{ $section->quiz->questions->count() ?? 0 }})</span>
                    <span class="text-xs text-on-surface ltr:ml-2 rtl:mr-2 font-bold">{{ __('messages.Pass') }}: {{ $section->quiz->pass_percentage }}%</span>
                    @if($section->quiz->deleted_at)
                        <span class="px-2 py-0.5 neo-border-sm neo-radius text-[10px] font-bold bg-error/10 text-error ltr:ml-2 rtl:mr-2">{{ __('messages.Deleted') }}</span>
                    @endif
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('tenant.instructor.quizzes') }}" class="w-7 h-7 neo-border-sm neo-radius flex items-center justify-center text-on-surface hover:bg-primary-container transition-colors" title="Manage Questions">
                        <i class="fas fa-external-link-alt text-xs"></i>
                    </a>
                    <button wire:click="openQuizEditModal({{ $section->quiz->id }})"
                        class="w-7 h-7 neo-border-sm neo-radius flex items-center justify-center text-on-surface hover:bg-primary-container hover:text-on-primary-container transition-colors" title="Edit Quiz">
                        <i class="fas fa-edit text-xs"></i>
                    </button>
                    @if(!$section->quiz->deleted_at)
                        <button wire:click="openQuizDeleteModal({{ $section->quiz->id }})"
                            class="w-7 h-7 neo-border-sm neo-radius flex items-center justify-center text-error hover:bg-error hover:text-white transition-colors" title="Delete Quiz">
                            <i class="fas fa-trash text-xs"></i>
                        </button>
                    @endif
                </div>
            </div>
        </div>
    @endif

    @if($section->lessons && count($section->lessons) > 0)
        <div class="divide-y divide-[#E5E5E5]">
            @foreach($section->lessons->sortBy('order') as $lesson)
                <div class="flex items-center justify-between px-4 py-2 hover:bg-surface-container-high transition-colors">
                    <div class="flex items-center">
                        <i class="fas {{ $lesson->type === 'video' ? 'fa-play-circle text-on-surface' : ($lesson->type === 'text' ? 'fa-file-alt' : ($lesson->type === 'quiz' ? 'fa-list-check' : 'fa-file-text')) }} text-secondary ltr:mr-3 rtl:ml-3 text-xs"></i>
                        <span class="text-sm text-on-surface">{{ $lesson->title }}</span>
                        @if($lesson->duration_seconds)
                            <span class="text-xs text-secondary ltr:ml-2 rtl:mr-2 font-bold">{{ gmdate('i:s', $lesson->duration_seconds) }}</span>
                        @endif
                        @if($lesson->deleted_at)
                            <span class="px-2 py-0.5 neo-border-sm neo-radius text-[10px] font-bold bg-error/10 text-error ltr:ml-2 rtl:mr-2">{{ __('messages.Deleted') }}</span>
                        @endif
                    </div>
                    <div class="flex items-center gap-2">
                        <button wire:click="openLessonEditModal({{ $lesson->id }})"
                            class="w-7 h-7 neo-border-sm neo-radius flex items-center justify-center text-on-surface hover:bg-primary-container hover:text-on-primary-container transition-colors" title="Edit">
                            <i class="fas fa-edit text-xs"></i>
                        </button>
                        @if($lesson->deleted_at)
                            <button wire:click="openLessonRestoreModal({{ $lesson->id }})"
                                class="w-7 h-7 neo-border-sm neo-radius flex items-center justify-center text-on-surface hover:bg-primary-container hover:text-on-primary-container transition-colors" title="Restore">
                                <i class="fas fa-undo text-xs"></i>
                            </button>
                        @else
                            <button wire:click="openLessonDeleteModal({{ $lesson->id }})"
                                class="w-7 h-7 neo-border-sm neo-radius flex items-center justify-center text-error hover:bg-error hover:text-white transition-colors" title="Delete">
                                <i class="fas fa-trash text-xs"></i>
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
        <div class="px-4 py-3 text-xs text-secondary italic">
            {{ __('messages.No lessons yet. Click the + button to add a lesson.') }}
        </div>
    @endif
</div>