{{-- Student Sidebar Navigation Items --}}
<x-ui.sidebar title="E-Learning">
    {{-- General: Dashboard --}}
    <div class="px-1 py-2">
        <p class="mb-1 px-2 text-[11px] font-semibold uppercase tracking-widest text-gray-400">{{ __('messages.General') }}</p>
        <nav class="space-y-0.5">
            <a href="{{ route('tenant.dashboard') }}" wire:navigate
               class="flex items-center px-2 py-1.5 text-sm rounded-lg {{ request()->routeIs('tenant.dashboard') ? 'bg-indigo-50 text-indigo-700 font-medium' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                <i class="w-5 h-4 text-xs text-center fas fa-home"></i>
                <span class="ml-3">{{ __('messages.Dashboard') }}</span>
            </a>
        </nav>
    </div>

    {{-- My Learning --}}
    <div class="px-1 py-2">
        <p class="mb-1 px-2 text-[11px] font-semibold uppercase tracking-widest text-gray-400">{{ __('messages.My Learning') }}</p>

        <nav class="space-y-0.5">
            <a href="{{ route('tenant.student.courses') }}" wire:navigate
               class="flex items-center px-2 py-1.5 text-sm rounded-lg {{ request()->routeIs('tenant.student.courses') && !request()->routeIs('tenant.student.enrolled-courses*') ? 'bg-indigo-50 text-indigo-700 font-medium' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                <i class="w-5 h-4 text-xs text-center fas fa-graduation-cap"></i>
                <span class="ml-3">{{ __('messages.Browse Courses') }}</span>
            </a>
            <a href="{{ route('tenant.student.enrolled-courses') }}" wire:navigate
               class="flex items-center px-2 py-1.5 text-sm rounded-lg {{ request()->routeIs('tenant.student.enrolled-courses*') ? 'bg-indigo-50 text-indigo-700 font-medium' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                <i class="w-5 h-4 text-xs text-center fas fa-play-circle"></i>
                <span class="ml-3">{{ __('messages.My Enrolled Courses') }}</span>
            </a>
            <a href="{{ route('tenant.student.leaderboard') }}" wire:navigate
               class="flex items-center px-2 py-1.5 text-sm rounded-lg {{ request()->routeIs('tenant.student.leaderboard*') ? 'bg-indigo-50 text-indigo-700 font-medium' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                <i class="w-5 h-4 text-xs text-center fas fa-trophy"></i>
                <span class="ml-3">{{ __('messages.Leaderboard') }}</span>
            </a>
        </nav>
    </div>

    {{-- Account: Profile --}}
    <div class="px-1 py-2">
        <p class="mb-1 px-2 text-[11px] font-semibold uppercase tracking-widest text-gray-400">{{ __('messages.Account') }}</p>
        <nav class="space-y-0.5">
            <a href="{{ route('tenant.profile') }}" wire:navigate
               class="flex items-center px-2 py-1.5 text-sm rounded-lg {{ request()->routeIs('tenant.profile') ? 'bg-indigo-50 text-indigo-700 font-medium' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                <i class="w-5 h-4 text-xs text-center fas fa-user"></i>
                <span class="ml-3">{{ __('messages.Profile') }}</span>
            </a>
        </nav>
    </div>

    {{-- Footer --}}
    <x-slot name="footer">
        <x-ui.language-switcher />
        <x-ui.user-footer />
    </x-slot>

    {{-- Collapsed icons --}}
    <x-slot name="icons">
        <nav class="flex flex-col items-center space-y-1">
            <a href="{{ route('tenant.dashboard') }}" wire:navigate
               class="p-2 rounded-lg {{ request()->routeIs('tenant.dashboard') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-400 hover:text-indigo-600 hover:bg-gray-50' }}"
               title="{{ __('messages.Dashboard') }}">
                <i class="text-sm fas fa-home"></i>
            </a>
            <a href="{{ route('tenant.student.courses') }}" wire:navigate
               class="p-2 rounded-lg {{ request()->routeIs('tenant.student.courses') && !request()->routeIs('tenant.student.enrolled-courses*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-400 hover:text-indigo-600 hover:bg-gray-50' }}"
               title="{{ __('messages.Browse Courses') }}">
                <i class="text-sm fas fa-graduation-cap"></i>
            </a>
            <a href="{{ route('tenant.student.enrolled-courses') }}" wire:navigate
               class="p-2 rounded-lg {{ request()->routeIs('tenant.student.enrolled-courses*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-400 hover:text-indigo-600 hover:bg-gray-50' }}"
               title="{{ __('messages.My Enrolled Courses') }}">
                <i class="text-sm fas fa-play-circle"></i>
            </a>
            <a href="{{ route('tenant.student.leaderboard') }}" wire:navigate
               class="p-2 rounded-lg {{ request()->routeIs('tenant.student.leaderboard*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-400 hover:text-indigo-600 hover:bg-gray-50' }}"
               title="{{ __('messages.Leaderboard') }}">
                <i class="text-sm fas fa-trophy"></i>
            </a>
            <a href="{{ route('tenant.profile') }}" wire:navigate
               class="p-2 rounded-lg {{ request()->routeIs('tenant.profile') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-400 hover:text-indigo-600 hover:bg-gray-50' }}"
               title="{{ __('messages.Profile') }}">
                <i class="text-sm fas fa-user"></i>
            </a>
        </nav>
    </x-slot>
</x-ui.sidebar>
