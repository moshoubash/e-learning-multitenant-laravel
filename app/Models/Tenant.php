<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Contracts\Tenant as TenantContract;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;
use Stancl\Tenancy\Database\Concerns\CentralConnection;
use Stancl\Tenancy\Database\Concerns\HasDataColumn;

class Tenant extends Model implements TenantContract, TenantWithDatabase
{
    use HasFactory, HasDatabase, HasDomains, CentralConnection, HasDataColumn;

    protected $guarded = [];

    protected $dispatchesEvents = [
        'created' => \Stancl\Tenancy\Events\TenantCreated::class,
        'updated' => \Stancl\Tenancy\Events\TenantUpdated::class,
        'deleted' => \Stancl\Tenancy\Events\TenantDeleted::class,
        'saved' => \Stancl\Tenancy\Events\TenantSaved::class,
    ];

    /**
     * Use 'settings' column instead of the default 'data' column.
     */
    public static function getDataColumn(): string
    {
        return 'settings';
    }

    /**
     * The custom columns that are actual DB columns.
     */
    public static function getCustomColumns(): array
    {
        return [
            'id',
            'name',
            'slug',
            'domain',
            'plan',
            'max_users',
            'is_active',
            'settings',
        ];
    }

    /**
     * Get the name of the key used for identifying the tenant.
     */
    public function getTenantKeyName(): string
    {
        return 'id';
    }

    /**
     * Get the value of the key used for identifying the tenant.
     */
    public function getTenantKey()
    {
        return $this->getAttribute('id');
    }

    /**
     * Run a callback in this tenant's context.
     */
    public function run(callable $callable)
    {
        return tenancy()->run($this, $callable);
    }

    /**
     * Get an internal key.
     */
    public function getInternal(string $key)
    {
        return $this->getAttribute($key);
    }

    /**
     * Set an internal key.
     */
    public function setInternal(string $key, $value)
    {
        $this->setAttribute($key, $value);
        return $this;
    }

    /**
     * Get the prefix for internal keys.
     */
    public static function internalPrefix(): string
    {
        return 'tenancy_';
    }
}
