<?php

namespace App\Providers;

use Hyn\Tenancy\Environment;
use TCG\Voyager\Facades\Voyager;
use App\Actions\TenantViewAction;
use App\Actions\TenantLoginAction;
use App\Actions\TenantDeleteAction;
use TCG\Voyager\Actions\ViewAction;
use TCG\Voyager\Actions\DeleteAction;
use TCG\Voyager\Models\User as VoyagerUser;
use App\User;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $env = app(Environment::class);
        $isSystem = true;

        if ($hostname = optional($env->hostname())->fqdn) {
            \Log::info('Detected hostname: ' . $hostname);
            if (\App\Tenant::getRootFqdn() !== $hostname) {
                config(['database.default' => 'tenant']);
                config(['voyager.storage.disk' => 'tenant']);
                $isSystem = false;
            }
        } else {
            \Log::warning('No hostname detected.');
        }

        if ($isSystem) {
            Voyager::addAction(TenantLoginAction::class);
            Voyager::replaceAction(ViewAction::class, TenantViewAction::class);
            Voyager::replaceAction(DeleteAction::class, TenantDeleteAction::class);
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(VoyagerUser::class, User::class);
    }
}


