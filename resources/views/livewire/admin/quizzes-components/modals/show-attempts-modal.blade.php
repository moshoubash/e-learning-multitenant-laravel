@if($showAttemptsModal && $selectedQuizId)
    @php
        $quiz = \App\Models\Tenant\Quiz::find($selectedQuizId);
        $attempts = $this->attempts;
    @endphp
    <div class="fixed inset-0 z-50 overflow-y-auto" x-data="{ show: true }" x-show="show">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-on-surface/60 transition-opacity" wire:click="closeModals"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div class="inline-block overflow-hidden ltr:text-left rtl:text-right align-bottom transition-all transform bg-surface-container-lowest neo-border neo-radius sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4 max-h-[90vh] overflow-y-auto">
                    <h3 class="text-sm font-bold uppercase tracking-widest text-on-surface mb-4">
                        {{ __('messages.Quiz Attempts') }}: {{ $quiz?->title ?? __('messages.Quiz') }}
                        <span class="text-xs font-normal text-secondary ltr:ml-2 rtl:mr-2">({{ $attempts->count() }} {{ __('messages.attempts') }})</span>
                    </h3>
                    @if($attempts->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full ltr:text-left rtl:text-right">
                                <thead class="bg-surface-container-low border-b-2 border-on-surface">
                                    <tr>
                                        <th class="p-3 text-[10px] font-bold uppercase tracking-widest text-secondary">{{ __('messages.Student') }}</th>
                                        <th class="p-3 text-[10px] font-bold uppercase tracking-widest text-secondary">{{ __('messages.Score') }}</th>
                                        <th class="p-3 text-[10px] font-bold uppercase tracking-widest text-secondary">{{ __('messages.Status') }}</th>
                                        <th class="p-3 text-[10px] font-bold uppercase tracking-widest text-secondary">{{ __('messages.Submitted At') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-[#E5E5E5]">
                                    @foreach($attempts as $attempt)
                                        <tr class="hover:bg-surface-container-low transition-colors">
                                            <td class="p-3 font-bold text-sm text-on-surface">{{ $attempt->user?->name ?? 'Unknown' }}</td>
                                            <td class="p-3">
                                                <span class="font-bold text-sm text-on-surface">{{ $attempt->score }}%</span>
                                                <span class="text-xs text-secondary"> / {{ $quiz?->pass_percentage ?? 0 }}% {{ __('messages.pass') }}</span>
                                            </td>
                                            <td class="p-3">
                                                @if($attempt->passed)
                                                    <span class="inline-flex items-center px-2.5 py-1 neo-border-sm neo-radius text-[10px] font-bold bg-primary-container text-on-primary-container">
                                                        <i class="fas fa-check-circle ltr:mr-1 rtl:ml-1"></i> {{ __('messages.Passed') }}
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-1 neo-border-sm neo-radius text-[10px] font-bold bg-error/10 text-error">
                                                        <i class="fas fa-times-circle ltr:mr-1 rtl:ml-1"></i> {{ __('messages.Failed') }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="p-3 text-sm text-secondary">
                                                {{ app()->getLocale() === 'ar' ? $attempt->submitted_at->isoFormat('dddd, D MMMM YYYY, HH:mm') : ($attempt->submitted_at ? $attempt->submitted_at->format('Y-m-d H:i') : 'N/A') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="py-12 text-center">
                            <i class="mb-3 text-4xl text-secondary fas fa-inbox"></i>
                            <p class="text-sm text-secondary">{{ __('messages.No attempts yet for this quiz.') }}</p>
                        </div>
                    @endif
                </div>
                <div class="px-4 py-3 bg-surface-container-low sm:px-6 sm:flex sm:flex-row-reverse">
                    <button wire:click="closeModals" type="button"
                        class="inline-flex justify-center w-full px-4 py-2 neo-border neo-radius bg-surface-container text-on-primary-container text-xs font-bold uppercase tracking-widest hover:bg-on-surface hover:text-white transition-colors sm:w-auto">
                        {{ __('messages.Close') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif
