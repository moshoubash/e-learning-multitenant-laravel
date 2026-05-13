<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;

class Tenant extends BaseTenant implements TenantWithDatabase
{
    use HasDatabase, HasDomains, HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'name',
        'data',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'data' => 'array',
    ];

    /**
     * Get the domains associated with the tenant.
     */
    public function domains(): HasMany
    {
        return $this->hasMany(\Stancl\Tenancy\Database\Models\Domain::class);
    }

    /**
     * Get the primary domain for the tenant.
     */
    public function getPrimaryDomain(): ?\Stancl\Tenancy\Database\Models\Domain
    {
        return $this->domains()->first();
    }

    /**
     * Check if the tenant is active.
     */
    public function isActive(): bool
    {
        return $this->data['status'] ?? true;
    }

    /**
     * Activate the tenant.
     */
    public function activate(): void
    {
        $data = $this->data ?? [];
        $data['status'] = true;
        $this->update(['data' => $data]);
    }

    /**
     * Deactivate the tenant.
     */
    public function deactivate(): void
    {
        $data = $this->data ?? [];
        $data['status'] = false;
        $this->update(['data' => $data]);
    }
}
