<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;

new class extends Component
{
    public string $name = '';
    public string $email = '';

    public function mount(): void
    {
        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;
    }

    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(get_class($user))->ignore($user->id)],
        ]);

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        $this->dispatch('profile-updated', name: $user->name);
    }

    public function sendVerification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));
            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }
}; ?>

<section>
    <header class="mb-6">
        <h2 class="text-[16px] font-bold uppercase tracking-widest text-on-surface leading-none">
            {{ __('messages.Profile Information') }}
        </h2>
        <p class="mt-2 text-sm text-secondary">
            {{ __("messages.Update your account's profile information and email address.") }}
        </p>
    </header>

    <form wire:submit="updateProfileInformation" class="space-y-5">
        <div>
            <label for="name" class="block text-xs font-bold uppercase tracking-widest text-secondary mb-1.5">{{ __('messages.Name') }}</label>
            <input wire:model="name" id="name" name="name" type="text" required autofocus autocomplete="name"
                   class="block w-full px-3 py-2.5 neo-border neo-radius bg-surface-container-low text-on-surface text-sm focus:outline-none focus:bg-surface-container-lowest focus:border-on-surface transition-colors" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <label for="email" class="block text-xs font-bold uppercase tracking-widest text-secondary mb-1.5">{{ __('messages.Email') }}</label>
            <input wire:model="email" id="email" name="email" type="email" required autocomplete="username"
                   class="block w-full px-3 py-2.5 neo-border neo-radius bg-surface-container-low text-on-surface text-sm focus:outline-none focus:bg-surface-container-lowest focus:border-on-surface transition-colors" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! auth()->user()->hasVerifiedEmail())
                <div class="mt-3">
                    <p class="text-sm text-secondary">
                        {{ __('messages.Your email address is unverified.') }}
                        <button wire:click.prevent="sendVerification" class="underline font-bold text-on-surface hover:text-primary-container transition-colors">
                            {{ __('messages.Click here to re-send the verification email.') }}
                        </button>
                    </p>
                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 text-sm font-bold text-on-surface">
                            {{ __('messages.A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4 pt-2">
            <button type="submit" class="px-5 py-2 neo-border neo-radius bg-on-surface text-white text-xs font-bold uppercase tracking-widest hover:bg-primary-container hover:text-on-surface hover:border-on-surface transition-colors">
                {{ __('messages.Save') }}
            </button>
            <x-action-message class="text-sm text-on-surface font-bold" on="profile-updated">
                {{ __('messages.Saved.') }}
            </x-action-message>
        </div>
    </form>
</section>