<?php

namespace App\Providers;

use App\Interfaces\PerfilInterface;
use App\Interfaces\UsuarioInterface;
use App\Repositories\PerfilRepository;
use App\Repositories\UsuarioRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(UsuarioInterface::class, UsuarioRepository::class);
        $this->app->bind(PerfilInterface::class, PerfilRepository::class);
    }
}
