<div class="bg-surface-container-lowest neo-border neo-radius overflow-hidden">
    <div class="p-[24px] border-b-2 border-on-surface flex items-center justify-between">
        <h3 class="text-[18px] font-bold uppercase tracking-widest text-on-surface leading-none">{{ __('messages.All Quizzes') }}</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full ltr:text-left rtl:text-right">
            <thead class="bg-surface-container-low border-b-2 border-on-surface">
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
                    <tr class="hover:bg-surface-container-low transition-colors duration-150">
                        <td class="p-4 font-bold text-sm text-on-surface">{{ $quiz->title }}</td>
                        <td class="p-4">
                            <div class="text-sm text-on-surface">{{ $quiz->section?->course?->title ?? 'N/A' }}</div>
                            <div class="text-xs text-secondary mt-0.5">{{ $quiz->section?->title ?? 'N/A' }}</div>
                        </td>
                        <td class="p-4 font-bold text-sm text-on-surface">{{ $quiz->questions->count() }}</td>
                        <td class="p-4">
                            <span class="inline-flex items-center px-2.5 py-1 neo-border-sm neo-radius text-[10px] font-bold bg-primary-container text-on-primary-container">
                                {{ $quiz->pass_percentage }}%
                            </span>
                        </td>
                        <td class="p-4">
                            <div class="flex items-center gap-2">
                                <button wire:click="openEditQuizModal({{ $quiz->id }})"
                                    class="w-8 h-8 neo-border-sm neo-radius flex items-center justify-center text-on-surface hover:bg-primary-container hover:text-on-primary-container transition-colors" title="Edit Quiz">
                                    <i class="fas fa-edit text-xs"></i>
                                </button>
                                <button wire:click="openAttemptsModal({{ $quiz->id }})"
                                    class="w-8 h-8 neo-border-sm neo-radius flex items-center justify-center text-on-surface hover:bg-primary-container hover:text-on-primary-container transition-colors" title="View Attempts">
                                    <i class="fas fa-list text-xs"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-8 text-center text-sm text-secondary">{{ __('messages.No quizzes found.') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-4 border-t-2 border-on-surface">
        {{ $quizzes->links() }}
    </div>
</div>
