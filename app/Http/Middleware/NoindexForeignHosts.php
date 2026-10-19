<?php

namespace App\Http\Middleware;

use App\Support\CanonicalUrl;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Tells search engines to stay off every host but the configured one.
 *
 * The same site also answers on www.corexweb.co.za and on the server's bare IP
 * and port. nginx should redirect those (see the deploy notes), but until it
 * does — or if it ever stops — a crawler that finds one would index a second
 * copy of the site. This header stops that whatever nginx is doing, and the
 * canonical tags already point at the right host either way.
 *
 * Production only by default, because local and staging hosts never match
 * APP_URL and a header there would be noise. config('seo.noindex_foreign_hosts.production_only')
 * turns that off so the behaviour can be tested.
 */
class NoindexForeignHosts
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (config('seo.noindex_foreign_hosts.production_only', true) && ! app()->isProduction()) {
            return $response;
        }

        if (strtolower($request->getHttpHost()) !== CanonicalUrl::host()) {
            $response->headers->set('X-Robots-Tag', 'noindex, nofollow');
        }

        return $response;
    }
}
