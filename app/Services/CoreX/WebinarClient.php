<?php

namespace App\Services\CoreX;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Psr\Http\Message\StreamInterface;
use Throwable;

/**
 * Every conversation this website has with CoreX OS.
 *
 * CoreX owns the webinars, the registrants and the demo credentials each
 * registrant is issued. This site stores none of it — the only table it has of
 * its own holds the admin logins. So this class is the whole data layer: what
 * it returns is what the page renders, and nothing is written down in between.
 *
 * The reason that matters is the access deadline. Registering issues a login
 * for demo1.corexos.co.za that expires on a date derived from the webinar
 * record. If we kept our own copy of that record and the two ever drifted — a
 * date edited here and not there — people would be told one deadline and have
 * another enforced. No error would be raised; they would simply be locked out
 * early, or keep access they should have lost.
 *
 * BOTH TOKENS ARE SERVER-SIDE ONLY. They are read from config here and never
 * leave this class — not into a view, a data attribute, a URL or a log line. A
 * token in the browser is the whole registrant list plus an unlimited supply of
 * demo logins, handed to anyone who opens dev tools.
 */
class WebinarClient
{
    /** The public page's token: registration only. */
    public const SCOPE_PUBLIC = 'public';

    /** The admin console's token: webinar admin plus registrant PII. */
    public const SCOPE_ADMIN = 'admin';

    /**
     * Reachability probe. Proves a freshly pasted token before a prospect
     * discovers it is wrong by hitting a form that 401s.
     */
    public function ping(string $scope): CoreXResult
    {
        return $this->send($scope, 'GET', '/api/v1/webinars/ping');
    }

    // ────────────────────────────────────────────────────────────────
    // Public — the registration page. It uses the public token deliberately,
    // even though the admin token would also work: two scopes exist so that a
    // compromise of the public path cannot reach the registrant list.
    // ────────────────────────────────────────────────────────────────

    public function show(string $slug): CoreXResult
    {
        return $this->send(self::SCOPE_PUBLIC, 'GET', "/api/v1/webinars/{$slug}");
    }

    /**
     * @param  array{first_name: string, last_name: string, email: string, company_name: string, phone?: string|null}  $fields
     */
    public function register(string $slug, array $fields): CoreXResult
    {
        $payload = [
            'first_name' => $fields['first_name'],
            'last_name' => $fields['last_name'],
            'email' => $fields['email'],
            'company_name' => $fields['company_name'],
            'phone' => $fields['phone'] ?? null,
        ];

        // CoreX is mid-way through splitting `name` into first/last. Until that
        // lands its validator requires `name` and ignores the pair; afterwards
        // it requires the pair. Sending all three satisfies both, so neither
        // side has to be deployed first. See config/corex.php.
        if (config('corex.send_legacy_name')) {
            $payload['name'] = trim($fields['first_name'].' '.$fields['last_name']);
        }

        return $this->send(self::SCOPE_PUBLIC, 'POST', "/api/v1/webinars/{$slug}/register", $payload);
    }

    // ────────────────────────────────────────────────────────────────
    // Admin — behind this website's own login. Admin token.
    // ────────────────────────────────────────────────────────────────

    /**
     * The full record behind the edit form.
     *
     * The public read deliberately omits join_url — it is earned by registering,
     * not by loading a page — so the edit screen has to ask with the admin
     * token. If CoreX ever answers this without the edit-only fields, the form
     * omits them from the save rather than blanking them; see
     * Admin\WebinarController::payload().
     */
    public function adminWebinar(string $slug): CoreXResult
    {
        return $this->send(self::SCOPE_ADMIN, 'GET', "/api/v1/webinars/{$slug}");
    }

    public function webinars(bool $includeArchived = false): CoreXResult
    {
        return $this->send(self::SCOPE_ADMIN, 'GET', '/api/v1/webinars', [
            'include_archived' => $includeArchived ? 'true' : 'false',
        ]);
    }

    /**
     * @param  array<string, mixed>  $fields
     */
    public function createWebinar(array $fields): CoreXResult
    {
        return $this->send(self::SCOPE_ADMIN, 'POST', '/api/v1/webinars', $fields);
    }

    /**
     * @param  array<string, mixed>  $fields
     */
    public function updateWebinar(string $slug, array $fields): CoreXResult
    {
        return $this->send(self::SCOPE_ADMIN, 'PUT', "/api/v1/webinars/{$slug}", $fields);
    }

    /**
     * Archive. There is no delete — the registration link stops working, but
     * everyone already registered keeps the demo access they were promised.
     */
    public function archiveWebinar(string $slug): CoreXResult
    {
        return $this->send(self::SCOPE_ADMIN, 'DELETE', "/api/v1/webinars/{$slug}");
    }

    /**
     * Set the joining link and email it to everyone already registered.
     *
     * One call, because they are one intention. Saving the link without sending
     * it leaves everyone who signed up before you had it holding a confirmation
     * email with no way in, and nothing anywhere says so.
     *
     * CoreX sends the email — this site never does. It owns the registrant list,
     * the templates and the sending reputation, and a second sender would mean
     * two different-looking emails about one webinar.
     */
    public function sendJoinLink(string $slug, string $joinUrl, ?string $meetingId = null, ?string $passcode = null): CoreXResult
    {
        return $this->send(self::SCOPE_ADMIN, 'POST', "/api/v1/webinars/{$slug}/join-link", [
            'join_url' => $joinUrl,
            // Sent as empty strings rather than omitted when cleared, so CoreX
            // can tell "there is no passcode" from "this caller said nothing
            // about the passcode" — the latter must leave what is stored alone.
            'join_meeting_id' => (string) $meetingId,
            'join_passcode' => (string) $passcode,
        ]);
    }

