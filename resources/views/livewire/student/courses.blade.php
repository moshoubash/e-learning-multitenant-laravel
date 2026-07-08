<div>
    <header class="h-16 flex justify-between items-center px-[24px] bg-surface-container-lowest border-b-2 border-on-surface sticky top-0 z-40">
        <div>
            <h2 class="text-[24px] font-bold text-on-surface leading-none">{{ __('messages.Browse Courses') }}</h2>
            <p class="text-[12px] font-medium uppercase text-secondary mt-0.5 tracking-wider">{{ __('messages.Discover new courses and continue learning') }}</p>
        </div>
        <div class="flex items-center gap-2">
            @livewire('shared.notification-bell')
        </div>
    </header>

    <div class="p-[24px] max-w-[1400px] mx-auto">
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            {{-- Course List Sidebar --}}
            <div class="space-y-6 lg:col-span-1">
                @include('livewire.student.courses-components.tables.course-sidebar')
            </div>

            {{-- Course Content Area --}}
            <div class="lg:col-span-2">
                @include('livewire.student.courses-components.tables.course-detail')
            </div>
        </div>
    </div>
</div>
