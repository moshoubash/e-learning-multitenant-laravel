@if($showAttemptsModal && $selectedQuizId)
    @php
        $quiz = \App\Models\Tenant\Quiz::find($selectedQuizId);
        $attempts = $this->attempts;
    @endphp
    <div class="fixed inset-0 z-50 overflow-y-auto" x-data="{ show: true }" x-show="show">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" wire:click="closeModals"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 max-h-[90vh] overflow-y-auto">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        {{ __('Quiz Attempts') }}: {{ $quiz?->title ?? 'Quiz' }}
                    </h3>
                    
                    <table class="w-full text-left">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="p-3 text-sm font-semibold text-gray-600">{{ __("Student") }}</th>
                                <th class="p-3 text-sm font-semibold text-gray-600">{{ __("Score") }}</th>
                                <th class="p-3 text-sm font-semibold text-gray-600">{{ __("Status") }}</th>
                                <th class="p-3 text-sm font-semibold text-gray-600">{{ __("Submitted At") }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($attempts as $attempt)
                                <tr class="border-t border-gray-200 hover:bg-gray-50">
                                    <td class="p-3">{{ $attempt->user?->name ?? 'Unknown' }}</td>
                                    <td class="p-3">
                                        <span class="font-medium">{{ $attempt->score }}%</span>
                                        <span class="text-gray-500 text-sm"> / {{ $quiz?->pass_percentage }}% pass</span>
                                    </td>
                                    <td class="p-3">
                                        @if($attempt->passed)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <i class="fas fa-check-circle mr-1"></i> Passed
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                <i class="fas fa-times-circle mr-1"></i> Failed
                                            </span>
                                        @endif
                                    </td>
                                    <td class="p-3 text-gray-500">{{ $attempt->submitted_at ? $attempt->submitted_at->format('Y-m-d H:i') : 'N/A' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="p-4 text-center text-gray-500">
                                        No attempts yet for this quiz.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button wire:click="closeModals" type="button"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-gray-600 text-base font-medium text-white hover:bg-gray-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                        {{ __('Close') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif