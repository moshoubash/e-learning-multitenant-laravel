<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Tenant;
use Illuminate\Console\Command;
use Stancl\Tenancy\UUIDGenerator;

class CreateTenant extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'tenant:create
                            {name : The name of the tenant (e.g. "School A")}
                            {subdomain : The subdomain for the tenant (e.g. "schoola")}';

    /**
     * The console command description.
     */
    protected $description = 'Create a new tenant with a subdomain. This will create the tenant record, its database, and run migrations.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $name = $this->argument('name');
        $subdomain = strtolower($this->argument('subdomain'));

        // Validate subdomain format
        if (!preg_match('/^[a-z0-9]([a-z0-9-]*[a-z0-9])?$/', $subdomain)) {
            $this->error("Invalid subdomain: '{$subdomain}'. Use only lowercase letters, numbers, and hyphens.");
            return self::FAILURE;
        }

        // Check if subdomain already exists
        $existing = \Stancl\Tenancy\Database\Models\Domain::where('domain', $subdomain)->first();
        if ($existing) {
            $this->error("Subdomain '{$subdomain}' is already taken.");
            return self::FAILURE;
        }

        $this->info("Creating tenant '{$name}' with subdomain '{$subdomain}'...");

        try {
            // Create the tenant — this triggers:
            // 1. CreateDatabase job (creates tenant_<uuid> database)
            // 2. MigrateDatabase job (runs migrations from database/migrations/tenant/)
            $tenant = Tenant::create([
                'id' => \Str::uuid()->toString(),
                'name' => $name,
            ]);

            // Create the subdomain record
            $tenant->domains()->create([
                'domain' => $subdomain,
            ]);

            $this->newLine();
            $this->info('🎉 Tenant created successfully!');
            $this->newLine();

            $this->table(
                ['Property', 'Value'],
                [
                    ['Tenant ID', $tenant->id],
                    ['Name', $tenant->name],
                    ['Subdomain', $subdomain],
                    ['Full URL', "http://{$subdomain}.elearning.test"],
                    ['Database', "tenant_{$tenant->id}"],
                ]
            );

            $this->newLine();
            $this->info("Visit http://{$subdomain}.elearning.test to access the tenant.");

        } catch (\Exception $e) {
            $this->error('Failed to create tenant: ' . $e->getMessage());
            return self::FAILURE;
        }

        return self::SUCCESS;
    }
}
