<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role  Die erforderliche Rolle
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $role)
    {
        if (!Auth::check() || Auth::user()->role !== $role) {
            // Wenn der Benutzer nicht angemeldet ist oder nicht die erforderliche Rolle hat
            return response()->json(['message' => 'Unautorisiert'], 403);
        }

        return $next($request);
    }
}
