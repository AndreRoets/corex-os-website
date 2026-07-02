<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDemoRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;

class DemoRequestController extends Controller
{
    /**
     * Handle a demo request from the marketing site.
     *
     * There is no database yet — we record the enquiry to the log channel so
     * nothing is lost, then flash a success message and return to the form.
     * Swap the Log call for a Mailable / Notification / persistence when the
     * backend is ready.
     */
    public function store(StoreDemoRequest $request): RedirectResponse
    {
        $data = $request->safe()->except('website');

        Log::channel('stack')->info('CoreX OS demo request', $data);

        return redirect()
            ->route('home')
            ->with('demo_success', "Thanks {$data['name']} — your demo request is in. We'll be in touch within one business day.")
            ->withFragment('demo');
    }
}
