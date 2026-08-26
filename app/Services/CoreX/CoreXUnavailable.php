<?php

namespace App\Services\CoreX;

use RuntimeException;
use Throwable;

/**
 * CoreX could not be reached, or refused us.
 *
 * This is OUR fault, never the visitor's — a revoked token, a wrong host, a
 * timeout, a 500. It is deliberately distinct from a 404 or a 422, which are
 * ordinary answers the caller renders as real states on the page. Anything
 * that reaches here gets logged for someone to fix and shown to the visitor as
 * a generic "please try again shortly".
 */
class CoreXUnavailable extends RuntimeException
{
    public function __construct(
        string $message,
        public readonly ?int $status = null,
        public readonly bool $isAuthFailure = false,
        ?Throwable $previous = null,
    ) {
        parent::__construct($message, 0, $previous);
    }
}
