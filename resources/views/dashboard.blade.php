<div class="space-y-6">
    {{-- Page header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ __('messages.Dashboard') }}</h1>
            <p class="mt-1 text-sm text-gray-500">{{ __('messages.Welcome back,') }} {{ auth()->user()->name }}</p>
        </div>
    </div>

    {{-- KPI cards --}}
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
        @foreach ($kpis as $kpi)
            <x-ui.kpi-card
                :label="$kpi['label']"
                :value="$kpi['value']"
                :progress="$kpi['progress'] ?? null"
                :color="$kpi['color'] ?? '#6366f1'"
                :change="$kpi['change'] ?? null"
            >
                <x-slot name="icon">
                    <div class="flex items-center justify-center w-10 h-10 rounded-lg"
                         style="background-color: {{ ($kpi['color'] ?? '#6366f1') }}1a; color: {{ $kpi['color'] ?? '#6366f1' }};">
                        <i class="{{ $kpi['icon'] ?? 'fas fa-chart-line' }}"></i>
                    </div>
                </x-slot>
                @if(!empty($kpi['sub']))
                    <span class="mt-2 text-xs text-gray-500">{{ $kpi['sub'] }}</span>
                @endif
            </x-ui.kpi-card>
        @endforeach
    </div>

    {{-- Role-specific content --}}
    @if($role === 'admin')
        @include('livewire.dashboard.partials.admin')
    @elseif($role === 'instructor')
        @include('livewire.dashboard.partials.instructor')
    @else
        @include('livewire.dashboard.partials.student')
    @endif
</div>
