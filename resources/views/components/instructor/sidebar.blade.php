@php
    $user = auth()->user();
    $currentLocale = app()->getLocale();
@endphp

<aside class="fixed ltr:left-0 rtl:right-0 top-0 h-full w-[240px] hidden lg:flex flex-col bg-surface-container-lowest ltr:border-r-2 rtl:border-l-2 border-on-surface z-50">
    {{-- Brand Header --}}
    <div class="flex items-center h-16 gap-3 p-6 border-b-2 border-on-surface">
        <div class="flex items-center justify-center w-10 h-10 neo-border bg-primary-container neo-radius">
            <i class="fas fa-chalkboard-teacher text-on-surface"></i>
        </div>
        <div>
            <h1 class="text-[16px] font-bold text-on-surface uppercase leading-none tracking-[0.08em]">GRID LMS</h1>
            <p class="text-[10px] font-bold uppercase tracking-widest text-secondary mt-1">{{ __('messages.Instructor Portal') }}</p>
        </div>
    </div>

    {{-- Navigation Links --}}
    <nav class="flex-1 p-4 space-y-2 overflow-y-auto no-scrollbar">
        <a href="{{ route('tenant.dashboard') }}" wire:navigate
           class="flex items-center gap-3 px-4 py-3 neo-radius {{ request()->routeIs('tenant.dashboard') ? 'bg-primary-container text-on-primary-container border-2 border-on-surface font-bold' : 'text-on-surface hover:bg-surface-container-high transition-colors duration-100' }}">
            <i class="w-5 text-center fas fa-home"></i>
            <span class="text-[14px] font-medium">{{ __('messages.Dashboard') }}</span>
        </a>
        <a href="{{ route('tenant.notifications') }}" wire:navigate
           class="flex items-center gap-3 px-4 py-3 neo-radius {{ request()->routeIs('tenant.notifications*') ? 'bg-primary-container text-on-primary-container border-2 border-on-surface font-bold' : 'text-on-surface hover:bg-surface-container-high transition-colors duration-100' }}">
            <i class="w-5 text-center fas fa-bell"></i>
            <span class="text-[14px] font-medium">{{ __('messages.Notifications') }}</span>
        </a>
        <a href="{{ route('tenant.instructor.courses') }}" wire:navigate
           class="flex items-center gap-3 px-4 py-3 neo-radius {{ request()->routeIs('tenant.instructor.courses*') ? 'bg-primary-container text-on-primary-container border-2 border-on-surface font-bold' : 'text-on-surface hover:bg-surface-container-high transition-colors duration-100' }}">
            <i class="w-5 text-center fas fa-book-open"></i>
            <span class="text-[14px] font-medium">{{ __('messages.Courses') }}</span>
        </a>
        <a href="{{ route('tenant.instructor.quizzes') }}" wire:navigate
           class="flex items-center gap-3 px-4 py-3 neo-radius {{ request()->routeIs('tenant.instructor.quizzes*') ? 'bg-primary-container text-on-primary-container border-2 border-on-surface font-bold' : 'text-on-surface hover:bg-surface-container-high transition-colors duration-100' }}">
            <i class="w-5 text-center fas fa-question-circle"></i>
            <span class="text-[14px] font-medium">{{ __('messages.Quizzes') }}</span>
        </a>
        <a href="{{ route('tenant.instructor.assignments') }}" wire:navigate
           class="flex items-center gap-3 px-4 py-3 neo-radius {{ request()->routeIs('tenant.instructor.assignments*') ? 'bg-primary-container text-on-primary-container border-2 border-on-surface font-bold' : 'text-on-surface hover:bg-surface-container-high transition-colors duration-100' }}">
            <i class="w-5 text-center fas fa-file-alt"></i>
            <span class="text-[14px] font-medium">{{ __('messages.Assignments') }}</span>
        </a>
        <a href="{{ route('tenant.profile') }}" wire:navigate
           class="flex items-center gap-3 px-4 py-3 neo-radius {{ request()->routeIs('tenant.profile') ? 'bg-primary-container text-on-primary-container border-2 border-on-surface font-bold' : 'text-on-surface hover:bg-surface-container-high transition-colors duration-100' }}">
            <i class="w-5 text-center fas fa-user"></i>
            <span class="text-[14px] font-medium">{{ __('messages.Profile') }}</span>
        </a>
    </nav>

    {{-- Footer / Toggles --}}
    <div class="p-4 space-y-4 border-t-2 border-on-surface">
        <div class="flex overflow-hidden neo-border neo-radius">
            <a href="{{ url('lang/en') }}"
               class="flex-1 py-2 text-[12px] font-bold text-center {{ $currentLocale === 'en' ? 'bg-primary-container text-on-primary-container' : 'bg-surface-container-lowest text-secondary ltr:border-r-2 rtl:border-l-2 border-on-surface' }}">EN</a>
            <a href="{{ url('lang/ar') }}"
               class="flex-1 py-2 text-[12px] font-bold text-center {{ $currentLocale === 'ar' ? 'bg-primary-container text-on-primary-container' : 'bg-surface-container-lowest text-secondary ltr:border-l-2 rtl:border-r-2 border-on-surface' }}">AR</a>
        </div>
        <div class="flex items-center gap-3">
            <div class="flex items-center justify-center w-10 h-10 font-bold neo-border bg-surface-container-high neo-radius text-on-surface">
                {{ substr($user?->name ?? 'U', 0, 2) }}
            </div>
            <div class="flex-1">
                <p class="text-sm font-bold text-on-surface">{{ $user?->name ?? 'User' }}</p>
                <p class="text-xs text-secondary">ID: {{ $user?->id ?? '—' }}</p>
            </div>
            <div class="flex items-center gap-1">
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="flex items-center justify-center w-8 h-8 transition-colors hover:bg-error hover:text-white neo-radius" title="{{ __('messages.Log Out') }}">
                        <i class="fas fa-sign-out-alt text-error"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</aside>
