<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\Contracts\RemoteMangaServiceInterface;
use App\Services\RemoteMangaService;

class RemoteMangaServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(RemoteMangaServiceInterface::class, RemoteMangaService::class);
    }

    public function boot()
    {
        
    }
}