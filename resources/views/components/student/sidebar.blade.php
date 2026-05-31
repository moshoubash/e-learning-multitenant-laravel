<x-ui.sidebar title="E-Learning" class="hidden lg:flex">
    {{-- Menu group: My Learning --}}
    <div class="px-3 py-2">
        <p class="mb-1 px-2 text-[11px] font-semibold uppercase tracking-widest text-gray-400">
            {{ __('messages.My Learning') }}
        </p>
        <nav class="space-y-0.5">
            <a href="{{ route('tenant.student.courses') }}" wire:navigate
               class="flex items-center px-2 py-1.5 text-sm rounded-lg {{ request()->routeIs('tenant.student.courses') ? 'bg-indigo-50 text-indigo-700 font-medium' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                <i class="w-5 h-4 text-xs text-center fas fa-graduation-cap"></i>
                <span class="ml-3">{{ __('messages.My Courses') }}</span>
            </a>
            <a href="{{ route('tenant.student.enrolled-courses') }}" wire:navigate
               class="flex items-center px-2 py-1.5 text-sm rounded-lg {{ request()->routeIs('tenant.student.enrolled-courses*') ? 'bg-indigo-50 text-indigo-700 font-medium' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                <i class="w-5 h-4 text-xs text-center fas fa-play-circle"></i>
                <span class="ml-3">{{ __('messages.In Progress') }}</span>
            </a>
            <a href="{{ route('tenant.student.leaderboard') }}" wire:navigate
               class="flex items-center px-2 py-1.5 text-sm rounded-lg {{ request()->routeIs('tenant.student.leaderboard*') ? 'bg-indigo-50 text-indigo-700 font-medium' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                <i class="w-5 h-4 text-xs text-center fas fa-trophy"></i>
                <span class="ml-3">{{ __('messages.Leaderboard') }}</span>
            </a>
        </nav>
    </div>

    {{-- Bottom CTA card --}}
    <x-slot name="footer">
        <div class="p-3 rounded-xl">
            <div class="flex items-start space-x-3">
                <a href="/profile" wire:navigate
                class="px-2 py-1.5 text-sm {{ request()->routeIs('profile') ? ' text-gray-700 font-medium' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <i class="w-5 h-4 text-xs text-center fas fa-trophy"></i>
                    <span class="ml-3">Profile</span>
                </a>
            </div>
        </div>
    </x-slot>

    {{-- Icons for collapsed state --}}
    <x-slot name="icons">
        <nav class="flex flex-col items-center mt-4 space-y-3">
            <a href="{{ route('tenant.student.courses') }}" wire:navigate class="p-2 text-gray-400 transition-colors hover:text-indigo-600">
                <i class="text-sm fas fa-graduation-cap"></i>
            </a>
            <a href="{{ route('tenant.student.enrolled-courses') }}" wire:navigate class="p-2 text-gray-400 transition-colors hover:text-indigo-600">
                <i class="text-sm fas fa-play-circle"></i>
            </a>
            <a href="{{ route('tenant.student.leaderboard') }}" wire:navigate class="p-2 text-gray-400 transition-colors hover:text-indigo-600">
                <i class="text-sm fas fa-trophy"></i>
            </a>
        </nav>
    </x-slot>
</x-ui.sidebar>
