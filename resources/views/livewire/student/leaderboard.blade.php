<div>
    <header class="h-16 flex justify-between items-center px-[24px] bg-surface-container-lowest border-b-2 border-on-surface sticky top-0 z-40">
        <div>
            <h2 class="text-[24px] font-bold text-on-surface leading-none">{{ __('messages.Leaderboard') }}</h2>
        <p class="text-[12px] font-medium uppercase text-secondary mt-0.5 tracking-wider">{{ __('messages.See how you rank among your peers') }}</p>
    </div>
    <div class="flex items-center gap-2">
        @livewire('shared.notification-bell')
    </div>
</header>

    <div class="p-[24px] max-w-[1400px] mx-auto">
        <div class="p-12 text-center bg-surface-container-lowest neo-border neo-radius">
            <div class="inline-flex items-center justify-center w-16 h-16 mb-4 neo-border neo-radius bg-primary-container">
                <i class="text-2xl text-on-primary-container fas fa-trophy"></i>
            </div>
            <h3 class="text-sm font-bold tracking-widest uppercase text-on-surface">{{ __('messages.Leaderboard Coming Soon') }}</h3>
            <p class="mt-2 text-sm text-secondary">{{ __('messages.Track your progress and compete with other learners.') }}</p>
        </div>
    </div>
</div>
