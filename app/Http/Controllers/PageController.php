<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Support\Sast;
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
