<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        // Not logged in
        if (!$user) {
            return redirect()->route('login');
        }

        // Deactivated account
        if (!$user->is_active) {
            auth()->logout();
            return redirect()->route('login')
                ->withErrors(['email' => 'Your account has been deactivated.']);
        }

        // Role check
        if (!in_array($user->role, ['super_admin', 'admin', 'moderator'], true)) {
            abort(403, 'Unauthorized.');
        }

        // Moderator restrictions
        if ($user->role === 'moderator') {
            $restricted = ['admin/bkash-settings', 'admin/users', 'admin/settings'];

            foreach ($restricted as $prefix) {
                if (str_starts_with($request->path(), $prefix)) {
                    abort(403, 'Moderators cannot access this section.');
                }
            }

            if (preg_match('#^admin/registrations/\d+/(verify-payment|reject-payment|approve|reject)$#', $request->path())) {
                abort(403, 'Moderators cannot approve or verify.');
            }
        }

        // Optional: IP whitelist for production
        if (app()->environment('production')) {
            $allowed = array_filter(explode(',', (string) config('services.admin.allowed_ips', '')));
            if (!empty($allowed) && !in_array($request->ip(), $allowed, true)) {
                abort(403, 'Admin panel is restricted from your location.');
            }
        }

        return $next($request);
    }
}