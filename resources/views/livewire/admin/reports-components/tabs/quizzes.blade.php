{{-- Quiz Attempts Trend Chart --}}
<div class="bg-surface-container-lowest neo-border p-[24px] neo-radius">
    <h3 class="text-[18px] font-bold uppercase tracking-widest text-on-surface leading-none mb-6">{{ __('messages.Quiz Attempts Trend') }}</h3>
    <x-ui.chart
        :type="$charts['quizAttemptsTrend']['type']"
        :labels="$charts['quizAttemptsTrend']['labels']"
        :datasets="$charts['quizAttemptsTrend']['datasets']"
        :height="280"
        class="!bg-transparent !shadow-none !rounded-none !p-0"
    />
</div>

{{-- Quiz Performance Table --}}
@if(isset($tables['quizPerformance']) && count($tables['quizPerformance']))
    <div class="overflow-hidden bg-surface-container-lowest neo-border neo-radius">
        <div class="p-[24px] border-b-2 border-on-surface">
            <h3 class="text-[18px] font-bold uppercase tracking-widest text-on-surface leading-none">{{ __('messages.Quiz Performance') }}</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full ltr:text-left rtl:text-right">
                <thead class="border-b-2 bg-surface-container-low border-on-surface">
                    <tr>
                        <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-secondary leading-none">{{ __('messages.Quiz') }}</th>
                        <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-secondary leading-none">{{ __('messages.Course') }}</th>
                        <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-secondary leading-none">{{ __('messages.Attempts') }}</th>
                        <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-secondary leading-none">{{ __('messages.Passed') }}</th>
                        <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-secondary leading-none">{{ __('messages.Pass Rate') }}</th>
                        <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-secondary leading-none">{{ __('messages.Avg Score') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E5E5E5]">
                    @foreach($tables['quizPerformance'] as $quiz)
                        <tr>
                            <td class="px-6 py-4 text-sm font-bold text-on-surface">{{ $quiz['quiz'] }}</td>
                            <td class="px-6 py-4 text-sm text-secondary">{{ $quiz['course'] }}</td>
                            <td class="px-6 py-4 text-sm text-on-surface">{{ $quiz['attempts'] }}</td>
                            <td class="px-6 py-4 text-sm text-on-surface">{{ $quiz['passed'] }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-block px-2 py-0.5 neo-border-sm neo-radius text-[10px] font-bold leading-none
                                    {{ $quiz['pass_rate'] >= 70 ? 'bg-primary-container text-on-primary-container' : ($quiz['pass_rate'] >= 40 ? 'bg-surface-container-high text-on-surface' : 'bg-error text-white') }}">
                                    {{ $quiz['pass_rate'] }}%
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-on-surface">{{ $quiz['avg_score'] }}%</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif
