{{-- Submission Status Chart --}}
<div class="max-w-md mx-auto bg-surface-container-lowest neo-border p-[24px] neo-radius">
    <h3 class="text-[18px] font-bold uppercase tracking-widest text-on-surface leading-none mb-6 text-center">{{ __('messages.Submission Status') }}</h3>
    <x-ui.chart
        :type="$charts['submissionStatus']['type']"
        :labels="$charts['submissionStatus']['labels']"
        :datasets="$charts['submissionStatus']['datasets']"
        :height="300"
        class="!bg-transparent !shadow-none !rounded-none !p-0"
    />
</div>
