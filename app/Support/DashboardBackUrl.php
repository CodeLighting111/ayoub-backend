<?php

namespace App\Support;

use Illuminate\Support\Facades\Route;

class DashboardBackUrl
{
    public static function resolve(?string $routeName = null, array $parameters = []): ?string
    {
        $routeName ??= request()->route()?->getName();

        if ($routeName === null || $routeName === 'admin.onboarding.index') {
            return null;
        }

        if (str_ends_with($routeName, '.index')) {
            return null;
        }

        if ($routeName === 'admin.notifications.send.create') {
            return route('admin.notifications.index');
        }

        if ($routeName === 'admin.clients.edit' && isset($parameters['client'])) {
            return route('admin.clients.show', $parameters['client']);
        }

        if ($routeName === 'admin.onboarding.edit' && isset($parameters['onboarding_screen'])) {
            return route('admin.onboarding.show', $parameters['onboarding_screen']);
        }

        if (in_array($routeName, [
            'admin.about.edit',
            'admin.settings.edit',
            'admin.profile.edit',
        ], true)) {
            return null;
        }

        if (str_ends_with($routeName, '.create') || str_ends_with($routeName, '.edit') || str_ends_with($routeName, '.show')) {
            $indexRoute = preg_replace('/\.(create|edit|show)$/', '.index', $routeName);

            if ($indexRoute && Route::has($indexRoute)) {
                return route($indexRoute);
            }
        }

        return route('admin.onboarding.index');
    }
}
