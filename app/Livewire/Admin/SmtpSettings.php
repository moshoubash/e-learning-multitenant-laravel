<?php

namespace App\Livewire\Admin;

use App\Models\Tenant\SmtpSetting;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Masmerise\Toaster\Toaster;

#[Layout('layouts.admin')]
class SmtpSettings extends Component
{
    public $mailMailer = 'smtp';
    public $mailHost = '';
    public $mailPort = 587;
    public $mailUsername = '';
    public $mailPassword = '';
    public $mailEncryption = 'tls';
    public $mailFromAddress = '';
    public $mailFromName = '';
    public $isActive = true;

    public function mount()
    {
        $setting = SmtpSetting::where('is_active', true)->first();

        if ($setting) {
            $this->mailMailer = $setting->mail_mailer ?? 'smtp';
            $this->mailHost = $setting->mail_host ?? '';
            $this->mailPort = $setting->mail_port ?? 587;
            $this->mailUsername = $setting->mail_username ?? '';
            $this->mailPassword = '';
            $this->mailEncryption = $setting->mail_encryption ?? 'tls';
            $this->mailFromAddress = $setting->mail_from_address ?? '';
            $this->mailFromName = $setting->mail_from_name ?? '';
            $this->isActive = $setting->is_active;
        }
    }

    public function save()
    {
        $this->validate([
            'mailMailer' => 'required|string|max:255',
            'mailHost' => 'nullable|string|max:255',
            'mailPort' => 'nullable|integer|min:1|max:65535',
            'mailUsername' => 'nullable|string|max:255',
            'mailPassword' => 'nullable|string|max:255',
            'mailEncryption' => 'nullable|string|max:255',
            'mailFromAddress' => 'nullable|email|max:255',
            'mailFromName' => 'nullable|string|max:255',
            'isActive' => 'boolean',
        ]);

        $data = [
            'mail_mailer' => $this->mailMailer,
            'mail_host' => $this->mailHost ?: null,
            'mail_port' => $this->mailPort ?: null,
            'mail_username' => $this->mailUsername ?: null,
            'mail_encryption' => $this->mailEncryption ?: null,
            'mail_from_address' => $this->mailFromAddress ?: null,
            'mail_from_name' => $this->mailFromName ?: null,
            'is_active' => $this->isActive,
        ];

        if ($this->mailPassword) {
            $data['mail_password'] = $this->mailPassword;
        }

        $setting = SmtpSetting::where('is_active', true)->first();

        if ($setting) {
            $setting->update($data);
        } else {
            SmtpSetting::create($data);
        }

        $this->dispatch('smtp-settings-saved');
        Toaster::success(__('messages.SMTP settings saved successfully!'));
    }

    public function testConnection()
    {
        try {
            $this->save();

            \Illuminate\Support\Facades\Mail::raw('Test email from GRID LMS', function ($message) {
                $message->to(auth()->user()->email)
                    ->subject('SMTP Test - GRID LMS');
            });

            Toaster::success(__('messages.Test email sent to :email!', ['email' => auth()->user()->email]));
        } catch (\Throwable $e) {
            Toaster::error(__('messages.Connection failed: :error', ['error' => $e->getMessage()]));
        }
    }

    public function render()
    {
        return view('livewire.admin.smtp-settings');
    }
}
