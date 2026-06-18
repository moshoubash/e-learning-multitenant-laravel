{{-- ROW 1: Charts --}}
<div class="grid grid-cols-1 gap-4 lg:grid-cols-3">
    {{-- Enrollment Trends (col-span-2) --}}
    <div class="lg:col-span-2 bg-surface-container-lowest neo-border p-[24px] neo-radius">
        <h3 class="text-[18px] font-bold uppercase tracking-widest text-on-surface leading-none mb-6">{{ __('messages.Enrollments & New Users') }}</h3>
        <x-ui.chart
            :type="$charts['enrollmentTrend']['type']"
            :labels="$charts['enrollmentTrend']['labels']"
            :datasets="$charts['enrollmentTrend']['datasets']"
            :height="280"
            class="!bg-transparent !shadow-none !rounded-none !p-0"
        />
    </div>

    {{-- Users by Role --}}
    <div class="bg-surface-container-lowest neo-border p-[24px] neo-radius">
        <h3 class="text-[18px] font-bold uppercase tracking-widest text-on-surface leading-none mb-6">{{ __('messages.Users by Role') }}</h3>
        <x-ui.chart
            :type="$charts['userRoles']['type']"
            :labels="$charts['userRoles']['labels']"
            :datasets="$charts['userRoles']['datasets']"
            :height="280"
            class="!bg-transparent !shadow-none !rounded-none !p-0"
        />
    </div>
</div>

{{-- ROW 2: Courses by Status + Recent Users --}}
<div class="grid grid-cols-1 gap-4 lg:grid-cols-5">
    {{-- Courses by Status (col-span-2) --}}
    <div class="lg:col-span-2 bg-surface-container-lowest neo-border p-[24px] neo-radius">
        <h3 class="text-[18px] font-bold uppercase tracking-widest text-on-surface leading-none mb-6">{{ __('messages.Courses by Status') }}</h3>
        <x-ui.chart
            :type="$charts['courseStatus']['type']"
            :labels="$charts['courseStatus']['labels']"
            :datasets="$charts['courseStatus']['datasets']"
            :height="220"
            class="!bg-transparent !shadow-none !rounded-none !p-0"
        />
    </div>

    {{-- Recent Users (col-span-3) --}}
    <div class="overflow-hidden lg:col-span-3 bg-surface-container-lowest neo-border neo-radius">
        <div class="p-[24px] border-b-2 border-on-surface">
            <h3 class="text-[18px] font-bold uppercase tracking-widest text-on-surface leading-none">{{ __('messages.Latest Users') }}</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full ltr:text-left rtl:text-right">
                <thead class="border-b-2 bg-surface-container-low border-on-surface">
                    <tr>
                        <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-secondary leading-none">{{ __('messages.Name') }}</th>
                        <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-secondary leading-none">{{ __('messages.Role') }}</th>
                        <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-secondary leading-none">{{ __('messages.Created At') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E5E5E5]">
                    @forelse($tables['recentUsers'] as $user)
                        @php $roleName = optional($user->roles->first())->name ?? 'student'; @endphp
                        <tr>
                            <td class="px-6 py-4">
                                <div class="text-sm font-bold text-on-surface">{{ $user->name }}</div>
                                <div class="text-xs text-secondary mt-0.5">{{ $user->email }}</div>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $roleColors = [
                                        'admin' => 'bg-error text-white',
                                        'instructor' => 'bg-surface-container-high text-on-surface',
                                        'student' => 'bg-primary-container text-on-primary-container',
                                    ];
                                @endphp
                                <span class="inline-block px-2 py-0.5 neo-border-sm neo-radius text-[10px] font-bold leading-none {{ $roleColors[$roleName] ?? 'bg-surface-container text-secondary' }}">
                                    {{ __('messages.' . ucfirst($roleName)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-xs text-secondary">{{ $user->created_at->diffForHumans() }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="px-6 py-8 text-sm text-center text-secondary">{{ __('messages.No Enrolled Courses') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ROW 3: Tables --}}
<div class="grid grid-cols-1 gap-4 lg:grid-cols-5">
    {{-- Recent Enrollments (col-span-3) --}}
    <div class="overflow-hidden lg:col-span-3 bg-surface-container-lowest neo-border neo-radius">
        <div class="p-[24px] border-b-2 border-on-surface">
            <h3 class="text-[18px] font-bold uppercase tracking-widest text-on-surface leading-none">{{ __('messages.Recent Enrollments') }}</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full ltr:text-left rtl:text-right">
                <thead class="border-b-2 bg-surface-container-low border-on-surface">
                    <tr>
                        <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-secondary leading-none">{{ __('messages.Student') }}</th>
                        <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-secondary leading-none">{{ __('messages.Course') }}</th>
                        <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-secondary leading-none">{{ __('messages.When') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E5E5E5]">
                    @forelse($tables['recentEnrollments'] as $enrollment)
                        <tr>
                            <td class="px-6 py-4 text-sm font-medium text-on-surface">{{ optional($enrollment->user)->name ?? '—' }}</td>
                            <td class="px-6 py-4 text-sm text-on-surface">{{ optional($enrollment->course)->title ?? '—' }}</td>
                            <td class="px-6 py-4 text-xs text-secondary">{{ $enrollment->enrolled_at?->diffForHumans() }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="px-6 py-8 text-sm text-center text-secondary">{{ __('messages.No Enrolled Courses') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Top Courses (col-span-2) --}}
    <div class="overflow-hidden lg:col-span-2 bg-surface-container-lowest neo-border neo-radius">
        <div class="p-[24px] border-b-2 border-on-surface">
            <h3 class="text-[18px] font-bold uppercase tracking-widest text-on-surface leading-none">{{ __('messages.Top Courses') }}</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full ltr:text-left rtl:text-right">
                <thead class="border-b-2 bg-surface-container-low border-on-surface">
                    <tr>
                        <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-secondary leading-none">#</th>
                        <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-secondary leading-none">{{ __('messages.Course') }}</th>
                        <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-secondary leading-none">{{ __('messages.By') }}</th>
                        <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-secondary leading-none">{{ __('messages.Students') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E5E5E5]">
                    @forelse($tables['topCourses'] as $index => $course)
                        <tr>
                            <td class="px-6 py-4 text-xs font-bold text-secondary">{{ $index + 1 }}</td>
                            <td class="px-6 py-4 text-sm font-bold text-on-surface">{{ $course->title }}</td>
                            <td class="px-6 py-4 text-sm text-on-surface">{{ optional($course->instructor)->name ?? '—' }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-block px-2 py-0.5 neo-border-sm neo-radius text-[10px] font-bold leading-none bg-primary-container text-on-primary-container">{{ $course->enrollments_count }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-6 py-8 text-sm text-center text-secondary">{{ __('messages.No Enrolled Courses') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
