<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        $guard = tenant() ? 'tenant' : 'web';

        Session::regenerate();

        // $this->redirectIntended(default: route("{$guard}.dashboard", absolute: false), navigate: true);

        // Clear any intended URL saved in the session fallback
        Session::forget('url.intended');

        // Redirect to home and replace the browser history state
        $this->redirect('/');
    }
}; ?>

<div>
    <x-auth-session-status class="mb-6" :status="session('status')" />

    <form wire:submit="login" class="space-y-6">
        {{-- Email --}}
        <div>
            <x-input-label for="email" :value="__('messages.Email')" />
            <x-text-input wire:model="form.email" id="email" class="block w-full mt-2" type="email" name="email"
                required autofocus autocomplete="username" placeholder="admin@example.com" />
            <x-input-error :messages="$errors->get('form.email')" class="mt-2" />
        </div>

        {{-- Password --}}
        <div>
            <div class="flex items-center justify-between">
                <x-input-label for="password" :value="__('messages.Password')" />
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" wire:navigate
                        class="text-xs font-bold underline uppercase transition-colors decoration-2" style="color: var(--color-on-surface, #0A0A0A);">
                        {{ __('messages.Forgot?') }}
                    </a>
                @endif
            </div>
            <x-text-input wire:model="form.password" id="password" class="block w-full mt-2" type="password"
                name="password" required autocomplete="current-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('form.password')" class="mt-2" />
        </div>

        {{-- Remember me --}}
        <div class="flex items-center">
            <input wire:model="form.remember" id="remember" type="checkbox"
                style="border: 2px solid var(--color-on-surface, #0A0A0A); border-radius: 2px;"
                class="w-5 h-5 text-primary-container focus:ring-2 focus:ring-primary-container"
                name="remember">
            <label for="remember" class="block text-sm font-bold uppercase ltr:ml-3 rtl:mr-3" style="color: var(--color-on-surface, #0A0A0A);">
                {{ __('messages.Remember me') }}
            </label>
        </div>

        {{-- Submit --}}
        <div class="pt-2">
            <button type="submit"
                class="w-full px-6 py-4 text-sm font-bold uppercase transition-all duration-200"
                style="background-color: var(--color-primary-container, #FFD600); border: 2px solid var(--color-on-surface, #0A0A0A); border-radius: 4px; color: var(--color-on-surface, #0A0A0A);"
                onmouseover="this.style.backgroundColor='var(--color-on-surface,#0A0A0A)'; this.style.color='var(--color-primary-container,#FFD600)';"
                onmouseout="this.style.backgroundColor='var(--color-primary-container,#FFD600)'; this.style.color='var(--color-on-surface,#0A0A0A)';">
                {{ __('messages.Log In to Dashboard') }}
            </button>
        </div>
    </form>

    @if (app(\App\Services\OAuthService::class)->isProviderConfigured('google'))
        <div class="relative my-6">
            <div class="absolute inset-0 flex items-center" aria-hidden="true">
                <div class="w-full border-t" style="border-color: var(--color-on-surface, #0A0A0A); opacity: 0.2;"></div>
            </div>
            <div class="relative flex justify-center text-sm">
                <span class="px-2 text-xs font-bold tracking-widest uppercase" style="color: var(--color-secondary, #5f5e5e); background-color: var(--color-surface-container-lowest, #FFFFFF);">
                    {{ __('messages.Or continue with') }}
                </span>
            </div>
        </div>

        <div>
            <a href="{{ route('auth.google.redirect') }}"
                class="inline-flex items-center justify-center w-full px-4 py-3 text-sm font-bold transition-all duration-200"
                style="background-color: var(--color-surface-container-lowest, #FFFFFF); border: 2px solid var(--color-on-surface, #0A0A0A); border-radius: 4px; color: var(--color-on-surface, #0A0A0A);"
                onmouseover="this.style.backgroundColor='var(--color-surface-container-high, #E8E8E8)';"
                onmouseout="this.style.backgroundColor='var(--color-surface-container-lowest, #FFFFFF)';">
                <svg class="w-5 h-5 ltr:mr-2 rtl:ml-2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
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

{{-- @push('auth-extra')
    <div class="mt-8 text-center">
        <p class="text-sm font-medium" style="color: var(--color-on-surface, #0A0A0A);">
            {{ __('messages.Don\'t have an account yet?') }}
            <a href="{{ route('register') }}" wire:navigate
                class="px-1 font-bold underline transition-all decoration-2 hover:bg-primary-container"
                style="color: var(--color-on-surface, #0A0A0A);">
                {{ __('messages.Start Free Trial') }}
            </a>
        </p>
    </div>
@endpush --}}
