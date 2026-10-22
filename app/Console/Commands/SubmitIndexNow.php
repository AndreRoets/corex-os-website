<?php

namespace App\Console\Commands;

use App\Support\IndexNow;
use App\Support\SitemapBuilder;
use Illuminate\Console\Command;

/**
 * Run after a deploy that changes public pages: every URL in the sitemap is
 * resubmitted in one batch. Pages saved from the admin panel submit
 * themselves, so this is only for changes that arrive in code.
 */
class SubmitIndexNow extends Command
{
    protected $signature = 'seo:indexnow {--url=* : Submit only these URLs instead of the whole sitemap}';

    protected $description = 'Tell IndexNow (Bing and others) that the site\'s pages have changed';

    public function handle(SitemapBuilder $sitemap): int
    {
        if (IndexNow::key() === null) {
            $this->error('INDEXNOW_KEY is not set (8–128 letters, digits or dashes). Nothing submitted.');

            return self::FAILURE;
        }

        $urls = $this->option('url') ?: array_column($sitemap->urls(), 'loc');

        if (! IndexNow::submit($urls)) {
            $this->error('IndexNow did not accept the submission — see the log.');

            return self::FAILURE;
        }

        $this->info('Submitted '.count($urls).' URL(s) to IndexNow.');

        return self::SUCCESS;
    }
}
