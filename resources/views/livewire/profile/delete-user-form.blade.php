<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;

new class extends Component
{
    public string $password = '';

    public function deleteUser(Logout $logout): void
    {
        $this->validate([
            'password' => ['required', 'string', 'current_password'],
        ]);

        tap(Auth::user(), $logout(...))->delete();

        $this->redirect('/', navigate: true);
    }
}; ?>

<section class="space-y-6">
    <header class="mb-6">
        <h2 class="text-[16px] font-bold uppercase tracking-widest text-on-surface leading-none">
            {{ __('messages.Delete Account') }}
        </h2>
        <p class="mt-2 text-sm text-secondary">
            {{ __('messages.Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </p>
    </header>

    <button type="button"
            x-data=""
            x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
            class="px-5 py-2 neo-border neo-radius bg-error text-white text-xs font-bold uppercase tracking-widest hover:bg-on-surface transition-colors">
        {{ __('messages.Delete Account') }}
    </button>

    <div x-data="{
            show: @js($errors->isNotEmpty()),
            focusables() {
                let selector = 'a, button, input:not([type=\'hidden\']), textarea, select, details, [tabindex]:not([tabindex=\'-1\'])'
                return [...$el.querySelectorAll(selector)].filter(el => !el.hasAttribute('disabled'))
            },
            firstFocusable() { return this.focusables()[0] },
        }"
        x-init="$watch('show', value => { document.body.classList.toggle('overflow-y-hidden', value); if (value) setTimeout(() => firstFocusable()?.focus(), 100); })"
        x-on:open-modal.window="$event.detail == 'confirm-user-deletion' ? show = true : null"
        x-on:close-modal.window="$event.detail == 'confirm-user-deletion' ? show = false : null"
        x-on:keydown.escape.window="show = false"
        x-on:close.stop="show = false"
        x-show="show"
        class="fixed inset-0 overflow-y-auto px-4 py-6 sm:px-0 z-50"
        style="display: none;">
        <div x-show="show" class="fixed inset-0 transform transition-all" x-on:click="show = false"
             x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
            <div class="absolute inset-0 bg-on-surface/50"></div>
        </div>
        <div x-show="show" class="mb-6 bg-surface-container-lowest neo-border neo-radius overflow-hidden transform transition-all sm:w-full sm:max-w-lg sm:mx-auto"
             x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
            <form wire:submit="deleteUser" class="p-6">
                <h2 class="text-[16px] font-bold uppercase tracking-widest text-on-surface leading-none mb-4">
                    {{ __('messages.Are you sure you want to delete your account?') }}
                </h2>
                <p class="text-sm text-secondary mb-6">
                    {{ __('messages.Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
                </p>
                <div class="mb-6">
                    <label for="password" class="block text-xs font-bold uppercase tracking-widest text-secondary mb-1.5 sr-only">{{ __('messages.Password') }}</label>
                    <input wire:model="password" id="password" name="password" type="password"
                           class="block w-3/4 px-3 py-2.5 neo-border neo-radius bg-surface-container-low text-on-surface text-sm focus:outline-none focus:bg-surface-container-lowest focus:border-on-surface transition-colors"
                           placeholder="{{ __('messages.Password') }}" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" x-on:click="$dispatch('close-modal', 'confirm-user-deletion')"
                            class="px-5 py-2 neo-border neo-radius bg-surface-container-lowest text-on-primary-container text-xs font-bold uppercase tracking-widest hover:bg-surface-container-high transition-colors">
                        {{ __('messages.Cancel') }}
                    </button>
                    <button type="submit"
                            class="px-5 py-2 neo-border neo-radius bg-error text-white text-xs font-bold uppercase tracking-widest hover:bg-on-surface transition-colors">
                        {{ __('messages.Delete Account') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>