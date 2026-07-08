<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Livewire\Volt\Component;

new class extends Component
{
    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';

    public function updatePassword(): void
    {
        try {
            $validated = $this->validate([
                'current_password' => ['required', 'string', 'current_password'],
                'password' => ['required', 'string', Password::defaults(), 'confirmed'],
            ]);
        } catch (ValidationException $e) {
            $this->reset('current_password', 'password', 'password_confirmation');
            throw $e;
        }

        Auth::user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        $this->reset('current_password', 'password', 'password_confirmation');
        $this->dispatch('password-updated');
    }
}; ?>

<section>
    <header class="mb-6">
        <h2 class="text-[16px] font-bold uppercase tracking-widest text-on-surface leading-none">
            {{ __('messages.Update Password') }}
        </h2>
        <p class="mt-2 text-sm text-secondary">
            {{ __('messages.Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <form wire:submit="updatePassword" class="space-y-5">
        <div>
            <label for="update_password_current_password" class="block text-xs font-bold uppercase tracking-widest text-secondary mb-1.5">{{ __('messages.Current Password') }}</label>
            <input wire:model="current_password" id="update_password_current_password" name="current_password" type="password" autocomplete="current-password"
                   class="block w-full px-3 py-2.5 neo-border neo-radius bg-surface-container-low text-on-surface text-sm focus:outline-none focus:bg-surface-container-lowest focus:border-on-surface transition-colors" />
            <x-input-error :messages="$errors->get('current_password')" class="mt-2" />
        </div>

        <div>
            <label for="update_password_password" class="block text-xs font-bold uppercase tracking-widest text-secondary mb-1.5">{{ __('messages.New Password') }}</label>
            <input wire:model="password" id="update_password_password" name="password" type="password" autocomplete="new-password"
                   class="block w-full px-3 py-2.5 neo-border neo-radius bg-surface-container-low text-on-surface text-sm focus:outline-none focus:bg-surface-container-lowest focus:border-on-surface transition-colors" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div>
            <label for="update_password_password_confirmation" class="block text-xs font-bold uppercase tracking-widest text-secondary mb-1.5">{{ __('messages.Confirm Password') }}</label>
            <input wire:model="password_confirmation" id="update_password_password_confirmation" name="password_confirmation" type="password" autocomplete="new-password"
                   class="block w-full px-3 py-2.5 neo-border neo-radius bg-surface-container-low text-on-surface text-sm focus:outline-none focus:bg-surface-container-lowest focus:border-on-surface transition-colors" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4 pt-2">
            <button type="submit" class="px-4 py-2 text-xs font-bold tracking-widest uppercase transition-colors text-on-primary-container neo-border neo-radius bg-primary-container hover:bg-on-surface hover:text-primary-container hover:border-on-surface">
                {{ __('messages.Save') }}
            </button>
            <x-action-message class="text-sm font-bold text-on-surface" on="password-updated">
                {{ __('messages.Saved.') }}
            </x-action-message>
        </div>
    </form>
</section>
