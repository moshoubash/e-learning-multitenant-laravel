{{-- Instructor Sidebar Navigation Items --}}
<x-ui.sidebar title="E-Learning" class="hidden lg:flex">
    {{-- Navigation slot (top bar moved into sidebar) --}}
    <x-slot name="navigation">
        <livewire:layout.navigation />
    </x-slot>
    <x-slot name="navigationIcons">
        <livewire:layout.navigation />
    </x-slot>

    {{-- Menu group: Learning Content --}}
    <div class="px-3 py-2">
        <p class="mb-1 px-2 text-[11px] font-semibold uppercase tracking-widest text-gray-400">{{ __('messages.Learning Content') }}</p>

        <nav class="space-y-0.5">
            <a href="{{ route('tenant.instructor.courses') }}" wire:navigate
               class="flex items-center px-2 py-1.5 text-sm rounded-lg {{ request()->routeIs('tenant.instructor.courses*') ? 'bg-indigo-50 text-indigo-700 font-medium' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                <i class="w-5 h-4 text-xs text-center fas fa-book-open"></i>
                <span class="ml-3">{{ __('messages.Course') }}</span>
            </a>
            <a href="{{ route('tenant.instructor.quizzes') }}" wire:navigate
               class="flex items-center px-2 py-1.5 text-sm rounded-lg {{ request()->routeIs('tenant.instructor.quizzes*') ? 'bg-indigo-50 text-indigo-700 font-medium' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                <i class="w-5 h-4 text-xs text-center fas fa-question-circle"></i>
                <span class="ml-3">{{ __('messages.Quiz') }}</span>
            </a>
            <a href="{{ route('tenant.instructor.assignments') }}" wire:navigate
               class="flex items-center px-2 py-1.5 text-sm rounded-lg {{ request()->routeIs('tenant.instructor.assignments*') ? 'bg-indigo-50 text-indigo-700 font-medium' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                <i class="w-5 h-4 text-xs text-center fas fa-file-alt"></i>
                <span class="ml-3">{{ __('messages.Assignments') }}</span>
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
            <a href="{{ route('tenant.instructor.courses') }}" wire:navigate class="p-2 text-gray-400 transition-colors hover:text-indigo-600">
                <i class="text-sm fas fa-book-open"></i>
            </a>
            <a href="{{ route('tenant.instructor.quizzes') }}" wire:navigate class="p-2 text-gray-400 transition-colors hover:text-indigo-600">
                <i class="text-sm fas fa-question-circle"></i>
            </a>
            <a href="{{ route('tenant.instructor.assignments') }}" wire:navigate class="p-2 text-gray-400 transition-colors hover:text-indigo-600">
                <i class="text-sm fas fa-file-alt"></i>
            </a>
        </nav>
    </x-slot>
</x-ui.sidebar>
