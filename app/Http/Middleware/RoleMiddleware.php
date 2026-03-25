<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $roles): Response
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        $user = $request->user();

        if (! $user->role) {
            Auth::logout();

            return redirect()->route('login')->with('error', 'Unauthorized access.');
        }

        $rolesArray = explode(',', $roles);
        $rolesArray = array_map('trim', $rolesArray);

        $userRoleSlug = $user->role->slug;

        if (! in_array($userRoleSlug, $rolesArray)) {
            abort(403, 'Unauthorized access. You do not have permission to access this page.');
        }

        return $next($request);
    }
}
