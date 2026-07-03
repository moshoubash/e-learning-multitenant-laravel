<?php

namespace App\Console\Commands;

use App\Models\Tenant;
use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate {--protocol=https}';

    protected $description = 'Generate the XML sitemap covering central and all tenant landing pages.';

    public function handle(): void
    {
        $protocol = $this->option('protocol');
        $sitemap = Sitemap::create();

        // Central app pages
        $sitemap->add(
            Url::create('/')
                ->setPriority(1.0)
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
        );

        // Tenant landing pages
        $centralDomains = config('tenancy.central_domains', []);
        Tenant::query()
            ->where('is_active', true)
            ->with('domains')
            ->each(function (Tenant $tenant) use ($sitemap, $protocol, $centralDomains) {
                foreach ($tenant->domains as $domain) {
                    // Skip domains matching central app domains
                    if (in_array($domain->domain, $centralDomains, true)) {
                        continue;
                    }

                    $url = "{$protocol}://{$domain->domain}/";

                    $sitemap->add(
                        Url::create($url)
                            ->setPriority(0.8)
                            ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                    );
                }
            });

        $path = public_path('sitemap.xml');
        $sitemap->writeToFile($path);

        $this->info("Sitemap saved to {$path}");
    }
}
