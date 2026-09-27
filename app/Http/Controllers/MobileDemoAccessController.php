<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMobileDemoRequest;
use App\Mail\ContactMessage;
use App\Mail\MobileDemoDetails;
use App\Models\ContactRequest;
use App\Support\Attribution;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Throwable;

class MobileDemoAccessController extends Controller
{
    /**
     * Hand out the mobile app's demo login.
     *
     * Exactly the same shape as ContactController@store — the row is the lead
     * and lands in the same list, with a topic that says where it came from —
     * plus one extra send: the credentials, to the person who asked.
     *
     * Neither email can block the reveal. They asked for a login; a dead SMTP
     * host must not turn that into an apology. The page shows the credentials
     * from config either way, and `emailed_at` tells the console which
     * notifications actually went out.
     */
    public function store(StoreMobileDemoRequest $request): RedirectResponse
    {
        $data = $request->safe()->except(['website', 'consent']);

        $enquiry = ContactRequest::create([
            ...$data,
            'topic' => 'mobile-demo',
            // There is no message box on this form, so we write the one the
            // person would have written. The console lists an excerpt of this.
            'message' => 'Asked for the mobile app demo login from the mobile app page.',
            ...Attribution::current($request),
            'page_url' => Str::limit((string) $request->headers->get('referer', ''), 2000, '') ?: null,
            'ip_address' => $request->ip(),
            'user_agent' => Str::limit((string) $request->userAgent(), 500, '') ?: null,
        ]);

        Log::channel('stack')->info('CoreX OS mobile demo login issued', [
            'id' => $enquiry->id,
            'email' => $enquiry->email,
            'channel' => $enquiry->channel(),
        ]);

        // Ours first: the lead is the thing we cannot recover if it is lost.
        try {
            Mail::to(config('mail.demo.address'), config('mail.demo.name'))
                ->send(new ContactMessage($enquiry));

            $enquiry->forceFill(['emailed_at' => now()])->save();
        } catch (Throwable $e) {
            Log::error('CoreX OS mobile demo notification could not be emailed', [
                'id' => $enquiry->id,
                'error' => $e->getMessage(),
                'email' => $enquiry->email,
            ]);
        }

        // Theirs second, and separately — a failure on one send must not cost
        // us the other.
        $emailed = true;

        try {
            Mail::to($enquiry->email, $enquiry->name)->send(new MobileDemoDetails($enquiry));
        } catch (Throwable $e) {
            $emailed = false;

            Log::error('CoreX OS mobile demo details could not be emailed to the requester', [
                'id' => $enquiry->id,
                'error' => $e->getMessage(),
                'email' => $enquiry->email,
            ]);
        }

        return redirect()
            ->route('mobile-app')
            ->with('mobile_demo_name', $enquiry->name)
            ->with('mobile_demo_emailed', $emailed)
            ->withFragment('demo-login');
    }
}
