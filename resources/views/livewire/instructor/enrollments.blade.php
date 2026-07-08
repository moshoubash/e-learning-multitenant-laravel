<div>
    <header class="h-16 flex justify-between items-center px-[24px] bg-surface-container-lowest border-b-2 border-on-surface sticky top-0 z-40">
        <div>
            <h2 class="text-[24px] font-bold text-on-surface leading-none">{{ __('messages.Enrollments') }}</h2>
            <p class="text-[12px] font-medium uppercase text-secondary mt-0.5 tracking-wider">{{ __('messages.Monitor student enrollments in your courses') }}</p>
        </div>
        <div class="flex items-center gap-2">
            @livewire('shared.notification-bell')
        </div>
    </header>

    <div class="p-[24px] max-w-[1400px] mx-auto space-y-6">
        <div class="bg-surface-container-lowest neo-border neo-radius overflow-hidden">
            @include('livewire.instructor.enrollments-components.tables.enrollment-filters')
            @include('livewire.instructor.enrollments-components.tables.enrollments-table')
        </div>
    </div>
</div>
