<?php

namespace vilija19\web3login;

use Illuminate\Support\ServiceProvider;

class Web3ServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}