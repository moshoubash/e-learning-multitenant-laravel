{{-- Admin Sidebar Navigation Items --}}
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

    {{-- Management group --}}
    <div class="px-1 py-2">
        <p class="mb-1 px-2 text-[11px] font-semibold uppercase tracking-widest text-gray-400">{{ __('messages.Management') }}</p>

        <nav class="space-y-0.5">
            <a href="{{ route('tenant.admin.users') }}" wire:navigate
               class="flex items-center px-2 py-1.5 text-sm rounded-lg {{ request()->routeIs('tenant.admin.users*') ? 'bg-indigo-50 text-indigo-700 font-medium' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                <i class="w-5 h-4 text-xs text-center fas fa-users"></i>
                <span class="ml-3">{{ __('messages.Users') }}</span>
            </a>
            <a href="{{ route('tenant.admin.courses') }}" wire:navigate
               class="flex items-center px-2 py-1.5 text-sm rounded-lg {{ request()->routeIs('tenant.admin.courses*') ? 'bg-indigo-50 text-indigo-700 font-medium' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                <i class="w-5 h-4 text-xs text-center fas fa-book-open"></i>
                <span class="ml-3">{{ __('messages.Courses') }}</span>
            </a>
            <a href="{{ route('tenant.admin.quizzes') }}" wire:navigate
               class="flex items-center px-2 py-1.5 text-sm rounded-lg {{ request()->routeIs('tenant.admin.quizzes*') ? 'bg-indigo-50 text-indigo-700 font-medium' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                <i class="w-5 h-4 text-xs text-center fas fa-question-circle"></i>
                <span class="ml-3">{{ __('messages.Quizzes') }}</span>
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

    {{-- Footer: language switcher + user block --}}
    <x-slot name="footer">
        <x-ui.language-switcher />
        <x-ui.user-footer />
    </x-slot>

    {{-- Icons for collapsed desktop state --}}
    <x-slot name="icons">
        <nav class="flex flex-col items-center space-y-1">
            <a href="{{ route('tenant.dashboard') }}" wire:navigate
               class="p-2 rounded-lg {{ request()->routeIs('tenant.dashboard') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-400 hover:text-indigo-600 hover:bg-gray-50' }}"
               title="{{ __('messages.Dashboard') }}">
                <i class="text-sm fas fa-home"></i>
            </a>
            <a href="{{ route('tenant.admin.users') }}" wire:navigate
               class="p-2 rounded-lg {{ request()->routeIs('tenant.admin.users*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-400 hover:text-indigo-600 hover:bg-gray-50' }}"
               title="{{ __('messages.Users') }}">
                <i class="text-sm fas fa-users"></i>
            </a>
            <a href="{{ route('tenant.admin.courses') }}" wire:navigate
               class="p-2 rounded-lg {{ request()->routeIs('tenant.admin.courses*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-400 hover:text-indigo-600 hover:bg-gray-50' }}"
               title="{{ __('messages.Courses') }}">
                <i class="text-sm fas fa-book-open"></i>
            </a>
            <a href="{{ route('tenant.admin.quizzes') }}" wire:navigate
               class="p-2 rounded-lg {{ request()->routeIs('tenant.admin.quizzes*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-400 hover:text-indigo-600 hover:bg-gray-50' }}"
               title="{{ __('messages.Quizzes') }}">
                <i class="text-sm fas fa-question-circle"></i>
            </a>
            <a href="{{ route('tenant.profile') }}" wire:navigate
               class="p-2 rounded-lg {{ request()->routeIs('tenant.profile') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-400 hover:text-indigo-600 hover:bg-gray-50' }}"
               title="{{ __('messages.Profile') }}">
                <i class="text-sm fas fa-user"></i>
            </a>
        </nav>
    </x-slot>
</x-ui.sidebar>
