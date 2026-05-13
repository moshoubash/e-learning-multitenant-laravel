<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;

class Tenant extends BaseTenant implements TenantWithDatabase
{
    use HasDatabase, HasDomains, HasFactory;

    /**
     * The custom columns that are actual DB columns
     * (not stored in the 'data' JSON column).
     */
    public static function getCustomColumns(): array
    {
        return [
            'id',
            'name',
        ];
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

