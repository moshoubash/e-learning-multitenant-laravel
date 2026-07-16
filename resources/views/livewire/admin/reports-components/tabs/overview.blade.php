{{-- KPIs --}}
<div class="grid grid-cols-1 gap-4 md:grid-cols-4">
    @foreach($kpis as $kpi)
        <div class="bg-surface-container-lowest neo-border p-[24px] neo-radius flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-bold uppercase text-secondary tracking-widest">{{ $kpi['label'] }}</span>
                <span class="text-[10px] font-bold uppercase {{ ($kpi['change'] ?? 0) >= 0 ? 'text-error' : 'text-secondary' }}">
                    @isset($kpi['change'])
                        {{ $kpi['change'] >= 0 ? '+' : '' }}{{ $kpi['change'] }}%
                    @endisset
                </span>
            </div>
            <div class="flex items-end justify-between mt-4">
                <span class="text-[40px] font-bold text-on-surface leading-none tracking-tight">{{ $kpi['value'] }}</span>
                <div class="flex items-center justify-center w-8 h-8 neo-border bg-surface-container-high neo-radius">
                    <i class="fas {{ $kpi['icon'] }} text-sm"></i>
                </div>
            </div>
        </div>
    @endforeach
</div>

{{-- Charts Row --}}
<div class="grid grid-cols-1 gap-4 lg:grid-cols-3">
    <div class="lg:col-span-2 bg-surface-container-lowest neo-border p-[24px] neo-radius">
        <h3 class="text-[18px] font-bold uppercase tracking-widest text-on-surface leading-none mb-6">{{ __('messages.Enrollments & New Users') }}</h3>
        <x-ui.chart
            :type="$charts['enrollmentTrend']['type']"
            :labels="$charts['enrollmentTrend']['labels']"
            :datasets="$charts['enrollmentTrend']['datasets']"
            :height="280"
            class="!bg-transparent !shadow-none !rounded-none !p-0"
        />
    </div>
    <div class="bg-surface-container-lowest neo-border p-[24px] neo-radius">
        <h3 class="text-[18px] font-bold uppercase tracking-widest text-on-surface leading-none mb-6">{{ __('messages.Courses by Status') }}</h3>
        <x-ui.chart
            :type="$charts['courseStatus']['type']"
            :labels="$charts['courseStatus']['labels']"
            :datasets="$charts['courseStatus']['datasets']"
            :height="280"
            class="!bg-transparent !shadow-none !rounded-none !p-0"
        />
    </div>
</div>
