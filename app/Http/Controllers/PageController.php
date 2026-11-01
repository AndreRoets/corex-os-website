<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Support\Features\FeatureInventory;
use App\Support\Sast;
use App\Support\WebsiteShowcase;
use Carbon\CarbonImmutable;
use Illuminate\View\View;

class PageController extends Controller
{
    public function home(): View
    {
        return view('landing', ['page' => Page::where('key', 'home')->first()]);
    }

    public function pricing(): View
    {
        return view('pricing', ['page' => Page::where('key', 'pricing')->first()]);
    }

    public function features(): View
    {
        return view('features', [
            'page' => Page::where('key', 'features')->first(),
            'categories' => FeatureInventory::categories(),
        ]);
    }

    /**
     * The rental CRM page lists the Rentals features from the same inventory
     * /features renders, so it can never claim more than the product does.
     */
    public function rentals(): View
    {
        return view('rentals', [
            'page' => Page::where('key', 'rentals')->first(),
            'features' => FeatureInventory::categories()['Rentals'] ?? [],
        ]);
    }

    public function websites(): View
    {
        return view('websites', [
            'page' => Page::where('key', 'websites')->first(),
            'liveSites' => WebsiteShowcase::liveSites(),
            'templates' => WebsiteShowcase::templates(),
        ]);
    }

    /**
     * One example template, full size. Also what each card on the websites
     * page frames, scaled down. Kept out of the index: a fictional agency.
     */
    public function websiteTemplate(string $template): View
    {
        return view('website-templates.preview', [
            'key' => $template,
            'template' => WebsiteShowcase::templates()[$template],
        ]);
    }

    public function mobileApp(): View
    {
        return view('mobile-app', ['page' => Page::where('key', 'mobile-app')->first()]);
    }

    /**
     * The take-on timeline names the sign-up month and the two after it, worked
     * out per request in SAST so the page never goes stale. Counted from the
     * first of the month, or adding a month to 31 January would skip February.
     */
    public function takeOn(): View
    {
        $thisMonth = CarbonImmutable::now(Sast::ZONE)->startOfMonth();

        return view('take-on', [
            'page' => Page::where('key', 'take-on')->first(),
            'months' => [
                $thisMonth->format('F'),
                $thisMonth->addMonth()->format('F'),
                $thisMonth->addMonths(2)->format('F'),
            ],
        ]);
    }
}
