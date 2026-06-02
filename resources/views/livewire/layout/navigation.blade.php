<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component {
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }

    public function changeLanguage($locale)
    {
        return redirect()->to('lang/' . $locale);
    }
}; ?>

<nav x-data="{ open: false }" class="bg-white">
    <!-- Primary Navigation Menu (compact for sidebar) -->
    <div class="flex items-center justify-between px-3 h-14">
        <div class="flex items-center gap-2">
            <!-- Logo -->
            <a href="/" class="flex-shrink-0">
                <x-application-logo class="text-gray-800 fill-current w-7 h-7" />
            </a>

            <!-- Dashboard Link -->
            <x-nav-link :href="tenant() ? route('tenant.dashboard') : route('dashboard')"
                :active="request()->routeIs('dashboard')" wire:navigate class="text-xs !px-1.5">
                {{ __('messages.Dashboard') }}
            </x-nav-link>

            <!-- Language Switcher -->
            <select wire:change="changeLanguage($event.target.value)" class="text-xs text-gray-500 bg-transparent border-0 border-b-2 cursor-pointer border-b-gray-100 hover:border-b-2 hover:border-gray-200 focus:border-gray-500 focus:ring-0 focus:outline-none">
                <option value="en" {{ app()->getLocale() === 'en' ? 'selected' : '' }}>EN</option>
                <option value="ar" {{ app()->getLocale() === 'ar' ? 'selected' : '' }}>AR</option>
            </select>
        </div>

        <!-- User Dropdown -->
        <div class="relative">
            <button @click="open = ! open"
                class="flex items-center gap-1 p-1 text-xs font-medium text-gray-600 transition-colors rounded-md hover:bg-gray-100 focus:outline-none">
                <div class="flex items-center justify-center w-6 h-6 text-xs font-bold text-indigo-700 bg-indigo-100 rounded-full">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
            </button>

            <!-- Dropdown Menu -->
            <div x-show="open" @click.away="open = false"
                class="absolute z-50 w-48 mt-1 bg-white border border-gray-200 rounded-lg shadow-lg start-0"
                style="display: none;">
                <div class="px-3 py-2 text-xs text-gray-500 truncate border-b border-gray-100">
                    {{ auth()->user()->name }}
                </div>
                <div class="py-1">
                    <x-dropdown-link :href="tenant() ? route('tenant.profile') : route('profile')" wire:navigate class="text-xs">
                        {{ __('messages.Profile') }}
                    </x-dropdown-link>

                    @role('admin')
                    <x-dropdown-link :href="route('tenant.admin.users')" wire:navigate class="text-xs">
                        {{ __('messages.Users Management') }}
                    </x-dropdown-link>
                    <x-dropdown-link :href="route('tenant.admin.courses')" wire:navigate class="text-xs">
                        {{ __('messages.Courses Management') }}
                    </x-dropdown-link>
                    <x-dropdown-link :href="route('tenant.admin.quizzes')" wire:navigate class="text-xs">
                        {{ __('messages.Quizzes Management') }}
                    </x-dropdown-link>
                    @endrole
                    @role('instructor')
                    <x-dropdown-link :href="route('tenant.instructor.courses')" wire:navigate class="text-xs">
                        {{ __('messages.Courses') }}
                    </x-dropdown-link>
                    <x-dropdown-link :href="route('tenant.instructor.quizzes')" wire:navigate class="text-xs">
                        {{ __('messages.Quizzes') }}
                    </x-dropdown-link>
                    <x-dropdown-link :href="route('tenant.instructor.assignments')" wire:navigate class="text-xs">
                        {{ __('messages.Assignments') }}
                    </x-dropdown-link>
                    @endrole
                    @role('student')
                    <x-dropdown-link :href="route('tenant.student.courses')" wire:navigate class="text-xs">
                        {{ __('messages.Browse Courses') }}
                    </x-dropdown-link>
                    @endrole

                    <button wire:click="logout" class="w-full text-start">
                        <x-dropdown-link class="text-xs">
                            {{ __('messages.Log Out') }}
                        </x-dropdown-link>
                    </button>
                </div>
            </div>
        </div>
    </div>
</nav>
