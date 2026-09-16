<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactRequest;
use App\Models\Page;
use App\Models\SiteSetting;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(): Response
    {
        $settings = SiteSetting::current();

        return Inertia::render('Admin/Dashboard', [
            'pageCount' => Page::count(),
            'userCount' => User::count(),
            'enquiryCount' => ContactRequest::count(),
            'enquiriesLast30Days' => ContactRequest::where('created_at', '>=', now()->subDays(30))->count(),
            'integrations' => [
                ['key' => 'ga4', 'label' => 'Google Analytics 4', 'configured' => filled($settings->ga4_measurement_id)],
                ['key' => 'gtm', 'label' => 'Google Tag Manager', 'configured' => filled($settings->gtm_container_id)],
                ['key' => 'search_console', 'label' => 'Google Search Console', 'configured' => filled($settings->google_search_console_verification)],
                ['key' => 'google_ads', 'label' => 'Google Ads', 'configured' => filled($settings->google_ads_conversion_id)],
            ],
        ]);
    }
}
