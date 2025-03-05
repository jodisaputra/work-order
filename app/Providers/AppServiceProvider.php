<?php

namespace App\Providers;

use App\Http\Middleware\CheckRole;
use App\Http\Middleware\CheckWorkOrderAccess;
use App\Models\WorkOrder;
use App\Policies\WorkOrderPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

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
        // Register policies
        Gate::policy(WorkOrder::class, WorkOrderPolicy::class);
    }
}
