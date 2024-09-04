<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Admin;
use App\Models\Owner;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();

            // Check if the user is an admin
            if ($user instanceof admin) {
                return $next($request);
            }

            // Check if the user is an owner
            if ($user instanceof Owner) {
                return $next($request);
            }
        }

        // If the user is neither admin nor owner, deny access
        return response()->json(['error' => 'Forbidden'], 403);
    }
}
