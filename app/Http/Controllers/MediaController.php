<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Serves files uploaded through the admin's SEO media picker (og:image,
 * twitter:image, marketing artwork) back out on a public, unauthenticated
 * route — social-media crawlers fetch these to build a link preview and
 * carry no session of their own.
 */
class MediaController extends Controller
{
    public function show(string $filename): StreamedResponse|Response
    {
        $path = 'seo/'.$filename;

        if (! Storage::disk('public')->exists($path)) {
            abort(404);
        }

        return Storage::disk('public')->response($path);
    }
}
