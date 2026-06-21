<div>
    {{-- TopAppBar --}}
    <header class="h-16 flex justify-between items-center px-[24px] bg-surface-container-lowest border-b-2 border-on-surface sticky top-0 z-40">
        <div>
            <h2 class="text-[24px] font-bold text-on-surface leading-none tracking-[0.08em]">{{ __('messages.Profile') }}</h2>
            <p class="text-[12px] font-medium uppercase text-secondary mt-0.5 tracking-wider">{{ __('messages.Manage your account settings') }}</p>
        </div>
        <div class="flex items-center gap-4">
            <div class="w-10 h-10 neo-border neo-radius bg-primary-container flex items-center justify-center overflow-hidden">
                <i class="fas fa-user text-on-surface"></i>
            </div>
        </div>
    </header>

    <div class="p-[24px] max-w-[1400px] mx-auto space-y-[24px]">
        <div class="bg-surface-container-lowest neo-border neo-radius p-[24px]">
            <div class="max-w-xl">
                <livewire:profile.update-profile-information-form />
            </div>
        </div>

        <div class="bg-surface-container-lowest neo-border neo-radius p-[24px]">
            <div class="max-w-xl">
                <livewire:profile.update-password-form />
            </div>
        </div>

        <div class="bg-surface-container-lowest neo-border neo-radius p-[24px]">
            <livewire:profile.delete-user-form />
        </div>
    </div>
</div>