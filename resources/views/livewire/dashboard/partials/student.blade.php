{{-- Student dashboard partial --}}
<div class="space-y-6">
    {{-- Charts row --}}
    <div class="grid grid-cols-1 gap-5 lg:grid-cols-3">
        <div class="lg:col-span-2">
            <x-ui.chart
                :title="__('messages.My Weekly Progress')"
                :type="$charts['progress']['type']"
                :labels="$charts['progress']['labels']"
                :datasets="$charts['progress']['datasets']"
                :height="280"
            />
        </div>
        <x-ui.chart
            :title="__('messages.Recent Quiz Scores')"
            :type="$charts['quizScores']['type']"
            :labels="$charts['quizScores']['labels']"
            :datasets="$charts['quizScores']['datasets']"
            :height="280"
        />
    </div>

    {{-- Tables row --}}
    <div class="grid grid-cols-1 gap-5 lg:grid-cols-2">
        {{-- My courses with progress --}}
        <x-ui.dashboard-panel
            :title="__('messages.My Enrolled Courses')"
            icon="fas fa-graduation-cap"
        >
            <table class="w-full text-left">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-5 py-2.5 text-xs font-semibold text-gray-500 uppercase">{{ __('messages.Course') }}</th>
                        <th class="px-5 py-2.5 text-xs font-semibold text-gray-500 uppercase">{{ __('messages.By') }}</th>
                        <th class="px-5 py-2.5 text-xs font-semibold text-gray-500 uppercase">{{ __('messages.Progress') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tables['enrollments'] as $enrollment)
                        <tr class="border-t border-gray-100">
                            <td class="px-5 py-3">
                                <a href="{{ route('tenant.student.course', optional($enrollment->course)->slug) }}" class="font-medium text-gray-800 hover:text-indigo-600">
                                    {{ optional($enrollment->course)->title ?? '—' }}
                                </a>
                            </td>
                            <td class="px-5 py-3 text-sm text-gray-500">{{ optional(optional($enrollment->course)->instructor)->name ?? '—' }}</td>
                            <td class="px-5 py-3 w-40">
                                <div class="flex items-center gap-2">
                                    <div class="flex-1 h-1.5 bg-gray-200 rounded-full">
                                        <div class="h-1.5 rounded-full {{ $enrollment->progress_percent >= 100 ? 'bg-green-500' : 'bg-indigo-500' }}"
                                             style="width: {{ (int) $enrollment->progress_percent }}%"></div>
                                    </div>
                                    <span class="text-xs font-medium text-gray-600">{{ (int) $enrollment->progress_percent }}%</span>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="px-5 py-6 text-center text-sm text-gray-500">{{ __('messages.No Enrolled Courses') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </x-ui.dashboard-panel>

        {{-- Recent quiz attempts --}}
        <x-ui.dashboard-panel
            :title="__('messages.Recent Quiz Attempts')"
            icon="fas fa-question-circle"
        >
            <table class="w-full text-left">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-5 py-2.5 text-xs font-semibold text-gray-500 uppercase">{{ __('messages.Quiz') }}</th>
                        <th class="px-5 py-2.5 text-xs font-semibold text-gray-500 uppercase">{{ __('messages.Score') }}</th>
                        <th class="px-5 py-2.5 text-xs font-semibold text-gray-500 uppercase">{{ __('messages.Status') }}</th>
                        <th class="px-5 py-2.5 text-xs font-semibold text-gray-500 uppercase">{{ __('messages.Submitted At') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tables['attempts'] as $attempt)
                        <tr class="border-t border-gray-100">
                            <td class="px-5 py-3 text-sm font-medium text-gray-800">{{ optional($attempt->quiz)->title ?? '—' }}</td>
                            <td class="px-5 py-3 text-sm font-semibold text-gray-700">{{ (int) $attempt->score }}%</td>
                            <td class="px-5 py-3">
                                <x-ui.status-pill :variant="$attempt->passed ? 'success' : 'danger'" size="xs">
                                    {{ $attempt->passed ? __('messages.Passed') : __('messages.Failed') }}
                                </x-ui.status-pill>
                            </td>
                            <td class="px-5 py-3 text-xs text-gray-500">{{ $attempt->submitted_at?->diffForHumans() }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-5 py-6 text-center text-sm text-gray-500">{{ __('messages.No Enrolled Courses') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </x-ui.dashboard-panel>
    </div>
</div>
