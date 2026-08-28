<?php

namespace App\Providers;

use App\Models\GeneralSetting;
use App\Services\AdminNotificationService;
use App\Support\DashboardBackUrl;
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
            $route = request()->route();

            $view->with([
                'platformName' => GeneralSetting::platformName(),
                'platformLogoUrl' => GeneralSetting::platformLogoUrl(),
                'pageBackUrl' => DashboardBackUrl::resolve(
                    $route?->getName(),
                    $route?->parameters() ?? [],
                ),
            ]);
        });

        View::composer('dashboard.partials.header', function ($view) {
            if (auth('admin')->check()) {
                $notificationService = app(AdminNotificationService::class);
                $view->with('unreadNotificationsCount', $notificationService->unreadCount());
            }
        });

        View::composer('dashboard.partials.sidebar', function ($view) {
            if (auth('admin')->check()) {
                $view->with('sidebarBadges', app(AdminNotificationService::class)->sidebarBadgeCounts());
            }
        });
    }
}
