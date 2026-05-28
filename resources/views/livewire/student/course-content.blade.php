<x-slot name="header">
    <div class="flex items-center justify-between">
        <div class="flex items-center">
            <a href="{{ route('tenant.student.courses') }}" class="@if(app()->getLocale() === 'ar') ml-4 @else mr-4 @endif text-gray-500 hover:text-gray-700">
                @if(app()->getLocale() === 'ar')
                     <i class="fas fa-arrow-right"></i>
                @else
                    <i class="fas fa-arrow-left"></i>
                @endif
            </a>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                {{ $course->title ?? __('messages.Course') }}
            </h2>
        </div>
        <div class="flex items-center @if(app()->getLocale() === 'ar') gap-4 @else space-x-4 @endif">
            <div class="text-sm text-gray-500">
                {{ __('messages.Progress') }}: {{ $progressPercent }}%
            </div>
            <div class="w-32 h-2 bg-gray-200 rounded-full">
                <div class="h-2 bg-green-500 rounded-full" style="width: {{ $progressPercent }}%"></div>
            </div>
        </div>
    </div>
</x-slot>

<div class="max-w-full px-4 py-6 mx-auto sm:px-6 lg:px-8">
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <!-- Video/Content Area -->
        <div class="lg:col-span-2">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                @if($selectedLesson)
                    @if($selectedLesson->type === 'video' && $selectedLesson->video_url)
                        <!-- Plyr Video Player -->
                        <div class="aspect-video">
                            <video
                                x-data="{}"
                                x-init="$nextTick(() => { new Plyr($refs.player) })"
                                x-ref="player"
                                playsinline
                                controls
                                preload="metadata"
                                class="w-full"
                            >
                                <!-- Removed crossorigin from here -->
                                <source src="{{ $selectedLesson->video_url }}" type="video/mp4" />
                                Your browser does not support the video tag.
                            </video>
                        </div>

                        <div wire:loading.delay>
                            <div class="text-center text-white">
                                <i class="mb-4 text-6xl opacity-50 fas fa-spinner fa-spin"></i>
                                <p class="text-lg">Loading...</p>
                            </div>
                        </div>
                    @elseif($selectedLesson->type === 'video')
                        <!-- Video Placeholder when no Video URL -->
                        <div class="flex items-center justify-center bg-gray-900 aspect-video">
                            <div class="text-center text-white">
                                <i class="mb-4 text-6xl opacity-50 fas fa-play-circle"></i>
                                <p class="text-lg">{{ $selectedLesson->title }}</p>
                                <p class="mt-2 text-sm text-gray-400">Video content - no video assigned yet</p>
                            </div>
                        </div>
                    @else
                        <!-- Text/Quiz Placeholder -->
                        <div class="flex items-center justify-center bg-gray-900 aspect-video">
                            <div class="text-center text-white">
                                <i class="mb-4 text-6xl opacity-50 fas fa-book-open"></i>
                                <p class="text-lg">{{ $selectedLesson->title }}</p>
                                <p class="mt-2 text-sm text-gray-400">
                                    @if($selectedLesson->type === 'text')
                                        {{ __('messages.Text Lesson') }}
                                    @elseif($selectedLesson->type === 'quiz')
                                        {{ __('messages.Quiz') }}
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
                                    {{ $selectedLesson->section->title ?? __('messages.Section') }}
                                </p>
                            </div>
                            @if(!$this->isLessonCompleted($selectedLesson->id))
                                <button wire:click="markLessonComplete"
                                    class="px-4 py-2 text-sm font-medium text-white transition-colors bg-green-600 rounded-lg hover:bg-green-700">
                                    <i class="@if(app()->getLocale() === 'ar') ml-2 @else mr-2 @endif fas fa-check"></i>
                                    {{ __('messages.Mark Complete') }}
                                </button>
                            @else
                                <span class="inline-flex items-center px-3 py-1 text-sm font-medium text-green-700 bg-green-100 rounded-lg">
                                    <i class="@if(app()->getLocale() === 'ar') ml-1 @else mr-1 @endif fas fa-check-circle"></i>
                                    {{ __('messages.Completed') }}
                                </span>
                            @endif
                        </div>

                        @php
                            $isContainEnglish = preg_match('/[a-zA-Z]/', substr($selectedLesson->content, 0, 100));
                        @endphp

                        <!-- Lesson Content -->
                        @if($selectedLesson->type === 'text' || $selectedLesson->type === 'video' && $selectedLesson->content)
                            <div class="prose text-gray-700 max-w-none {{ $isContainEnglish ? 'text-left' : 'text-right' }}">
                                {!! nl2br($selectedLesson->content) !!}
                            </div>
                        @elseif($selectedLesson->type === 'quiz')
                            <div class="p-4 border border-purple-200 rounded-lg bg-purple-50">
                                <p class="text-sm text-purple-700">
                                    <i class="mr-2 fas fa-question-circle"></i>
                                    {{ __('messages.Take the quiz to test your knowledge.') }}
                                </p>
                            </div>
                        @endif
                    </div>
                @else
                    <div class="flex items-center justify-center bg-gray-900 aspect-video">
                        <div class="text-center text-white">
                            <i class="mb-4 text-6xl opacity-50 fas fa-book-open"></i>
                            <p class="text-lg">{{ __('messages.Select a lesson from the sidebar') }}</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Table of Contents Sidebar -->
        <div class="lg:col-span-1">
            <div class="sticky overflow-hidden bg-white shadow-sm sm:rounded-lg top-6">
                <div class="p-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-800">
                        <i class="fas fa-list @if(app()->getLocale() === 'ar') ml-2 @else mr-2 @endif"></i>
                        {{ __('messages.Course Content') }}
                    </h3>
                </div>

                <div class="max-h-[70vh] overflow-y-auto">
                    @forelse($course->sections as $section)
                        <div class="border-b border-gray-100">
                            <!-- Section Header -->
                            <div wire:click="toggleSection({{ $section->id }})"
                                class="flex items-center justify-between p-4 cursor-pointer bg-gray-50 hover:bg-gray-100">
                                <div class="flex items-center">
                                    @if(app()->getLocale() === 'ar')
                                        <i class="fas fa-chevron-left ml-2 text-gray-400 transition-transform {{ $this->isSectionExpanded($section->id) ? 'rotate-90' : '' }}"></i>
                                    @else
                                        <i class="fas fa-chevron-right @if(app()->getLocale() === 'ar') ml-2 @else mr-2 @endif text-gray-400 transition-transform {{ $this->isSectionExpanded($section->id) ? 'rotate-90' : '' }}"></i>
                                    @endif

                                    <span class="text-sm font-medium text-gray-700">{{ $section->title }}</span>
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
                                            class="p-3 cursor-pointer hover:bg-gray-50 "
                                            style="{{ $selectedLesson && $selectedLesson->id === $lesson->id ? 'background-color: #ebf8ff; border-left-width: 4px; border-top-width:0; border-color: #4299e1;' : '' }}">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center">
                                                    <!-- Completion Status Icon -->
                                                    @if($this->isLessonCompleted($lesson->id))
                                                        <i class="@if(app()->getLocale() === 'ar') ml-3 @else mr-3 @endif text-green-500 fas fa-check-circle"></i>
                                                    @elseif($lesson->type === 'video')
                                                        <i class="@if(app()->getLocale() === 'ar') ml-3 @else mr-3 @endif text-blue-400 fas fa-play-circle"></i>
                                                    @elseif($lesson->type === 'text')
                                                        <i class="@if(app()->getLocale() === 'ar') ml-3 @else mr-3 @endif fas fa-file"></i>
                                                    @else
                                                        <i class="@if(app()->getLocale() === 'ar') ml-3 @else mr-3 @endif text-gray-400 fas fa-file-text"></i>
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
                                        <div class="p-3 border-t border-purple-100 bg-purple-50">
                                            <div class="flex items-center justify-between">
                                                <a href="{{ route('tenant.student.quiz', $section->quiz->id) }}"
                                                   class="flex items-center flex-1 p-2 -m-2 transition-colors rounded hover:bg-purple-100">
                                                    <i class="@if(app()->getLocale() === 'ar') ml-3 @else mr-3 @endif text-purple-500 fas fa-clipboard-list"></i>
                                                    <span class="flex-1 text-sm font-medium text-purple-700">
                                                        {{ $section->quiz->title }}
                                                    </span>
                                                </a>
                                                <span class="ml-2 text-xs text-purple-500">
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
