<div>
    <header class="h-16 flex justify-between items-center px-[24px] bg-surface-container-lowest border-b-2 border-on-surface sticky top-0 z-40">
        <div>
            <h2 class="text-[24px] font-bold text-on-surface leading-none tracking-[0.08em]">{{ __('messages.Browse Courses') }}</h2>
        <p class="text-[12px] font-medium uppercase text-secondary mt-0.5 tracking-wider">{{ __('messages.Discover new courses and continue learning') }}</p>
    </div>
    <div class="flex items-center gap-2">
        @livewire('shared.notification-bell')
    </div>
</header>

    <div class="p-[24px] max-w-[1400px] mx-auto">
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            {{-- Course List Sidebar --}}
            <div class="lg:col-span-1 space-y-6">
                {{-- Enrolled Courses Section --}}
                @php
                    $enrolledCourses = \App\Models\Tenant\Enrollment::where('user_id', auth()->id())
                        ->with('course.instructor', 'course.sections.lessons')
                        ->get()
                        ->filter(fn($e) => $e->course && $e->course->status === 'published')
                        ->pluck('course');
                @endphp

                @if($enrolledCourses->count() > 0)
                    <div class="bg-surface-container-lowest neo-border neo-radius overflow-hidden">
                        <div class="p-4 border-b-2 border-on-surface">
                            <h3 class="text-xs font-bold uppercase tracking-widest text-on-surface">{{ __('messages.My Enrolled Courses') }}</h3>
                        </div>
                        <div class="divide-y divide-[#E5E5E5]">
                            @foreach($enrolledCourses as $course)
                                <div wire:click="selectCourse({{ $course->id }})"
                                    class="p-4 cursor-pointer hover:bg-surface-container-high transition-colors duration-100"
                                    style="border-left: 4px solid {{ $selectedCourse == $course->id ? '#FFD600' : 'transparent' }}">
                                    <h4 class="font-bold text-sm text-on-surface">{{ $course->title }}</h4>
                                    <p class="mt-1 text-xs text-secondary">{{ $course->instructor->name ?? 'N/A' }}</p>
                                    <div class="flex items-center gap-3 mt-2 text-[10px] text-secondary font-bold uppercase tracking-widest">
                                        <span><i class="fas fa-folder mr-1"></i> {{ $course->sections->count() }} {{ __('messages.sections') }}</span>
                                        <span><i class="fas fa-book mr-1"></i> {{ $course->sections->sum(fn($s) => $s->lessons->count()) }} {{ __('messages.lessons') }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Available Courses Section --}}
                <div class="bg-surface-container-lowest neo-border neo-radius overflow-hidden">
                    <div class="p-4 border-b-2 border-on-surface">
                        <h3 class="text-xs font-bold uppercase tracking-widest text-on-surface">{{ __('messages.Available Courses') }}</h3>
                    </div>
                    <div class="divide-y divide-[#E5E5E5]">
                        @forelse($courses as $course)
                            <div wire:click="selectCourse({{ $course->id }})"
                                class="p-4 cursor-pointer hover:bg-surface-container-high transition-colors duration-100"
                                style="border-left: 4px solid {{ $selectedCourse == $course->id ? '#FFD600' : 'transparent' }}">
                                <h4 class="font-bold text-sm text-on-surface">{{ $course->title }}</h4>
                                <p class="mt-1 text-xs text-secondary">{{ $course->instructor->name ?? 'N/A' }}</p>
                                <div class="flex items-center gap-3 mt-2 text-[10px] text-secondary font-bold uppercase tracking-widest">
                                    <span><i class="fas fa-folder mr-1"></i> {{ $course->sections->count() }} {{ __('messages.sections') }}</span>
                                    <span><i class="fas fa-book mr-1"></i> {{ $course->sections->sum(fn($s) => $s->lessons->count()) }} {{ __('messages.lessons') }}</span>
                                </div>
                            </div>
                        @empty
                            <div class="p-8 text-center">
                                <i class="fas fa-book-open text-4xl text-secondary mb-2"></i>
                                <p class="text-sm text-secondary">{{ __('messages.No courses found.') }}</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Course Content Area --}}
            <div class="lg:col-span-2">
                @if($selectedCourse && $selectedCourseData)
                    <div class="bg-surface-container-lowest neo-border neo-radius overflow-hidden">
                        {{-- Course Header --}}
                        <div class="p-[24px] border-b-2 border-on-surface">
                            <h3 class="text-[24px] font-bold text-on-surface leading-tight tracking-[0.02em]">{{ $selectedCourseData->title }}</h3>
                            <p class="mt-2 text-sm text-secondary">{{ __('messages.By') }} {{ $selectedCourseData->instructor->name ?? 'N/A' }}</p>
                            <div class="flex items-center gap-4 mt-4 text-xs text-secondary font-bold uppercase tracking-widest">
                                <span><i class="fas fa-dollar-sign mr-1"></i> ${{ number_format($selectedCourseData->price, 2) }}</span>
                                <span><i class="fas fa-folder mr-1"></i> {{ __('messages.sections') }} {{ $selectedCourseData->sections->count() }}</span>
                            </div>
                            @if($selectedCourseData->description)
                                <p class="mt-4 text-sm text-on-surface">{{ $selectedCourseData->description }}</p>
                            @endif

                            {{-- Enroll Button --}}
                            @if(!$this->isEnrolled($selectedCourseData->id))
                                <div class="mt-6">
                                    <button wire:click="enrollInCourse({{ $selectedCourseData->id }})"
                                        class="px-5 py-2 neo-border neo-radius bg-primary-container text-on-surface font-bold uppercase text-xs tracking-widest hover:bg-on-surface hover:text-white transition-colors">
                                        <i class="fas fa-graduation-cap mr-2"></i>
                                        @if($selectedCourseData->price == 0)
                                            {{ __('messages.Enroll for Free') }}
                                        @else
                                            {{ __('messages.Enroll Now') }} - ${{ number_format($selectedCourseData->price, 2) }}
                                        @endif
                                    </button>
                                </div>
                            @else
                                <div class="mt-6">
                                    <span class="inline-flex items-center px-3 py-1.5 neo-border-sm neo-radius text-xs font-bold bg-surface-container-high text-on-surface">
                                        <i class="fas fa-check-circle mr-2"></i>
                                        {{ __('messages.Enrolled') }}
                                    </span>
                                </div>
                            @endif
                        </div>

                        {{-- Curriculum --}}
                        <div class="p-[24px]">
                            <h4 class="text-sm font-bold uppercase tracking-widest text-on-surface mb-4">{{ __('messages.Course Curriculum') }}</h4>

                            @forelse($selectedCourseData->sections->sortBy('order') as $section)
                                <div class="mb-3 neo-border neo-radius overflow-hidden">
                                    {{-- Section Header --}}
                                    <div wire:click="toggleSection({{ $section->id }})"
                                        class="flex items-center justify-between p-4 bg-surface-container-low cursor-pointer hover:bg-surface-container transition-colors">
                                        <div class="flex items-center gap-3">
                                            <i class="fas fa-chevron-right text-secondary text-xs transition-transform {{ $this->isSectionExpanded($section->id) ? 'rotate-90' : '' }}"></i>
                                            <span class="font-bold text-sm text-on-surface">{{ $section->title }}</span>
                                        </div>
                                        <span class="text-xs text-secondary font-bold uppercase tracking-widest">{{ $section->lessons->count() }} {{ __('messages.lessons') }}</span>
                                    </div>

                                    {{-- Lessons List --}}
                                    @if($this->isSectionExpanded($section->id))
                                        <div class="divide-y divide-[#E5E5E5]">
                                            @forelse($section->lessons->sortBy('order') as $lesson)
                                                <div class="p-4 hover:bg-surface-container-high transition-colors">
                                                    <div class="flex items-center justify-between">
                                                        <div class="flex items-center gap-3">
                                                            <i class="fas {{ $lesson->type === 'video' ? 'fa-play-circle text-on-surface' : ($lesson->type === 'text' ? 'fa-file-alt text-on-surface' : 'fa-list-check text-on-surface') }}"></i>
                                                            <span class="text-sm text-on-surface">{{ $lesson->title }}</span>
                                                        </div>
                                                        @if($lesson->duration_seconds)
                                                            <span class="text-xs text-secondary font-bold">{{ gmdate('i:s', $lesson->duration_seconds) }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            @empty
                                                <div class="p-4 text-sm text-center text-secondary">
                                                    {{ __('messages.No sections available yet.') }}
                                                </div>
                                            @endforelse

                                            {{-- Quiz if exists --}}
                                            @if($section->quiz)
                                                <div class="p-4 bg-primary-container/20 neo-border-sm neo-radius m-2">
                                                    <div class="flex items-center justify-between">
                                                        <div class="flex items-center gap-3">
                                                            <i class="fas fa-question-circle text-on-surface"></i>
                                                            <span class="font-bold text-sm text-on-surface">{{ $section->quiz->title }}</span>
                                                            <span class="px-2 py-0.5 neo-border-sm neo-radius text-[10px] font-bold bg-surface-container-high text-on-surface">{{ $section->quiz->questions->count() }} {{ __('messages.questions') }}</span>
                                                        </div>
                                                        <span class="text-xs font-bold text-on-surface">{{ __('messages.Pass') }}: {{ $section->quiz->pass_percentage }}%</span>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            @empty
                                <div class="py-8 text-center">
                                    <i class="fas fa-folder-open text-4xl text-secondary mb-2"></i>
                                    <p class="text-sm text-secondary">{{ __('messages.No sections available yet.') }}</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                @else
                    <div class="bg-surface-container-lowest neo-border neo-radius">
                        <div class="p-12 text-center">
                            <i class="fas fa-book-open text-6xl text-secondary mb-4"></i>
                            <h3 class="text-sm font-bold uppercase tracking-widest text-on-surface">{{ __('messages.Select a Course') }}</h3>
                            <p class="mt-2 text-sm text-secondary">{{ __('messages.Discover new courses and continue learning') }}</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>