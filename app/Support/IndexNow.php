<?php

namespace App\Support;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Submits changed URLs to IndexNow (https://www.indexnow.org). One POST
 * reaches every participating engine — Bing, Yandex, Seznam, Naver — and
 * Bing's index is what ChatGPT search and Copilot read from.
 *
 * Best-effort by design: a failed submission is logged and forgotten. The
 * sitemap still lists every page, so the worst case is a slower recrawl.
 */
class IndexNow
{
    public static function key(): ?string
    {
        $key = config('seo.indexnow.key');

        return is_string($key) && preg_match('/^[A-Za-z0-9-]{8,128}$/', $key) ? $key : null;
    }

    /**
     * @param  list<string>  $urls  absolute URLs on the canonical host
     * @return bool whether IndexNow accepted the batch
     */
    public static function submit(array $urls): bool
    {
        $key = self::key();

        // IndexNow rejects the whole batch if any URL is on another host.
        $urls = array_values(array_filter($urls, fn (string $url) => parse_url($url, PHP_URL_HOST) === CanonicalUrl::host()));

        if ($key === null || $urls === []) {
            return false;
        }

        try {
            $response = Http::timeout(10)->acceptJson()->post(config('seo.indexnow.endpoint'), [
                'host' => CanonicalUrl::host(),
                'key' => $key,
                'keyLocation' => CanonicalUrl::to('/'.$key.'.txt'),
                'urlList' => $urls,
            ]);
        } catch (Throwable $e) {
            Log::warning('IndexNow submission failed', ['error' => $e->getMessage()]);

            return false;
        }

        // 200 and 202 both mean accepted; 202 is "key not yet verified".
        if (! $response->successful()) {
            Log::warning('IndexNow submission rejected', ['status' => $response->status(), 'urls' => count($urls)]);
        }

        return $response->successful();
    }
}
