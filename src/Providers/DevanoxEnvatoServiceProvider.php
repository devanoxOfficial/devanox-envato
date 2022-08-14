<?php

namespace Devanox\Envato\Providers;

use Illuminate\Support\ServiceProvider;
use Devanox\Envato\Commands\MigrateCheckCommand;

class DevanoxEnvatoServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->commands([
            MigrateCheckCommand::class
        ]);
    }

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
