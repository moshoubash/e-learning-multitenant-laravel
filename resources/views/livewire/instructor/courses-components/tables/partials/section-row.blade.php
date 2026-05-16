<div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
    {{-- Section Header --}}
    <div class="flex items-center justify-between px-4 py-3 bg-gray-100">
        <div class="flex items-center">
            <i class="fas fa-folder text-gray-500 mr-2"></i>
            <span class="font-medium text-gray-700">{{ $section->title }}</span>
            <span class="ml-2 text-xs text-gray-500">(Order: {{ $section->order }})</span>
            @if($section->deleted_at)
                <span class="ml-2 px-2 py-0.5 bg-red-100 text-red-600 text-xs rounded">Deleted</span>
            @endif
        </div>
        <div class="flex items-center space-x-2">
            @if(!$section->quiz)
                <button wire:click="openQuizCreateModal({{ $section->id }})"
                    class="text-purple-600 hover:text-purple-800 text-sm" title="Add Quiz">
                    <i class="fas fa-clipboard-list"></i>
                </button>
            @endif
            <button wire:click="openLessonCreateModal({{ $section->id }})"
                class="text-green-600 hover:text-green-800 text-sm" title="Add Lesson">
                <i class="fas fa-plus-circle"></i>
            </button>
            <button wire:click="openSectionEditModal({{ $section->id }})"
                class="text-blue-600 hover:text-blue-800 text-sm" title="Edit">
                <i class="fas fa-edit"></i>
            </button>
            @if($section->deleted_at)
                <button wire:click="openSectionRestoreModal({{ $section->id }})"
                    class="text-green-600 hover:text-green-800 text-sm" title="Restore">
                    <i class="fas fa-undo"></i>
                </button>
            @else
                <button wire:click="openSectionDeleteModal({{ $section->id }})"
                    class="text-red-600 hover:text-red-800 text-sm" title="Delete">
                    <i class="fas fa-trash"></i>
                </button>
            @endif
        </div>
    </div>

    {{-- Quiz Section (if exists) --}}
    @if($section->quiz)
        <div class="bg-purple-50 border-b border-purple-200 px-4 py-2">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <i class="fas fa-clipboard-list text-purple-500 mr-2"></i>
                    <span class="font-medium text-purple-700">{{ $section->quiz->title }}</span>
                    <span class="ml-2 text-xs text-purple-500">({{ $section->quiz->questions->count() ?? 0 }}
                        questions)</span>
                    <span class="ml-2 text-xs text-purple-500">Pass: {{ $section->quiz->pass_percentage }}%</span>
                    @if($section->quiz->deleted_at)
                        <span class="ml-2 px-2 py-0.5 bg-red-100 text-red-600 text-xs rounded">Deleted</span>
                    @endif
                </div>
                <div class="flex items-center space-x-2">
                    <a href="{{ route('tenant.instructor.quizzes') }}" class="text-purple-600 hover:text-purple-800 text-sm"
                        title="Manage Questions">
                        <i class="fas fa-external-link-alt"></i>
                    </a>
                    <button wire:click="openQuizEditModal({{ $section->quiz->id }})"
                        class="text-blue-600 hover:text-blue-800 text-sm" title="Edit Quiz">
                        <i class="fas fa-edit"></i>
                    </button>
                    @if(!$section->quiz->deleted_at)
                        <button wire:click="openQuizDeleteModal({{ $section->quiz->id }})"
                            class="text-red-600 hover:text-red-800 text-sm" title="Delete Quiz">
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
                            class="fas @if($lesson->type === 'video') fa-play-circle text-blue-500 @elseif($lesson->type === 'quiz') fa-list-check text-purple-500 @else fa-file-text text-gray-500 @endif mr-3"></i>
                        <span class="text-sm text-gray-700">{{ $lesson->title }}</span>
                        <span class="ml-2 text-xs text-gray-400">
                            @if($lesson->duration_seconds)
                                {{ gmdate('i:s', $lesson->duration_seconds) }}
                            @endif
                        </span>
                        @if($lesson->deleted_at)
                            <span class="ml-2 px-2 py-0.5 bg-red-100 text-red-600 text-xs rounded">Deleted</span>
                        @endif
                    </div>
                    <div class="flex items-center space-x-2">
                        <button wire:click="openLessonEditModal({{ $lesson->id }})"
                            class="text-blue-600 hover:text-blue-800 text-sm" title="Edit">
                            <i class="fas fa-edit"></i>
                        </button>
                        @if($lesson->deleted_at)
                            <button wire:click="openLessonRestoreModal({{ $lesson->id }})"
                                class="text-green-600 hover:text-green-800 text-sm" title="Restore">
                                <i class="fas fa-undo"></i>
                            </button>
                        @else
                            <button wire:click="openLessonDeleteModal({{ $lesson->id }})"
                                class="text-red-600 hover:text-red-800 text-sm" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="px-4 py-3 text-sm text-gray-500 italic">
            No lessons yet. Click the + button to add a lesson.
        </div>
    @endif
</div>