    public function registrations(string $slug, int $page = 1, int $perPage = 100): CoreXResult
    {
        return $this->send(self::SCOPE_ADMIN, 'GET', "/api/v1/webinars/{$slug}/registrations", [
            'page' => $page,
            'per_page' => $perPage,
        ]);
    }

    /**
     * Open the CSV as a stream we can pipe straight to the browser.
     *
     * The bytes are NOT parsed, re-encoded or "improved" on the way through.
     * The Zoom format in particular is column-for-column what Zoom's importer
     * expects; reshaping it here would put a second, silent definition of that
     * format in the wrong repository. If Zoom changes its template, the fix
     * belongs in CoreX, which generates the file.
     *
     * @param  string  $format  'zoom' or 'full'
     * @return array{stream: StreamInterface, headers: array<string, string>}
     */
    public function registrationsCsv(string $slug, string $format): array
    {
        $path = "/api/v1/webinars/{$slug}/registrations.csv";

        try {
            $response = $this->request(self::SCOPE_ADMIN)
                ->timeout((int) config('corex.download_timeout'))
                ->withOptions(['stream' => true])
                ->get($this->url($path), ['format' => $format]);
        } catch (ConnectionException $e) {
            $this->logUnreachable('GET', $path, $e);

            throw new CoreXUnavailable("Could not reach CoreX for GET {$path}.", previous: $e);
        }

        if (! $response->successful()) {
            $this->throwFor($response, 'GET', $path);
        }

        return [
            'stream' => $response->toPsrResponse()->getBody(),
            'headers' => [
                'Content-Type' => $response->header('Content-Type') ?: 'text/csv; charset=UTF-8',
                // Pass CoreX's own filename through — it names the webinar and
                // the format, and there will be several of these in one folder.
                'Content-Disposition' => $response->header('Content-Disposition')
                    ?: 'attachment; filename="'.$slug.'-'.$format.'.csv"',
            ],
        ];
    }

    // ────────────────────────────────────────────────────────────────

    /**
     * @param  array<string, mixed>  $data
     */
    protected function send(string $scope, string $method, string $path, array $data = []): CoreXResult
    {
        $method = strtoupper($method);

        try {
            $request = $this->request($scope);

            $response = match ($method) {
                'GET' => $request->get($this->url($path), $data),
                'DELETE' => $request->delete($this->url($path), $data),
                'PUT' => $request->put($this->url($path), $data),
                default => $request->post($this->url($path), $data),
            };
        } catch (ConnectionException $e) {
            $this->logUnreachable($method, $path, $e);

            throw new CoreXUnavailable("Could not reach CoreX for {$method} {$path}.", previous: $e);
        }

        // 404 and 422 are real answers that a screen renders as a state of its
        // own. Everything else that failed is ours to fix, not the visitor's.
        if (! $response->successful() && ! in_array($response->status(), [404, 422], true)) {
            $this->throwFor($response, $method, $path);
        }

        return new CoreXResult($response->status(), $this->decode($response));
    }

    protected function request(string $scope): PendingRequest
    {
        return Http::withToken($this->token($scope))
            ->acceptJson()
            ->timeout((int) config('corex.timeout'))
            // Retry only on a failure to connect at all. A POST that got
            // through and then timed out may already have registered someone,
            // and retrying that blindly would mean a second confirmation email
            // for one signup.
            ->retry(2, 200, fn (Throwable $e) => $e instanceof ConnectionException, throw: false);
    }

    protected function token(string $scope): string
    {
        $token = $scope === self::SCOPE_ADMIN
            ? config('corex.admin_token')
            : config('corex.public_token');

        if (blank($token)) {
            throw new CoreXUnavailable(
                "The CoreX {$scope} token is not configured.",
                isAuthFailure: true,
            );
        }

        return $token;
    }

    protected function url(string $path): string
    {
        return config('corex.base_url').$path;
    }

    /**
     * @return array<string, mixed>
     */
    protected function decode(Response $response): array
    {
        $json = $response->json();

        return is_array($json) ? $json : [];
    }

    /**
     * We never got an answer at all — DNS, TLS, refused, or timed out.
     *
     * Logged separately from throwFor() because there is no status code to log,
     * and because without this the admin sees "CoreX could not be reached" while
     * the log file says nothing whatsoever — which is the one case where you most
     * need to know whether it was us, them, or the network in between.
     */
    protected function logUnreachable(string $method, string $path, Throwable $e): void
    {
        Log::error('CoreX API unreachable', [
            'method' => $method,
            'path' => $path,
            'base_url' => config('corex.base_url'),
            'reason' => $e->getMessage(),
            'hint' => 'No HTTP response at all — DNS, TLS, a refused connection or a timeout. Check COREX_API_BASE and that the CoreX host is up.',
        ]);
    }

    protected function throwFor(Response $response, string $method, string $path): never
    {
        $status = $response->status();
        $isAuth = in_array($status, [401, 403], true);

        // Log enough to diagnose and nothing that could leak: no token, and no
        // response body — a failed registrant fetch would otherwise write PII
        // into the log file.
        Log::error('CoreX API call failed', [
            'method' => $method,
            'path' => $path,
            'status' => $status,
            'hint' => match (true) {
                $isAuth => 'Token is wrong, revoked, or lacks this scope — check COREX_WEBINAR_*_TOKEN.',
                $status === 404 => 'Endpoint not found — check COREX_API_BASE points at the right CoreX host.',
                default => 'CoreX returned an unexpected status.',
            },
        ]);

        throw new CoreXUnavailable(
            "CoreX returned {$status} for {$method} {$path}.",
            status: $status,
            isAuthFailure: $isAuth,
        );
    }
}
