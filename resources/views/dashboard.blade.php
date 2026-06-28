@php
    $icons = ['fa-graduation-cap', 'fa-check-circle', 'fa-question-circle', 'fa-chart-line'];
@endphp

<div>
    {{-- TopAppBar --}}
    <header class="h-16 flex justify-between items-center px-[24px] bg-surface-container-lowest border-b-2 border-on-surface sticky top-0 z-40">
        <div>
            <h2 class="text-[24px] font-bold text-on-surface leading-none">{{ __('messages.Dashboard') }}</h2>
            <p class="text-[12px] font-medium uppercase text-secondary mt-0.5 tracking-wider">{{ __('messages.Welcome back,') }} {{ auth()->user()->name }}!</p>
        </div>
        <div class="flex items-center gap-2">
            @livewire('shared.notification-bell')
        </div>
    </header>

    <div class="p-[24px] max-w-[1400px] mx-auto space-y-[24px]">
    {{-- Content Row 1: Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        @foreach($kpis as $index => $kpi)
            @if(isset($kpi['progress']))
                {{-- Pass Rate card with SVG donut --}}
                <div class="bg-surface-container-lowest neo-border p-[24px] neo-radius flex flex-col justify-between">
                    <span class="text-[10px] font-bold uppercase text-secondary tracking-widest">{{ $kpi['label'] }}</span>
                    <div class="flex justify-between items-center mt-4">
                        <span class="text-[40px] font-bold text-on-surface leading-none tracking-tight">{{ $kpi['value'] }}</span>
                        <div class="relative w-[60px] h-[60px]">
                            @php
                                $circumference = 2 * pi() * 26;
                                $progress = (int) $kpi['value'];
                                $dashoffset = $circumference * (1 - $progress / 100);
                            @endphp
                            <svg class="w-full h-full transform -rotate-90">
                                <circle cx="30" cy="30" fill="transparent" r="26" stroke="#E5E2E1" stroke-width="6"></circle>
                                <circle cx="30" cy="30" fill="transparent" r="26" stroke="#0A0A0A" stroke-dasharray="{{ $circumference }}" stroke-dashoffset="{{ $dashoffset }}" stroke-width="6"></circle>
                            </svg>
                        </div>
                    </div>
                </div>
            @else
                {{-- Standard stat card --}}
                <div class="bg-surface-container-lowest neo-border p-[24px] neo-radius flex flex-col justify-between">
                    <span class="text-[10px] font-bold uppercase text-secondary tracking-widest">{{ $kpi['label'] }}</span>
                    <div class="flex justify-between items-end mt-4">
                        <span class="text-[40px] font-bold text-on-surface leading-none tracking-tight">{{ $kpi['value'] }}</span>
                        <div class="w-8 h-8 neo-border bg-surface-container-high flex items-center justify-center neo-radius">
                            <i class="fas {{ $icons[$index] ?? 'fa-graduation-cap' }} text-sm"></i>
                        </div>
                    </div>
                </div>
            @endif
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
</div>
