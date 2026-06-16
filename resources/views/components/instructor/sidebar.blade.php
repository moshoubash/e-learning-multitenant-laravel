@php
    $user = auth()->user();
    $currentLocale = app()->getLocale();
@endphp

<aside class="fixed ltr:left-0 rtl:right-0 top-0 h-full w-[240px] flex flex-col bg-surface-container-lowest ltr:border-r-2 rtl:border-l-2 border-on-surface z-50">
    {{-- Brand Header --}}
    <div class="p-6 flex items-center gap-3 border-b-2 border-on-surface">
        <div class="w-10 h-10 neo-border bg-primary-container flex items-center justify-center neo-radius">
            <i class="fas fa-chalkboard-teacher text-on-surface"></i>
        </div>
        <div>
            <h1 class="text-[24px] font-bold text-on-surface uppercase leading-none tracking-[0.08em]">LEARN_OS</h1>
            <p class="text-[10px] font-bold uppercase tracking-widest text-secondary mt-1">Instructor Portal</p>
        </div>
    </div>

    {{-- Navigation Links --}}
    <nav class="flex-1 p-4 space-y-2 overflow-y-auto no-scrollbar">
        <a href="{{ route('tenant.dashboard') }}" wire:navigate
           class="flex items-center gap-3 px-4 py-3 neo-radius {{ request()->routeIs('tenant.dashboard') ? 'bg-primary-container text-on-primary-container border-2 border-on-surface font-bold' : 'text-on-surface hover:bg-surface-container-high transition-colors duration-100' }}">
            <i class="fas fa-home w-5 text-center"></i>
            <span class="text-[14px] font-medium">{{ __('messages.Dashboard') }}</span>
        </a>
        <a href="{{ route('tenant.instructor.courses') }}" wire:navigate
           class="flex items-center gap-3 px-4 py-3 neo-radius {{ request()->routeIs('tenant.instructor.courses*') ? 'bg-primary-container text-on-primary-container border-2 border-on-surface font-bold' : 'text-on-surface hover:bg-surface-container-high transition-colors duration-100' }}">
            <i class="fas fa-book-open w-5 text-center"></i>
            <span class="text-[14px] font-medium">{{ __('messages.Courses') }}</span>
        </a>
        <a href="{{ route('tenant.instructor.quizzes') }}" wire:navigate
           class="flex items-center gap-3 px-4 py-3 neo-radius {{ request()->routeIs('tenant.instructor.quizzes*') ? 'bg-primary-container text-on-primary-container border-2 border-on-surface font-bold' : 'text-on-surface hover:bg-surface-container-high transition-colors duration-100' }}">
            <i class="fas fa-question-circle w-5 text-center"></i>
            <span class="text-[14px] font-medium">{{ __('messages.Quizzes') }}</span>
        </a>
        <a href="{{ route('tenant.instructor.assignments') }}" wire:navigate
           class="flex items-center gap-3 px-4 py-3 neo-radius {{ request()->routeIs('tenant.instructor.assignments*') ? 'bg-primary-container text-on-primary-container border-2 border-on-surface font-bold' : 'text-on-surface hover:bg-surface-container-high transition-colors duration-100' }}">
            <i class="fas fa-file-alt w-5 text-center"></i>
            <span class="text-[14px] font-medium">{{ __('messages.Assignments') }}</span>
        </a>
        <a href="{{ route('tenant.profile') }}" wire:navigate
           class="flex items-center gap-3 px-4 py-3 neo-radius {{ request()->routeIs('tenant.profile') ? 'bg-primary-container text-on-primary-container border-2 border-on-surface font-bold' : 'text-on-surface hover:bg-surface-container-high transition-colors duration-100' }}">
            <i class="fas fa-user w-5 text-center"></i>
            <span class="text-[14px] font-medium">{{ __('messages.Profile') }}</span>
        </a>
    </nav>

    {{-- Footer / Toggles --}}
    <div class="p-4 border-t-2 border-on-surface space-y-4">
        <div class="flex neo-border neo-radius overflow-hidden">
            <a href="{{ url('lang/en') }}"
               class="flex-1 py-2 text-[12px] font-bold text-center {{ $currentLocale === 'en' ? 'bg-primary-container text-on-surface' : 'bg-surface-container-lowest text-secondary ltr:border-r-2 rtl:border-l-2 border-on-surface' }}">EN</a>
            <a href="{{ url('lang/ar') }}"
               class="flex-1 py-2 text-[12px] font-bold text-center {{ $currentLocale === 'ar' ? 'bg-primary-container text-on-surface' : 'bg-surface-container-lowest text-secondary ltr:border-l-2 rtl:border-r-2 border-on-surface' }}">AR</a>
        </div>
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 neo-border bg-surface-container-high flex items-center justify-center neo-radius font-bold text-on-surface">
                {{ substr($user?->name ?? 'U', 0, 2) }}
            </div>
            <div class="flex-1">
                <p class="font-bold text-on-surface text-sm">{{ $user?->name ?? 'User' }}</p>
                <p class="text-xs text-secondary">ID: {{ $user?->id ?? '—' }}</p>
            </div>
            <div class="flex items-center gap-1">
                <a href="{{ route('tenant.profile') }}" wire:navigate class="w-8 h-8 flex items-center justify-center hover:bg-surface-container-high neo-radius transition-colors" title="{{ __('messages.Profile') }}">
                    <i class="fas fa-cog text-on-surface"></i>
                </a>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="w-8 h-8 flex items-center justify-center hover:bg-surface-container-high neo-radius transition-colors" title="{{ __('messages.Log Out') }}">
                        <i class="fas fa-sign-out-alt text-on-surface"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</aside>