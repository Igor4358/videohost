<?php

namespace App\Providers;

use App\Repositories\Eloquent\UserRepository;
use App\Repositories\Eloquent\VideoRepository;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\Interfaces\VideoRepositoryInterface;
use App\Services\Contracts\VideoValidationInterface;
use App\Services\RutubeValidationService;
use App\Services\UserService;
use App\Services\VideoService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Репозитории
        $this->app->bind(VideoRepositoryInterface::class, VideoRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);

        // Сервисы валидации
        $this->app->bind(VideoValidationInterface::class, RutubeValidationService::class);

        // Сервисы бизнес-логики
        $this->app->singleton(VideoService::class, function ($app) {
            return new VideoService(
                $app->make(VideoRepositoryInterface::class),
                $app->make(VideoValidationInterface::class)
            );
        });

        $this->app->singleton(UserService::class, function ($app) {
            return new UserService(
                $app->make(UserRepositoryInterface::class)
            );
        });
    }

    public function boot(): void
    {
        //
    }
}
