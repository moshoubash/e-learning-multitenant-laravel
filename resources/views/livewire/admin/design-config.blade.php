<div>
    <header class="h-16 flex justify-between items-center px-[24px] bg-surface-container-lowest border-b-2 border-on-surface sticky top-0 z-40">
        <div>
            <h2 class="text-[24px] font-bold text-on-surface leading-none tracking-[0.08em]">{{ __('messages.Design Configuration') }}</h2>
            <p class="text-[12px] font-medium uppercase text-secondary mt-0.5 tracking-wider">{{ __('messages.Customize the appearance of your system') }}</p>
        </div>
        <div class="flex items-center gap-2">
            <button wire:click="resetDefaults"
                class="px-4 py-2 text-xs font-bold tracking-widest uppercase transition-colors neo-border neo-radius bg-surface-container text-on-surface hover:bg-on-surface hover:text-white">
                {{ __('messages.Reset Defaults') }}
            </button>
            <button wire:click="save"
                class="px-4 py-2 text-xs font-bold tracking-widest uppercase transition-colors neo-border neo-radius bg-primary-container text-on-surface hover:bg-on-surface hover:text-white">
                <i class="fas fa-save ltr:mr-2 rtl:ml-2"></i>
                {{ __('messages.Save') }}
            </button>
        </div>
    </header>

    <div class="p-[24px] max-w-[1400px] mx-auto space-y-6">
        {{-- Preview Banner --}}
        <div class="p-4 neo-border neo-radius bg-surface-container-lowest">
            <div class="flex items-center justify-between p-4 rounded" style="background-color: var(--color-primary-container, #FFD600);">
                <span class="text-sm font-bold" style="color: var(--color-on-primary-container, #705d00);">{{ __('messages.This is a live preview of your color scheme') }}</span>
                <span class="px-3 py-1 text-xs font-bold rounded" style="background-color: var(--color-on-surface, #0A0A0A); color: var(--color-surface-container-lowest, #FFFFFF);">{{ __('messages.Preview') }}</span>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            {{-- Main Colors --}}
            <div class="p-6 bg-surface-container-lowest neo-border neo-radius">
                <h3 class="mb-4 text-xs font-bold tracking-widest uppercase text-on-surface">{{ __('messages.Main Colors') }}</h3>
                <div class="space-y-4">
                    @foreach ([
                        ['label' => 'primary-container', 'prop' => 'primaryContainer', 'err' => 'primaryContainer'],
                        ['label' => 'on-surface', 'prop' => 'onSurface', 'err' => 'onSurface'],
                        ['label' => 'on-primary-container', 'prop' => 'onPrimaryContainer', 'err' => 'onPrimaryContainer'],
                        ['label' => 'secondary', 'prop' => 'secondary', 'err' => 'secondary'],
                        ['label' => 'error', 'prop' => 'error', 'err' => 'error'],
                    ] as $field)
                        <div class="flex items-center gap-4">
                            <input type="color" wire:model.lazy="{{ $field['prop'] }}"
                                class="w-12 h-12 border-0 cursor-pointer neo-border-sm neo-radius">
                            <div class="flex-1">
                                <label class="block text-xs font-bold tracking-widest uppercase text-on-surface">{{ $field['label'] }}</label>
                                <input type="text" wire:model.lazy="{{ $field['prop'] }}"
                                    class="w-full px-3 py-1.5 mt-1 text-xs font-mono neo-border-sm neo-radius bg-surface-container-low text-on-surface focus:outline-none focus:ring-0">
                                @error($field['err']) <span class="text-xs text-error mt-0.5 block font-bold">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Surface Colors --}}
            <div class="p-6 bg-surface-container-lowest neo-border neo-radius">
                <h3 class="mb-4 text-xs font-bold tracking-widest uppercase text-on-surface">{{ __('messages.Surface Colors') }}</h3>
                <div class="space-y-4">
                    @foreach ([
                        ['label' => 'surface-container-lowest', 'prop' => 'surfaceContainerLowest', 'err' => 'surfaceContainerLowest'],
                        ['label' => 'surface-container-low', 'prop' => 'surfaceContainerLow', 'err' => 'surfaceContainerLow'],
                        ['label' => 'surface-container', 'prop' => 'surfaceContainer', 'err' => 'surfaceContainer'],
                        ['label' => 'surface-container-high', 'prop' => 'surfaceContainerHigh', 'err' => 'surfaceContainerHigh'],
                        ['label' => 'surface-container-highest', 'prop' => 'surfaceContainerHighest', 'err' => 'surfaceContainerHighest'],
                    ] as $field)
                        <div class="flex items-center gap-4">
                            <input type="color" wire:model.lazy="{{ $field['prop'] }}"
                                class="w-12 h-12 border-0 cursor-pointer neo-border-sm neo-radius">
                            <div class="flex-1">
                                <label class="block text-xs font-bold tracking-widest uppercase text-on-surface">{{ $field['label'] }}</label>
                                <input type="text" wire:model.lazy="{{ $field['prop'] }}"
                                    class="w-full px-3 py-1.5 mt-1 text-xs font-mono neo-border-sm neo-radius bg-surface-container-low text-on-surface focus:outline-none focus:ring-0">
                                @error($field['err']) <span class="text-xs text-error mt-0.5 block font-bold">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Chart Colors --}}
            <div class="p-6 bg-surface-container-lowest neo-border neo-radius lg:col-span-2">
                <h3 class="mb-4 text-xs font-bold tracking-widest uppercase text-on-surface">{{ __('messages.Chart Colors') }}</h3>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6">
                    @foreach (['chart1', 'chart2', 'chart3', 'chart4', 'chart5', 'chart6'] as $chart)
                        <div class="flex flex-col items-center gap-2 p-3 bg-surface-container-low neo-border-sm neo-radius">
                            <input type="color" wire:model.lazy="{{ $chart }}"
                                class="w-full h-10 border-0 cursor-pointer neo-border-sm neo-radius">
                            <input type="text" wire:model.lazy="{{ $chart }}"
                                class="w-full px-2 py-1 text-xs font-mono text-center neo-border-sm neo-radius bg-surface-container-lowest text-on-surface focus:outline-none focus:ring-0">
                            @error($chart) <span class="text-xs text-error font-bold">{{ $message }}</span> @enderror
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Auth Colors --}}
            <div class="p-6 bg-surface-container-lowest neo-border neo-radius lg:col-span-2">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xs font-bold tracking-widest uppercase text-on-surface">{{ __('messages.Auth Page Colors') }}</h3>
                    <span class="px-2 py-1 text-[10px] font-bold uppercase tracking-widest bg-primary-container/20 text-on-primary-container neo-border-sm neo-radius">{{ __('messages.Separate from main design') }}</span>
                </div>
                <p class="mb-4 text-xs text-secondary">{{ __('messages.These colors only apply to login, register, and other auth pages') }}</p>
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    @foreach ([
                        ['label' => 'auth-body-bg', 'prop' => 'authBodyBg', 'err' => 'authBodyBg'],
                        ['label' => 'auth-card-bg', 'prop' => 'authCardBg', 'err' => 'authCardBg'],
                        ['label' => 'auth-primary', 'prop' => 'authPrimary', 'err' => 'authPrimary'],
                        ['label' => 'auth-on-primary', 'prop' => 'authOnPrimary', 'err' => 'authOnPrimary'],
                        ['label' => 'auth-text', 'prop' => 'authText', 'err' => 'authText'],
                        ['label' => 'auth-secondary', 'prop' => 'authSecondary', 'err' => 'authSecondary'],
                        ['label' => 'auth-border', 'prop' => 'authBorder', 'err' => 'authBorder'],
                        ['label' => 'auth-error', 'prop' => 'authError', 'err' => 'authError'],
                    ] as $field)
                        <div class="flex items-center gap-4">
                            <input type="color" wire:model.lazy="{{ $field['prop'] }}"
                                class="w-12 h-12 border-0 cursor-pointer neo-border-sm neo-radius">
                            <div class="flex-1">
                                <label class="block text-xs font-bold tracking-widest uppercase text-on-surface">{{ $field['label'] }}</label>
                                <input type="text" wire:model.lazy="{{ $field['prop'] }}"
                                    class="w-full px-3 py-1.5 mt-1 text-xs font-mono neo-border-sm neo-radius bg-surface-container-low text-on-surface focus:outline-none focus:ring-0">
                                @error($field['err']) <span class="text-xs text-error mt-0.5 block font-bold">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

</div>
