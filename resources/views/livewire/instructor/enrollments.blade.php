<div>
    <header class="h-16 flex justify-between items-center px-[24px] bg-surface-container-lowest border-b-2 border-on-surface sticky top-0 z-40">
        <div>
            <h2 class="text-[24px] font-bold text-on-surface leading-none">{{ __('messages.Enrollments') }}</h2>
        <p class="text-[12px] font-medium uppercase text-secondary mt-0.5 tracking-wider">{{ __('messages.Monitor student enrollments in your courses') }}</p>
    </div>
    <div class="flex items-center gap-2">
        @livewire('shared.notification-bell')
    </div>
</header>

    <div class="p-[24px] max-w-[1400px] mx-auto space-y-6">
        <div class="bg-surface-container-lowest neo-border neo-radius overflow-hidden">
            <div class="p-[24px] border-b-2 border-on-surface bg-surface-container-low">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex-1 max-w-md">
                        <div class="flex overflow-hidden neo-border-sm neo-radius bg-surface-container-lowest">
                            <input type="text" wire:model.lazy="search" placeholder="{{ __('messages.Search by student or course...') }}"
                                class="flex-1 px-3 py-2 bg-transparent text-on-surface text-sm focus:outline-none focus:ring-0 placeholder:text-secondary ltr:border-r rtl:border-l border-on-surface/20">
                            <button wire:click="$refresh"
                                class="px-3 py-2 bg-transparent text-secondary hover:text-on-surface transition-colors flex items-center justify-center shrink-0">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <select wire:model.lazy="statusFilter"
                            class="px-3 py-2 neo-border-sm neo-radius bg-surface-container-lowest text-on-surface text-sm focus:outline-none focus:ring-0">
                            <option value="">{{ __('messages.All Statuses') }}</option>
                            <option value="active">{{ __('messages.Active') }}</option>
                            <option value="completed">{{ __('messages.Completed') }}</option>
                            <option value="pending">{{ __('messages.Pending') }}</option>
                            <option value="cancelled">{{ __('messages.Cancelled') }}</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b-2 border-on-surface bg-surface-container">
                            <th class="ltr:text-left rtl:text-right px-[24px] py-3 text-xs font-bold uppercase tracking-widest text-on-surface">{{ __('messages.Student') }}</th>
                            <th class="ltr:text-left rtl:text-right px-[24px] py-3 text-xs font-bold uppercase tracking-widest text-on-surface">{{ __('messages.Course') }}</th>
                            <th class="ltr:text-left rtl:text-right px-[24px] py-3 text-xs font-bold uppercase tracking-widest text-on-surface">{{ __('messages.Enrolled At') }}</th>
                            <th class="ltr:text-left rtl:text-right px-[24px] py-3 text-xs font-bold uppercase tracking-widest text-on-surface">{{ __('messages.Progress') }}</th>
                            <th class="ltr:text-left rtl:text-right px-[24px] py-3 text-xs font-bold uppercase tracking-widest text-on-surface">{{ __('messages.Status') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y-2 divide-on-surface/10">
                        @forelse($enrollments as $enrollment)
                            <tr class="hover:bg-surface-container-high transition-colors">
                                <td class="px-[24px] py-3">
                                    <div class="flex items-center gap-3">
                                        <div class="flex items-center justify-center w-9 h-9 font-bold neo-border-sm bg-surface-container-high neo-radius text-xs text-on-surface shrink-0">
                                            {{ substr($enrollment->user->name ?? '?', 0, 2) }}
                                        </div>
                                        <div>
                                            <p class="font-bold text-on-surface">{{ $enrollment->user->name ?? '—' }}</p>
                                            <p class="text-xs text-secondary">{{ $enrollment->user->email ?? '—' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-[24px] py-3">
                                    <span class="font-medium text-on-surface">{{ $enrollment->course->title ?? '—' }}</span>
                                </td>
                                <td class="px-[24px] py-3 text-secondary">
                                    {{ $enrollment->enrolled_at ? $enrollment->enrolled_at->format('M d, Y') : '—' }}
                                </td>
                                <td class="px-[24px] py-3">
                                    <div class="flex items-center gap-2">
                                        <div class="flex-1 max-w-[100px] h-2 neo-border-sm bg-surface-container overflow-hidden">
                                            <div class="h-full transition-all duration-300 {{ $enrollment->progress_percent >= 100 ? 'bg-primary-container' : 'bg-on-surface' }}" style="width: {{ $enrollment->progress_percent }}%"></div>
                                        </div>
                                        <span class="text-xs font-bold text-on-surface">{{ $enrollment->progress_percent }}%</span>
                                    </div>
                                </td>
                                <td class="px-[24px] py-3">
                                    @php
                                        $statusClasses = [
                                            'active' => 'bg-primary-container text-on-primary-container',
                                            'completed' => 'bg-surface-container-high text-on-surface',
                                            'pending' => 'bg-surface-container text-secondary',
                                            'cancelled' => 'bg-error/10 text-error',
                                        ];
                                        $class = $statusClasses[$enrollment->status] ?? 'bg-surface-container text-secondary';
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-1 text-[10px] font-bold uppercase tracking-widest neo-border-sm {{ $class }}">
                                        {{ __(ucfirst($enrollment->status)) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-[24px] py-12 text-center">
                                    <i class="mb-3 text-3xl fas fa-user-graduate text-secondary"></i>
                                    <p class="text-sm text-secondary">{{ __('messages.No enrollments found') }}</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($enrollments->hasPages())
                <div class="px-[24px] py-4 border-t-2 border-on-surface bg-surface-container-low">
                    {{ $enrollments->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
