<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check() || !auth()->user()->is_active) {
            abort(403, 'Unauthorized.');
        }

        $role = auth()->user()->role;

        // Moderators: read-only on registrations, no bKash/users/settings
        if ($role === 'moderator') {
            $restricted = [
                'admin/bkash-settings',
                'admin/users',
                'admin/settings',
            ];

            foreach ($restricted as $prefix) {
                if (str_starts_with($request->path(), $prefix)) {
                    abort(403, 'Moderators cannot access this section.');
                }
            }

            // Block write actions on registrations
            if (preg_match('#^admin/registrations/\d+/(verify-payment|reject-payment|approve|reject)$#', $request->path())) {
                abort(403, 'Moderators cannot approve or verify.');
            }
        }

        return $next($request);
    }
}