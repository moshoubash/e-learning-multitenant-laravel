<div class="divide-y-2 divide-on-surface/10">
    @forelse($enrollments as $enrollment)
        @php $courseDeleted = !$enrollment->course || $enrollment->course->trashed(); @endphp
        <div class="p-[24px] hover:bg-surface-container-high transition-colors {{ $courseDeleted ? 'opacity-60' : '' }}">
            <div class="flex items-start gap-4">
                @if($courseDeleted)
                    <div class="flex items-center justify-center w-20 h-16 neo-border-sm neo-radius bg-surface-container shrink-0">
                        <i class="text-xl text-secondary fas fa-ban"></i>
                    </div>
                @elseif($enrollment->course->thumbnail)
                    <img src="{{ $enrollment->course->thumbnail }}" alt="{{ $enrollment->course->title }}"
                        class="object-cover w-20 h-16 neo-border-sm neo-radius shrink-0">
                @else
                    <div class="flex items-center justify-center w-20 h-16 neo-border-sm neo-radius bg-surface-container shrink-0">
                        <i class="text-xl text-secondary fas fa-book"></i>
                    </div>
                @endif
                <div class="flex-1 min-w-0">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h3 class="font-bold text-on-surface">
                                {{ $courseDeleted ? __('messages.Course Unavailable') : $enrollment->course->title }}
                            </h3>
                            <p class="text-xs text-secondary mt-0.5">
                                {{ $courseDeleted ? __('messages.This course is no longer available') : __('messages.By') . ' ' . ($enrollment->course->instructor->name ?? '—') }}
                            </p>
                        </div>
                        @php
                            $statusClasses = [
                                'active' => 'bg-primary-container text-on-primary-container',
                                'completed' => 'bg-surface-container-high text-on-surface',
                                'pending' => 'bg-surface-container text-secondary',
                                'cancelled' => 'bg-error/10 text-error',
                            ];
                            $class = $statusClasses[$enrollment->status] ?? 'bg-surface-container text-secondary';
                        @endphp
                        <span class="shrink-0 inline-flex items-center px-2.5 py-1 text-[10px] font-bold uppercase tracking-widest neo-border-sm {{ $class }}">
                            {{ __(ucfirst($enrollment->status)) }}
                        </span>
                    </div>
                    <div class="flex flex-wrap items-center gap-4 mt-3 text-xs text-secondary">
                        <span>
                            <span class="font-medium text-on-surface">{{ __('messages.Enrolled') }}:</span>
                            {{ $enrollment->enrolled_at ? $enrollment->enrolled_at->format('M d, Y') : '—' }}
                        </span>
                        @if($enrollment->completed_at)
                            <span>
                                <span class="font-medium text-on-surface">{{ __('messages.Completed') }}:</span>
                                {{ $enrollment->completed_at->format('M d, Y') }}
                            </span>
                        @endif
                    </div>
                    <div class="flex items-center gap-3 mt-3">
                        <div class="flex-1 max-w-[200px] h-2.5 neo-border-sm bg-surface-container overflow-hidden">
                            <div class="h-full transition-all duration-300 {{ $enrollment->progress_percent >= 100 ? 'bg-primary-container' : 'bg-on-surface' }}" style="width: {{ $enrollment->progress_percent }}%"></div>
                        </div>
                        <span class="text-xs font-bold text-on-surface shrink-0">{{ $enrollment->progress_percent }}%</span>
                        @if(!$courseDeleted && ($enrollment->isActive() || $enrollment->isCompleted()))
                            <a href="{{ route('tenant.student.course', ['course' => $enrollment->course->slug]) }}"
                                class="px-3 py-1 text-[10px] font-bold uppercase tracking-widest neo-border-sm bg-primary-container text-on-primary-container hover:bg-on-surface hover:text-white transition-colors shrink-0">
                                <i class="fas fa-play ltr:mr-1 rtl:ml-1"></i>
                                {{ __('messages.Continue') }}
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="p-[24px] text-center py-12">
            <i class="mb-3 text-3xl fas fa-user-graduate text-secondary"></i>
            <p class="text-sm text-secondary">{{ __('messages.No enrollments found') }}</p>
            <a href="{{ route('tenant.student.courses') }}"
                class="inline-flex items-center px-4 py-2 mt-4 text-xs font-bold tracking-widest uppercase transition-colors neo-border neo-radius bg-primary-container text-on-primary-container hover:bg-on-surface hover:text-white">
                <i class="fas fa-graduation-cap ltr:mr-2 rtl:ml-2"></i>
                {{ __('messages.Browse Courses') }}
            </a>
        </div>
    @endforelse
</div>

@if($enrollments->hasPages())
    <div class="px-[24px] py-4 border-t-2 border-on-surface bg-surface-container-low">
        {{ $enrollments->links() }}
    </div>
@endif
