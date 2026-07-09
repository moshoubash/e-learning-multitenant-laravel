<div>
    <header class="px-[24px] py-[9px] bg-surface-container-lowest border-b-2 border-on-surface sticky top-0 z-40">
        <div class="flex items-start justify-between">
            <div class="min-w-0 flex-1 ltr:mr-3 rtl:ml-3">
                <h2 class="text-lg sm:text-[24px] font-bold text-on-surface leading-tight sm:leading-none">{{ __('messages.Enrollment History') }}</h2>
                <p class="text-[10px] sm:text-[12px] font-medium uppercase text-secondary mt-0.5 tracking-wider">{{ __('messages.View all your course enrollments and progress') }}</p>
            </div>
            <div class="flex items-center gap-2 shrink-0">
                @livewire('shared.notification-bell')
            </div>
        </div>
    </header>

    <div class="p-[24px] max-w-[1400px] mx-auto space-y-6">
        <div class="overflow-hidden bg-surface-container-lowest neo-border neo-radius">
            @include('livewire.student.enrollments-history-components.tables.enrollment-filters')
            @include('livewire.student.enrollments-history-components.tables.enrollment-list')
        </div>
    </div>
</div>
