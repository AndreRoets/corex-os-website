<?php

namespace App\Support;

use Illuminate\Http\Request;

/**
 * Absolute URLs on the one public host — config('app.url') — whatever host the
 * request actually arrived on.
 *
 * The site answers on more than one name (www., the bare IP and port), and
 * url() would echo whichever was asked for. Every address we hand to a search
 * engine — canonical, og:url, sitemap, robots.txt, structured data — goes
 * through here instead, so all of them name the same site.
 */
class CanonicalUrl
{
    /**
     * The configured root, never with a trailing slash.
     */
    public static function root(): string
    {
        return rtrim((string) config('app.url'), '/');
    }

    /**
     * The host (and port, if one is configured) of that root, lower-cased.
     */
    public static function host(): string
    {
        $parts = parse_url(self::root());
        $host = strtolower($parts['host'] ?? '');

        return isset($parts['port']) ? $host.':'.$parts['port'] : $host;
    }

    /**
     * An absolute URL for a site path. The home page is the bare root.
     */
    public static function to(string $path): string
    {
        $path = trim($path, '/');

        return $path === '' ? self::root() : self::root().'/'.$path;
    }

    /**
     * The canonical address of the current request: its path on the configured
     * host, without a query string, a trailing slash or a front-controller
     * prefix.
     */
    public static function forRequest(Request $request): string
    {
        return self::to(self::cleanPath($request->getPathInfo()));
    }

    /**
     * A path with any leading "/index.php" removed. Only the whole segment —
     * "/index.phpx" is somebody else's path, not the front controller.
     */
    public static function cleanPath(string $path): string
    {
        if ($path === '/index.php' || str_starts_with($path, '/index.php/')) {
            $path = substr($path, strlen('/index.php'));
        }

        return '/'.trim($path, '/');
    }
}
