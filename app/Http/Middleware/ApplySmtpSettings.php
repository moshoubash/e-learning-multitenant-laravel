<?php

namespace App\Http\Middleware;

use App\Models\Tenant\SmtpSetting;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApplySmtpSettings
{
    public function handle(Request $request, Closure $next): Response
    {
        $setting = SmtpSetting::where('is_active', true)->first();

        if ($setting) {
            config()->set('mail.default', $setting->mail_mailer ?? 'smtp');
            config()->set('mail.mailers.smtp.host', $setting->mail_host);
            config()->set('mail.mailers.smtp.port', $setting->mail_port);
            config()->set('mail.mailers.smtp.username', $setting->mail_username);
            config()->set('mail.mailers.smtp.password', $setting->mail_password);
            config()->set('mail.mailers.smtp.encryption', $setting->mail_encryption);
            config()->set('mail.from.address', $setting->mail_from_address);
            config()->set('mail.from.name', $setting->mail_from_name);
        }

        return $next($request);
    }
}
