<?php

namespace App\Providers;

use App\Models\GeneralSetting;
use App\Services\AdminNotificationService;
use Illuminate\Support\Facades\View;
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
        View::composer('dashboard.*', function ($view) {
            $view->with([
                'platformName' => GeneralSetting::platformName(),
                'platformLogoUrl' => GeneralSetting::platformLogoUrl(),
            ]);
        });

        View::composer('dashboard.partials.header', function ($view) {
            if (auth('admin')->check()) {
                $view->with('unreadNotificationsCount', app(AdminNotificationService::class)->unreadCount());
            }
        });
    }
}
