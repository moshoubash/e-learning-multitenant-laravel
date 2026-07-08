{{-- Enrolled Courses Section --}}
@php
    $enrolledCourses = \App\Models\Tenant\Enrollment::where('user_id', auth()->id())
        ->with('course.instructor', 'course.sections.lessons')
        ->get()
        ->filter(fn($e) => $e->course && $e->course->status === 'published')
        ->pluck('course');
@endphp

@if($enrolledCourses->count() > 0)
    <div class="overflow-hidden bg-surface-container-lowest neo-border neo-radius">
        <div class="p-4 border-b-2 border-on-surface">
            <h3 class="text-xs font-bold tracking-widest uppercase text-on-surface">{{ __('messages.My Enrolled Courses') }}</h3>
        </div>
        <div class="divide-y divide-[#E5E5E5]">
            @foreach($enrolledCourses as $course)
                <div wire:click="selectCourse({{ $course->id }})"
                    class="flex gap-3 p-4 transition-colors duration-100 cursor-pointer hover:bg-surface-container-high"
                    style="border-left: 4px solid {{ $selectedCourse == $course->id ? 'var(--color-primary-container)' : 'transparent' }}">
                    @if ($course->thumbnail)
                        <img src="{{ $course->thumbnail }}" class="object-cover h-12 w-14 neo-border-sm neo-radius shrink-0" alt="">
                    @else
                        <div class="flex items-center justify-center h-12 w-14 neo-border-sm neo-radius bg-surface-container text-secondary shrink-0">
                            <i class="text-sm fas fa-book"></i>
                        </div>
                    @endif
                    <div class="min-w-0">
                        <h4 class="text-sm font-bold truncate text-on-surface">{{ $course->title }}</h4>
                        <p class="mt-0.5 text-xs text-secondary truncate">{{ $course->instructor->name ?? 'N/A' }}</p>
                        <div class="flex items-center gap-3 mt-1.5 text-[10px] text-secondary font-bold uppercase tracking-widest">
                            <span><i class="mr-1 fas fa-folder"></i> {{ $course->sections->count() }} {{ __('messages.sections') }}</span>
                            <span><i class="mr-1 fas fa-book"></i> {{ $course->sections->sum(fn($s) => $s->lessons->count()) }} {{ __('messages.lessons') }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif

{{-- Available Courses Section --}}
<div class="overflow-hidden bg-surface-container-lowest neo-border neo-radius">
    <div class="p-4 border-b-2 border-on-surface">
        <h3 class="text-xs font-bold tracking-widest uppercase text-on-surface">{{ __('messages.Available Courses') }}</h3>
    </div>
    <div class="divide-y divide-[#E5E5E5]">
        @forelse($courses as $course)
            <div wire:click="selectCourse({{ $course->id }})"
                class="flex gap-3 p-4 transition-colors duration-100 cursor-pointer hover:bg-surface-container-high"
                style="border-left: 4px solid {{ $selectedCourse == $course->id ? 'var(--color-primary-container)' : 'transparent' }}">
                @if ($course->thumbnail)
                    <img src="{{ $course->thumbnail }}" class="object-cover h-12 w-14 neo-border-sm neo-radius shrink-0" alt="">
                @else
                    <div class="flex items-center justify-center h-12 w-14 neo-border-sm neo-radius bg-surface-container text-secondary shrink-0">
                        <i class="text-sm fas fa-book"></i>
                    </div>
                @endif
                <div class="min-w-0">
                    <h4 class="text-sm font-bold truncate text-on-surface">{{ $course->title }}</h4>
                    <p class="mt-0.5 text-xs text-secondary truncate">{{ $course->instructor->name ?? 'N/A' }}</p>
                    <div class="flex items-center gap-3 mt-1.5 text-[10px] text-secondary font-bold uppercase tracking-widest">
                        <span><i class="mr-1 fas fa-folder"></i> {{ $course->sections->count() }} {{ __('messages.sections') }}</span>
                        <span><i class="mr-1 fas fa-book"></i> {{ $course->sections->sum(fn($s) => $s->lessons->count()) }} {{ __('messages.lessons') }}</span>
                    </div>
                </div>
            </div>
        @empty
            <div class="p-8 text-center">
                <i class="mb-2 text-4xl fas fa-book-open text-secondary"></i>
                <p class="text-sm text-secondary">{{ __('messages.No courses found.') }}</p>
            </div>
        @endforelse
    </div>
</div>
