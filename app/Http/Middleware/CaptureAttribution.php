<?php

namespace App\Http\Middleware;

use App\Support\Attribution;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Remembers, per session, the first page a visitor arrived on and what sent
 * them there — see App\Support\Attribution for why.
 *
 * Page views only. Form posts, the admin console and the machine-readable
 * endpoints (sitemap, robots, uploaded media) say nothing about how a human
 * found the site, and letting a crawler's robots.txt fetch count as a
 * "landing page" would poison the numbers.
 */
class CaptureAttribution
{
    private const SKIP = ['admin', 'admin/*', 'login', 'logout', 'up', 'sitemap.xml', 'robots.txt', 'media/*'];

    public function handle(Request $request, Closure $next): Response
    {
        if ($request->isMethod('GET') && ! $request->is(...self::SKIP) && ! $request->expectsJson() && ! $request->header('X-Inertia')) {
            Attribution::capture($request);
        }

        return $next($request);
    }
}
