{{-- Instructor Secondary Sidebar Navigation Items --}}
<x-ui.sidebar title="E-Learning" class="hidden lg:flex">
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
        <div class="p-3 bg-gradient-to-br from-indigo-50 to-purple-50 rounded-xl">
            <div class="flex items-start space-x-3">
                <div class="p-1.5 bg-indigo-100 rounded-lg">
                    <i class="text-xs text-indigo-600 fas fa-gem"></i>
                </div>
                <div class="flex-1">
                    <p class="text-xs font-semibold text-indigo-900">Pro Features</p>
                    <p class="text-[10px] text-indigo-600 mt-0.5">Unlock advanced analytics</p>
                </div>
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
