{{-- ROW 2: Charts --}}
<div class="grid grid-cols-1 gap-4 lg:grid-cols-3">
    {{-- Weekly Progress Chart (col-span-2) --}}
    <div class="lg:col-span-2 bg-surface-container-lowest neo-border p-[24px] neo-radius">
        <div class="flex items-center justify-between mb-8">
            <h3 class="text-[18px] font-bold uppercase tracking-widest text-on-surface leading-none">{{ __('messages.My Weekly Progress') }}</h3>
            <div class="px-3 py-1 bg-on-surface text-white neo-radius text-[10px] font-bold uppercase tracking-widest leading-none">
                {{ now()->startOfWeek()->format('M d') }} - {{ now()->endOfWeek()->format('M d') }}
            </div>
        </div>
        @php $progressChartId = 'progress-' . uniqid(); @endphp
        <div class="relative" style="height: 200px;">
            <canvas id="{{ $progressChartId }}" wire:ignore></canvas>
        </div>
        <script>
            (function () {
                const el = document.getElementById(@json($progressChartId));
                if (!el || el.dataset.initialized === '1') return;
                el.dataset.initialized = '1';
                const labels = @json($charts['progress']['labels'] ?? []);
                const datasets = @json($charts['progress']['datasets'] ?? []);
                const data = datasets[0]?.data ?? [];
                new Chart(el.getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: datasets[0]?.label ?? 'Lessons',
                            data: data,
                            borderColor: '#0A0A0A',
                            backgroundColor: '#F4F4F4',
                            borderWidth: 2,
                            tension: 0.35,
                            fill: true,
                            pointBackgroundColor: '#0A0A0A',
                            pointRadius: 4,
                            pointHoverRadius: 6,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            x: { grid: { display: false }, ticks: { font: { size: 10, weight: 'bold' }, color: '#5f5e5e' } },
                            y: { beginAtZero: true, grid: { color: '#E5E2E1' }, ticks: { font: { size: 10 }, color: '#5f5e5e', precision: 0 } }
                        }
                    }
                });
            })();
        </script>
    </div>

    {{-- Recent Quiz Scores --}}
    <div class="bg-surface-container-lowest neo-border p-[24px] neo-radius">
        <h3 class="text-[18px] font-bold uppercase tracking-widest text-on-surface leading-none mb-8">{{ __('messages.Recent Quiz Scores') }}</h3>
        @php $scoreData = $charts['quizScores']['datasets'][0]['data'] ?? []; @endphp
        @if(count($scoreData) > 0)
            <div class="space-y-6">
                @foreach($charts['quizScores']['labels'] as $i => $label)
                    @php
                        $score = (int) ($scoreData[$i] ?? 0);
                        $passed = $score >= 50;
                    @endphp
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold tracking-widest uppercase text-on-surface">{{ $label }}</span>
                            <span class="px-2 py-0.5 neo-border-sm neo-radius text-[10px] font-bold leading-none {{ $passed ? 'bg-primary-container text-on-primary-container' : 'bg-surface-container-lowest' }}">{{ $passed ? __('messages.Passed') : __('messages.Failed') }}</span>
                        </div>
                        <div class="w-full h-4 overflow-hidden neo-border neo-radius bg-surface-container">
                            <div class="h-full {{ $passed ? 'bg-primary-container' : 'bg-error' }}" style="width: {{ $score }}%;" title="{{ $score }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-sm text-secondary">{{ __('messages.No quiz scores yet') }}</p>
        @endif
    </div>
</div>

