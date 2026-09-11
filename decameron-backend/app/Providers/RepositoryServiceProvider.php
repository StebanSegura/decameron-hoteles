<?php

namespace App\Providers;

use App\Repositories\CatalogoRepositoryInterface;
use App\Repositories\ConfiguracionRepositoryInterface;
use App\Repositories\EloquentCatalogoRepository;
use App\Repositories\EloquentConfiguracionRepository;
use App\Repositories\EloquentHotelRepository;
use App\Repositories\HotelRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(HotelRepositoryInterface::class, EloquentHotelRepository::class);
        $this->app->bind(ConfiguracionRepositoryInterface::class, EloquentConfiguracionRepository::class);
        $this->app->bind(CatalogoRepositoryInterface::class, EloquentCatalogoRepository::class);
    }
}
