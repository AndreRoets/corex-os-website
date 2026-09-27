<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * The "get the demo login" form on the mobile app page.
 *
 * Deliberately shorter than StoreContactRequest — the ask is a login, not a
 * conversation, and every extra field is a reason not to bother. Name and
 * email only, because the email is where the credentials are sent and the name
 * is what the reply is addressed to.
 */
class StoreMobileDemoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:120'],
            'email' => ['required', 'email:rfc', 'max:180'],
            'agency' => ['nullable', 'string', 'max:160'],
            'phone' => ['nullable', 'string', 'max:40'],
            'consent' => ['accepted'],
            // Honeypot — must stay empty. Bots tend to fill every field.
            'website' => ['nullable', 'prohibited'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'consent.accepted' => 'Please agree to be contacted so we can send you the login.',
            'website.prohibited' => 'Something went wrong. Please try again.',
        ];
    }
}
