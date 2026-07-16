<div>
    <header class="h-16 flex justify-between items-center px-[24px] bg-surface-container-lowest border-b-2 border-on-surface sticky top-0 z-40">
        <div>
            <h2 class="text-[24px] font-bold text-on-surface leading-none">{{ __('messages.Reports') }}</h2>
            <p class="text-[12px] font-medium uppercase text-secondary mt-0.5 tracking-wider">{{ __('messages.Analytics & Insights') }}</p>
        </div>
        <div class="flex items-center gap-2">
            <button wire:click="exportCsv"
                class="flex items-center px-3 text-[10px] h-9 font-bold uppercase neo-border neo-radius bg-primary-container text-on-surface hover:bg-on-surface hover:text-white transition-colors">
                <i class="fas fa-download mr-2"></i>
                {{ __('messages.Export CSV') }}
            </button>
            @livewire('shared.notification-bell')
        </div>
    </header>

    <div class="p-[24px] max-w-[1400px] mx-auto space-y-[24px]">
        {{-- Period Selector --}}
        <div class="flex items-center justify-between">
            <div class="flex gap-2">
                @foreach([7, 30, 90, 365] as $p)
                    <button wire:click="setPeriod({{ $p }})"
                        class="px-4 py-2 text-[10px] font-bold uppercase neo-border neo-radius transition-colors
                            {{ $period === $p ? 'bg-primary-container text-on-primary-container border-2 border-on-surface' : 'bg-surface-container-lowest text-secondary hover:bg-surface-container-high' }}">
                        {{ $p === 365 ? __('messages.Last Year') : ($p === 30 ? __('messages.Last Month') : ($p === 7 ? __('messages.Last Week') : __('messages.Last 3 Months'))) }}
                    </button>
                @endforeach
            </div>
        </div>

        {{-- Tab Navigation --}}
        <div class="flex overflow-hidden border-2 border-on-surface neo-radius bg-surface-container-lowest">
            @foreach(['overview', 'users', 'courses', 'quizzes', 'assignments'] as $tab)
                <button wire:click="switchTab('{{ $tab }}')"
                    class="flex-1 py-3 text-[11px] font-bold uppercase tracking-widest transition-colors
                        {{ $activeTab === $tab ? 'bg-primary-container text-on-primary-container' : 'bg-surface-container-lowest text-secondary hover:bg-surface-container-high' }}
                        {{ !$loop->first ? 'border-l-2 border-on-surface' : '' }}">
                    {{ __("messages." . ucfirst($tab)) }}
                </button>
            @endforeach
        </div>

        {{-- Tab Content --}}
        @switch($activeTab)
            @case('overview')
                @include('livewire.admin.reports-components.tabs.overview')
                @break
            @case('users')
                @include('livewire.admin.reports-components.tabs.users')
                @break
            @case('courses')
                @include('livewire.admin.reports-components.tabs.courses')
                @break
            @case('quizzes')
                @include('livewire.admin.reports-components.tabs.quizzes')
                @break
            @case('assignments')
                @include('livewire.admin.reports-components.tabs.assignments')
                @break
        @endswitch
    </div>
</div>
