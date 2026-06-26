<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

class SmtpSetting extends Model
{
    protected $fillable = [
        'mail_mailer',
        'mail_host',
        'mail_port',
        'mail_username',
        'mail_password',
        'mail_encryption',
        'mail_from_address',
        'mail_from_name',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'mail_port' => 'integer',
            'is_active' => 'boolean',
        ];
    }
}
