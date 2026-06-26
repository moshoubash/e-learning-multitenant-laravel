<?php

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $email = '';

    /**
     * Send a password reset link to the provided email address.
     */
    public function sendPasswordResetLink(): void
    {
        $this->validate([
            'email' => ['required', 'string', 'email'],
        ]);

        $status = Password::sendResetLink(
            $this->only('email')
        );

        if ($status != Password::RESET_LINK_SENT) {
            $this->addError('email', __($status));

            return;
        }

        $this->reset('email');

        session()->flash('status', __($status));
    }
}; ?>

<div>
    <div class="mb-6 text-sm font-medium" style="color: var(--color-secondary, #5f5e5e);">
        {{ __('messages.Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
    </div>

    <x-auth-session-status class="mb-6" :status="session('status')" />

    <form wire:submit="sendPasswordResetLink" class="space-y-6">
        <div>
            <x-input-label for="email" :value="__('messages.Email')" />
            <x-text-input wire:model="email" id="email" class="block w-full mt-2" type="email" name="email" required autofocus autocomplete="email" placeholder="admin@example.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="pt-2">
            <button type="submit"
                class="w-full px-6 py-4 text-sm font-bold uppercase transition-all duration-200"
                style="background-color: var(--color-primary-container, #FFD600); border: 2px solid var(--color-on-surface, #0A0A0A); border-radius: 4px; color: var(--color-on-surface, #0A0A0A);"
                onmouseover="this.style.backgroundColor='var(--color-on-surface,#0A0A0A)'; this.style.color='var(--color-primary-container,#FFD600)';"
                onmouseout="this.style.backgroundColor='var(--color-primary-container,#FFD600)'; this.style.color='var(--color-on-surface,#0A0A0A)';">
                {{ __('messages.Email Password Reset Link') }}
            </button>
        </div>
    </form>
</div>

@push('auth-extra')
    <div class="mt-8 text-center">
        <p class="text-sm font-medium" style="color: var(--color-on-surface, #0A0A0A);">
            {{ __('messages.Remember your password?') }}
            <a href="{{ route('login') }}" wire:navigate
                class="px-1 font-bold underline transition-all decoration-2 hover:bg-primary-container"
                style="color: var(--color-on-surface, #0A0A0A);">
                {{ __('messages.Log in') }}
            </a>
        </p>
    </div>
@endpush
