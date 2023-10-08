<?php

namespace Gvillela7\Vault\Commands;

use Illuminate\Console\Command;
use Gvillela7\Vault\Services\Vault;
class Unseal extends Command
{
    protected $signature = 'vault:unseal
    {keys : Key to unseal the vault}
    ';

    protected $description = 'Unseal Vault';

    public function handle()
    {
        $result = app(Vault::class)->unseal($this->argument('keys'));
        if ($result) {
            $this->info("Vault unseal");
        }
    }
}