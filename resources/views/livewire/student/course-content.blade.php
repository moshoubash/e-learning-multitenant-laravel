<div>
    <header class="px-[24px] py-[13px] bg-surface-container-lowest border-b-2 border-on-surface sticky top-0 z-40">
        {{-- Desktop: single row --}}
        <div class="items-center justify-between hidden lg:flex">
            <div class="flex items-center gap-4">
                <a href="{{ route('tenant.student.courses') }}" class="transition-colors text-secondary hover:text-on-surface">
                    <i class="text-lg fas fa-arrow-left"></i>
                </a>
                <h2 class="text-[24px] font-bold text-on-surface leading-none ">{{ $course->title ?? __('messages.Course') }}</h2>
            </div>
            <div class="flex items-center gap-3">
                @livewire('shared.notification-bell')
                <span class="text-[10px] font-bold uppercase tracking-widest text-secondary ltr:ml-2 rtl:mr-2">{{ __('messages.Progress') }}: {{ $progressPercent }}%</span>
                <div class="w-32 h-2 overflow-hidden neo-border-sm neo-radius bg-surface-container">
                    <div class="h-full transition-all duration-300 bg-on-surface neo-radius" style="width: {{ $progressPercent }}%"></div>
                </div>
            </div>
        </div>
        {{-- Mobile: two rows --}}
        <div class="lg:hidden">
            <div class="flex items-start justify-between">
                <div class="flex items-center gap-4">
                    <a href="{{ route('tenant.student.courses') }}" class="transition-colors text-secondary hover:text-on-surface">
                        <i class="text-lg fas fa-arrow-left"></i>
                    </a>
                    <h2 class="text-[19px] font-bold text-on-surface leading-none ">{{ $course->title ?? __('messages.Course') }}</h2>
                </div>
                <div>
                    @livewire('shared.notification-bell')
                </div>
            </div>
            <div class="flex items-center gap-3 mt-3">
                <span class="text-[10px] font-bold uppercase tracking-widest text-secondary">{{ __('messages.Progress') }}: {{ $progressPercent }}%</span>
                <div class="flex-1 h-2 max-w-xs overflow-hidden neo-border-sm neo-radius bg-surface-container">
                    <div class="h-full transition-all duration-300 bg-on-surface neo-radius" style="width: {{ $progressPercent }}%"></div>
                </div>
            </div>
        </div>
    </header>

    <div class="p-[24px] max-w-[1400px] mx-auto">
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            {{-- Video/Content Area --}}
            <div class="lg:col-span-2">
                <div class="overflow-hidden bg-surface-container-lowest neo-border neo-radius">
                    @if($selectedAssignment)
                        <div class="p-[24px]">
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <h3 class="text-sm font-bold text-on-surface">{{ $selectedAssignment->title }}</h3>
                                    <p class="text-xs text-secondary">{{ $selectedAssignment->section->title ?? __('messages.Section') }}</p>
                                </div>
                                <button wire:click="$set('selectedAssignment', null)"
                                    class="px-4 py-2 text-xs font-bold transition-colors neo-border-sm neo-radius text-on-surface bg-surface-container hover:bg-on-surface hover:text-white">
                                    <i class="fas fa-arrow-left ltr:mr-2 rtl:ml-2"></i>
                                    {{ __('messages.Back') }}
                                </button>
                            </div>

                            {{-- Assignment Status Info --}}
                            <div class="p-4 mb-4 neo-border-sm neo-radius bg-surface-container-low">
                                <div class="grid grid-cols-2 gap-4 md:grid-cols-4">
                                    @if($selectedAssignment->max_score)
                                        <div class="text-center">
                                            <div class="text-[10px] font-bold uppercase tracking-widest text-secondary">{{ __('messages.Max Score') }}</div>
                                            <div class="text-lg font-bold text-on-surface">{{ $selectedAssignment->max_score }}</div>
                                        </div>
                                    @endif
                                    @if($selectedAssignment->due_date)
                                        <div class="text-center">
                                            <div class="text-[10px] font-bold uppercase tracking-widest text-secondary">{{ __('messages.Due Date') }}</div>
                                            <div class="text-lg font-bold {{ $this->isAssignmentPastDue() ? 'text-error' : 'text-on-surface' }}">
                                                {{ $selectedAssignment->due_date->format('Y-m-d H:i') }}
                                            </div>
                                        </div>
                                    @endif
                                    <div class="text-center">
                                        <div class="text-[10px] font-bold uppercase tracking-widest text-secondary">{{ __('messages.Status') }}</div>
                                        <div class="mt-1">
                                            <span class="px-2 py-1 neo-border-sm neo-radius text-[10px] font-bold {{ $selectedAssignment->status === 'published' ? 'bg-primary-container text-on-primary-container' : 'bg-surface-container text-on-surface' }}">
                                                {{ __('messages.'.$selectedAssignment->status) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-[10px] font-bold uppercase tracking-widest text-secondary">{{ __('messages.Late Submissions') }}</div>
                                        <div class="text-lg font-bold text-on-surface">
                                            {{ $selectedAssignment->allow_late ? __('messages.Allowed') : __('messages.Not Allowed') }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Description --}}
                            @if($selectedAssignment->description)
                                <div class="mb-4 text-sm text-on-surface">
                                    <h4 class="mb-2 text-xs font-bold tracking-widest uppercase text-on-surface">{{ __('messages.Description') }}</h4>
                                    {!! nl2br(e($selectedAssignment->description)) !!}
                                </div>
                            @endif

                            {{-- Instructions --}}
                            @if($selectedAssignment->instructions)
                                <div class="mb-4 text-sm text-on-surface">
                                    <h4 class="mb-2 text-xs font-bold tracking-widest uppercase text-on-surface">{{ __('messages.Instructions') }}</h4>
                                    {!! nl2br(e($selectedAssignment->instructions)) !!}
                                </div>
                            @endif

                            {{-- Attachments --}}
                            @if($selectedAssignment->attachments && $selectedAssignment->attachments->count() > 0)
                                <div class="p-4 mb-4 neo-border-sm neo-radius bg-primary-container/20">
                                    <h4 class="mb-3 text-xs font-bold tracking-widest uppercase text-on-surface">
                                        <i class="fas fa-paperclip ltr:mr-2 rtl:ml-2"></i>
                                        {{ __('messages.Attachments') }} ({{ $selectedAssignment->attachments->count() }})
                                    </h4>
                                    <ul class="space-y-2">
                                        @foreach($selectedAssignment->attachments as $attachment)
                                            <li class="flex items-center justify-between p-3 neo-border-sm neo-radius bg-surface-container-lowest">
                                                <div class="flex items-center">
                                                    <i class="fas fa-file text-secondary ltr:mr-3 rtl:ml-3"></i>
                                                    <div>
                                                        <span class="text-sm font-medium text-on-surface">{{ $attachment->file_name }}</span>
                                                        <span class="text-xs text-secondary ltr:ml-2 rtl:mr-2">({{ number_format($attachment->size / 1024, 1) }} KB)</span>
                                                    </div>
                                                </div>
                                                <a href="{{ $attachment->file_path }}" target="_blank"
                                                    class="px-3 py-1 neo-border-sm neo-radius text-[10px] font-bold text-on-surface bg-surface-container hover:bg-on-surface hover:text-white transition-colors">
                                                    <i class="fas fa-download ltr:mr-1 rtl:ml-1"></i> {{ __('messages.Download') }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            {{-- User Submission --}}
                            @php
                                $userSubmission = $this->getUserSubmission();
                            @endphp

                            @if($userSubmission)
                                <div class="p-4 mb-4 neo-border-sm neo-radius bg-primary-container/20">
                                    <h4 class="mb-3 text-xs font-bold tracking-widest uppercase text-on-surface">
                                        <i class="fas fa-check-circle ltr:mr-2 rtl:ml-2"></i>
                                        {{ __('messages.Your Submission') }}
                                    </h4>
                                    <div class="p-3 neo-border-sm neo-radius bg-surface-container-lowest">
                                        <div class="flex items-center justify-between mb-2">
                                            <div class="text-xs text-secondary">
                                                {{ __('messages.Submitted') }}: {{ $userSubmission->submitted_at->format('Y-m-d H:i') }}
                                            </div>
                                            <span class="px-2 py-1 neo-border-sm neo-radius text-[10px] font-bold {{ $userSubmission->status === 'graded' ? 'bg-primary-container text-on-primary-container' : 'bg-surface-container-high text-on-surface' }}">
                                                {{ ucfirst($userSubmission->status) }}
                                            </span>
                                        </div>
                                        @if($userSubmission->content)
                                            <div class="mb-3 text-sm text-on-surface">
                                                <strong>{{ __('messages.Content') }}:</strong>
                                                <p class="mt-1">{!! nl2br(e($userSubmission->content)) !!}</p>
                                            </div>
                                        @endif
                                        @if($userSubmission->file_path)
                                            <div class="mb-3">
                                                <a href="{{ $userSubmission->file_path }}" target="_blank"
                                                    class="text-xs font-bold text-on-surface hover:underline">
                                                    <i class="fas fa-paperclip ltr:mr-1 rtl:ml-1"></i> {{ __('messages.View Submission File') }}
                                                </a>
                                            </div>
                                        @endif
                                        @if($userSubmission->score !== null)
                                            <div class="pt-3 mt-3 border-t-2 border-on-surface">
                                                <div class="flex items-center justify-between">
                                                    <span class="text-lg font-bold text-on-surface">
                                                        {{ __('messages.Score') }}: {{ $userSubmission->score }}/{{ $selectedAssignment->max_score ?? 100 }}
                                                    </span>
                                                    @if($selectedAssignment->max_score)
                                                        <span class="text-xs font-bold text-secondary">
                                                            ({{ round(($userSubmission->score / $selectedAssignment->max_score) * 100) }}%)
                                                        </span>
                                                    @endif
                                                </div>
                                                @if($userSubmission->feedback)
                                                    <div class="mt-3">
                                                        <strong class="text-xs font-bold tracking-widest uppercase text-on-surface">{{ __('messages.Feedback') }}:</strong>
                                                        <p class="mt-1 text-sm text-on-surface">{!! nl2br(e($userSubmission->feedback)) !!}</p>
                                                    </div>
                                                @endif
                                            </div>
                                        @elseif($userSubmission->status === 'submitted')
                                            <div class="mt-2 text-xs font-bold text-primary-container">
                                                <i class="fas fa-clock ltr:mr-1 rtl:ml-1"></i> {{ __('messages.Pending grading') }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            {{-- Submission Form --}}
                            @if($selectedAssignment->status === 'published' && (!$this->isAssignmentPastDue() || $this->canSubmitLate()))
                                <div class="mt-4">
                                    @if(!$userSubmission)
                                        @if($showSubmissionForm)
                                            <div class="p-4 neo-border-sm neo-radius bg-surface-container-low">
                                                <h4 class="mb-4 text-xs font-bold tracking-widest uppercase text-on-surface">{{ __('messages.Submit Your Work') }}</h4>
                                                <form wire:submit="submitAssignment">
                                                    <div class="mb-4">
                                                        <label class="block mb-2 text-xs font-bold tracking-widest uppercase text-on-surface">{{ __('messages.Content') }}</label>
                                                        <textarea wire:model.lazy="submissionContent" rows="5"
                                                            class="w-full px-3 py-2 text-sm neo-border-sm neo-radius bg-surface-container-lowest text-on-surface placeholder:text-secondary focus:outline-none focus:ring-0"
                                                            placeholder="{{ __('messages.Enter your submission content') }}"></textarea>
                                                        @error('submissionContent') <span class="block mt-1 text-xs font-bold text-error">{{ $message }}</span> @enderror
                                                    </div>
                                                    <div class="mb-4">
                                                        <label class="block mb-2 text-xs font-bold tracking-widest uppercase text-on-surface">{{ __('messages.Files') }}</label>
                                                        <input type="file" wire:model="submissionFiles"
                                                            class="w-full px-3 py-2 text-sm neo-border-sm neo-radius bg-surface-container-lowest text-on-surface file:neo-border-sm file:neo-radius file:bg-surface-container file:text-on-surface file:text-xs file:font-bold file:uppercase file:tracking-widest file:px-3 file:py-1 file:mr-3 file:cursor-pointer focus:outline-none focus:ring-0">
                                                        @error('submissionFiles') <span class="block mt-1 text-xs font-bold text-error">{{ $message }}</span> @enderror
                                                        @if($submissionFiles)
                                                            <div class="mt-2 text-xs font-bold text-secondary">
                                                                {{ $submissionFiles->getClientOriginalName() }} {{ __('messages.selected') }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="flex gap-3">
                                                        <button type="submit"
                                                            class="px-4 py-2 text-xs font-bold tracking-widest uppercase transition-colors neo-border neo-radius bg-primary-container text-on-primary-container hover:bg-on-surface hover:text-white">
                                                            {{ __('messages.Submit Assignment') }}
                                                        </button>
                                                        <button type="button" wire:click="toggleSubmissionForm"
                                                            class="px-4 py-2 text-xs font-bold transition-colors neo-border-sm neo-radius text-on-surface bg-surface-container hover:bg-on-surface hover:text-white">
                                                            {{ __('messages.Cancel') }}
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        @else
                                            <button wire:click="toggleSubmissionForm"
                                                class="w-full px-4 py-3 text-xs font-bold tracking-widest uppercase transition-colors neo-border neo-radius bg-primary-container text-on-primary-container hover:bg-on-surface hover:text-white">
                                                <i class="fas fa-upload ltr:mr-2 rtl:ml-2"></i>
                                                {{ __('messages.Submit Assignment') }}
                                            </button>
                                        @endif
                                    @endif
                                </div>
                            @elseif($this->isAssignmentPastDue() && !$this->canSubmitLate() && !$userSubmission)
                                <div class="p-4 mt-4 neo-border-sm neo-radius bg-error/10">
                                    <p class="text-xs font-bold text-error">
                                        <i class="fas fa-exclamation-triangle ltr:mr-2 rtl:ml-2"></i>
                                        {{ __('messages.Submission deadline has passed. Late submissions are not allowed for this assignment.') }}
                                    </p>
                                </div>
                            @endif
                        </div>
                    @elseif($selectedLesson)
                        @if($selectedLesson->type === 'video' && $selectedLesson->video_url)
                            @if($selectedLesson->is_youtube_url)
                                <div class="aspect-video bg-on-surface" wire:ignore.self>
                                    <iframe class="w-full h-full" src="{{ $selectedLesson->youtube_embed_url }}"
                                        frameborder="0"
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                        allowfullscreen>
                                    </iframe>
                                </div>
                            @else
                                <div class="aspect-video bg-on-surface" wire:ignore.self>
                                    <video class="w-full h-full js-lesson-video" data-lesson-id="{{ $selectedLesson->id }}" playsinline controls preload="metadata">
                                        <source src="{{ $selectedLesson->video_url }}" type="video/mp4" />
                                    </video>
                                </div>
                            @endif
                            <div wire:loading.delay class="hidden"></div>
                        @elseif($selectedLesson->type === 'video')
                            <div class="flex items-center justify-center bg-on-surface aspect-video">
                                <div class="text-center">
                                    <i class="mb-4 text-5xl fas fa-play-circle text-white/50"></i>
                                    <p class="text-lg font-bold text-white">{{ $selectedLesson->title }}</p>
                                    <p class="mt-2 text-xs text-white/50">{{ __('messages.Video content - no video assigned yet') }}</p>
                                </div>
                            </div>
                        @else
                            <div class="flex items-center justify-center bg-on-surface aspect-video">
                                <div class="text-center">
                                    <i class="mb-4 text-5xl fas fa-book-open text-white/50"></i>
                                    <p class="text-lg font-bold text-white">{{ $selectedLesson->title }}</p>
                                    <p class="mt-2 text-xs text-white/50">
                                        @if($selectedLesson->type === 'text')
                                            {{ __('messages.Text Lesson') }}
                                        @elseif($selectedLesson->type === 'quiz')
                                            {{ __('messages.Quiz') }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                        @endif

                        {{-- Lesson Info --}}
                        <div class="p-[24px]">
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <h3 class="text-sm font-bold text-on-surface">{{ $selectedLesson->title }}</h3>
                                    <p class="text-xs text-secondary">{{ $selectedLesson->section->title ?? __('messages.Section') }}</p>
                                </div>
                                @if(!$this->isLessonCompleted($selectedLesson->id))
                                    <button wire:click="markLessonComplete"
                                        class="px-4 py-2 text-xs font-bold tracking-widest uppercase transition-colors neo-border neo-radius bg-primary-container text-on-primary-container hover:bg-on-surface hover:text-white">
                                        <i class="fas fa-check ltr:mr-2 rtl:ml-2"></i>
                                        {{ __('messages.Mark Complete') }}
                                    </button>
                                @else
                                    <span class="inline-flex items-center gap-2">
                                        <span class="inline-flex items-center px-3 py-1 text-xs font-bold neo-border-sm neo-radius bg-surface-container-high text-on-surface">
                                            <i class="fas fa-check-circle ltr:mr-1 rtl:ml-1"></i>
                                            {{ __('messages.Completed') }}
                                        </span>
                                        @if($this->isCourseCompleted())
                                            <a href="{{ route('tenant.student.certificate.download', $course->slug) }}" target="_blank"
                                               class="inline-flex items-center gap-2 px-3 py-1 text-xs font-bold uppercase transition-all neo-border neo-radius bg-primary-container text-on-primary-container hover:bg-on-surface hover:text-white">
                                                <i class="fas fa-certificate"></i>
                                                {{ __('messages.Get Certificate') }}
                                            </a>
                                        @endif
                                    </span>
                                @endif
                            </div>

                            @php
                                $isContainEnglish = preg_match('/[a-zA-Z]/', substr($selectedLesson->content, 0, 100));
                            @endphp

                            @if($selectedLesson->type === 'text' || ($selectedLesson->type === 'video' && $selectedLesson->content))
                                <div class="text-sm text-on-surface {{ $isContainEnglish ? 'text-left' : 'text-right' }}">
                                    {!! nl2br($selectedLesson->content) !!}
                                </div>
                            @elseif($selectedLesson->type === 'quiz')
                                <div class="p-4 neo-border-sm neo-radius bg-surface-container-high">
                                    <p class="text-xs font-bold text-on-surface">
                                        <i class="fas fa-question-circle ltr:mr-2 rtl:ml-2"></i>
                                        {{ __('messages.Take the quiz to test your knowledge.') }}
                                    </p>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="flex items-center justify-center bg-on-surface aspect-video">
                            <div class="text-center">
                                <i class="mb-4 text-5xl fas fa-book-open text-white/50"></i>
                                <p class="text-lg font-bold text-white">{{ __('messages.Select a lesson from the sidebar') }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Table of Contents Sidebar --}}
            <div class="lg:col-span-1">
                <div class="sticky top-[90px] overflow-hidden bg-surface-container-lowest neo-border neo-radius">
                    <div class="p-4 border-b-2 border-on-surface bg-surface-container-low">
                        <h3 class="text-xs font-bold tracking-widest uppercase text-on-surface">
                            <i class="fas fa-list ltr:mr-2 rtl:ml-2"></i>
                            {{ __('messages.Course Content') }}
                        </h3>
                    </div>

                    <div class="max-h-[70vh] overflow-y-auto no-scrollbar">
                        @forelse($course->sections as $section)
                            <div class="border-b border-on-surface/10">
                                <div wire:click="toggleSection({{ $section->id }})"
                                    class="flex items-center justify-between p-4 transition-colors cursor-pointer bg-surface-container-low hover:bg-surface-container">
                                    <div class="flex items-center">
                                        @if(app()->getLocale() == 'ar')
                                            <i class="fas fa-chevron-left text-secondary text-xs transition-transform ltr:mr-2 rtl:ml-2 {{ $this->isSectionExpanded($section->id) ? '-rotate-90' : '' }}"></i>
                                        @else
                                            <i class="fas fa-chevron-right fa-chevron-left text-secondary text-xs transition-transform ltr:mr-2 rtl:ml-2 {{ $this->isSectionExpanded($section->id) ? 'rotate-90' : '' }}"></i>
                                        @endif
                                        <span class="text-xs font-bold text-on-surface">{{ $section->title }}</span>
                                    </div>
                                    <span class="text-[10px] font-bold uppercase tracking-widest text-secondary">{{ $section->lessons->count() }}</span>
                                </div>

                                @if($this->isSectionExpanded($section->id))
                                    <div class="divide-y divide-on-surface/5">
                                        @foreach($section->lessons as $lesson)
                                            <div wire:click="selectLesson({{ $lesson->id }})"
                                                class="p-3 transition-colors cursor-pointer hover:bg-surface-container-high {{ $selectedLesson && $selectedLesson->id === $lesson->id ? 'bg-primary-container' : '' }}">
                                                <div class="flex items-center justify-between">
                                                    <div class="flex items-center">
                                                        @if($this->isLessonCompleted($lesson->id))
                                                            <i class="text-xs fas fa-check-circle text-on-primary-container ltr:mr-3 rtl:ml-3"></i>
                                                        @elseif($lesson->type === 'video')
                                                            <i class="text-xs fas fa-play-circle text-on-primary-container ltr:mr-3 rtl:ml-3"></i>
                                                        @elseif($lesson->type === 'text')
                                                            <i class="text-xs fas fa-file-alt text-on-primary-container ltr:mr-3 rtl:ml-3"></i>
                                                        @else
                                                            <i class="text-xs fas fa-file text-on-primary-container ltr:mr-3 rtl:ml-3"></i>
                                                        @endif
                                                        <span class="text-xs {{ $selectedLesson && $selectedLesson->id === $lesson->id ? 'font-bold text-on-primary-container' : 'text-on-surface' }}">
                                                            {{ $lesson->title }}
                                                        </span>
                                                    </div>
                                                    @if($lesson->duration_seconds)
                                                        <span class="text-[10px] text-on-primary-container font-bold">{{ gmdate('i:s', $lesson->duration_seconds) }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach

                                        @if($section->assignments && $section->assignments->count() > 0)
                                            <div class="border-t border-on-surface/10">
                                                <div class="px-3 py-2 text-[10px] font-bold uppercase tracking-widest text-secondary bg-surface-container-low">{{ __('messages.Assignments') }}</div>
                                                @foreach($section->assignments as $assignment)
                                                    <div wire:click="selectAssignment({{ $assignment->id }})"
                                                        class="p-3 transition-colors cursor-pointer hover:bg-surface-container-high {{ $selectedAssignment && $selectedAssignment->id === $assignment->id ? 'bg-primary-container' : '' }}">
                                                        <div class="flex items-center justify-between">
                                                            <div class="flex items-center">
                                                                <i class="text-xs fas fa-tasks text-on-primary-container ltr:mr-3 rtl:ml-3"></i>
                                                                <span class="text-xs {{ $selectedAssignment && $selectedAssignment->id === $assignment->id ? 'font-bold text-on-primary-container' : 'text-on-surface' }}">
                                                                    {{ $assignment->title }}
                                                                </span>
                                                            </div>
                                                            @if($assignment->due_date)
                                                                <span class="text-[10px] text-secondary font-bold">{{ $assignment->due_date->format('M d') }}</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif

                                        @if($section->quiz)
                                            <div class="border-t border-on-surface/10">
                                                <div class="px-3 py-2 text-[10px] font-bold uppercase tracking-widest text-secondary bg-surface-container-low">{{ __('messages.Quiz') }}</div>
                                                <a href="{{ route('tenant.student.quiz', $section->quiz->id) }}"
                                                    wire:navigate
                                                    class="flex items-center justify-between p-3 transition-colors cursor-pointer hover:bg-surface-container-high">
                                                    <div class="flex items-center">
                                                        <i class="text-xs fas fa-clipboard-list text-on-primary-container ltr:mr-3 rtl:ml-3"></i>
                                                        <span class="text-xs text-on-surface">{{ $section->quiz->title }}</span>
                                                    </div>
                                                    <span class="text-[10px] text-secondary font-bold">{{ $section->quiz->questions->count() }} {{ __('messages.questions') }}</span>
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        @empty
                            <div class="p-6 text-xs text-center text-secondary">
                                <p>{{ __('messages.No content available yet.') }}</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
