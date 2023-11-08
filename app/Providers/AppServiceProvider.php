<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Microservice\Application\Services\Delete\DeleteLeadApplication;
use Microservice\Infrastructure\EloquentLeadRepository;
use Microservice\Infrastructure\ApiLeadScoringRepository;
use Microservice\Infrastructure\EloquentClientRepository;
use Microservice\Application\Services\Get\GetLeadInterface;
use Microservice\Application\Services\Put\PutLeadInterface;
use Microservice\Application\Services\Get\GetLeadApplication;
use Microservice\Application\Services\Post\PostLeadInterface;
use Microservice\Application\Services\Put\PutLeadApplication;
use Microservice\Domain\Repositories\LeadRepositoryInterface;
use Microservice\Application\Services\Post\PostLeadApplication;
use Microservice\Domain\Repositories\ClientRepositoryInterface;
use Microservice\Application\Services\Delete\DeleteLeadInterface;
use Microservice\Domain\Repositories\LeadScoringRepositoryInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->app->bind(GetLeadInterface::class, GetLeadApplication::class);
        $this->app->bind(LeadRepositoryInterface::class, EloquentLeadRepository::class);
        $this->app->bind(PostLeadInterface::class, PostLeadApplication::class);
        $this->app->bind(LeadScoringRepositoryInterface::class, ApiLeadScoringRepository::class);
        $this->app->bind(ClientRepositoryInterface::class, EloquentClientRepository::class);
        $this->app->bind(PutLeadInterface::class, PutLeadApplication::class);
        $this->app->bind(DeleteLeadInterface::class, DeleteLeadApplication::class);
    }
}
