<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class PermissionMiddleware
{
    public function handle(Request $request, Closure $next, string $permissions): Response
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        $user = $request->user();

        if (! $user->role) {
            Auth::logout();

            return redirect()->route('login')->with('error', 'Unauthorized access.');
        }

        $permissionsArray = explode(',', $permissions);
        $permissionsArray = array_map('trim', $permissionsArray);

        foreach ($permissionsArray as $permission) {
            if (! $user->role->hasPermission($permission)) {
                abort(403, 'Unauthorized. You do not have the required permission: '.$permission);
            }
        }

        return $next($request);
    }
}
