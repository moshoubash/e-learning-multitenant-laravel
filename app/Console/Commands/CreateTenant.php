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
                            {slug : The slug for the tenant (e.g. "school-a")}
                            {--plan=free : The plan for the tenant (free, pro, enterprise)}
                            {--domain= : The custom domain (e.g. schoola.com)}';

    /**
     * The console command description.
     */
    protected $description = 'Create a new tenant with its own database and domain.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $name = $this->argument('name');
        $slug = strtolower($this->argument('slug'));
        $plan = $this->option('plan');
        $customDomain = $this->option('domain');

        // Validate slug format
        if (!preg_match('/^[a-z0-9]([a-z0-9-]*[a-z0-9])?$/', $slug)) {
            $this->error("Invalid slug: '{$slug}'. Use only lowercase letters, numbers, and hyphens.");
            return self::FAILURE;
        }

        // Validate plan
        if (!in_array($plan, ['free', 'pro', 'enterprise'])) {
            $this->error("Invalid plan: '{$plan}'. Use: free, pro, or enterprise.");
            return self::FAILURE;
        }

        // Check if slug already exists
        if (Tenant::where('slug', $slug)->exists()) {
            $this->error("Slug '{$slug}' is already taken.");
            return self::FAILURE;
        }

        $this->info("Creating tenant '{$name}' with slug '{$slug}'...");

        try {
            $tenant = Tenant::create([
                'name' => $name,
                'slug' => $slug,
                'plan' => $plan,
                'domain' => $customDomain,
            ]);


            // Create the primary domain record (for subdomain identification)
            $domain = $slug . '.' . config('tenancy.central_domains')[2];
            $tenant->domains()->create([
                'domain' => $domain,
            ]);

            $this->newLine();
            $this->info('🎉 Tenant created successfully!');
            $this->newLine();

            $this->table(
                ['Property', 'Value'],
                [
                    ['Tenant ID', $tenant->id],
                    ['Name', $tenant->name],
                    ['Slug', $slug],
                    ['Plan', $plan],
                    ['Domain', $domain],
                    ['Custom Domain', $customDomain ?? 'N/A'],
                    ['URL', "http://{$domain}"],
                ]
            );

            $this->newLine();
            $this->info("Visit http://{$domain} to access the tenant.");

        } catch (\Exception $e) {
            $this->error('Failed to create tenant: ' . $e->getMessage());
            return self::FAILURE;
        }

        return self::SUCCESS;
    }
}
