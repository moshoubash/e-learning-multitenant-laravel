<div class="overflow-hidden bg-surface-container-lowest neo-border neo-radius">
    <div class="p-[24px] border-b-2 border-on-surface flex items-center justify-between">
        <h3 class="text-[18px] font-bold uppercase tracking-widest text-on-surface leading-none">{{ __('messages.All Quizzes') }}</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full ltr:text-left rtl:text-right">
            <thead class="border-b-2 bg-surface-container-low border-on-surface">
                <tr>
                    <th class="p-4 text-[10px] font-bold uppercase tracking-widest text-secondary">{{ __('messages.Title') }}</th>
                    <th class="p-4 text-[10px] font-bold uppercase tracking-widest text-secondary">{{ __('messages.Course / Section') }}</th>
                    <th class="p-4 text-[10px] font-bold uppercase tracking-widest text-secondary">{{ __('messages.Questions') }}</th>
                    <th class="p-4 text-[10px] font-bold uppercase tracking-widest text-secondary">{{ __('messages.Pass %') }}</th>
                    <th class="p-4 text-[10px] font-bold uppercase tracking-widest text-secondary">{{ __('messages.Actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#E5E5E5]">
                @forelse ($quizzes as $quiz)
                    <tr class="transition-colors duration-150 hover:bg-surface-container-low">
                        <td class="p-4 text-sm font-bold text-on-surface">{{ $quiz->title }}</td>
                        <td class="p-4">
                            <div class="text-sm text-on-surface">{{ $quiz->section?->course?->title ?? 'N/A' }}</div>
                            <div class="text-xs text-secondary mt-0.5">{{ $quiz->section?->title ?? 'N/A' }}</div>
                        </td>
                        <td class="p-4 text-sm font-bold text-on-surface">{{ $quiz->questions->count() }}</td>
                        <td class="p-4">
                            <span class="inline-flex items-center px-2.5 py-1 neo-border-sm neo-radius text-[10px] font-bold bg-primary-container text-on-primary-container">
                                {{ $quiz->pass_percentage }}%
                            </span>
                        </td>
                        <td class="p-4">
                            <div class="flex items-center gap-2">
                                <button wire:click="openEditQuizModal({{ $quiz->id }})"
                                    class="flex items-center justify-center w-8 h-8 transition-colors neo-border-sm neo-radius text-on-surface hover:bg-primary-container hover:text-on-primary-container" title="Edit Quiz">
                                    <i class="text-xs fas fa-edit"></i>
                                </button>
                                <button wire:click="openAttemptsModal({{ $quiz->id }})"
                                    class="flex items-center justify-center w-8 h-8 transition-colors neo-border-sm neo-radius text-on-surface hover:bg-primary-container hover:text-on-primary-container" title="View Attempts">
                                    <i class="text-xs fas fa-list"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-8 text-sm text-center text-secondary">{{ __('messages.No quizzes found.') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-4 border-t-2 border-on-surface">
        {{ $quizzes->links() }}
    </div>
</div>
