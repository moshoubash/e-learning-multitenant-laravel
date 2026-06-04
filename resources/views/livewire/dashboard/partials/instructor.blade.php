{{-- Instructor dashboard partial --}}
<div class="space-y-6">
    {{-- Charts row --}}
    <div class="grid grid-cols-1 gap-5 lg:grid-cols-3">
        <div class="lg:col-span-2">
            <x-ui.chart
                :title="__('messages.Students per Course')"
                :type="$charts['studentsPerCourse']['type']"
                :labels="$charts['studentsPerCourse']['labels']"
                :datasets="$charts['studentsPerCourse']['datasets']"
                :height="280"
            />
        </div>
        <x-ui.chart
            :title="__('messages.Submissions Status')"
            :type="$charts['submissionStatus']['type']"
            :labels="$charts['submissionStatus']['labels']"
            :datasets="$charts['submissionStatus']['datasets']"
            :height="280"
        />
    </div>

    <x-ui.chart
        :title="__('messages.Quiz Attempts Trend')"
        :type="$charts['quizAttempts']['type']"
        :labels="$charts['quizAttempts']['labels']"
        :datasets="$charts['quizAttempts']['datasets']"
        :height="220"
    />

    {{-- Tables row --}}
    <div class="grid grid-cols-1 gap-5 lg:grid-cols-2">
        {{-- My courses --}}
        <x-ui.dashboard-panel
            :title="__('messages.My Courses')"
            icon="fas fa-book-open"
        >
            <table class="w-full text-left">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-5 py-2.5 text-xs font-semibold text-gray-500 uppercase">{{ __('messages.Course') }}</th>
                        <th class="px-5 py-2.5 text-xs font-semibold text-gray-500 uppercase">{{ __('messages.Status') }}</th>
                        <th class="px-5 py-2.5 text-xs font-semibold text-gray-500 uppercase text-right">{{ __('messages.Students') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tables['myCourses'] as $course)
                        <tr class="border-t border-gray-100">
                            <td class="px-5 py-3">
                                <div class="font-medium text-gray-800">{{ $course->title }}</div>
                                <div class="text-xs text-gray-500">{{ $course->sections_count }} {{ __('messages.sections') }}</div>
                            </td>
                            <td class="px-5 py-3">
                                <x-ui.status-pill :variant="$course->status === 'published' ? 'success' : ($course->status === 'draft' ? 'warning' : 'default')" size="xs">
                                    {{ __('messages.' . ucfirst($course->status)) }}
                                </x-ui.status-pill>
                            </td>
                            <td class="px-5 py-3 text-right text-sm font-semibold text-gray-700">
                                {{ $course->enrollments_count }}
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="px-5 py-6 text-center text-sm text-gray-500">{{ __('messages.No Enrolled Courses') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </x-ui.dashboard-panel>

        {{-- Recent enrollments --}}
        <x-ui.dashboard-panel
            :title="__('messages.Recent Enrollments')"
            icon="fas fa-user-graduate"
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
                            <td class="px-5 py-3 font-medium text-gray-800">{{ optional($enrollment->user)->name ?? '—' }}</td>
                            <td class="px-5 py-3 text-sm text-gray-600">{{ optional($enrollment->course)->title ?? '—' }}</td>
                            <td class="px-5 py-3 text-xs text-gray-500">{{ $enrollment->enrolled_at?->diffForHumans() }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="px-5 py-6 text-center text-sm text-gray-500">{{ __('messages.No Enrolled Courses') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </x-ui.dashboard-panel>
    </div>

    {{-- Recent submissions --}}
    <x-ui.dashboard-panel
        :title="__('messages.Recent Submissions')"
        icon="fas fa-inbox"
    >
        <table class="w-full text-left">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-5 py-2.5 text-xs font-semibold text-gray-500 uppercase">{{ __('messages.Student') }}</th>
                    <th class="px-5 py-2.5 text-xs font-semibold text-gray-500 uppercase">{{ __('messages.Assignment') }}</th>
                    <th class="px-5 py-2.5 text-xs font-semibold text-gray-500 uppercase">{{ __('messages.Course') }}</th>
                    <th class="px-5 py-2.5 text-xs font-semibold text-gray-500 uppercase">{{ __('messages.Status') }}</th>
                    <th class="px-5 py-2.5 text-xs font-semibold text-gray-500 uppercase">{{ __('messages.Submitted At') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tables['recentSubmissions'] as $submission)
                    <tr class="border-t border-gray-100">
                        <td class="px-5 py-3 font-medium text-gray-800">{{ optional($submission->student)->name ?? '—' }}</td>
                        <td class="px-5 py-3 text-sm text-gray-600">{{ optional($submission->assignment)->title ?? '—' }}</td>
                        <td class="px-5 py-3 text-sm text-gray-500">{{ optional(optional($submission->assignment)->course)->title ?? '—' }}</td>
                        <td class="px-5 py-3">
                            @php $variant = $submission->status === 'graded' ? 'success' : 'warning'; @endphp
                            <x-ui.status-pill :variant="$variant" size="xs">
                                {{ $submission->status === 'graded' ? __('messages.Graded') : __('messages.Pending') }}
                            </x-ui.status-pill>
                        </td>
                        <td class="px-5 py-3 text-xs text-gray-500">{{ $submission->submitted_at?->diffForHumans() }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-5 py-6 text-center text-sm text-gray-500">{{ __('messages.No submissions found') }}</td></tr>
                @endforelse
            </tbody>
        </table>
    </x-ui.dashboard-panel>
</div>
