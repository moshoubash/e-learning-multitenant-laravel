<div class="">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ __('messages.Assignment Submissions') }}</h1>
            <p class="mt-1 text-sm text-gray-500">{{ __('messages.Review and grade student submissions') }}</p>
        </div>
    </div>

    <div>
        <!-- Filter Tabs -->
        <div class="mb-6">
            <div class="flex flex-wrap gap-2 border-b border-gray-200">
                <button wire:click="setFilterStatus('all')"
                    class="px-4 py-2 text-sm font-medium transition-colors border-b-2 {{ $filterStatus === 'all' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                    {{ __('messages.All Submissions') }}
                </button>
                <button wire:click="setFilterStatus('pending')"
                    class="px-4 py-2 text-sm font-medium transition-colors border-b-2 {{ $filterStatus === 'pending' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                    {{ __('messages.Pending Review') }}
                </button>
                <button wire:click="setFilterStatus('graded')"
                    class="px-4 py-2 text-sm font-medium transition-colors border-b-2 {{ $filterStatus === 'graded' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                    {{ __('messages.Graded') }}
                </button>
            </div>
        </div>

        <!-- Submissions Table -->
        <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                {{ __('messages.Student') }}
                            </th>
                            <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                {{ __('messages.Assignment') }}
                            </th>
                            <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                {{ __('messages.Submitted') }}
                            </th>
                            <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                {{ __('messages.Status') }}
                            </th>
                            <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                {{ __('messages.Score') }}
                            </th>
                            <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                {{ __('messages.Graded By') }}
                            </th>
                            <th class="px-6 py-3 text-xs font-medium tracking-wider @rimauto('text-right') text-gray-500 uppercase">
                                {{ __('messages.Actions') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($this->submissions as $submission)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 w-10 h-10">
                                            <div
                                                class="flex items-center justify-center w-10 h-10 bg-blue-100 rounded-full">
                                                <span class="text-sm font-medium text-blue-600">
                                                    {{ substr($submission->student->name ?? 'N/A', 0, 1) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="ms-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $submission->student->name ?? 'Unknown' }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ $submission->student->email ?? '' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">{{ $submission->assignment->title ?? 'N/A' }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ __('messages.Max Score') }}:
                                        {{ $submission->assignment->max_score ?? 100 }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $submission->submitted_at ? $submission->submitted_at->format('M d, Y H:i') : 'N/A' }}
                                    </div>
                                    @if (
                                        $submission->submitted_at &&
                                            $submission->assignment->due_date &&
                                            $submission->submitted_at->gt($submission->assignment->due_date))
                                        <div class="text-xs text-red-600">
                                            <i class="fas fa-exclamation-triangle"></i>
                                            {{ __('messages.Late Submission') }}
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($submission->graded_at)
                                        <span
                                            class="inline-flex px-2 text-xs font-semibold leading-5 text-green-800 bg-green-100 rounded-full">
                                            {{ __('messages.Graded') }}
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex px-2 text-xs font-semibold leading-5 text-yellow-800 bg-yellow-100 rounded-full">
                                            {{ __('messages.Pending') }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($submission->score !== null)
                                        <span class="text-sm font-medium text-gray-900">
                                            {{ $submission->score }}/{{ $submission->assignment->max_score ?? 100 }}
                                        </span>
                                    @else
                                        <span class="text-sm text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $submission->gradedBy->name ?? '-' }}
                                    </div>
                                    @if ($submission->graded_at)
                                        <div class="text-xs text-gray-500">
                                            {{ $submission->graded_at->format('M d, Y') }}
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm font-medium @rimauto('text-right') whitespace-nowrap">
                                    <button wire:click="openGradingModal({{ $submission->id }})"
                                        class="text-blue-600 hover:text-blue-900">
                                        <i class="@rim('mr-1') fas fa-edit"></i>
                                        {{ $submission->graded_at ? __('messages.View/Edit Grade') : __('messages.Grade') }}
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12">
                                    <div class="text-center text-gray-400">
                                        <i class="text-4xl fas fa-inbox"></i>
                                        <p class="mt-2 text-lg text-center">{{ __('messages.No submissions found') }}
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($this->submissions->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $this->submissions->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Grading Modal -->

    @if ($showGradingModal && $this->gradingSubmission)
        <div class="fixed inset-0 z-50 overflow-y-auto" x-data="{ show: true }" x-show="show">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" wire:click="closeGradingModal">
                </div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
                <div
                    class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="px-4 pt-5 pb-4 bg-white sm:p-6 sm:pb-4">
                        <h3 class="mb-4 text-lg font-medium text-gray-900">
                            {{ __('messages.Grade Submission') }}
                        </h3>

                        <div class="p-4 mb-6 rounded-lg bg-gray-50">
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <span class="text-gray-500">{{ __('messages.Student') }}:</span>
                                    <span
                                        class="font-medium text-gray-900">{{ $this->gradingSubmission->student->name ?? 'N/A' }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-500">{{ __('messages.Assignment') }}:</span>
                                    <span
                                        class="font-medium text-gray-900">{{ $this->gradingSubmission->assignment->title ?? 'N/A' }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-500">{{ __('messages.Submitted') }}:</span>
                                    <span class="font-medium text-gray-900">
                                        {{ $this->gradingSubmission->submitted_at ? $this->gradingSubmission->submitted_at->format('M d, Y H:i') : 'N/A' }}
                                    </span>
                                </div>
                                <div>
                                    <span class="text-gray-500">{{ __('messages.Max Score') }}:</span>
                                    <span
                                        class="font-medium text-gray-900">{{ $this->gradingSubmission->assignment->max_score ?? 100 }}</span>
                                </div>
                            </div>

                            @if ($this->gradingSubmission->content)
                                <div class="mt-4">
                                    <span class="text-sm text-gray-500">{{ __('messages.Submission Content') }}:</span>
                                    <div
                                        class="p-3 mt-1 overflow-y-auto text-sm text-gray-700 bg-white border border-gray-200 rounded max-h-32">
                                        {{ $this->gradingSubmission->content }}
                                    </div>
                                </div>
                            @endif

                            @if ($this->gradingSubmission->file_path)
                                <div class="mt-3">
                                    <a href="{{ $this->gradingSubmission->file_path }}" target="_blank"
                                        class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800">
                                        <i class="@rim('mr-1') fas fa-paperclip"></i>
                                        {{ __('messages.View Attached File') }}
                                    </a>
                                </div>
                            @endif
                        </div>

                        <form wire:submit="submitGrade">
                            <div class="mb-4">
                                <label class="block mb-2 text-sm font-medium text-gray-700">
                                    {{ __('messages.Score') }} ({{ $gradingSubmission->assignment->max_score ?? 100 }}
                                    {{ __('messages.max') }})
                                </label>
                                <input type="number" wire:model.live="gradeScore" min="0"
                                    max="{{ $gradingSubmission->assignment->max_score ?? 100 }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @error('gradeScore')
                                    <span class="text-xs text-red-500">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label
                                    class="block mb-2 text-sm font-medium text-gray-700">{{ __('messages.Feedback') }}</label>
                                <textarea wire:model="gradeFeedback" rows="4"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    placeholder="{{ __('messages.Enter feedback for the student...') }}"></textarea>
                            </div>
                        </form>
                    </div>
                    <div class="px-4 py-3 bg-gray-50 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button wire:click="submitGrade" type="button"
                            class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                            {{ __('messages.Submit Grade') }}
                        </button>
                        <button wire:click="closeGradingModal" type="button"
                            class="inline-flex justify-center w-full px-4 py-2 mt-3 text-base font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            {{ __('messages.Cancel') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
