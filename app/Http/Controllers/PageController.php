<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class PageController extends Controller
{
    public function home(): View
    {
        return view('landing');
    }

    public function pricing(): View
    {
        return view('pricing');
    }
}
