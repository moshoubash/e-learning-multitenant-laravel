<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\Tenant\User as TenantUser;
use App\Models\User;
use App\Services\OAuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class GoogleAuthController extends Controller
{
    public function redirect(): RedirectResponse
    {
        if (! $this->googleConfigured()) {
            return redirect()
                ->route('login')
                ->withErrors(['google' => 'Google sign-in is not configured on this server.']);
        }

        $this->applyGoogleConfig();

        return Socialite::driver('google')->redirect();
    }

    public function callback(): RedirectResponse
    {
        if (! $this->googleConfigured()) {
            return redirect()->route('login');
        }

        $this->applyGoogleConfig();

        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (Throwable $e) {
            Log::warning('Google OAuth callback failed', ['error' => $e->getMessage()]);

            return redirect()
                ->route('login')
                ->withErrors(['google' => 'Google sign-in failed. Please try again.']);
        }

        $email = $googleUser->getEmail();
        $googleId = $googleUser->getId();
        $name = $googleUser->getName() ?: ($googleUser->getNickname() ?: 'Google User');
        $avatar = $googleUser->getAvatar();

        if (! $email) {
            return redirect()
                ->route('login')
                ->withErrors(['google' => 'Your Google account did not return an email address.']);
        }

        if (tenant()) {
            $user = $this->resolveTenantUser($email, $googleId, $name, $avatar);
            $guard = 'tenant';
        } else {
            $user = $this->resolveCentralUser($email, $googleId, $name, $avatar);
            $guard = 'web';
        }

        Auth::guard($guard)->login($user, true);
        $request = request();
        $request->session()->regenerate();

        return redirect()->intended($this->redirectTarget());
    }

    protected function resolveCentralUser(string $email, string $googleId, string $name, ?string $avatar): User
    {
        $user = User::where('google_id', $googleId)->first()
            ?? User::where('email', $email)->first();

        if (! $user) {
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => Str::random(48),
                'google_id' => $googleId,
                'avatar' => $avatar,
            ]);
        } else {
            $updates = [];
            if (! $user->google_id) {
                $updates['google_id'] = $googleId;
            }
            if ($avatar && $user->avatar !== $avatar) {
                $updates['avatar'] = $avatar;
            }
            if ($updates) {
                $user->update($updates);
            }
        }

        return $user;
    }

    protected function resolveTenantUser(string $email, string $googleId, string $name, ?string $avatar): TenantUser
    {
        $user = TenantUser::where('google_id', $googleId)->first()
            ?? TenantUser::where('email', $email)->first();

        if (! $user) {
            // $user = TenantUser::create([
            //     'name' => $name,
            //     'email' => $email,
            //     'password' => Str::random(48),
            //     'google_id' => $googleId,
            //     'avatar' => $avatar,
            // ]);

            // $user->assignRole('student');
            // user is not found
            abort(403, 'No account found for this Google account. Please contact your administrator.');
        } else {
            $updates = [];
            if (! $user->google_id) {
                $updates['google_id'] = $googleId;
            }
            if ($avatar && $user->avatar !== $avatar) {
                $updates['avatar'] = $avatar;
            }
            if ($updates) {
                $user->update($updates);
            }
        }

        return $user;
    }

    protected function redirectTarget(): string
    {
        if (tenant()) {
            $user = Auth::guard('tenant')->user();

            if ($user && method_exists($user, 'hasRole')) {
                if ($user->hasRole('admin')) {
                    return route('tenant.dashboard');
                }
                if ($user->hasRole('instructor')) {
                    return route('tenant.dashboard');
                }
            }

            return route('tenant.dashboard');
        }

        return route('web.dashboard');
    }

    protected function googleConfigured(): bool
    {
        if (tenant()) {
            return app(OAuthService::class)->isProviderConfigured('google');
        }

        $config = config('services.google');

        return ! empty($config['client_id']) && ! empty($config['client_secret']);
    }

    protected function applyGoogleConfig(): void
    {
        if (tenant()) {
            app(OAuthService::class)->applyConfigToServices('google');
        }
    }
}
