<?php

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    #[Locked]
    public string $token = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Mount the component.
     */
    public function mount(string $token): void
    {
        $this->token = $token;

        $this->email = request()->string('email');
    }

    /**
     * Reset the password for the given user.
     */
    public function resetPassword(): void
    {
        $this->validate([
            'token' => ['required'],
            'email' => ['required', 'string', 'email'],
            'password' => \App\Support\PasswordRules::default(),
        ]);

        $status = Password::reset(
            $this->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) {
                $user->forceFill([
                    'password' => Hash::make($this->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status != Password::PASSWORD_RESET) {
            $this->addError('email', __($status));

            return;
        }

        Session::flash('status', __($status));

        $this->redirectRoute('login', navigate: true);
    }
}; ?>

<div>
    <form wire:submit="resetPassword" class="space-y-5">
        <div>
            <x-input-label for="email" :value="__('messages.Email')" />
            <x-text-input wire:model="email" id="email" class="block w-full mt-2" type="email" name="email" required autofocus autocomplete="username" placeholder="admin@example.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password" :value="__('messages.Password')" />
            <x-text-input wire:model="password" id="password" class="block w-full mt-2" type="password" name="password" required autocomplete="new-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password_confirmation" :value="__('messages.Confirm Password')" />
            <x-text-input wire:model="password_confirmation" id="password_confirmation" class="block w-full mt-2"
                type="password" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="pt-2">
            <button type="submit"
                class="w-full px-6 py-4 text-sm font-bold uppercase transition-all duration-200"
                style="background-color: var(--color-primary-container, #FFD600); border: 2px solid var(--color-on-surface, #0A0A0A); border-radius: 4px; color: var(--color-on-surface, #0A0A0A);"
                onmouseover="this.style.backgroundColor='var(--color-on-surface,#0A0A0A)'; this.style.color='var(--color-primary-container,#FFD600)';"
                onmouseout="this.style.backgroundColor='var(--color-primary-container,#FFD600)'; this.style.color='var(--color-on-surface,#0A0A0A)';">
                {{ __('messages.Reset Password') }}
            </button>
        </div>
    </form>
</div>
