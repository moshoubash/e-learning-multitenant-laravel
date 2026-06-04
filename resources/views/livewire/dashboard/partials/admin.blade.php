{{-- Admin dashboard partial --}}
<div class="space-y-6">
    {{-- Charts row --}}
    <div class="grid grid-cols-1 gap-5 lg:grid-cols-3">
        <div class="lg:col-span-2">
            <x-ui.chart
                :title="__('messages.Enrollments & New Users')"
                :type="$charts['enrollmentTrend']['type']"
                :labels="$charts['enrollmentTrend']['labels']"
                :datasets="$charts['enrollmentTrend']['datasets']"
                :height="280"
            />
        </div>
        <x-ui.chart
            :title="__('messages.Users by Role')"
            :type="$charts['userRoles']['type']"
            :labels="$charts['userRoles']['labels']"
            :datasets="$charts['userRoles']['datasets']"
            :height="280"
        />
    </div>

    <div class="grid grid-cols-1 gap-5 lg:grid-cols-3">
        <x-ui.chart
            :title="__('messages.Courses by Status')"
            :type="$charts['courseStatus']['type']"
            :labels="$charts['courseStatus']['labels']"
            :datasets="$charts['courseStatus']['datasets']"
            :height="220"
        />

        {{-- Recent users table --}}
        <x-ui.dashboard-panel
            :title="__('messages.Latest Users')"
            icon="fas fa-user-plus"
            class="lg:col-span-2"
        >
            <table class="w-full text-left">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-5 py-2.5 text-xs font-semibold text-gray-500 uppercase">{{ __('messages.Name') }}</th>
                        <th class="px-5 py-2.5 text-xs font-semibold text-gray-500 uppercase">{{ __('messages.Role') }}</th>
                        <th class="px-5 py-2.5 text-xs font-semibold text-gray-500 uppercase">{{ __('messages.Created At') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tables['recentUsers'] as $user)
                        <tr class="border-t border-gray-100">
                            <td class="px-5 py-3">
                                <div class="font-medium text-gray-800">{{ $user->name }}</div>
                                <div class="text-xs text-gray-500">{{ $user->email }}</div>
                            </td>
                            <td class="px-5 py-3">
                                @php $r = optional($user->roles->first())->name ?? 'student'; @endphp
                                <x-ui.status-pill :variant="$r === 'admin' ? 'danger' : ($r === 'instructor' ? 'info' : 'success')" size="xs">
                                    {{ __('messages.' . ucfirst($r)) }}
                                </x-ui.status-pill>
                            </td>
                            <td class="px-5 py-3 text-xs text-gray-500">
                                {{ $user->created_at->diffForHumans() }}
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="px-5 py-6 text-center text-sm text-gray-500">{{ __('messages.No Enrolled Courses') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </x-ui.dashboard-panel>
    </div>

    {{-- Tables row --}}
    <div class="grid grid-cols-1 gap-5 lg:grid-cols-2">
        {{-- Recent enrollments --}}
        <x-ui.dashboard-panel
            :title="__('messages.Recent Enrollments')"
            icon="fas fa-graduation-cap"
        >
            <table class="w-full text-left">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-5 py-2.5 text-xs font-semibold text-gray-500 uppercase">{{ __('messages.Student') }}</th>
                        <th class="px-5 py-2.5 text-xs font-semibold text-gray-500 uppercase">{{ __('messages.Course') }}</th>
                        <th class="px-5 py-2.5 text-xs font-semibold text-gray-500 uppercase">{{ __('messages.When') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tables['recentEnrollments'] as $enrollment)
                        <tr class="border-t border-gray-100">
                            <td class="px-5 py-3">
                                <div class="font-medium text-gray-800">{{ optional($enrollment->user)->name ?? '—' }}</div>
                            </td>
                            <td class="px-5 py-3 text-sm text-gray-600">
                                {{ optional($enrollment->course)->title ?? '—' }}
                            </td>
                            <td class="px-5 py-3 text-xs text-gray-500">
                                {{ $enrollment->enrolled_at?->diffForHumans() }}
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="px-5 py-6 text-center text-sm text-gray-500">{{ __('messages.No Enrolled Courses') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </x-ui.dashboard-panel>

        {{-- Top courses --}}
        <x-ui.dashboard-panel
            :title="__('messages.Top Courses')"
            icon="fas fa-fire"
        >
            <table class="w-full text-left">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-5 py-2.5 text-xs font-semibold text-gray-500 uppercase">#</th>
                        <th class="px-5 py-2.5 text-xs font-semibold text-gray-500 uppercase">{{ __('messages.Course') }}</th>
                        <th class="px-5 py-2.5 text-xs font-semibold text-gray-500 uppercase">{{ __('messages.By') }}</th>
                        <th class="px-5 py-2.5 text-xs font-semibold text-gray-500 uppercase text-right">{{ __('messages.Students') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tables['topCourses'] as $index => $course)
                        <tr class="border-t border-gray-100">
                            <td class="px-5 py-3 text-xs text-gray-400">{{ $index + 1 }}</td>
                            <td class="px-5 py-3 font-medium text-gray-800">{{ $course->title }}</td>
                            <td class="px-5 py-3 text-sm text-gray-500">{{ optional($course->instructor)->name ?? '—' }}</td>
                            <td class="px-5 py-3 text-right">
                                <x-ui.status-pill variant="info" size="xs">
                                    {{ $course->enrollments_count }}
                                </x-ui.status-pill>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-5 py-6 text-center text-sm text-gray-500">{{ __('messages.No Enrolled Courses') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </x-ui.dashboard-panel>
    </div>
</div>
