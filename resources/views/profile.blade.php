<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">{{ __('messages.Profile') }}</h1>
        <p class="mt-1 text-sm text-gray-500">{{ __('messages.Manage your account settings') }}</p>
    </div>

    <div class="p-4 bg-white shadow-sm sm:p-8 rounded-2xl">
        <div class="max-w-xl">
            <livewire:profile.update-profile-information-form />
        </div>
    </div>

    <div class="p-4 bg-white shadow-sm sm:p-8 rounded-2xl">
        <div class="max-w-xl">
            <livewire:profile.update-password-form />
        </div>
    </div>

    <div class="p-4 bg-white shadow-sm sm:p-8 rounded-2xl">
        <div class="max-w-xl">
            <livewire:profile.delete-user-form />
        </div>
    </div>
</div>
