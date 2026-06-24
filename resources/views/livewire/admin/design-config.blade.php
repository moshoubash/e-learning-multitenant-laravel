<div>
    <header class="px-[24px] py-[16px] bg-surface-container-lowest border-b-2 border-on-surface sticky top-0 z-40">
        <div class="flex items-start justify-between">
            <div>
                <h2 class="text-[24px] font-bold text-on-surface leading-none tracking-[0.08em]">{{ __('messages.Design Configuration') }}</h2>
                <p class="text-[12px] font-medium uppercase text-secondary mt-0.5 tracking-wider">{{ __('messages.Customize the appearance of your system') }}</p>
            </div>
            <div>
                @livewire('shared.notification-bell')
            </div>
        </div>
        <div class="flex items-center gap-2 mt-3">
            <button wire:click="resetDefaults"
                class="px-4 py-2 text-xs font-bold tracking-widest uppercase transition-colors neo-border neo-radius bg-surface-container text-on-primary-container hover:bg-on-surface hover:text-white">
                {{ __('messages.Reset Defaults') }}
            </button>
            <button wire:click="save"
                class="px-4 py-2 text-xs font-bold tracking-widest uppercase transition-colors neo-border neo-radius bg-primary-container text-on-primary-container hover:bg-on-surface hover:text-white">
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
                        ['label' => 'primary-container', 'prop' => 'primaryContainer', 'err' => 'primaryContainer', 'desc' => 'Buttons, badges, active elements'],
                        ['label' => 'on-surface', 'prop' => 'onSurface', 'err' => 'onSurface', 'desc' => 'Main text color throughout the system'],
                        ['label' => 'on-primary-container', 'prop' => 'onPrimaryContainer', 'err' => 'onPrimaryContainer', 'desc' => 'Text on primary-colored elements'],
                        ['label' => 'secondary', 'prop' => 'secondary', 'err' => 'secondary', 'desc' => 'Muted text and secondary icons'],
                        ['label' => 'error', 'prop' => 'error', 'err' => 'error', 'desc' => 'Error messages and destructive actions'],
                    ] as $field)
                        <div class="flex items-start gap-4">
                            <input type="color" wire:model.lazy="{{ $field['prop'] }}"
                                class="w-12 h-12 border-0 cursor-pointer neo-border-sm neo-radius shrink-0">
                            <div class="flex-1 min-w-0">
                                <label class="block text-xs font-bold tracking-widest uppercase text-on-surface">{{ $field['label'] }}</label>
                                <span class="block text-[10px] text-secondary italic leading-tight mt-0.5">{{ $field['desc'] }}</span>
                                <input type="text" wire:model.lazy="{{ $field['prop'] }}"
                                    class="w-full px-3 py-1.5 mt-1.5 text-xs font-mono neo-border-sm neo-radius bg-surface-container-low text-on-surface focus:outline-none focus:ring-0">
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
                        ['label' => 'surface-container-lowest', 'prop' => 'surfaceContainerLowest', 'err' => 'surfaceContainerLowest', 'desc' => 'Card and page backgrounds'],
                        ['label' => 'surface-container-low', 'prop' => 'surfaceContainerLow', 'err' => 'surfaceContainerLow', 'desc' => 'Input fields and subtle backgrounds'],
                        ['label' => 'surface-container', 'prop' => 'surfaceContainer', 'err' => 'surfaceContainer', 'desc' => 'Table rows and hover states'],
                        ['label' => 'surface-container-high', 'prop' => 'surfaceContainerHigh', 'err' => 'surfaceContainerHigh', 'desc' => 'Active hover backgrounds'],
                        ['label' => 'surface-container-highest', 'prop' => 'surfaceContainerHighest', 'err' => 'surfaceContainerHighest', 'desc' => 'Borders and dividers'],
                    ] as $field)
                        <div class="flex items-start gap-4">
                            <input type="color" wire:model.lazy="{{ $field['prop'] }}"
                                class="w-12 h-12 border-0 cursor-pointer neo-border-sm neo-radius shrink-0">
                            <div class="flex-1 min-w-0">
                                <label class="block text-xs font-bold tracking-widest uppercase text-on-surface">{{ $field['label'] }}</label>
                                <span class="block text-[10px] text-secondary italic leading-tight mt-0.5">{{ $field['desc'] }}</span>
                                <input type="text" wire:model.lazy="{{ $field['prop'] }}"
                                    class="w-full px-3 py-1.5 mt-1.5 text-xs font-mono neo-border-sm neo-radius bg-surface-container-low text-on-surface focus:outline-none focus:ring-0">
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
                    @foreach ([
                        ['chart' => 'chart1', 'desc' => 'Chart color 1 (primary data series)'],
                        ['chart' => 'chart2', 'desc' => 'Chart color 2 (secondary data series)'],
                        ['chart' => 'chart3', 'desc' => 'Chart color 3 (tertiary data series)'],
                        ['chart' => 'chart4', 'desc' => 'Chart color 4 (quaternary data series)'],
                        ['chart' => 'chart5', 'desc' => 'Chart color 5 (quinary data series)'],
                        ['chart' => 'chart6', 'desc' => 'Chart color 6 (senary data series)'],
                    ] as $chartItem)
                        <div class="flex flex-col items-center gap-2 p-3 bg-surface-container-low neo-border-sm neo-radius">
                            <input type="color" wire:model.lazy="{{ $chartItem['chart'] }}"
                                class="w-full h-10 border-0 cursor-pointer neo-border-sm neo-radius">
                            <span class="text-[10px] text-secondary italic text-center leading-tight">{{ $chartItem['desc'] }}</span>
                            <input type="text" wire:model.lazy="{{ $chartItem['chart'] }}"
                                class="w-full px-2 py-1 font-mono text-xs text-center neo-border-sm neo-radius bg-surface-container-lowest text-on-surface focus:outline-none focus:ring-0">
                            @error($chartItem['chart']) <span class="text-xs font-bold text-error">{{ $message }}</span> @enderror
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
                        ['label' => 'auth-body-bg', 'prop' => 'authBodyBg', 'err' => 'authBodyBg', 'desc' => 'Auth page background'],
                        ['label' => 'auth-card-bg', 'prop' => 'authCardBg', 'err' => 'authCardBg', 'desc' => 'Auth card background'],
                        ['label' => 'auth-primary', 'prop' => 'authPrimary', 'err' => 'authPrimary', 'desc' => 'Primary button and accent color'],
                        ['label' => 'auth-on-primary', 'prop' => 'authOnPrimary', 'err' => 'authOnPrimary', 'desc' => 'Text on primary buttons'],
                        ['label' => 'auth-text', 'prop' => 'authText', 'err' => 'authText', 'desc' => 'Auth page text color'],
                        ['label' => 'auth-secondary', 'prop' => 'authSecondary', 'err' => 'authSecondary', 'desc' => 'Auth muted and helper text'],
                        ['label' => 'auth-border', 'prop' => 'authBorder', 'err' => 'authBorder', 'desc' => 'Auth card border'],
                        ['label' => 'auth-error', 'prop' => 'authError', 'err' => 'authError', 'desc' => 'Auth error messages'],
                    ] as $field)
                        <div class="flex items-start gap-4">
                            <input type="color" wire:model.lazy="{{ $field['prop'] }}"
                                class="w-12 h-12 border-0 cursor-pointer neo-border-sm neo-radius shrink-0">
                            <div class="flex-1 min-w-0">
                                <label class="block text-xs font-bold tracking-widest uppercase text-on-surface">{{ $field['label'] }}</label>
                                <span class="block text-[10px] text-secondary italic leading-tight mt-0.5">{{ $field['desc'] }}</span>
                                <input type="text" wire:model.lazy="{{ $field['prop'] }}"
                                    class="w-full px-3 py-1.5 mt-1.5 text-xs font-mono neo-border-sm neo-radius bg-surface-container-low text-on-surface focus:outline-none focus:ring-0">
                                @error($field['err']) <span class="text-xs text-error mt-0.5 block font-bold">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

</div>
