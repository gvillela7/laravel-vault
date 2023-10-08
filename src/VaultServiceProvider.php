<?php

namespace Gvillela7\Vault;

use Illuminate\Support\ServiceProvider;

class VaultServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->offerPublishing();
        $this->registerCommands();
    }
    
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/vault.php',
            'vault'
        );
    }
    
    protected function registerCommands()
    {
        $this->commands([
            Commands\Unseal::class,
        ]);
    }
    protected function offerPublishing()
    {
        if (! function_exists('config_path')) {
            // function not available and 'publish' not relevant in Lumen
            return;
        }

        $this->publishes([
            __DIR__.'/../config/vault.php' => config_path('vault.php'),
        ], 'vault-config');
    }
}
