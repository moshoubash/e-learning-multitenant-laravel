{{-- Charts Row --}}
<div class="grid grid-cols-1 gap-4 lg:grid-cols-3">
    <div class="bg-surface-container-lowest neo-border p-[24px] neo-radius">
        <h3 class="text-[18px] font-bold uppercase tracking-widest text-on-surface leading-none mb-6">{{ __('messages.Users by Role') }}</h3>
        <x-ui.chart
            :type="$charts['usersByRole']['type']"
            :labels="$charts['usersByRole']['labels']"
            :datasets="$charts['usersByRole']['datasets']"
            :height="280"
            class="!bg-transparent !shadow-none !rounded-none !p-0"
        />
    </div>
    <div class="lg:col-span-2 bg-surface-container-lowest neo-border p-[24px] neo-radius">
        <h3 class="text-[18px] font-bold uppercase tracking-widest text-on-surface leading-none mb-6">{{ __('messages.New Users Trend') }}</h3>
        <x-ui.chart
            :type="$charts['userTrend']['type']"
            :labels="$charts['userTrend']['labels']"
            :datasets="$charts['userTrend']['datasets']"
            :height="280"
            class="!bg-transparent !shadow-none !rounded-none !p-0"
        />
    </div>
</div>

{{-- Top Students Table --}}
@if(isset($tables['topStudents']) && count($tables['topStudents']))
    <div class="overflow-hidden bg-surface-container-lowest neo-border neo-radius">
        <div class="p-[24px] border-b-2 border-on-surface">
            <h3 class="text-[18px] font-bold uppercase tracking-widest text-on-surface leading-none">{{ __('messages.Top Students by XP') }}</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full ltr:text-left rtl:text-right">
                <thead class="border-b-2 bg-surface-container-low border-on-surface">
                    <tr>
                        <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-secondary leading-none">#</th>
                        <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-secondary leading-none">{{ __('messages.Name') }}</th>
                        <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-secondary leading-none">{{ __('messages.Email') }}</th>
                        <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-secondary leading-none">{{ __('messages.XP Points') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E5E5E5]">
                    @foreach($tables['topStudents'] as $student)
                        <tr>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center justify-center w-6 h-6 text-[10px] font-bold neo-border-sm neo-radius
                                    {{ $student['rank'] === 1 ? 'bg-[#FFD600] text-on-surface' : ($student['rank'] === 2 ? 'bg-surface-container-high text-on-surface' : ($student['rank'] === 3 ? 'bg-[#CD7F32] text-white' : 'bg-surface-container text-secondary')) }}">
                                    {{ $student['rank'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm font-bold text-on-surface">{{ $student['name'] }}</td>
                            <td class="px-6 py-4 text-sm text-secondary">{{ $student['email'] }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-block px-2 py-0.5 neo-border-sm neo-radius text-[10px] font-bold leading-none bg-primary-container text-on-primary-container">
                                    {{ number_format($student['points']) }} XP
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif
