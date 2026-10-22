<?php

namespace App\Models;

use App\Support\CanonicalUrl;
use Illuminate\Database\Eloquent\Model;

/**
 * A singleton row. Analytics tags and robots.txt content are read from it
 * site-wide (see resources/views/components/layouts/app.blade.php and
 * resources/views/app.blade.php), so nothing here is hardcoded in a view.
 */
class SiteSetting extends Model
{
    protected $fillable = [
        'site_name',
        'default_meta_description',
        'default_og_image',
        'default_twitter_handle',
        'ga4_measurement_id',
        'gtm_container_id',
        'google_search_console_verification',
        'bing_site_verification',
        'google_ads_conversion_id',
        'google_ads_conversion_label',
        'head_scripts',
        'body_scripts',
        'robots_txt',
        'same_as',
    ];

    /**
     * The company's profiles elsewhere, for the Organization's sameAs. One URL
     * per line in the admin field; blank lines and anything that is not an
     * http(s) URL are dropped rather than printed into the structured data.
     *
     * @return list<string>
     */
    public function sameAsUrls(): array
    {
        return collect(preg_split('/\R/', (string) $this->same_as))
            ->map(fn (string $line) => trim($line))
            ->filter(fn (string $url) => filter_var($url, FILTER_VALIDATE_URL) && preg_match('#^https?://#i', $url))
            ->unique()
            ->values()
            ->all();
    }

    public static function current(): self
    {
        /** @var self $settings */
        $settings = static::query()->firstOrCreate([]);

        return $settings;
    }

    /**
     * An admin's own robots.txt verbatim, or the generated one. The sitemap
     * address is built from APP_URL, never the request, so robots.txt fetched
     * on www. or the bare IP still points crawlers at the one real host.
     */
    public function defaultRobotsTxt(): string
    {
        return $this->robots_txt !== null && $this->robots_txt !== ''
            ? $this->robots_txt
            : "User-agent: *\nDisallow: /admin\nDisallow: /login\n\nSitemap: ".CanonicalUrl::to('/sitemap.xml')."\n";
    }
}
