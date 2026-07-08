<div class="overflow-hidden bg-surface-container-lowest neo-border neo-radius">
    <div class="overflow-x-auto">
        <table class="w-full text-left rtl:text-right">
            <thead class="border-b-2 bg-surface-container-low border-on-surface ">
                <tr>
                    <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-secondary">{{ __('messages.Student') }}</th>
                    <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-secondary">{{ __('messages.Assignment') }}</th>
                    <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-secondary">{{ __('messages.Submitted') }}</th>
                    <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-secondary">{{ __('messages.Status') }}</th>
                    <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-secondary">{{ __('messages.Score') }}</th>
                    <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-secondary">{{ __('messages.Graded By') }}</th>
                    <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-secondary">{{ __('messages.Actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#E5E5E5]">
                @forelse($this->submissions as $submission)
                    <tr class="transition-colors hover:bg-surface-container-low">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="flex items-center justify-center w-10 h-10 neo-border-sm neo-radius bg-primary-container shrink-0">
                                    <span class="text-sm font-bold text-on-primary-container">{{ substr($submission->student->name ?? 'N/A', 0, 1) }}</span>
                                </div>
                                <div class="ltr:ml-4 rtl:mr-4">
                                    <div class="text-sm font-bold text-on-surface">{{ $submission->student->name ?? 'Unknown' }}</div>
                                    <div class="text-xs text-secondary">{{ $submission->student->email ?? '' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-bold text-on-surface">{{ $submission->assignment->title ?? 'N/A' }}</div>
                            <div class="text-xs text-secondary">{{ __('messages.Max Score') }}: {{ $submission->assignment->max_score ?? 100 }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-on-surface">{{ $submission->submitted_at ? $submission->submitted_at->format('M d, Y H:i') : 'N/A' }}</div>
                            @if($submission->submitted_at && $submission->assignment->due_date && $submission->submitted_at->gt($submission->assignment->due_date))
                                <div class="mt-1 text-xs font-bold text-error">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    {{ __('messages.Late Submission') }}
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($submission->graded_at)
                                <span class="inline-flex px-2 py-1 neo-border-sm neo-radius text-[10px] font-bold bg-primary-container text-on-primary-container">
                                    {{ __('messages.Graded') }}
                                </span>
                            @else
                                <span class="inline-flex px-2 py-1 neo-border-sm neo-radius text-[10px] font-bold bg-surface-container-high text-on-surface">
                                    {{ __('messages.Pending') }}
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($submission->score !== null)
                                <span class="text-sm font-bold text-on-surface">{{ $submission->score }}/{{ $submission->assignment->max_score ?? 100 }}</span>
                            @else
                                <span class="text-sm text-secondary">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-on-surface">{{ $submission->gradedBy->name ?? '-' }}</div>
                            @if($submission->graded_at)
                                <div class="text-xs text-secondary">{{ $submission->graded_at->format('M d, Y') }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <button wire:click="openGradingModal({{ $submission->id }})"
                                class="px-3 py-1.5 neo-border-sm neo-radius text-[10px] font-bold uppercase tracking-widest bg-primary-container text-on-primary-container hover:bg-on-surface hover:text-white transition-colors">
                                <i class="fas fa-edit ltr:mr-1 rtl:ml-1"></i>
                                {{ $submission->graded_at ? __('messages.View/Edit Grade') : __('messages.Grade') }}
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12">
                            <div class="text-center text-secondary">
                                <i class="text-4xl fas fa-inbox"></i>
                                <p class="mt-2 text-sm font-bold">{{ __('messages.No submissions found') }}</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($this->submissions->hasPages())
        <div class="px-6 py-4 border-t-2 border-on-surface">
            {{ $this->submissions->links() }}
        </div>
    @endif
</div>
