<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePanelLoggedIn
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!session('panel_auth')) {
            return redirect()->route('login');
        }

        return $next($request);
    }
}

