<?php

namespace App\Console\Commands;

use App\Services\CoreX\CoreXUnavailable;
use App\Services\CoreX\WebinarClient;
use Illuminate\Console\Command;

/**
 * Prove both tokens before anyone depends on them.
 *
 * Without this, the first thing that tells you a token is wrong is a prospect
 * hitting a registration form that fails — which is the worst possible moment
 * and the least informative message. Run it after pasting tokens, after a
 * rotation, and on the server after a deploy.
 */
class PingCoreX extends Command
{
    protected $signature = 'corex:ping';

    protected $description = 'Check that this site can reach CoreX with each of its two tokens';

    public function handle(WebinarClient $corex): int
    {
        $this->line('  CoreX API: <options=bold>'.config('corex.base_url').'</>');
        $this->newLine();

        $failed = false;

        foreach ([
            WebinarClient::SCOPE_PUBLIC => 'COREX_WEBINAR_PUBLIC_TOKEN (public registration)',
            WebinarClient::SCOPE_ADMIN => 'COREX_WEBINAR_ADMIN_TOKEN (admin + registrant PII)',
        ] as $scope => $label) {
            try {
                $result = $corex->ping($scope);
            } catch (CoreXUnavailable $e) {
                $failed = true;

                $this->components->error($label);
                $this->line('    '.($e->isAuthFailure
                    ? 'Rejected. The token is wrong, revoked, or missing.'
                    : $e->getMessage()));

                if ($e->status === 404) {
                    $this->line('    A 404 here means COREX_API_BASE points at the wrong CoreX host.');
                }

                continue;
            }

            if ($result->ok() && $result->get('service') === 'corex-webinars') {
                $this->components->info($label.' — reachable');

                continue;
            }

            $failed = true;
            $this->components->error($label);
            $this->line('    Unexpected reply (HTTP '.$result->status.'). Is this a CoreX host?');
        }

        // The token values themselves are never printed. Whether one works is
        // the useful answer; what it is belongs only in the environment.
        return $failed ? self::FAILURE : self::SUCCESS;
    }
}
