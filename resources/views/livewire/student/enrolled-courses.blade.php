<div>
    <header class="px-[24px] py-[9px] bg-surface-container-lowest border-b-2 border-on-surface sticky top-0 z-40">
        {{-- Desktop: single row --}}
        <div class="items-center justify-between hidden lg:flex">
            <div>
                <h2 class="text-[24px] font-bold text-on-surface leading-none">{{ __('messages.My Enrolled Courses') }}</h2>
                <p class="text-[12px] font-medium uppercase text-secondary mt-0.5 tracking-wider">{{ __('messages.Continue where you left off') }}</p>
            </div>
            <div class="flex items-center gap-2">
                @livewire('shared.notification-bell')
                <a href="{{ route('tenant.student.courses') }}" class="px-4 py-2 text-xs font-bold tracking-widest uppercase transition-colors neo-border neo-radius bg-primary-container text-on-primary-container hover:bg-on-surface hover:text-white">
                    {{ __('messages.Browse More') }}
                    @if(app()->getLocale() == 'ar')
                        <i class="mr-1 fas fa-arrow-left"></i>
                    @else
                        <i class="ml-1 fas fa-arrow-right"></i>
                    @endif
                </a>
            </div>
        </div>
        {{-- Mobile: two rows --}}
        <div class="lg:hidden">
            <div class="flex items-start justify-between">
                <div class="min-w-0 flex-1 ltr:mr-3 rtl:ml-3">
                    <h2 class="text-lg font-bold text-on-surface leading-tight">{{ __('messages.My Enrolled Courses') }}</h2>
                    <p class="text-[10px] font-medium uppercase text-secondary mt-0.5 tracking-wider">{{ __('messages.Continue where you left off') }}</p>
                </div>
                <div class="shrink-0">
                    @livewire('shared.notification-bell')
                </div>
            </div>
            <div class="flex items-center gap-2 mt-3">
                <a href="{{ route('tenant.student.courses') }}" class="inline-flex items-center px-4 py-2 text-xs font-bold tracking-widest uppercase transition-colors neo-border neo-radius bg-primary-container text-on-primary-container hover:bg-on-surface hover:text-white">
                    {{ __('messages.Browse More') }}
                    @if(app()->getLocale() == 'ar')
                        <i class="mr-1 fas fa-arrow-left"></i>
                    @else
                        <i class="ml-1 fas fa-arrow-right"></i>
                    @endif
                </a>
            </div>
        </div>
    </header>

    <div class="p-[24px] max-w-[1400px] mx-auto">
        <div class="overflow-hidden bg-surface-container-lowest neo-border neo-radius">
            @forelse($enrollments as $enrollment)
                @if(!$enrollment->course) @continue @endif
                @php
                    $progress = $this->getCourseProgress($enrollment);
                @endphp
                <div class="p-[24px] {{ !$loop->last ? 'border-b-2 border-on-surface' : '' }} hover:bg-surface-container-low transition-colors">
                    <div class="flex flex-col sm:flex-row items-start justify-between gap-6">
                        <div class="flex-1 min-w-0 w-full sm:w-auto">
                            <h4 class="text-base font-bold text-on-surface">{{ $enrollment->course->title }}</h4>
                            <p class="mt-1 text-sm text-secondary">{{ $enrollment->course->instructor->name ?? 'N/A' }}</p>

                            {{-- Progress Bar --}}
                            <div class="mt-4">
                                <div class="flex items-center justify-between mb-1.5">
                                    <span class="text-xs font-bold tracking-widest uppercase text-secondary">{{ $progress['completed_lessons'] }} / {{ $progress['total_lessons'] }} {{ __('messages.lessons completed') }}</span>
                                    <span class="text-xs font-bold text-on-surface"><span class="text-secondary font-medium ltr:mr-1 rtl:ml-1">{{ __('messages.Completed') }}:</span>{{ $progress['progress_percent'] }}%</span>
                                </div>
                                <div class="w-full h-2 overflow-hidden neo-border-sm neo-radius bg-surface-container">
                                    <div class="h-full transition-all duration-300 bg-on-surface neo-radius" style="width: {{ $progress['progress_percent'] }}%"></div>
                                </div>
                            </div>

                            {{-- Current Checkpoint --}}
                            @if($progress['current_lesson'])
                                <div class="flex items-center mt-3 text-sm">
                                    <span class="text-xs font-bold tracking-widest uppercase ltr:mr-2 rtl:ml-2 text-secondary">{{ __('messages.Current') }}:</span>
                                    <span class="inline-flex items-center px-2 py-1 neo-border-sm neo-radius text-[10px] font-bold bg-surface-container-high text-on-surface">
                                        <i class="ltr:mr-1 rtl:ml-1 fas fa-play-circle"></i>
                                        {{ $progress['current_lesson']->title }}
                                    </span>
                                </div>
                            @elseif($progress['progress_percent'] == 100)
                                <div class="flex items-center mt-3">
                                    <span class="inline-flex items-center px-2 py-1 neo-border-sm neo-radius text-[10px] font-bold bg-primary-container text-on-primary-container">
                                        <i class="ltr:mr-1 rtl:ml-1 fas fa-check-circle"></i>
                                        {{ __('messages.Course Completed!') }}
                                    </span>
                                </div>
                            @endif
                        </div>

                        {{-- Actions --}}
                        <div class="shrink-0 w-full sm:w-auto">
                            <a href="{{ route('tenant.student.course', $enrollment->course->slug) }}"
                                class="inline-flex items-center justify-center w-full sm:w-auto px-4 py-2 text-xs font-bold tracking-widest uppercase transition-colors neo-border neo-radius bg-primary-container text-on-primary-container hover:bg-on-surface hover:text-white">
                                <i class="fas fa-play-circle rtl:ml-2 ltr:mr-2"></i>
                                {{ __('messages.Continue') }}
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-6 sm:p-12 text-center">
                    <i class="mb-4 text-6xl fas fa-graduation-cap text-secondary"></i>
                    <h3 class="text-sm font-bold tracking-widest uppercase text-on-surface">{{ __('messages.No Enrolled Courses') }}</h3>
                    <p class="mt-2 text-sm text-secondary">{{ __('messages.Start learning by enrolling in a course.') }}</p>
                    <a href="{{ route('tenant.student.courses') }}"
                        class="inline-flex items-center justify-center px-4 py-2 mt-4 text-xs font-bold tracking-widest uppercase transition-colors neo-border neo-radius bg-primary-container text-on-primary-container hover:bg-on-surface hover:text-white">
                        <i class="ltr:mr-2 rtl:ml-2 fas fa-search"></i>
                        {{ __('messages.Browse Courses') }}
                    </a>
                </div>
            @endforelse
        </div>
    </div>
</div>
