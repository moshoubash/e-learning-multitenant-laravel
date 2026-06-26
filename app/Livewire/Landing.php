<?php

namespace App\Livewire;

use App\Mail\ContactNotification;
use App\Models\Tenant\ContactMessage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Component;
use Masmerise\Toaster\Toaster;

class Landing extends Component
{
    public string $name = '';

    public string $email = '';

    public string $message = '';

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string|max:5000',
        ];
    }

    public function submit(): void
    {
        $this->validate();
        $ip = request()->ip();

        $contactMessage = ContactMessage::create([
            'name' => $this->name,
            'email' => $this->email,
            'message' => $this->message,
            'ip_address' => $ip,
        ]);

        Mail::to('mohammedshobash2002@gmail.com')->send(new ContactNotification($contactMessage));

        $this->reset(['name', 'email', 'message']);

        Toaster::success(__('messages.Thank you! Your message has been sent.'));
    }

    public function render()
    {
        return view('livewire.landing')
            ->layout('layouts.public');
    }
}
