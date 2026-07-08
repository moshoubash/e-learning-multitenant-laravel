<div>
    <header class="h-16 flex justify-between items-center px-[24px] bg-surface-container-lowest border-b-2 border-on-surface sticky top-0 z-40">
        <div>
            <h2 class="text-[24px] font-bold text-on-surface leading-none">{{ __('messages.SMTP Configuration') }}</h2>
            <p class="text-[12px] font-medium uppercase text-secondary mt-0.5 tracking-wider">{{ __('messages.Configure email server settings to override .env defaults') }}</p>
        </div>
        <div class="flex items-center gap-2">
            @livewire('shared.notification-bell')
        </div>
    </header>

    <div class="p-[24px] max-w-[1400px] mx-auto">
        <div class="overflow-hidden bg-surface-container-lowest neo-border neo-radius">
            <div class="p-[24px] border-b-2 border-on-surface">
                <h3 class="text-[18px] font-bold uppercase tracking-widest text-on-surface leading-none">{{ __('messages.Mail Server Settings') }}</h3>
                <p class="mt-2 text-xs text-secondary">{{ __('messages.These settings will override the .env mail configuration for this tenant. Leave fields empty to use defaults.') }}</p>
            </div>

            <form wire:submit="save" class="p-[24px] space-y-6">
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <label class="block mb-1 text-xs font-bold tracking-widest uppercase text-on-surface">{{ __('messages.Mailer') }}</label>
                        <select wire:model.lazy="mailMailer"
                            class="w-full px-3 py-2 text-sm neo-border-sm neo-radius bg-surface-container-low text-on-surface focus:outline-none focus:ring-0">
                            <option value="smtp">SMTP</option>
                            <option value="sendmail">Sendmail</option>
                            <option value="log">Log</option>
                        </select>
                        @error('mailMailer') <span class="block mt-1 text-xs font-bold text-error">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block mb-1 text-xs font-bold tracking-widest uppercase text-on-surface">{{ __('messages.Encryption') }}</label>
                        <select wire:model.lazy="mailEncryption"
                            class="w-full px-3 py-2 text-sm neo-border-sm neo-radius bg-surface-container-low text-on-surface focus:outline-none focus:ring-0">
                            <option value="">{{ __('messages.None') }}</option>
                            <option value="tls">TLS</option>
                            <option value="ssl">SSL</option>
                        </select>
                        @error('mailEncryption') <span class="block mt-1 text-xs font-bold text-error">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block mb-1 text-xs font-bold tracking-widest uppercase text-on-surface">{{ __('messages.Host') }}</label>
                        <input type="text" wire:model.lazy="mailHost" placeholder="smtp.gmail.com"
                            class="w-full px-3 py-2 text-sm neo-border-sm neo-radius bg-surface-container-low text-on-surface focus:outline-none focus:ring-0 placeholder:text-secondary">
                        @error('mailHost') <span class="block mt-1 text-xs font-bold text-error">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block mb-1 text-xs font-bold tracking-widest uppercase text-on-surface">{{ __('messages.Port') }}</label>
                        <input type="number" wire:model.lazy="mailPort" placeholder="587"
                            class="w-full px-3 py-2 text-sm neo-border-sm neo-radius bg-surface-container-low text-on-surface focus:outline-none focus:ring-0 placeholder:text-secondary">
                        @error('mailPort') <span class="block mt-1 text-xs font-bold text-error">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block mb-1 text-xs font-bold tracking-widest uppercase text-on-surface">{{ __('messages.Username') }}</label>
                        <input type="text" wire:model.lazy="mailUsername" placeholder="user@gmail.com"
                            class="w-full px-3 py-2 text-sm neo-border-sm neo-radius bg-surface-container-low text-on-surface focus:outline-none focus:ring-0 placeholder:text-secondary">
                        @error('mailUsername') <span class="block mt-1 text-xs font-bold text-error">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block mb-1 text-xs font-bold tracking-widest uppercase text-on-surface">{{ __('messages.Password') }}</label>
                        <input type="password" wire:model.lazy="mailPassword" placeholder="••••••••"
                            class="w-full px-3 py-2 text-sm neo-border-sm neo-radius bg-surface-container-low text-on-surface focus:outline-none focus:ring-0 placeholder:text-secondary">
                        <p class="mt-1 text-xs text-secondary">{{ __('messages.Leave empty to keep existing password') }}</p>
                        @error('mailPassword') <span class="block mt-1 text-xs font-bold text-error">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block mb-1 text-xs font-bold tracking-widest uppercase text-on-surface">{{ __('messages.From Address') }}</label>
                        <input type="email" wire:model.lazy="mailFromAddress" placeholder="noreply@example.com"
                            class="w-full px-3 py-2 text-sm neo-border-sm neo-radius bg-surface-container-low text-on-surface focus:outline-none focus:ring-0 placeholder:text-secondary">
                        @error('mailFromAddress') <span class="block mt-1 text-xs font-bold text-error">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block mb-1 text-xs font-bold tracking-widest uppercase text-on-surface">{{ __('messages.From Name') }}</label>
                        <input type="text" wire:model.lazy="mailFromName" placeholder="GRID LMS"
                            class="w-full px-3 py-2 text-sm neo-border-sm neo-radius bg-surface-container-low text-on-surface focus:outline-none focus:ring-0 placeholder:text-secondary">
                        @error('mailFromName') <span class="block mt-1 text-xs font-bold text-error">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="flex items-center gap-3 pt-4 border-t border-on-surface/10">
                    <div class="flex items-center gap-2">
                        <input type="checkbox" wire:model.lazy="isActive" id="isActive"
                            class="w-4 h-4 neo-border-sm neo-radius text-primary-container focus:ring-0">
                        <label for="isActive" class="text-xs font-bold tracking-widest uppercase cursor-pointer text-on-surface">{{ __('messages.Active') }}</label>
                    </div>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit"
                        class="px-4 py-2 text-xs font-bold tracking-widest uppercase transition-colors neo-border neo-radius bg-primary-container text-on-primary-container hover:bg-on-surface hover:text-white">
                        <i class="fas fa-save ltr:mr-2 rtl:ml-2"></i>
                        {{ __('messages.Save Settings') }}
                    </button>
                    <button type="button" wire:click="testConnection" wire:loading.attr="disabled"
                        class="px-4 py-2 text-xs font-bold tracking-widest uppercase transition-colors neo-border-sm neo-radius bg-surface-container text-on-surface hover:bg-on-surface hover:text-white disabled:opacity-40">
                        <i class="fas fa-paper-plane ltr:mr-2 rtl:ml-2"></i>
                        {{ __('messages.Send Test Email') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
