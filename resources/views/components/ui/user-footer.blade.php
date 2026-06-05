{{-- User footer block for the sidebar (avatar + name + logout button) --}}
@php
    $user = auth()->user();
@endphp

@if($user)
    <div class="px-3 py-3 border-t border-gray-100" x-data="{ open: false }">
        <button @click="open = !open"
                class="flex items-center w-full gap-2 p-2 text-sm font-medium text-gray-700 transition-colors rounded-lg hover:bg-gray-50">
            <div class="flex items-center justify-center flex-shrink-0 w-8 h-8 text-xs font-bold text-indigo-700 bg-indigo-100 rounded-full">
                {{ substr($user->name, 0, 1) }}
            </div>
            <div class="flex-1 text-start min-w-0">
                <div class="text-sm font-medium text-gray-800 truncate">{{ $user->name }}</div>
                <div class="text-xs text-gray-500 truncate">{{ $user->email }}</div>
            </div>
            <i class="text-xs text-gray-400 fas fa-chevron-up transition-transform" :class="{ 'rotate-180': open }"></i>
        </button>

        <div x-show="open"
             x-transition:enter="transition ease-out duration-100"
             x-transition:enter-start="opacity-0 -translate-y-1"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-75"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-1"
             class="mt-1 space-y-0.5"
             style="display: none;">
            <a href="{{ tenant() ? route('tenant.profile') : route('profile') }}" wire:navigate
               class="flex items-center gap-2 px-2 py-1.5 text-sm text-gray-600 rounded-md hover:bg-gray-50 hover:text-gray-900">
                <i class="w-5 text-xs text-center fas fa-user"></i>
                <span>{{ __('messages.Profile') }}</span>
            </a>
            <form method="POST" action="{{ route('logout') }}" class="w-full">
                @csrf
                <button type="submit"
                        class="flex items-center w-full gap-2 px-2 py-1.5 text-sm text-red-600 rounded-md hover:bg-red-50">
                    <i class="w-5 text-xs text-center fas fa-sign-out-alt"></i>
                    <span>{{ __('messages.Log Out') }}</span>
                </button>
            </form>
        </div>
    </div>
@endif
