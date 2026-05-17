<x-slot name="header">
    <div class="flex justify-between items-center">
        <div class="flex items-center">
            <a href="{{ route('tenant.student.courses') }}" class="text-gray-500 hover:text-gray-700 mr-4">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $course->title ?? 'Course' }}
            </h2>
        </div>
        <div class="flex items-center space-x-4">
            <div class="text-sm text-gray-500">
                Progress: {{ $progressPercent }}%
            </div>
            <div class="w-32 bg-gray-200 rounded-full h-2">
                <div class="bg-green-500 h-2 rounded-full" style="width: {{ $progressPercent }}%"></div>
            </div>
        </div>
    </div>
</x-slot>

<div class="max-w-full mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Video/Content Area -->
        <div class="lg:col-span-2">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                @if($selectedLesson)
                    @if($selectedLesson->type === 'video' && $selectedLesson->video_url)
                        <!-- Plyr Video Player -->
                        <div class="aspect-video" wire:ignore>
                            <video 
                                x-data="{}" 
                                x-init="new Plyr($refs.player)" 
                                x-ref="player" 
                                playsinline 
                                controls 
                                class="w-full"
                            >
                                <source src="{{ $selectedLesson->video_url }}" type="video/mp4" />
                            </video>
                        </div>
                    @elseif($selectedLesson->type === 'video')
                        <!-- Video Placeholder when no Video URL -->
                        <div class="bg-gray-900 aspect-video flex items-center justify-center">
                            <div class="text-center text-white">
                                <i class="fas fa-play-circle text-6xl mb-4 opacity-50"></i>
                                <p class="text-lg">{{ $selectedLesson->title }}</p>
                                <p class="text-sm text-gray-400 mt-2">Video content - no video assigned yet</p>
                            </div>
                        </div>
                    @else
                        <!-- Text/Quiz Placeholder -->
                        <div class="bg-gray-900 aspect-video flex items-center justify-center">
                            <div class="text-center text-white">
                                <i class="fas fa-book-open text-6xl mb-4 opacity-50"></i>
                                <p class="text-lg">{{ $selectedLesson->title }}</p>
                                <p class="text-sm text-gray-400 mt-2">
                                    @if($selectedLesson->type === 'text')
                                        Text Lesson
                                    @elseif($selectedLesson->type === 'quiz')
                                        Quiz
                                    @endif
                                </p>
                            </div>
                        </div>
                    @endif

                    <!-- Lesson Info -->
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h3 class="text-xl font-semibold text-gray-800">{{ $selectedLesson->title }}</h3>
                                <p class="text-sm text-gray-500">
                                    {{ $selectedLesson->section->title ?? 'Section' }}
                                </p>
                            </div>
                            @if(!$this->isLessonCompleted($selectedLesson->id))
                                <button wire:click="markLessonComplete"
                                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                                    <i class="fas fa-check mr-2"></i>
                                    Mark Complete
                                </button>
                            @else
                                <span class="inline-flex items-center px-3 py-1 bg-green-100 text-green-700 rounded-lg text-sm font-medium">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    Completed
                                </span>
                            @endif
                        </div>

                        <!-- Lesson Content -->
                        @if($selectedLesson->type === 'text' && $selectedLesson->content)
                            <div class="prose max-w-none text-gray-700">
                                {!! nl2br(e($selectedLesson->content)) !!}
                            </div>
                        @elseif($selectedLesson->type === 'video')
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <p class="text-blue-700 text-sm">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    Video playback placeholder. 
                                </p>
                            </div>
                        @elseif($selectedLesson->type === 'quiz')
                            <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                                <p class="text-purple-700 text-sm">
                                    <i class="fas fa-question-circle mr-2"></i>
                                    Take the quiz to test your knowledge.
                                </p>
                            </div>
                        @endif
                    </div>
                @else
                    <div class="bg-gray-900 aspect-video flex items-center justify-center">
                        <div class="text-center text-white">
                            <i class="fas fa-book-open text-6xl mb-4 opacity-50"></i>
                            <p class="text-lg">Select a lesson from the sidebar</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Table of Contents Sidebar -->
        <div class="lg:col-span-1">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg sticky top-6">
                <div class="p-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-800">
                        <i class="fas fa-list mr-2"></i>
                        {{ __('Course Content') }}
                    </h3>
                </div>

                <div class="max-h-[70vh] overflow-y-auto">
                    @forelse($course->sections as $section)
                        <div class="border-b border-gray-100">
                            <!-- Section Header -->
                            <div wire:click="toggleSection({{ $section->id }})"
                                class="flex items-center justify-between p-4 bg-gray-50 hover:bg-gray-100 cursor-pointer">
                                <div class="flex items-center">
                                    <i class="fas fa-chevron-right mr-3 text-gray-400 transition-transform {{ $this->isSectionExpanded($section->id) ? 'rotate-90' : '' }}"></i>
                                    <span class="font-medium text-gray-700 text-sm">{{ $section->title }}</span>
                                </div>
                                <span class="text-xs text-gray-400">
                                    {{ $section->lessons->count() }}
                                </span>
                            </div>

                            <!-- Lessons List -->
                            @if($this->isSectionExpanded($section->id))
                                <div class="divide-y divide-gray-50">
                                    @foreach($section->lessons as $lesson)
                                        <div wire:click="selectLesson({{ $lesson->id }})"
                                            class="p-3 hover:bg-gray-50 cursor-pointer {{ $selectedLesson && $selectedLesson->id === $lesson->id ? 'bg-blue-50 border-l-4 border-blue-500' : '' }}">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center">
                                                    <!-- Completion Status Icon -->
                                                    @if($this->isLessonCompleted($lesson->id))
                                                        <i class="fas fa-check-circle text-green-500 mr-3"></i>
                                                    @else
                                                        <i class="fas @if($lesson->type === 'video') fa-play-circle text-blue-400 @elseif($lesson->type === 'quiz') fa-question-circle text-purple-400 @else fa-file-text text-gray-400 @endif mr-3"></i>
                                                    @endif
                                                    <span class="text-sm text-gray-700 {{ $selectedLesson && $selectedLesson->id === $lesson->id ? 'font-medium' : '' }}">
                                                        {{ $lesson->title }}
                                                    </span>
                                                </div>
                                                @if($lesson->duration_seconds)
                                                    <span class="text-xs text-gray-400">
                                                        {{ gmdate('i:s', $lesson->duration_seconds) }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach

                                    <!-- Quiz -->
                                    @if($section->quiz)
                                        <div class="p-3 bg-purple-50 border-t border-purple-100">
                                            <div class="flex items-center justify-between">
                                                <a href="{{ route('tenant.student.quiz', $section->quiz->id) }}" 
                                                   class="flex items-center flex-1 hover:bg-purple-100 rounded p-2 -m-2 transition-colors">
                                                    <i class="fas fa-clipboard-list text-purple-500 mr-3"></i>
                                                    <span class="text-sm text-purple-700 font-medium flex-1">
                                                        {{ $section->quiz->title }}
                                                    </span>
                                                </a>
                                                <span class="text-xs text-purple-500 ml-2">
                                                    <i class="fas fa-external-link-alt"></i>
                                                </span>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="p-6 text-center text-gray-500">
                            <p>No content available yet.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>