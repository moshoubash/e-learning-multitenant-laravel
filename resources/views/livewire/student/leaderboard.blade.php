<div>
    <header class="h-16 flex justify-between items-center px-[24px] bg-surface-container-lowest border-b-2 border-on-surface sticky top-0 z-40">
        <div>
            <h2 class="text-[24px] font-bold text-on-surface leading-none">{{ __('messages.Leaderboard') }}</h2>
            <p class="text-[12px] font-medium uppercase text-secondary mt-0.5 tracking-wider">{{ __('messages.See how you rank among your peers') }}</p>
        </div>
        <div class="flex items-center gap-2">
            @livewire('shared.notification-bell')
        </div>
    </header>

    <div class="p-[24px] max-w-[1400px] mx-auto">
        @if($userRank)
            <div class="mb-6 p-4 neo-border neo-radius bg-primary-container flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <span class="text-sm font-bold text-on-primary-container">{{ __('messages.Your Rank') }}:</span>
                    <span class="text-2xl font-black text-on-primary-container">#{{ $userRank }}</span>
                </div>
                <div class="text-right">
                    <span class="text-sm font-bold text-on-primary-container">{{ $userPoints }}</span>
                    <span class="text-xs text-on-primary-container ml-1">{{ __('messages.points') }}</span>
                </div>
            </div>
        @endif

        <div class="mb-6 p-4 neo-border-sm neo-radius bg-surface-container-high">
            <h4 class="text-xs font-bold uppercase tracking-widest text-on-surface mb-3">
                <i class="fas fa-info-circle ltr:mr-1 rtl:ml-1"></i>
                {{ __('messages.How to earn points') }}
            </h4>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs text-secondary">
                <div class="flex items-center gap-2">
                    <span class="flex items-center justify-center w-7 h-7 neo-border-sm neo-radius bg-primary-container text-on-primary-container font-bold text-[10px] shrink-0">+10</span>
                    <span>{{ __('messages.Complete a lesson') }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="flex items-center justify-center w-7 h-7 neo-border-sm neo-radius bg-primary-container text-on-primary-container font-bold text-[10px] shrink-0">+50</span>
                    <span>{{ __('messages.Pass a quiz') }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="flex items-center justify-center w-7 h-7 neo-border-sm neo-radius bg-primary-container text-on-primary-container font-bold text-[10px] shrink-0">+100</span>
                    <span>{{ __('messages.Complete a course') }}</span>
                </div>
            </div>
            <p class="mt-2 text-[10px] text-secondary/70 italic">
                <i class="fas fa-star ltr:mr-1 rtl:ml-1 text-[#FFD600]"></i>
                {{ __('messages.Bonus') }}: <strong>+10</strong> {{ __('messages.Extra points for scoring 90% or higher on a quiz') }}
            </p>
        </div>

        <div class="neo-border neo-radius bg-surface-container-lowest overflow-x-auto">
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-surface-container-high border-b-2 border-on-surface">
                        <th class="text-left px-6 py-3 text-xs font-bold uppercase tracking-wider text-secondary w-[48px]">#</th>
                        <th class="text-left px-6 py-3 text-xs font-bold uppercase tracking-wider text-secondary">{{ __('messages.Student') }}</th>
                        <th class="text-right px-6 py-3 text-xs font-bold uppercase tracking-wider text-secondary w-[100px]">{{ __('messages.Points') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($leaders as $index => $leader)
                        <tr class="border-b-2 border-on-surface last:border-b-0 {{ $leader->id === auth()->id() ? 'bg-primary-container/30' : '' }}">
                            <td class="px-6 py-4 align-middle">
                                <div class="flex justify-center">
                                    @if($index === 0)
                                        <span class="text-xl">🥇</span>
                                    @elseif($index === 1)
                                        <span class="text-xl">🥈</span>
                                    @elseif($index === 2)
                                        <span class="text-xl">🥉</span>
                                    @else
                                        <span class="text-sm font-bold text-on-surface">{{ $index + 1 }}</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 align-middle">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 neo-border neo-radius-full bg-surface-container-high flex-shrink-0 overflow-hidden">
                                        @if($leader->avatar)
                                            <img src="{{ $leader->avatar }}" alt="" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-xs font-bold text-on-surface">
                                                {{ substr($leader->name, 0, 2) }}
                                            </div>
                                        @endif
                                    </div>
                                    <span class="text-sm font-bold text-on-surface truncate">{{ $leader->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 align-middle text-right">
                                <span class="text-sm font-bold text-on-surface">{{ $leader->total_points }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="p-12 text-center">
                                <div class="inline-flex items-center justify-center w-16 h-16 mb-4 neo-border neo-radius bg-primary-container">
                                    <i class="text-2xl text-on-primary-container fas fa-trophy"></i>
                                </div>
                                <h3 class="text-sm font-bold tracking-widest uppercase text-on-surface">{{ __('messages.No rankings yet') }}</h3>
                                <p class="mt-2 text-sm text-secondary">{{ __('messages.Complete lessons and quizzes to earn points') }}</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
