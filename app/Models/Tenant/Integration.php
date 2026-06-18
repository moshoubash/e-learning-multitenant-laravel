<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

class Integration extends Model
{
    protected $fillable = [
        'provider',
        'client_id',
        'client_secret',
        'redirect_url',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public static function isProviderConfigured(string $provider): bool
    {
        return static::where('provider', $provider)
            ->where('is_active', true)
            ->whereNotNull('client_id')
            ->where('client_id', '!=', '')
            ->whereNotNull('client_secret')
            ->where('client_secret', '!=', '')
            ->exists();
    }

    public static function getProviderConfig(string $provider): ?self
    {
        return static::where('provider', $provider)->first();
    }
}
