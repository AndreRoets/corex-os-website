<?php

namespace App\Models;

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
        'google_ads_conversion_id',
        'google_ads_conversion_label',
        'head_scripts',
        'body_scripts',
        'robots_txt',
    ];

    public static function current(): self
    {
        /** @var self $settings */
        $settings = static::query()->firstOrCreate([]);

        return $settings;
    }

    public function defaultRobotsTxt(): string
    {
        return $this->robots_txt !== null && $this->robots_txt !== ''
            ? $this->robots_txt
            : "User-agent: *\nDisallow: /admin\n\nSitemap: ".url('/sitemap.xml')."\n";
    }
}
