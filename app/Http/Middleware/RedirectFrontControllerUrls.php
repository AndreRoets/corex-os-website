<?php

namespace App\Http\Middleware;

use App\Support\CanonicalUrl;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * /index.php/pricing and /index.php?x render the same page as /pricing and /,
 * so each is a duplicate a crawler can find. Send them to the clean address
 * permanently, keeping the query string.
 *
 * GET and HEAD only: a 301 on a form post would drop the body.
 */
class RedirectFrontControllerUrls
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->isMethod('GET') && ! $request->isMethod('HEAD')) {
            return $next($request);
        }

        // The raw query, as sent: getQueryString() would re-sort and re-encode
        // it, and tracking links are matched on the exact string.
        [$path, $query] = array_pad(explode('?', $request->getRequestUri(), 2), 2, null);

        if ($path !== '/index.php' && ! str_starts_with($path, '/index.php/')) {
            return $next($request);
        }

        $target = CanonicalUrl::cleanPath($path).($query !== null && $query !== '' ? '?'.$query : '');

        return redirect($target, 301);
    }
}
