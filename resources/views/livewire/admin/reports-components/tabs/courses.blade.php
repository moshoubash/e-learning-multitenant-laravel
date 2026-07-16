{{-- Charts Row --}}
<div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
    <div class="bg-surface-container-lowest neo-border p-[24px] neo-radius">
        <h3 class="text-[18px] font-bold uppercase tracking-widest text-on-surface leading-none mb-6">{{ __('messages.Top Courses by Enrollment') }}</h3>
        <x-ui.chart
            :type="$charts['courseEnrollments']['type']"
            :labels="$charts['courseEnrollments']['labels']"
            :datasets="$charts['courseEnrollments']['datasets']"
            :height="300"
            class="!bg-transparent !shadow-none !rounded-none !p-0"
        />
    </div>
    <div class="bg-surface-container-lowest neo-border p-[24px] neo-radius">
        <h3 class="text-[18px] font-bold uppercase tracking-widest text-on-surface leading-none mb-6">{{ __('messages.Completion Rates') }}</h3>
        <x-ui.chart
            :type="$charts['completionRates']['type']"
            :labels="$charts['completionRates']['labels']"
            :datasets="$charts['completionRates']['datasets']"
            :height="300"
            class="!bg-transparent !shadow-none !rounded-none !p-0"
        />
    </div>
</div>
