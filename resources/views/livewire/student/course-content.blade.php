<div>
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center">
            <a href="{{ route('tenant.student.courses') }}" class="@if(app()->getLocale() === 'ar') ml-4 @else mr-4 @endif text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    @if(app()->getLocale() === 'ar')
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7"/>
                    @else
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7"/>
                    @endif
                </svg>
            </a>
            <h1 class="text-2xl font-bold text-gray-900">{{ $course->title ?? __('messages.Course') }}</h1>
        </div>
        <div class="flex items-center gap-4">
            <div class="text-sm text-gray-500">
                {{ __('messages.Progress') }}: {{ $progressPercent }}%
            </div>
            <div class="w-32 h-2 bg-gray-200 rounded-full">
                <div class="h-2 bg-green-500 rounded-full" style="width: {{ $progressPercent }}%"></div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <!-- Video/Content Area -->
        <div class="lg:col-span-2">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                @if($selectedAssignment)
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h3 class="text-xl font-semibold text-gray-800">{{ $selectedAssignment->title }}</h3>
                                <p class="text-sm text-gray-500">
                                    {{ $selectedAssignment->section->title ?? __('messages.Section') }}
                                </p>
                            </div>
                            <button wire:click="$set('selectedAssignment', null)"
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">
                                <i class="@if(app()->getLocale() === 'ar') ml-2 @else mr-2 @endif fas fa-arrow-left"></i>
                                {{ __('messages.Back') }}
                            </button>
                        </div>

                        <!-- Assignment Status Info -->
                        <div class="p-4 mb-4 rounded-lg bg-gray-50">
                            <div class="grid grid-cols-2 gap-4 md:grid-cols-4">
                                @if($selectedAssignment->max_score)
                                    <div class="text-center">
                                        <div class="text-xs text-gray-500">{{ __('messages.Max Score') }}</div>
                                        <div class="text-lg font-semibold text-gray-800">{{ $selectedAssignment->max_score }}</div>
                                    </div>
                                @endif
                                @if($selectedAssignment->due_date)
                                    <div class="text-center">
                                        <div class="text-xs text-gray-500">{{ __('messages.Due Date') }}</div>
                                        <div class="text-lg font-semibold {{ $this->isAssignmentPastDue() ? 'text-red-600' : 'text-gray-800' }}">
                                            {{ $selectedAssignment->due_date->format('Y-m-d H:i') }}
                                        </div>
                                    </div>
                                @endif
                                <div class="text-center">
                                    <div class="text-xs text-gray-500">{{ __('messages.Status') }}</div>
                                    <div class="text-lg font-semibold">
                                        <span class="px-2 py-1 text-xs rounded {{ $selectedAssignment->status === 'published' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                            {{ __('messages.'.$selectedAssignment->status) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <div class="text-xs text-gray-500">{{ __('messages.Late Submissions') }}</div>
                                    <div class="text-lg font-semibold text-gray-800">
                                        {{ $selectedAssignment->allow_late ? __('messages.Allowed') : __('messages.Not Allowed') }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Description -->
                        @if($selectedAssignment->description)
                            <div class="mb-4 prose text-left text-gray-700 max-w-none">
                                <h4 class="text-sm font-semibold text-gray-800">{{ __('messages.Description') }}</h4>
                                {!! nl2br(e($selectedAssignment->description)) !!}
                            </div>
                        @endif

                        <!-- Instructions -->
                        @if($selectedAssignment->instructions)
                            <div class="mb-4 prose text-left text-gray-700 max-w-none">
                                <h4 class="text-sm font-semibold text-gray-800">{{ __('messages.Instructions') }}</h4>
                                {!! nl2br(e($selectedAssignment->instructions)) !!}
                            </div>
                        @endif

                        <!-- Attachments Section -->
                        @if($selectedAssignment->attachments && $selectedAssignment->attachments->count() > 0)
                            <div class="p-4 mb-4 rounded-lg bg-blue-50">
                                <h4 class="mb-3 text-sm font-semibold text-gray-800">
                                    <i class="mr-2 fas fa-paperclip"></i>
                                    {{ __('messages.Attachments') }} ({{ $selectedAssignment->attachments->count() }})
                                </h4>
                                <ul class="space-y-2">
                                    @foreach($selectedAssignment->attachments as $attachment)
                                        <li class="flex items-center justify-between p-3 bg-white border rounded">
                                            <div class="flex items-center">
                                                <i class="mr-3 text-gray-400 fas fa-file"></i>
                                                <div>
                                                    <span class="text-sm font-medium text-gray-700">{{ $attachment->file_name }}</span>
                                                    <span class="ml-2 text-xs text-gray-400">({{ number_format($attachment->size / 1024, 1) }} KB)</span>
                                                </div>
                                            </div>
                                            <a href="{{ $attachment->file_path }}" target="_blank"
                                               class="px-3 py-1 text-xs font-medium text-blue-600 bg-blue-100 rounded hover:bg-blue-200">
                                                <i class="mr-1 fas fa-download"></i> {{ __('messages.Download') }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- User's Submission & Grades -->
                        @php
                            $userSubmission = $this->getUserSubmission();
                        @endphp

                        @if($userSubmission)
                            <div class="p-4 mb-4 rounded-lg bg-green-50">
                                <h4 class="mb-3 text-sm font-semibold text-gray-800">
                                    <i class="mr-2 fas fa-check-circle"></i>
                                    {{ __('messages.Your Submission') }}
                                </h4>
                                <div class="p-3 bg-white border rounded">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="text-sm text-gray-500">
                                            Submitted: {{ $userSubmission->submitted_at->format('Y-m-d H:i') }}
                                        </div>
                                        <span class="px-2 py-1 text-xs rounded {{ $userSubmission->status === 'graded' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                            {{ ucfirst($userSubmission->status) }}
                                        </span>
                                    </div>
                                    @if($userSubmission->content)
                                        <div class="mb-3 text-sm text-gray-700">
                                            <strong>{{ __('messages.Content') }}:</strong>
                                            <p class="mt-1">{!! nl2br(e($userSubmission->content)) !!}</p>
                                        </div>
                                    @endif
                                    @if($userSubmission->file_path)
                                        <div class="mb-3">
                                            <a href="{{ $userSubmission->file_path }}" target="_blank"
                                               class="text-blue-600 hover:underline">
                                                <i class="mr-1 fas fa-paperclip"></i> {{ __('messages.View Submission File') }}
                                            </a>
                                        </div>
                                    @endif
                                    @if($userSubmission->score !== null)
                                        <div class="pt-3 mt-3 border-t">
                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <span class="text-lg font-bold text-gray-800">
                                                        {{ __('messages.Score') }}: {{ $userSubmission->score }}/{{ $selectedAssignment->max_score ?? 100 }}
                                                    </span>
                                                    @if($selectedAssignment->max_score)
                                                        <span class="ml-2 text-sm text-gray-500">
                                                            ({{ round(($userSubmission->score / $selectedAssignment->max_score) * 100) }}%)
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                            @if($userSubmission->feedback)
                                                <div class="mt-3">
                                                    <strong class="text-sm text-gray-700">{{ __('messages.Feedback') }}:</strong>
                                                    <p class="mt-1 text-sm text-gray-600">{!! nl2br(e($userSubmission->feedback)) !!}</p>
                                                </div>
                                            @endif
                                        </div>
                                    @elseif($userSubmission->status === 'submitted')
                                        <div class="mt-2 text-sm text-yellow-600">
                                            <i class="mr-1 fas fa-clock"></i> {{ __('messages.Pending grading') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <!-- Submission Form -->
                        @if($selectedAssignment->status === 'published' && (!$this->isAssignmentPastDue() || $this->canSubmitLate()))
                            <div class="mt-4">
                                @if(!$userSubmission)
                                    @if($showSubmissionForm)
                                        <div class="p-4 rounded-lg bg-gray-50">
                                            <h4 class="mb-4 text-sm font-semibold text-gray-800">{{ __('messages.Submit Your Work') }}</h4>
                                            <form wire:submit="submitAssignment">
                                                <div class="mb-4">
                                                    <label class="block mb-2 text-sm font-medium text-gray-700">{{ __('messages.Content') }}</label>
                                                    <textarea wire:model.lazy="submissionContent" rows="5"
                                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                                        placeholder="{{ __('messages.Enter your submission content') }}"></textarea>
                                                    @error('submissionContent') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                                                </div>
                                                <div class="mb-4">
                                                    <label class="block mb-2 text-sm font-medium text-gray-700">{{ __('messages.Files') }}</label>
                                                    <input type="file" wire:model="submissionFiles" multiple
                                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                                    @error('submissionFiles.*') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                                                    @if(count($submissionFiles) > 0)
                                                        <div class="mt-2 text-sm text-gray-600">
                                                            {{ count($submissionFiles) }} {{ __('messages.file(s) selected') }}
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="flex gap-3">
                                                    <button type="submit"
                                                        class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">
                                                        {{ __('messages.Submit Assignment') }}
                                                    </button>
                                                    <button type="button" wire:click="toggleSubmissionForm"
                                                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300">
                                                        {{ __('messages.Cancel') }}
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    @else
                                        <button wire:click="toggleSubmissionForm"
                                            class="w-full px-4 py-3 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                                            <i class="mr-2 fas fa-upload"></i>
                                            {{ __('messages.Submit Assignment') }}
                                        </button>
                                    @endif
                                @endif
                            </div>
                        @elseif($this->isAssignmentPastDue() && !$this->canSubmitLate() && !$userSubmission)
                            <div class="p-4 mt-4 rounded-lg bg-red-50">
                                <p class="text-sm text-red-600">
                                    <i class="mr-2 fas fa-exclamation-triangle"></i>
                                    {{ __('messages.Submission deadline has passed. Late submissions are not allowed for this assignment.') }}
                                </p>
                            </div>
                        @endif
                    </div>
                @elseif($selectedLesson)
                    @if($selectedLesson->type === 'video' && $selectedLesson->video_url)
                        <!-- Plyr Video Player -->
                        <div class="aspect-video" wire:ignore.self>
                            <video
                                class="js-lesson-video w-full"
                                data-lesson-id="{{ $selectedLesson->id }}"
                                playsinline
                                controls
                                preload="metadata"
                            >
                                <source src="{{ $selectedLesson->video_url }}" type="video/mp4" />
                                Your browser does not support the video tag.
                            </video>
                        </div>

                        @push('scripts')
                            <script>
                                (function () {
                                    if (window.__lessonPlyrBound) { return; }
                                    window.__lessonPlyrBound = true;

                                    function initAll() {
                                        if (typeof Plyr === 'undefined') { return; }
                                        document.querySelectorAll('video.js-lesson-video:not([data-plyr-inited])').forEach(function (video) {
                                            new Plyr(video, {
                                                controls: ['play-large', 'play', 'progress', 'current-time', 'mute', 'volume', 'captions', 'settings', 'pip', 'fullscreen'],
                                                loadSprite: false,
                                                iconUrl: false
                                            });
                                            video.setAttribute('data-plyr-inited', '1');
                                        });
                                    }

                                    document.addEventListener('DOMContentLoaded', initAll);
                                    document.addEventListener('livewire:navigated', initAll);

                                    if (window.Livewire) {
                                        Livewire.hook('morph.updated', function () {
                                            setTimeout(initAll, 0);
                                        });
                                    }
                                })();
                            </script>
                        @endpush

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

                                    @if($section->assignments && $section->assignments->count() > 0)
                                        <div class="pt-3 mt-3 border-t border-gray-100">
                                            <div class="px-3 pb-2 text-xs font-semibold tracking-wide text-gray-500 uppercase">
                                                {{ __('messages.Assignments') }}
                                            </div>
                                            @foreach($section->assignments as $assignment)
                                                <div wire:click="selectAssignment({{ $assignment->id }})"
                                                    class="px-3 py-3 cursor-pointer hover:bg-gray-50 {{ $selectedAssignment && $selectedAssignment->id === $assignment->id ? 'bg-blue-50 border-l-4 border-blue-500' : '' }}">
                                                    <div class="flex items-center justify-between">
                                                        <div class="flex items-center gap-2">
                                                            <i class="text-indigo-500 fas fa-tasks"></i>
                                                            <span class="text-sm text-gray-700">{{ $assignment->title }}</span>
                                                        </div>
                                                        <span class="text-xs text-gray-400">{{ $assignment->order }}</span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif

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
