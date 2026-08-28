<?php

namespace App\Http\Middleware;

use App\Models\Admin;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdminIsActive
{
    public function handle(Request $request, Closure $next): Response
    {
        $admin = Auth::guard('admin')->user();

        if (! Admin::hasStatusColumn()) {
            return $next($request);
        }

        if ($admin?->isPrimarySuperAdmin()) {
            if (! $admin->isActive()) {
                $admin->forceFill(['status' => 'active'])->saveQuietly();
            }

            return $next($request);
        }

        if ($admin && ! $admin->isActive()) {
            Auth::guard('admin')->logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()
                ->route('admin.login')
                ->withErrors(['email' => 'حسابك غير نشط. تواصل مع مدير النظام.']);
        }

        return $next($request);
    }
}
