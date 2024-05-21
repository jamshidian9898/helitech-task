<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class DomainServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // load domain's migrations
        $this->loadDomainsMigrations();
    }

    public function loadDomainsMigrations()
    {
        if (!file_exists(base_path('app/Domain')))
            return;

        $domains = array_diff(scandir(base_path('app/Domain')), array('.', '..'));

        foreach ($domains as $domain) {
            $dirPath = base_path('app/Domain/') . $domain;

            if (!is_dir($dirPath))
                continue;

            $dirs = array_diff(scandir(base_path('app/Domain/' . $domain)), array('.', '..'));
            $routesDirExists = in_array('Migrations', $dirs);
            if (!$routesDirExists)
                continue;

            $this->loadMigrationsFrom('app/Domain/' . $domain . '/Migrations/');
        }
    }
}
