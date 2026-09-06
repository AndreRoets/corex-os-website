<?php

namespace App\Http\Controllers;

use App\Models\SiteSetting;
use Illuminate\Http\Response;

class RobotsController extends Controller
{
    public function __invoke(): Response
    {
        return response(SiteSetting::current()->defaultRobotsTxt(), 200, ['Content-Type' => 'text/plain']);
    }
}
