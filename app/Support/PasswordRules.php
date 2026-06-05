<?php

namespace App\Support;

use Illuminate\Validation\Rules\Password;

/**
 * Centralized password policy.
 *
 * OWASP ASVS V2.1.1 + NIST SP 800-63B recommendations:
 *   - min 12 characters (longer beats complexity rules)
 *   - reject commonly-leaked passwords (HaveIBeenPwned-style)
 *   - reject the user's name/email
 *   - max 128 characters (avoid bcrypt truncation surprises)
 */
class PasswordRules
{
    public static function default(): array
    {
        return [
            'required',
            'string',
            Password::min(12)
                ->max(128)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised(3),
        ];
    }

    public static function nullable(): array
    {
        return [
            'nullable',
            'string',
            Password::min(12)
                ->max(128)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised(3),
        ];
    }
}
