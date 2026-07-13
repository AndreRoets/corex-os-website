<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDemoRequest;
use App\Mail\DemoRequested;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class DemoRequestController extends Controller
{
    /**
     * Handle a demo request from the marketing site.
     *
     * There is no database yet, so the enquiry is logged (nothing is ever lost,
     * even if the mail host is down) and emailed to the demo inbox.
     */
    public function store(StoreDemoRequest $request): RedirectResponse
    {
        $data = $request->safe()->except('website');

        Log::channel('stack')->info('CoreX OS demo request', $data);

        try {
            Mail::to(config('mail.demo.address'), config('mail.demo.name'))
                ->send(new DemoRequested($data));
        } catch (Throwable $e) {
            // A dead SMTP host must not cost us the lead or show the visitor an
            // error — the enquiry is already in the log above.
            Log::error('CoreX OS demo request could not be emailed', [
                'error' => $e->getMessage(),
                'email' => $data['email'],
            ]);
        }

        return redirect()
            ->route('home')
            ->with('demo_success', "Thanks {$data['name']} — your demo request is in. We'll be in touch within one business day.")
            ->withFragment('demo');
    }
}
