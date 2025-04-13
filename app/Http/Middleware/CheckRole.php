<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = Auth::user();

        if (!$user || !in_array($user->role, $roles)) {
            if ($request->is('api/*')) {
                return response()->json(['message' => 'Access Denied'], 403);
            }
            if ($request->is('web/*')) {
                return redirect()->route('login')->with('error', 'Access Denied');
            }
            // return response()->json(['message' => 'Access Denied'], 403);
        }

        return $next($request);
    }
}
