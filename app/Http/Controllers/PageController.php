<?php

namespace App\Http\Controllers;

use App\Models\Page;
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
}
