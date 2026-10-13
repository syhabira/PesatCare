<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureRole
{
    public function handle(Request $request, Closure $next, string ...$roles)
    {
        $user = $request->user();

        if (! $user || ! $user->isActive()) {
            Auth::logout();
            $request->session()->invalidate();
            return redirect()->route('login');
        }

        abort_unless(in_array($user->role, $roles, true), 403);

        return $next($request);
    }
}