<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $userModel = tenant() ? \App\Models\Tenant\User::class : \App\Models\User::class;

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email:rfc,dns', 'max:255', 'unique:' . $userModel],
            'password' => \App\Support\PasswordRules::default(),
        ]);

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered($user = $userModel::create($validated)));

        $user->assignRole('student');

        $guard = tenant() ? 'tenant' : 'web';

        Auth::guard($guard)->login($user);

        $this->redirect(route("{$guard}.dashboard", absolute: false), navigate: true);
    }
}; ?>

<div>
    <form wire:submit="register">
        <div>
            <x-input-label for="name" :value="__('messages.Name')" />
            <x-text-input wire:model="name" id="name" class="block mt-1 w-full" type="text" name="name" required
                autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="email" :value="__('messages.Email')" />
            <x-text-input wire:model="email" id="email" class="block mt-1 w-full" type="email" name="email" required
                autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" :value="__('messages.Password')" />

            <x-text-input wire:model="password" id="password" class="block mt-1 w-full" type="password" name="password"
                required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('messages.Confirm Password')" />

            <x-text-input wire:model="password_confirmation" id="password_confirmation" class="block mt-1 w-full"
                type="password" name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="text-sm text-secondary underline hover:text-on-surface transition-colors duration-150"
                href="{{ route('login') }}" wire:navigate>
                {{ __('messages.Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('messages.Register') }}
            </x-primary-button>
        </div>
    </form>

    @if (app(\App\Services\OAuthService::class)->isProviderConfigured('google'))
        <div class="relative my-6">
            <div class="absolute inset-0 flex items-center" aria-hidden="true">
                <div class="w-full border-t border-surface-container-high"></div>
            </div>
            <div class="relative flex justify-center text-sm">
                <span class="px-2 text-secondary bg-surface-container-lowest">{{ __('messages.Or continue with') }}</span>
            </div>
        </div>

        <div>
            <a href="{{ route('auth.google.redirect') }}" wire:navigate
                class="inline-flex items-center justify-center w-full px-4 py-2 text-sm font-bold text-on-surface bg-surface-container-low neo-border-sm neo-radius hover:bg-surface-container-high transition-colors duration-150">
                <svg class="w-5 h-5 me-2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                </svg>
                {{ __('messages.Sign in with Google') }}
            </a>
        </div>
    @endif
</div>
