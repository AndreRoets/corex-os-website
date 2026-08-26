<?php

namespace App\Services\CoreX;

/**
 * One answer from CoreX that the caller is expected to branch on.
 *
 * Only the outcomes a screen actually renders reach here: success, 404 (closed
 * / archived / unknown) and 422 (validation). Everything else — 401, 5xx, a
 * timeout — is a fault on our side and is thrown as CoreXUnavailable instead,
 * so a controller can never accidentally render a broken token as if it were
 * something the visitor did.
 */
final class CoreXResult
{
    /**
     * @param  array<string, mixed>  $json
     */
    public function __construct(
        public readonly int $status,
        public readonly array $json = [],
    ) {}

    public function ok(): bool
    {
        return $this->status < 300 && ($this->json['ok'] ?? false) === true;
    }

    public function notFound(): bool
    {
        return $this->status === 404;
    }

    public function invalid(): bool
    {
        return $this->status === 422;
    }

    /**
     * CoreX's field-keyed validation messages, ready to hand to a Laravel
     * error bag. The text is already written in plain English for a
     * non-technical reader — render it as-is rather than substituting our own.
     *
     * @return array<string, array<int, string>>
     */
    public function errors(): array
    {
        $errors = $this->json['errors'] ?? [];

        if (! is_array($errors)) {
            return [];
        }

        // Normalise to array-of-messages; CoreX sends arrays, but a single
        // string would otherwise blow up the error bag.
        return array_map(
            fn ($messages) => array_values(array_map('strval', (array) $messages)),
            $errors,
        );
    }

    public function message(): ?string
    {
        $message = $this->json['message'] ?? null;

        return is_string($message) ? $message : null;
    }

    /**
     * @return array<string, mixed>
     */
    public function get(string $key, mixed $default = []): mixed
    {
        return data_get($this->json, $key, $default);
    }
}
