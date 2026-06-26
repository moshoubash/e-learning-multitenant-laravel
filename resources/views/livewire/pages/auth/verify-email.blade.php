<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    /**
     * Send an email verification notification to the user.
     */
    public function sendVerification(): void
    {
        if (Auth::user()->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);

            return;
        }

        Auth::user()->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }

    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<div>
    <div class="mb-6 text-sm font-medium" style="color: var(--color-secondary, #5f5e5e);">
        {{ __('messages.Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-6 text-sm font-medium" style="color: #16a34a;">
            {{ __('messages.A new verification link has been sent to the email address you provided during registration.') }}
        </div>
    @endif

    <div class="space-y-4">
        <button wire:click="sendVerification"
            class="w-full px-6 py-4 text-sm font-bold uppercase transition-all duration-200"
            style="background-color: var(--color-primary-container, #FFD600); border: 2px solid var(--color-on-surface, #0A0A0A); border-radius: 4px; color: var(--color-on-surface, #0A0A0A);"
            onmouseover="this.style.backgroundColor='var(--color-on-surface,#0A0A0A)'; this.style.color='var(--color-primary-container,#FFD600)';"
            onmouseout="this.style.backgroundColor='var(--color-primary-container,#FFD600)'; this.style.color='var(--color-on-surface,#0A0A0A)';">
            {{ __('messages.Resend Verification Email') }}
        </button>

        <button wire:click="logout" type="submit"
            class="w-full py-3 text-sm font-bold tracking-widest uppercase transition-all duration-200"
            style="border: 2px solid var(--color-on-surface, #0A0A0A); border-radius: 4px; color: var(--color-on-surface, #0A0A0A); background-color: transparent;"
            onmouseover="this.style.backgroundColor='var(--color-on-surface,#0A0A0A)'; this.style.color='var(--color-primary-container,#FFD600)';"
            onmouseout="this.style.backgroundColor='transparent'; this.style.color='var(--color-on-surface,#0A0A0A)';">
            {{ __('messages.Log Out') }}
        </button>
    </div>
</div>
