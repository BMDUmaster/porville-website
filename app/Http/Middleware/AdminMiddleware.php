<?php

namespace App\Http\Middleware;

use App\Support\AdminModules;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('dashboard.login');
        }

        $user = auth()->user();

        if (! $user->isStaff() || $user->status !== 'active') {
            abort(403, 'Access denied. Admin account required.');
        }

        // Sub admins only reach the modules the main admin assigned to them.
        if (! AdminModules::allowsRoute($user, $request->route()?->getName())) {
            if ($request->isMethod('GET') && ! $request->expectsJson()) {
                return redirect()->to(AdminModules::homeUrl($user))
                    ->with('error', 'You do not have access to that section. Ask the main admin for permission.');
            }

            abort(403, 'You do not have access to this section.');
        }

        return $next($request);
    }
}