{{-- ROW 3: Tables --}}
<div class="grid grid-cols-1 gap-4 lg:grid-cols-5">
    {{-- My Enrolled Courses (col-span-3) --}}
    <div class="overflow-hidden lg:col-span-3 bg-surface-container-lowest neo-border neo-radius">
        <div class="p-[24px] border-b-2 border-on-surface">
            <h3 class="text-[18px] font-bold uppercase tracking-widest text-on-surface leading-none">{{ __('messages.My Enrolled Courses') }}</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="border-b-2 bg-surface-container-low border-on-surface">
                    <tr>
                        <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-secondary leading-none">{{ __('messages.Course') }}</th>
                        <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-secondary leading-none">{{ __('messages.By') }}</th>
                        <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-secondary leading-none">{{ __('messages.Progress') }}</th>
                        <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-secondary leading-none">{{ __('messages.Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E5E5E5]">
                    @forelse($tables['enrollments'] as $enrollment)
                        @php $progress = (int) $enrollment->progress_percent; @endphp
                        @if(!$enrollment->course) @continue @endif
                        <tr>
                            <td class="px-6 py-4 text-sm font-bold text-on-surface">
                                <a href="{{ route('tenant.student.course', optional($enrollment->course)->slug) }}" class="hover:underline">
                                    {{ optional($enrollment->course)->title ?? '—' }}
                                </a>
                            </td>
                            <td class="px-6 py-4 text-sm text-on-surface">
                                {{ optional(optional($enrollment->course)->instructor)->name ?? '—' }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <div class="flex-1 h-2 overflow-hidden neo-border neo-radius bg-surface-container">
                                        <div class="h-full bg-on-surface" style="width: {{ $progress }}%;"></div>
                                    </div>
                                    <span class="text-[10px] font-bold leading-none">{{ $progress }}%</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('tenant.student.course', optional($enrollment->course)->slug) }}"
                                   class="inline-block px-3 py-1 neo-border neo-radius bg-primary-container text-on-primary-container text-[10px] font-bold uppercase leading-none hover:translate-x-0.5 hover:translate-y-0.5 transition-transform">{{ __('messages.Continue') }}</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-6 py-8 text-sm text-center text-secondary">{{ __('messages.No Enrolled Courses') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Recent Quiz Attempts (col-span-2) --}}
    <div class="lg:col-span-2 bg-surface-container-lowest neo-border neo-radius p-[24px]">
        <h3 class="text-[18px] font-bold uppercase tracking-widest text-on-surface leading-none mb-6">{{ __('messages.Recent Quiz Attempts') }}</h3>
        <div class="space-y-4">
            @forelse($tables['attempts'] as $attempt)
                @php $score = (int) $attempt->score; @endphp
                <div class="flex items-center gap-4 p-3 border-2 border-on-surface neo-radius">
                    <div class="w-[44px] h-[44px] shrink-0 neo-border neo-radius bg-primary-container text-on-primary-container flex items-center justify-center font-bold text-lg leading-none">{{ $score }}</div>
                    <div class="flex-1 min-w-0">
                        <h4 class="text-xs font-bold leading-tight tracking-wider uppercase text-on-surface">{{ optional($attempt->quiz)->title ?? '—' }}</h4>
                        <p class="text-[10px] text-secondary mt-1 leading-none">{{ $attempt->submitted_at?->diffForHumans() }}</p>
                    </div>
                    <span class="shrink-0 px-2 py-1 text-[10px] font-bold neo-radius leading-none {{ $attempt->passed ? 'bg-on-surface text-white' : 'bg-surface-container-lowest text-on-surface border-2 border-on-surface' }}">{{ $attempt->passed ? __('messages.Passed') : __('messages.Failed') }}</span>
                </div>
            @empty
                <p class="py-8 text-sm text-center text-secondary">{{ __('messages.No quiz attempts yet') }}</p>
            @endforelse
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.querySelectorAll('button').forEach(btn => {
        btn.addEventListener('mousedown', () => { btn.style.transform = 'scale(0.95)'; });
        btn.addEventListener('mouseup', () => { btn.style.transform = 'scale(1)'; });
        btn.addEventListener('mouseleave', () => { btn.style.transform = 'scale(1)'; });
    });
</script>
@endpush
