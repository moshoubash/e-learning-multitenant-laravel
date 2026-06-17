<div>
    <header class="h-16 flex justify-between items-center px-[24px] bg-surface-container-lowest border-b-2 border-on-surface sticky top-0 z-40">
        <div class="flex items-center gap-4">
            <a href="{{ route('tenant.student.courses') }}" class="text-secondary hover:text-on-surface transition-colors">
                <i class="fas fa-arrow-left text-lg"></i>
            </a>
            <h2 class="text-[24px] font-bold text-on-surface leading-none tracking-[0.08em]">{{ $course->title ?? __('messages.Course') }}</h2>
        </div>
        <div class="flex items-center gap-3">
            <span class="text-[10px] font-bold uppercase tracking-widest text-secondary">{{ __('messages.Progress') }}: {{ $progressPercent }}%</span>
            <div class="w-32 h-2 neo-border-sm neo-radius bg-surface-container overflow-hidden">
                <div class="h-full bg-on-surface neo-radius transition-all duration-300" style="width: {{ $progressPercent }}%"></div>
            </div>
        </div>
    </header>

    <div class="p-[24px] max-w-[1400px] mx-auto">
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            {{-- Video/Content Area --}}
            <div class="lg:col-span-2">
                <div class="bg-surface-container-lowest neo-border neo-radius overflow-hidden">
                    @if($selectedAssignment)
                        <div class="p-[24px]">
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <h3 class="text-sm font-bold text-on-surface">{{ $selectedAssignment->title }}</h3>
                                    <p class="text-xs text-secondary">{{ $selectedAssignment->section->title ?? __('messages.Section') }}</p>
                                </div>
                                <button wire:click="$set('selectedAssignment', null)"
                                    class="px-4 py-2 neo-border-sm neo-radius text-xs font-bold text-on-surface bg-surface-container hover:bg-on-surface hover:text-white transition-colors">
                                    <i class="fas fa-arrow-left ltr:mr-2 rtl:ml-2"></i>
                                    {{ __('messages.Back') }}
                                </button>
                            </div>

                            {{-- Assignment Status Info --}}
                            <div class="p-4 neo-border-sm neo-radius bg-surface-container-low mb-4">
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
                                    <h4 class="text-xs font-bold uppercase tracking-widest text-on-surface mb-2">{{ __('messages.Description') }}</h4>
                                    {!! nl2br(e($selectedAssignment->description)) !!}
                                </div>
                            @endif

                            {{-- Instructions --}}
                            @if($selectedAssignment->instructions)
                                <div class="mb-4 text-sm text-on-surface">
                                    <h4 class="text-xs font-bold uppercase tracking-widest text-on-surface mb-2">{{ __('messages.Instructions') }}</h4>
                                    {!! nl2br(e($selectedAssignment->instructions)) !!}
                                </div>
                            @endif

                            {{-- Attachments --}}
                            @if($selectedAssignment->attachments && $selectedAssignment->attachments->count() > 0)
                                <div class="p-4 neo-border-sm neo-radius bg-primary-container/20 mb-4">
                                    <h4 class="text-xs font-bold uppercase tracking-widest text-on-surface mb-3">
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
                                <div class="p-4 neo-border-sm neo-radius bg-primary-container/20 mb-4">
                                    <h4 class="text-xs font-bold uppercase tracking-widest text-on-surface mb-3">
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
                                                        <span class="text-xs text-secondary font-bold">
                                                            ({{ round(($userSubmission->score / $selectedAssignment->max_score) * 100) }}%)
                                                        </span>
                                                    @endif
                                                </div>
                                                @if($userSubmission->feedback)
                                                    <div class="mt-3">
                                                        <strong class="text-xs font-bold uppercase tracking-widest text-on-surface">{{ __('messages.Feedback') }}:</strong>
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
                                                <h4 class="text-xs font-bold uppercase tracking-widest text-on-surface mb-4">{{ __('messages.Submit Your Work') }}</h4>
                                                <form wire:submit="submitAssignment">
                                                    <div class="mb-4">
                                                        <label class="block mb-2 text-xs font-bold uppercase tracking-widest text-on-surface">{{ __('messages.Content') }}</label>
                                                        <textarea wire:model.lazy="submissionContent" rows="5"
                                                            class="w-full px-3 py-2 neo-border-sm neo-radius bg-surface-container-lowest text-on-surface text-sm placeholder:text-secondary focus:outline-none focus:ring-0"
                                                            placeholder="{{ __('messages.Enter your submission content') }}"></textarea>
                                                        @error('submissionContent') <span class="text-xs text-error mt-1 block font-bold">{{ $message }}</span> @enderror
                                                    </div>
                                                    <div class="mb-4">
                                                        <label class="block mb-2 text-xs font-bold uppercase tracking-widest text-on-surface">{{ __('messages.Files') }}</label>
                                                        <input type="file" wire:model="submissionFiles" multiple
                                                            class="w-full px-3 py-2 neo-border-sm neo-radius bg-surface-container-lowest text-on-surface text-sm file:neo-border-sm file:neo-radius file:bg-surface-container file:text-on-surface file:text-xs file:font-bold file:uppercase file:tracking-widest file:px-3 file:py-1 file:mr-3 file:cursor-pointer focus:outline-none focus:ring-0">
                                                        @error('submissionFiles.*') <span class="text-xs text-error mt-1 block font-bold">{{ $message }}</span> @enderror
                                                        @if(count($submissionFiles) > 0)
                                                            <div class="mt-2 text-xs text-secondary font-bold">
                                                                {{ count($submissionFiles) }} {{ __('messages.file(s) selected') }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="flex gap-3">
                                                        <button type="submit"
                                                            class="px-4 py-2 neo-border neo-radius bg-primary-container text-on-surface text-xs font-bold uppercase tracking-widest hover:bg-on-surface hover:text-white transition-colors">
                                                            {{ __('messages.Submit Assignment') }}
                                                        </button>
                                                        <button type="button" wire:click="toggleSubmissionForm"
                                                            class="px-4 py-2 neo-border-sm neo-radius text-xs font-bold text-on-surface bg-surface-container hover:bg-on-surface hover:text-white transition-colors">
                                                            {{ __('messages.Cancel') }}
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        @else
                                            <button wire:click="toggleSubmissionForm"
                                                class="w-full px-4 py-3 neo-border neo-radius bg-primary-container text-on-surface text-xs font-bold uppercase tracking-widest hover:bg-on-surface hover:text-white transition-colors">
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
                            <div class="aspect-video bg-on-surface" wire:ignore.self>
                                <video class="js-lesson-video w-full h-full" data-lesson-id="{{ $selectedLesson->id }}" playsinline controls preload="metadata">
                                    <source src="{{ $selectedLesson->video_url }}" type="video/mp4" />
                                </video>
                            </div>
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
                                        class="px-4 py-2 neo-border neo-radius bg-primary-container text-on-surface text-xs font-bold uppercase tracking-widest hover:bg-on-surface hover:text-white transition-colors">
                                        <i class="fas fa-check ltr:mr-2 rtl:ml-2"></i>
                                        {{ __('messages.Mark Complete') }}
                                    </button>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 neo-border-sm neo-radius bg-surface-container-high text-on-surface text-xs font-bold">
                                        <i class="fas fa-check-circle ltr:mr-1 rtl:ml-1"></i>
                                        {{ __('messages.Completed') }}
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
                <div class="sticky top-[100px] bg-surface-container-lowest neo-border neo-radius overflow-hidden">
                    <div class="p-4 border-b-2 border-on-surface bg-surface-container-low">
                        <h3 class="text-xs font-bold uppercase tracking-widest text-on-surface">
                            <i class="fas fa-list ltr:mr-2 rtl:ml-2"></i>
                            {{ __('messages.Course Content') }}
                        </h3>
                    </div>

                    <div class="max-h-[70vh] overflow-y-auto no-scrollbar">
                        @forelse($course->sections as $section)
                            <div class="border-b border-on-surface/10">
                                <div wire:click="toggleSection({{ $section->id }})"
                                    class="flex items-center justify-between p-4 cursor-pointer bg-surface-container-low hover:bg-surface-container transition-colors">
                                    <div class="flex items-center">
                                        <i class="fas fa-chevron-right text-secondary text-xs transition-transform ltr:mr-2 rtl:ml-2 {{ $this->isSectionExpanded($section->id) ? 'rotate-90' : '' }}"></i>
                                        <span class="text-xs font-bold text-on-surface">{{ $section->title }}</span>
                                    </div>
                                    <span class="text-[10px] font-bold uppercase tracking-widest text-secondary">{{ $section->lessons->count() }}</span>
                                </div>

                                @if($this->isSectionExpanded($section->id))
                                    <div class="divide-y divide-on-surface/5">
                                        @foreach($section->lessons as $lesson)
                                            <div wire:click="selectLesson({{ $lesson->id }})"
                                                class="p-3 cursor-pointer hover:bg-surface-container-high transition-colors"
                                                style="{{ $selectedLesson && $selectedLesson->id === $lesson->id ? 'background-color: #FFD600; border-left: 4px solid #0A0A0A;' : '' }}">
                                                <div class="flex items-center justify-between">
                                                    <div class="flex items-center">
                                                        @if($this->isLessonCompleted($lesson->id))
                                                            <i class="fas fa-check-circle text-on-surface ltr:mr-3 rtl:ml-3 text-xs"></i>
                                                        @elseif($lesson->type === 'video')
                                                            <i class="fas fa-play-circle text-secondary ltr:mr-3 rtl:ml-3 text-xs"></i>
                                                        @elseif($lesson->type === 'text')
                                                            <i class="fas fa-file-alt text-secondary ltr:mr-3 rtl:ml-3 text-xs"></i>
                                                        @else
                                                            <i class="fas fa-file text-secondary ltr:mr-3 rtl:ml-3 text-xs"></i>
                                                        @endif
                                                        <span class="text-xs {{ $selectedLesson && $selectedLesson->id === $lesson->id ? 'font-bold text-on-surface' : 'text-on-surface' }}">
                                                            {{ $lesson->title }}
                                                        </span>
                                                    </div>
                                                    @if($lesson->duration_seconds)
                                                        <span class="text-[10px] text-secondary font-bold">{{ gmdate('i:s', $lesson->duration_seconds) }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach

                                        @if($section->assignments && $section->assignments->count() > 0)
                                            <div class="pt-3 mt-3 border-t border-on-surface/10 px-3 pb-2">
                                                <div class="text-[10px] font-bold uppercase tracking-widest text-secondary mb-2">{{ __('messages.Assignments') }}</div>
                                                @foreach($section->assignments as $assignment)
                                                    <div wire:click="selectAssignment({{ $assignment->id }})"
                                                        class="p-3 cursor-pointer hover:bg-surface-container-high transition-colors {{ $selectedAssignment && $selectedAssignment->id === $assignment->id ? 'bg-primary-container' : '' }}">
                                                        <div class="flex items-center justify-between">
                                                            <div class="flex items-center gap-2">
                                                                <i class="fas fa-tasks text-secondary text-xs"></i>
                                                                <span class="text-xs text-on-surface">{{ $assignment->title }}</span>
                                                            </div>
                                                            <span class="text-[10px] text-secondary text-xs">{{ $assignment->order }}</span>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif

                                        @if($section->quiz)
                                            <div class="p-3 neo-border-sm neo-radius bg-primary-container/20 m-2">
                                                <a href="{{ route('tenant.student.quiz', $section->quiz->id) }}"
                                                    class="flex items-center justify-between">
                                                    <div class="flex items-center">
                                                        <i class="fas fa-clipboard-list text-on-surface ltr:mr-3 rtl:ml-3 text-xs"></i>
                                                        <span class="text-xs font-bold text-on-surface">{{ $section->quiz->title }}</span>
                                                    </div>
                                                    <i class="fas fa-external-link-alt text-secondary text-[10px]"></i>
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        @empty
                            <div class="p-6 text-center text-xs text-secondary">
                                <p>{{ __('messages.No content available yet.') }}</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>