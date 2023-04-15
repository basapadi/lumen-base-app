<?php

namespace Btx\QueryFilter;

use Illuminate\Support\ServiceProvider;

class BtxQueryFilterServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/config/btx.php' => config_path('btx.php'),
        ], 'config');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom( __DIR__.'/config/btx.php', 'btx');
    }
}
