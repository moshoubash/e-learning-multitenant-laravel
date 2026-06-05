<div class="overflow-hidden bg-white border border-gray-200 rounded-lg">
    {{-- Section Header --}}
    <div class="flex items-center justify-between px-4 py-3 bg-gray-100">
        <div class="flex items-center">
            <i class="@rim('mr-2') text-gray-500 fas fa-folder"></i>
            <span class="font-medium text-gray-700">{{ $section->title }}</span>
            <span class="@rim('ml-2') text-xs text-gray-500">
                ({{ __('messages.Order') }}: {{ $section->order }})
            </span>
            @if($section->deleted_at)
                <span class="@rim('ml-2') px-2 py-0.5 bg-red-100 text-red-600 text-xs rounded">Deleted</span>
            @endif
        </div>
        <div class="flex items-center space-x-2">
            @if(!$section->quiz)
                <button wire:click="openQuizCreateModal({{ $section->id }})"
                    class="text-sm text-purple-600 hover:text-purple-800" title="Add Quiz">
                    <i class="fas fa-clipboard-list"></i>
                </button>
            @endif
            <button wire:click="openAssignmentCreateModal({{ $section->id }})"
                class="text-sm text-indigo-600 hover:text-indigo-800" title="Add Assignment">
                <i class="fas fa-tasks"></i>
            </button>
            <button wire:click="openLessonCreateModal({{ $section->id }})"
                class="text-sm text-green-600 hover:text-green-800" title="Add Lesson">
                <i class="fas fa-plus-circle"></i>
            </button>
            <button wire:click="openSectionEditModal({{ $section->id }})"
                class="text-sm text-blue-600 hover:text-blue-800" title="Edit">
                <i class="fas fa-edit"></i>
            </button>
            @if($section->deleted_at)
                <button wire:click="openSectionRestoreModal({{ $section->id }})"
                    class="text-sm text-green-600 hover:text-green-800" title="Restore">
                    <i class="fas fa-undo"></i>
                </button>
            @else
                <button wire:click="openSectionDeleteModal({{ $section->id }})"
                    class="text-sm text-red-600 hover:text-red-800" title="Delete">
                    <i class="fas fa-trash"></i>
                </button>
            @endif
        </div>
    </div>

    {{-- Quiz Section (if exists) --}}
    @if($section->quiz)
        <div class="px-4 py-2 border-b border-purple-200 bg-purple-50">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <i class="@rim('mr-2') text-purple-500 fas fa-clipboard-list"></i>
                    <span class="font-medium text-purple-700">{{ $section->quiz->title }}</span>
                    <span class="@rim('ml-2') text-xs text-purple-500">({{ __('messages.questions') }}
                        {{ $section->quiz->questions->count() ?? 0 }})</span>
                    <span class="@rim('ml-2') text-xs text-purple-500">
                        {{ __('messages.Pass') }}: {{ $section->quiz->pass_percentage }}%
                    </span>
                    @if($section->quiz->deleted_at)
                        <span class="@rim('ml-2') px-2 py-0.5 bg-red-100 text-red-600 text-xs rounded">Deleted</span>
                    @endif
                </div>
                <div class="flex items-center space-x-2">
                    <a href="{{ route('tenant.instructor.quizzes') }}" class="text-sm text-purple-600 hover:text-purple-800"
                        title="Manage Questions">
                        <i class="fas fa-external-link-alt"></i>
                    </a>
                    <button wire:click="openQuizEditModal({{ $section->quiz->id }})"
                        class="text-sm text-blue-600 hover:text-blue-800" title="Edit Quiz">
                        <i class="fas fa-edit"></i>
                    </button>
                    @if(!$section->quiz->deleted_at)
                        <button wire:click="openQuizDeleteModal({{ $section->quiz->id }})"
                            class="text-sm text-red-600 hover:text-red-800" title="Delete Quiz">
                            <i class="fas fa-trash"></i>
                        </button>
                    @endif
                </div>
            </div>
        </div>
    @endif

    {{-- Lessons List --}}
    @if($section->lessons && count($section->lessons) > 0)
        <div class="divide-y divide-gray-100">
            @foreach($section->lessons->sortBy('order') as $lesson)
                <div class="flex items-center justify-between px-4 py-2 hover:bg-gray-50">
                    <div class="flex items-center">
                        <i
                            class="fas @if($lesson->type === 'video') fa-play-circle text-blue-500 @elseif($lesson->type === 'text') fa-file @elseif($lesson->type === 'quiz') fa-list-check text-purple-500 @else fa-file-text text-gray-500 @endif @rim('mr-3')"></i>
                        <span class="text-sm text-gray-700">{{ $lesson->title }}</span>
                        <span class="@rim('ml-2') text-xs text-gray-400 ">
                            @if($lesson->duration_seconds)
                                {{ gmdate('i:s', $lesson->duration_seconds) }}
                            @endif
                        </span>
                        @if($lesson->deleted_at)
                            <span class="@rim('ml-2') px-2 py-0.5 bg-red-100 text-red-600 text-xs rounded">Deleted</span>
                        @endif
                    </div>
                    <div class="flex items-center space-x-2">
                        <button wire:click="openLessonEditModal({{ $lesson->id }})"
                            class="text-sm text-blue-600 hover:text-blue-800" title="Edit">
                            <i class="fas fa-edit"></i>
                        </button>
                        @if($lesson->deleted_at)
                            <button wire:click="openLessonRestoreModal({{ $lesson->id }})"
                                class="text-sm text-green-600 hover:text-green-800" title="Restore">
                                <i class="fas fa-undo"></i>
                            </button>
                        @else
                            <button wire:click="openLessonDeleteModal({{ $lesson->id }})"
                                class="text-sm text-red-600 hover:text-red-800" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    @if($section->assignments && count($section->assignments) > 0)
        <div class="divide-y divide-gray-100">
            @foreach($section->assignments->sortBy('order') as $assignment)
                @include('livewire.instructor.courses-components.tables.partials.assignment-row', ['assignment' => $assignment])
            @endforeach
        </div>
    @endif

    @if(!($section->lessons && count($section->lessons) > 0) && !($section->assignments && count($section->assignments) > 0))
        <div class="px-4 py-3 text-sm italic text-gray-500">
            No lessons yet. Click the + button to add a lesson.
        </div>
    @endif
</div>
