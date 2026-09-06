<?php

namespace App\Http\Controllers;

use App\Support\SitemapBuilder;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function __invoke(SitemapBuilder $builder): Response
    {
        $xml = view('sitemap', ['urls' => $builder->urls()])->render();

        return response($xml, 200, ['Content-Type' => 'application/xml']);
    }
}
