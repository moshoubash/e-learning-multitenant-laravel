@php
    $locale = app()->getLocale();
@endphp

<div x-data="{ open: false }" class="relative">
    <button @click.prevent="open = !open" @click.outside="open = false"
        class="flex items-center justify-center w-8 h-8 transition-colors neo-border-sm neo-radius bg-surface-container-low text-on-surface hover:bg-on-surface hover:text-white">
        <i class="text-xs fas fa-ellipsis-v"></i>
    </button>

    <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
        class="absolute z-50 w-48 mt-2 shadow-lg ltr:right-0 rtl:left-0 neo-border neo-radius bg-surface-container-lowest ltr:origin-top-right rtl:origin-top-left"
        style="display: none;">
        <div class="p-2 space-y-1">
            <div class="flex items-center justify-center gap-2 px-3 py-2">
                <a href="{{ url('lang/en') }}"
                    class="px-2 py-1 text-xs font-bold uppercase transition-colors neo-border-sm neo-radius {{ $locale === 'en' ? 'bg-primary-container text-on-primary-container' : 'text-secondary hover:text-on-surface' }}">
                    EN
                </a>
                <span class="text-xs text-secondary">|</span>
                <a href="{{ url('lang/ar') }}"
                    class="px-2 py-1 text-xs font-bold uppercase transition-colors neo-border-sm neo-radius {{ $locale === 'ar' ? 'bg-primary-container text-on-primary-container' : 'text-secondary hover:text-on-surface' }}">
                    AR
                </a>
            </div>

            <hr class="border-on-surface/20">

            @if(auth()->user()->hasRole('admin'))
                <a href="{{ route('tenant.admin.design') }}"
                        class="flex items-center w-full px-3 py-2 text-xs font-bold transition-colors neo-radius hover:bg-primary-container hover:text-white">
                        <i class="fas fa-palette ltr:mr-2 rtl:ml-2"></i>
                        {{ __('messages.Design') }}
                </a>
            @endif

            @if(auth()->user()->hasRole('instructor'))
                <a href="{{ route('tenant.instructor.quizzes') }}"
                        class="flex items-center w-full px-3 py-2 text-xs font-bold transition-colors neo-radius hover:bg-primary-container hover:text-white">
                        <i class="fas fa-question-circle ltr:mr-2 rtl:ml-2"></i>
                        {{ __('messages.Quizzes') }}
                </a>
            @endif



            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="flex items-center w-full px-3 py-2 text-xs font-bold transition-colors neo-border-sm neo-radius text-error hover:bg-error hover:text-white">
                    <i class="fas fa-sign-out-alt ltr:mr-2 rtl:ml-2"></i>
                    {{ __('messages.Log Out') }}
                </button>
            </form>
        </div>
    </div>
</div>
