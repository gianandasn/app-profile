<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetApiLocale
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->query('lang')
            ?? $request->query('locale')
            ?? $request->header('Accept-Language');

        if ($locale) {
            // Extract primary 2-letter code e.g. "en-US,en;q=0.9" -> "en"
            $primary = strtolower(substr(trim(explode(',', (string) $locale)[0]), 0, 2));

            if (in_array($primary, ['id', 'en'], true)) {
                app()->setLocale($primary);
            }
        }

        return $next($request);
    }
}

