@if($showAttemptsModal && $attemptsQuizId)
    @php
        $quiz = \App\Models\Tenant\Quiz::find($attemptsQuizId);
        $attempts = $this->attempts;
    @endphp
    <div class="fixed inset-0 z-50 overflow-y-auto" x-data="{ show: true }" x-show="show">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" wire:click="closeAttemptsModal"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div
                class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 max-h-[90vh] overflow-y-auto">
                    <h3 class="mb-4 text-lg font-medium text-gray-900">
                        {{ __('messages.Student Attempts') }}: {{ $quiz?->title ?? 'Quiz' }}
                        <span class="ml-2 text-sm font-normal text-gray-500">({{ $attempts->count() }} {{ __('messages.attempts') }})</span>
                    </h3>

                    @if($attempts->count() > 0)
                        <table class="w-full text-left">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="p-3 text-sm font-semibold text-gray-600">{{ __("messages.Student") }}</th>
                                    <th class="p-3 text-sm font-semibold text-gray-600">{{ __("messages.Email") }}</th>
                                    <th class="p-3 text-sm font-semibold text-gray-600">{{ __("messages.Score") }}</th>
                                    <th class="p-3 text-sm font-semibold text-gray-600">{{ __("messages.Status") }}</th>
                                    <th class="p-3 text-sm font-semibold text-gray-600">{{ __("messages.Submitted At") }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($attempts as $attempt)
                                    <tr class="border-t border-gray-200 hover:bg-gray-50">
                                        <td class="p-3 font-medium">{{ $attempt->user?->name ?? 'Unknown' }}</td>
                                        <td class="p-3 text-gray-500">{{ $attempt->user?->email ?? 'N/A' }}</td>
                                        <td class="p-3">
                                            <span class="font-semibold">{{ $attempt->score }}%</span>
                                            <span class="text-sm text-gray-400"> / {{ $quiz?->pass_percentage ?? 0 }}% {{ __('messages.pass') }}</span>
                                        </td>
                                        <td class="p-3">
                                            @if($attempt->passed)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    <i class="@if(app()->getLocale() === 'ar') ml-1 @else mr-1 @endif fas fa-check-circle"></i> {{ __('messages.Passed') }}
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    <i class="@if(app()->getLocale() === 'ar') ml-1 @else mr-1 @endif fas fa-times-circle"></i> {{ __('messages.Failed') }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="p-3 text-gray-500">
                                            {{ $attempt->submitted_at ? $attempt->submitted_at->isoformat('dddd, D MMMM YYYY') : 'N/A' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="py-12 text-center">
                            <i class="mb-3 text-4xl text-gray-300 fas fa-inbox"></i>
                            <p class="text-gray-500">No student attempts yet for this quiz.</p>
                        </div>
                    @endif
                </div>
                <div class="px-4 py-3 bg-gray-50 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button wire:click="closeAttemptsModal" type="button"
                        class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white bg-gray-600 border border-transparent rounded-md shadow-sm hover:bg-gray-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                        {{ __('messages.Close') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif
