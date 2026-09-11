<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePricingEnquiry;
use App\Mail\PricingEnquiry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class PricingEnquiryController extends Controller
{
    /**
     * Handle a large-agency (40+ agents) enquiry from the pricing calculator.
     *
     * Mirrors DemoRequestController: logged first so nothing is lost, then
     * emailed to the same inbox the demo requests go to.
     */
    public function store(StorePricingEnquiry $request): RedirectResponse
    {
        $data = $request->safe()->except('website');

        Log::channel('stack')->info('CoreX OS pricing enquiry', $data);

        try {
            Mail::to(config('mail.demo.address'), config('mail.demo.name'))
                ->send(new PricingEnquiry($data));
        } catch (Throwable $e) {
            // A dead SMTP host must not cost us the lead or show the visitor an
            // error — the enquiry is already in the log above.
            Log::error('CoreX OS pricing enquiry could not be emailed', [
                'error' => $e->getMessage(),
                'email' => $data['email'],
            ]);
        }

        return redirect()
            ->route('pricing')
            ->with('pricing_enquiry_success', "Thanks {$data['name']} — your enquiry is in. We'll come back to you with a tailored quote within one business day.")
            ->withFragment('calculator');
    }
}
