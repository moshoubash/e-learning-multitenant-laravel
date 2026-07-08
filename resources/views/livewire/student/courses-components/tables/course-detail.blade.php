@if($selectedCourse && $selectedCourseData)
    <div class="overflow-hidden bg-surface-container-lowest neo-border neo-radius">
        {{-- Course Header --}}
        <div class="p-[24px] border-b-2 border-on-surface">
            <div class="flex items-start gap-4">
                <div class="shrink-0">
                    @if ($selectedCourseData->thumbnail)
                        <img src="{{ $selectedCourseData->thumbnail }}" class="object-cover h-20 w-28 neo-border-sm neo-radius" alt="">
                    @else
                        <div class="flex items-center justify-center h-20 w-28 neo-border-sm neo-radius bg-surface-container text-secondary">
                            <i class="text-2xl fas fa-book"></i>
                        </div>
                    @endif
                </div>
                <div>
                    <h3 class="text-[24px] font-bold text-on-surface leading-tight tracking-[0.02em]">{{ $selectedCourseData->title }}</h3>
                    <p class="mt-2 text-sm text-secondary">{{ __('messages.By') }} {{ $selectedCourseData->instructor->name ?? 'N/A' }}</p>
                </div>
            </div>
            <div class="flex items-center gap-4 mt-4 text-xs font-bold tracking-widest uppercase text-secondary">
                <span><i class="mr-1 fas fa-dollar-sign"></i> ${{ number_format($selectedCourseData->price, 2) }}</span>
                <span><i class="mr-1 fas fa-folder"></i> {{ __('messages.sections') }} {{ $selectedCourseData->sections->count() }}</span>
            </div>
            @if($selectedCourseData->description)
                <p class="mt-4 text-sm text-on-surface">{{ $selectedCourseData->description }}</p>
            @endif

            {{-- Enroll Button --}}
            @if(!$this->isEnrolled($selectedCourseData->id))
                <div class="mt-6">
                    <button wire:click="enrollInCourse({{ $selectedCourseData->id }})"
                        class="px-4 py-2 text-xs font-bold tracking-widest uppercase transition-colors neo-border neo-radius bg-primary-container text-on-primary-container hover:bg-on-surface hover:text-white">
                        <i class="mr-2 fas fa-graduation-cap"></i>
                        @if($selectedCourseData->price == 0)
                            {{ __('messages.Enroll Now') }}
                        @else
                            {{ __('messages.Enroll Now') }} - ${{ number_format($selectedCourseData->price, 2) }}
                        @endif
                    </button>
                </div>
            @else
                <div class="mt-6">
                    <span class="inline-flex items-center px-3 py-1.5 neo-border-sm neo-radius text-xs font-bold bg-surface-container-high text-on-surface">
                        <i class="mr-2 fas fa-check-circle"></i>
                        {{ __('messages.Enrolled') }}
                    </span>
                </div>
            @endif
        </div>

        {{-- Curriculum --}}
        <div class="p-[24px]">
            <h4 class="mb-4 text-sm font-bold tracking-widest uppercase text-on-surface">{{ __('messages.Course Curriculum') }}</h4>

            @forelse($selectedCourseData->sections->sortBy('order') as $section)
                <div class="mb-3 overflow-hidden neo-border neo-radius">
                    {{-- Section Header --}}
                    <div wire:click="toggleSection({{ $section->id }})"
                        class="flex items-center justify-between p-4 transition-colors cursor-pointer bg-surface-container-low hover:bg-surface-container">
                        <div class="flex items-center gap-3">
                            @if(app()->getLocale() == 'ar')
                                <i class="fas fa-chevron-left text-secondary text-xs transition-transform {{ $this->isSectionExpanded($section->id) ? 'rotate-90' : '' }}"></i>
                            @else
                                <i class="fas fa-chevron-right text-secondary text-xs transition-transform {{ $this->isSectionExpanded($section->id) ? 'rotate-90' : '' }}"></i>
                            @endif
                            <span class="text-sm font-bold text-on-surface">{{ $section->title }}</span>
                        </div>
                        <span class="text-xs font-bold tracking-widest uppercase text-secondary">{{ $section->lessons->count() }} {{ __('messages.lessons') }}</span>
                    </div>

                    {{-- Lessons List --}}
                    @if($this->isSectionExpanded($section->id))
                        <div class="divide-y divide-[#E5E5E5]">
                            @forelse($section->lessons->sortBy('order') as $lesson)
                                <div class="p-4 transition-colors hover:bg-surface-container-high">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-3">
                                            <i class="fas {{ $lesson->type === 'video' ? 'fa-play-circle text-on-surface' : ($lesson->type === 'text' ? 'fa-file-alt text-on-surface' : 'fa-list-check text-on-surface') }}"></i>
                                            <span class="text-sm text-on-surface" dir="auto">{{ $lesson->title }}</span>
                                        </div>
                                        @if($lesson->duration_seconds)
                                            <span class="text-xs font-bold text-secondary">{{ gmdate('i:s', $lesson->duration_seconds) }}</span>
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
                                <div class="p-4 m-2 bg-primary-container/20 neo-border-sm neo-radius">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-3">
                                            <i class="fas fa-question-circle text-on-surface"></i>
                                            <span class="text-sm font-bold text-on-surface">{{ $section->quiz->title }}</span>
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
                    <i class="mb-2 text-4xl fas fa-folder-open text-secondary"></i>
                    <p class="text-sm text-secondary">{{ __('messages.No sections available yet.') }}</p>
                </div>
            @endforelse
        </div>
    </div>
@else
    <div class="bg-surface-container-lowest neo-border neo-radius">
        <div class="p-12 text-center">
            <i class="mb-4 text-6xl fas fa-book-open text-secondary"></i>
            <h3 class="text-sm font-bold tracking-widest uppercase text-on-surface">{{ __('messages.Select a Course') }}</h3>
            <p class="mt-2 text-sm text-secondary">{{ __('messages.Discover new courses and continue learning') }}</p>
        </div>
    </div>
@endif
