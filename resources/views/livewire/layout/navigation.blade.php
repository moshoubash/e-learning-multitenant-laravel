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

<nav x-data="{ open: false }" class="bg-white ">
    <!-- Primary Navigation Menu -->
    <div class="px-4 mx-auto max-w-7xl">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <!-- Logo -->
                <a href="/" class="flex-shrink-0 hidden sm:flex">
                    <x-application-logo class="w-8 h-8 text-gray-800 fill-current" />
                </a>

                <!-- Navigation Links -->
                <div class="hidden @if(app()->getLocale() === 'ar') mr-8 gap-8 @else ml-8 space-x-8 @endif sm:-my-px sm:flex">
                    <x-nav-link :href="tenant() ? route('tenant.dashboard') : route('dashboard')"
                        :active="request()->routeIs('dashboard')" wire:navigate>
                        {{ __('messages.Dashboard') }}
                    </x-nav-link>

                    <select wire:change="changeLanguage($event.target.value)" class="text-sm text-gray-500 border-0 border-b-2 cursor-pointer border-b-gray-100 sparent border-gray hover:border-b-2 hover:border-gray-200 hover:text-gray-500 focus:border-gray-500 focus:ring-0 focus:outline-none">
                        <option value="en" {{ app()->getLocale() === 'en' ? 'selected' : '' }}>
                            {{ __('messages.English') }}
                        </option>
                        <option value="ar" {{ app()->getLocale() === 'ar' ? 'selected' : '' }}>
                            {{ __('messages.Arabic') }}
                        </option>
                    </select>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center px-3 py-2 text-sm font-medium leading-4 text-gray-500 transition duration-150 ease-in-out bg-white border border-transparent rounded-md hover:text-gray-700 focus:outline-none">
                            <div x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name"
                                x-on:profile-updated.window="name = $event.detail.name"></div>

                            <div class="ms-1">
                                <svg class="w-4 h-4 fill-current" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="tenant() ? route('tenant.profile') : route('profile')" wire:navigate>
                            {{ __('messages.Profile') }}
                        </x-dropdown-link>

                        <!-- admin routes  -->
                        @role('admin')
                        <x-dropdown-link :href="route('tenant.admin.users')" wire:navigate>
                            {{ __('messages.Users Management') }}
                        </x-dropdown-link>
                        <x-dropdown-link :href="route('tenant.admin.courses')" wire:navigate>
                            {{ __('messages.Courses Management') }}
                        </x-dropdown-link>
                        <x-dropdown-link :href="route('tenant.admin.quizzes')" wire:navigate>
                            {{ __('messages.Quizzes Management') }}
                        </x-dropdown-link>
                        @endrole
                        @role('instructor')
                        <x-dropdown-link :href="route('tenant.instructor.courses')" wire:navigate>
                            {{ __('messages.Courses') }}
                        </x-dropdown-link>
                        <x-dropdown-link :href="route('tenant.instructor.quizzes')" wire:navigate>
                            {{ __('messages.Quizzes') }}
                        </x-dropdown-link>
                        <x-dropdown-link :href="route('tenant.instructor.assignments')" wire:navigate>
                            {{ __('messages.Assignments') }}
                        </x-dropdown-link>
                        @endrole
                        @role('student')
                        <x-dropdown-link :href="route('tenant.student.courses')" wire:navigate>
                            {{ __('messages.Browse Courses') }}
                        </x-dropdown-link>
                        @endrole

                        <!-- Authentication -->
                        <button wire:click="logout" class="w-full text-start">
                            <x-dropdown-link>
                                {{ __('messages.Log Out') }}
                            </x-dropdown-link>
                        </button>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="flex items-center -me-2 sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 text-gray-400 transition duration-150 ease-in-out rounded-md hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500">
                    <svg class="w-6 h-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="tenant() ? route('tenant.dashboard') : route('dashboard')"
                :active="request()->routeIs('dashboard')" wire:navigate>
                {{ __('messages.Dashboard') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="text-base font-medium text-gray-800"
                    x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name"
                    x-on:profile-updated.window="name = $event.detail.name"></div>
                <div class="text-sm font-medium text-gray-500">{{ auth()->user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-dropdown-link :href="tenant() ? route('tenant.profile') : route('profile')" wire:navigate>
                    {{ __('messages.Profile') }}
                    </x-responsive-nav-link>

                    <!-- Authentication -->
                    <button wire:click="logout" class="w-full text-start">
                        <x-responsive-nav-link>
                            {{ __('messages.Log Out') }}
                        </x-responsive-nav-link>
                    </button>
            </div>
        </div>
    </div>
</nav>
