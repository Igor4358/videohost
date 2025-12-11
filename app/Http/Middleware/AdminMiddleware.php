<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if (Auth::user()->role !== 'admin') {
            \Log::warning('Non-admin user tried to access admin area', [
                'user_id' => Auth::id(),
                'user_role' => Auth::user()->role,
                'path' => $request->path()
            ]);

            abort(403, 'Доступ запрещен. Требуются права администратора.');
        }

        return $next($request);
    }
}
