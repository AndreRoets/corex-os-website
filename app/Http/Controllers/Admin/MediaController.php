<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class MediaController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'file' => ['required', 'image', 'max:5120'],
        ]);

        /** @var UploadedFile $file */
        $file = $request->file('file');

        // Slugified and randomised: readable in a listing, but never
        // guessable, and never trusting the original filename a browser sent.
        $filename = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME))
            .'-'.Str::random(8)
            .'.'.$file->getClientOriginalExtension();

        $file->storeAs('seo', $filename, 'public');

        return response()->json([
            'url' => route('media.uploaded', $filename),
            'name' => $filename,
        ]);
    }
}
