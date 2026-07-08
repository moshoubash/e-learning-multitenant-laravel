<div class="neo-border neo-radius bg-surface-container-lowest overflow-hidden">
    <div class="grid grid-cols-[48px_1fr_1fr_100px_80px] gap-4 px-6 py-3 bg-surface-container-high border-b-2 border-on-surface text-xs font-bold uppercase tracking-wider text-secondary">
        <span>#</span>
        <span>{{ __('messages.Name') }}</span>
        <span>{{ __('messages.Email') }}</span>
        <span class="text-right">{{ __('messages.Points') }}</span>
        <span class="text-right">{{ __('messages.Courses Completed') }}</span>
    </div>

    @forelse($students as $index => $student)
        <div class="grid grid-cols-[48px_1fr_1fr_100px_80px] gap-4 px-6 py-4 items-center border-b-2 border-on-surface last:border-b-0 hover:bg-surface-container-high transition-colors">
            <div class="flex justify-center">
                @if($index === 0)
                    <span class="text-xl">🥇</span>
                @elseif($index === 1)
                    <span class="text-xl">🥈</span>
                @elseif($index === 2)
                    <span class="text-xl">🥉</span>
                @else
                    <span class="text-sm font-bold text-on-surface">{{ $students->firstItem() + $index }}</span>
                @endif
            </div>
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 neo-border neo-radius-full bg-surface-container-high flex-shrink-0 overflow-hidden">
                    @if($student->avatar)
                        <img src="{{ $student->avatar }}" alt="" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-xs font-bold text-on-surface">
                            {{ substr($student->name, 0, 2) }}
                        </div>
                    @endif
                </div>
                <span class="text-sm font-bold text-on-surface truncate">{{ $student->name }}</span>
            </div>
            <span class="text-sm text-on-surface truncate">{{ $student->email }}</span>
            <span class="text-sm font-bold text-on-surface text-right">{{ $student->total_points }}</span>
            <span class="text-sm text-on-surface text-right">{{ $student->enrollments()->whereNotNull('completed_at')->count() }}</span>
        </div>
    @empty
        <div class="p-12 text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 mb-4 neo-border neo-radius bg-primary-container">
                <i class="text-2xl text-on-primary-container fas fa-trophy"></i>
            </div>
            <h3 class="text-sm font-bold tracking-widest uppercase text-on-surface">
                {{ $this->search ? __('messages.No students match your search') : __('messages.No rankings yet') }}
            </h3>
            <p class="mt-2 text-sm text-secondary">{{ __('messages.Students earn points by completing lessons and passing quizzes') }}</p>
        </div>
    @endforelse
</div>

<div class="mt-4">
    {{ $students->links() }}
</div>
