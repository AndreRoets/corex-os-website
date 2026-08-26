<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreWebinarRegistrationRequest;
use App\Services\CoreX\CoreXUnavailable;
use App\Services\CoreX\WebinarClient;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

/**
 * The public registration page.
 *
 * Nothing here is stored. The webinar is fetched from CoreX on every render and
 * the submission is posted straight on — CoreX creates the registration, issues
 * the demo login and sends the one email that carries the joining link, the
 * calendar invite and the credentials.
 *
 * The visitor's browser never talks to CoreX. It posts to us, and we make the
 * call server-side with the public token, which is why the token can stay a
 * secret and the registrant list can stay private.
 */
class WebinarRegistrationController extends Controller
{
    public function __construct(private readonly WebinarClient $corex) {}

    /**
     * The registration page, or the closed state.
     */
    public function show(string $slug): View
    {
        try {
            $result = $this->corex->show($slug);
        } catch (CoreXUnavailable $e) {
            return $this->unavailable($e, $slug);
        }

        // Unknown slug, archived, or past — CoreX answers all three the same
        // way on purpose, so nobody can map the sales calendar by guessing
        // slugs. To a visitor they are one state: you cannot sign up here.
        if ($result->notFound()) {
            return view('webinars.closed');
        }

        $webinar = (array) $result->get('webinar');

        if (($webinar['registration_open'] ?? false) !== true) {
            return view('webinars.closed', ['webinar' => $webinar]);
        }

        return view('webinars.show', [
            'slug' => $slug,
            'webinar' => $webinar,
        ]);
    }

    /**
     * Post the registration to CoreX and send the visitor somewhere true.
     */
    public function register(StoreWebinarRegistrationRequest $request, string $slug): RedirectResponse|View
    {
        $fields = $request->validated();

        try {
            $result = $this->corex->register($slug, $fields);
        } catch (CoreXUnavailable) {
            // Named route rather than back(): a redirect that depends on the
            // Referer header sends anyone whose browser withholds it to the
            // home page, losing both their input and the reason why.
            return redirect()
                ->route('webinars.show', $slug)
                ->withInput()
                ->with('webinar_error', 'Something went wrong on our side and your registration did not go through. Please try again in a few minutes.');
        }

        // Closed, archived or unknown — it may have closed between loading the
        // form and submitting it, so this is reachable in normal use.
        if ($result->notFound()) {
            return view('webinars.closed');
        }

        if ($result->invalid()) {
            return redirect()
                ->route('webinars.show', $slug)
                ->withInput()
                ->withErrors($this->fieldErrors($result->errors()));
        }

        // Two success shapes, one outcome.
        //
        // `throttled: true` means they submitted again within CoreX's 15-minute
        // cooldown. They are registered, the email they already have is the one
        // that works, and they did nothing wrong — so this is a success page,
        // never an error. Telling someone their registration failed when it
        // succeeded is how you lose a lead you had already won.
        $throttled = (bool) $result->get('throttled', false);

        return redirect()
            ->route('webinars.thanks', $slug)
            ->with('webinar_registered', [
                'first_name' => $fields['first_name'],
                'email' => $fields['email'],
                'throttled' => $throttled,
            ]);
    }

    /**
     * The thank-you page. Only reachable straight after registering — there is
     * nothing on it that belongs in a shareable URL.
     */
    public function thanks(Request $request, string $slug): RedirectResponse|View
    {
        $registered = $request->session()->get('webinar_registered');

        if (! is_array($registered)) {
            return redirect()->route('webinars.show', $slug);
        }

        return view('webinars.thanks', [
            'slug' => $slug,
            'registered' => $registered,
        ]);
    }

    /**
     * A revoked token or an unreachable CoreX is a configuration fault, not
     * something the visitor did. It is already logged with a hint by the
     * client; all they get is a plain apology.
     */
    private function unavailable(CoreXUnavailable $e, string $slug): View
    {
        Log::error('Webinar page could not be rendered', [
            'slug' => $slug,
            'auth_failure' => $e->isAuthFailure,
        ]);

        return view('webinars.unavailable');
    }

    /**
     * Map CoreX's field-keyed messages onto the inputs on our form.
     *
     * Almost all of them already match. The exception is `name`: while CoreX
     * still validates a single joined name, an error keyed `name` has no input
     * to attach to and would vanish silently. It shows against the first-name
     * field instead, which is where the visitor will start looking.
     *
     * @param  array<string, array<int, string>>  $errors
     * @return array<string, array<int, string>>
     */
    private function fieldErrors(array $errors): array
    {
        if (isset($errors['name'])) {
            $errors['first_name'] = array_merge($errors['first_name'] ?? [], $errors['name']);
            unset($errors['name']);
        }

        return $errors;
    }
}
