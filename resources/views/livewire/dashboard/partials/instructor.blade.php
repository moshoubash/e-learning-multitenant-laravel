{{-- ROW 1: Charts --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
    {{-- Students per Course (col-span-2) --}}
    <div class="lg:col-span-2 bg-surface-container-lowest neo-border p-[24px] neo-radius">
        <h3 class="text-[18px] font-bold uppercase tracking-widest text-on-surface leading-none mb-6">{{ __('messages.Students per Course') }}</h3>
        <x-ui.chart
            :type="$charts['studentsPerCourse']['type']"
            :labels="$charts['studentsPerCourse']['labels']"
            :datasets="$charts['studentsPerCourse']['datasets']"
            :height="280"
            class="!bg-transparent !shadow-none !rounded-none !p-0"
        />
    </div>

    {{-- Submissions Status --}}
    <div class="bg-surface-container-lowest neo-border p-[24px] neo-radius">
        <h3 class="text-[18px] font-bold uppercase tracking-widest text-on-surface leading-none mb-6">{{ __('messages.Submissions Status') }}</h3>
        <x-ui.chart
            :type="$charts['submissionStatus']['type']"
            :labels="$charts['submissionStatus']['labels']"
            :datasets="$charts['submissionStatus']['datasets']"
            :height="280"
            class="!bg-transparent !shadow-none !rounded-none !p-0"
        />
    </div>
</div>

{{-- ROW 2: Quiz Attempts Trend --}}
<div class="bg-surface-container-lowest neo-border p-[24px] neo-radius">
    <h3 class="text-[18px] font-bold uppercase tracking-widest text-on-surface leading-none mb-6">{{ __('messages.Quiz Attempts Trend') }}</h3>
    <x-ui.chart
        :type="$charts['quizAttempts']['type']"
        :labels="$charts['quizAttempts']['labels']"
        :datasets="$charts['quizAttempts']['datasets']"
        :height="220"
        class="!bg-transparent !shadow-none !rounded-none !p-0"
    />
</div>

{{-- ROW 3: Tables --}}
<div class="grid grid-cols-1 lg:grid-cols-5 gap-4">
    {{-- My Courses (col-span-3) --}}
    <div class="lg:col-span-3 bg-surface-container-lowest neo-border neo-radius overflow-hidden">
        <div class="p-[24px] border-b-2 border-on-surface">
            <h3 class="text-[18px] font-bold uppercase tracking-widest text-on-surface leading-none">{{ __('messages.My Courses') }}</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-surface-container-low border-b-2 border-on-surface">
                    <tr>
                        <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-secondary leading-none">{{ __('messages.Course') }}</th>
                        <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-secondary leading-none">{{ __('messages.Status') }}</th>
                        <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-secondary leading-none">{{ __('messages.Students') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E5E5E5]">
                    @forelse($tables['myCourses'] as $course)
                        <tr>
                            <td class="px-6 py-4">
                                <div class="font-bold text-sm text-on-surface">{{ $course->title }}</div>
                                <div class="text-xs text-secondary mt-0.5">{{ $course->sections_count }} {{ __('messages.sections') }}</div>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $statusColors = [
                                        'published' => 'bg-primary-container text-on-primary-container',
                                        'draft' => 'bg-surface-container-high text-on-surface',
                                        'archived' => 'bg-surface-container text-secondary',
                                    ];
                                    $statusClass = $statusColors[$course->status] ?? 'bg-surface-container text-secondary';
                                @endphp
                                <span class="inline-block px-2 py-0.5 neo-border-sm neo-radius text-[10px] font-bold leading-none {{ $statusClass }}">
                                    {{ __(ucfirst($course->status)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 font-bold text-sm text-on-surface">{{ $course->enrollments_count }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="px-6 py-8 text-center text-sm text-secondary">{{ __('messages.No Enrolled Courses') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Recent Enrollments (col-span-2) --}}
    <div class="lg:col-span-2 bg-surface-container-lowest neo-border neo-radius overflow-hidden">
        <div class="p-[24px] border-b-2 border-on-surface">
            <h3 class="text-[18px] font-bold uppercase tracking-widest text-on-surface leading-none">{{ __('messages.Recent Enrollments') }}</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-surface-container-low border-b-2 border-on-surface">
                    <tr>
                        <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-secondary leading-none">{{ __('messages.Student') }}</th>
                        <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-secondary leading-none">{{ __('messages.Course') }}</th>
                        <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-secondary leading-none">{{ __('messages.When') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E5E5E5]">
                    @forelse($tables['recentEnrollments'] as $enrollment)
                        <tr>
                            <td class="px-6 py-4 font-medium text-sm text-on-surface">{{ optional($enrollment->user)->name ?? '—' }}</td>
                            <td class="px-6 py-4 text-sm text-on-surface">{{ optional($enrollment->course)->title ?? '—' }}</td>
                            <td class="px-6 py-4 text-xs text-secondary">{{ $enrollment->enrolled_at?->diffForHumans() }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="px-6 py-8 text-center text-sm text-secondary">{{ __('messages.No Enrolled Courses') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ROW 4: Recent Submissions --}}
<div class="bg-surface-container-lowest neo-border neo-radius overflow-hidden">
    <div class="p-[24px] border-b-2 border-on-surface">
        <h3 class="text-[18px] font-bold uppercase tracking-widest text-on-surface leading-none">{{ __('messages.Recent Submissions') }}</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-surface-container-low border-b-2 border-on-surface">
                <tr>
                    <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-secondary leading-none">{{ __('messages.Student') }}</th>
                    <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-secondary leading-none">{{ __('messages.Assignment') }}</th>
                    <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-secondary leading-none">{{ __('messages.Course') }}</th>
                    <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-secondary leading-none">{{ __('messages.Status') }}</th>
                    <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-secondary leading-none">{{ __('messages.Submitted At') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#E5E5E5]">
                @forelse($tables['recentSubmissions'] as $submission)
                    <tr>
                        <td class="px-6 py-4 font-medium text-sm text-on-surface">{{ optional($submission->student)->name ?? '—' }}</td>
                        <td class="px-6 py-4 text-sm text-on-surface">{{ optional($submission->assignment)->title ?? '—' }}</td>
                        <td class="px-6 py-4 text-sm text-on-surface">{{ optional(optional($submission->assignment)->course)->title ?? '—' }}</td>
                        <td class="px-6 py-4">
                            @php
                                $isGraded = $submission->status === 'graded';
                            @endphp
                            <span class="inline-block px-2 py-0.5 neo-border-sm neo-radius text-[10px] font-bold leading-none {{ $isGraded ? 'bg-primary-container text-on-primary-container' : 'bg-surface-container-high text-on-surface' }}">
                                {{ $isGraded ? __('messages.Graded') : __('messages.Pending') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-xs text-secondary">{{ $submission->submitted_at?->diffForHumans() }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-6 py-8 text-center text-sm text-secondary">{{ __('messages.No submissions found') }}</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>