<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * The four fields on the registration form, plus an optional phone.
 *
 * CoreX validates this again on its side and its messages are the ones a
 * visitor sees for anything CoreX rejects — they are already written in plain
 * English for a non-technical reader. These rules exist so an obviously empty
 * form does not cost a round trip, and so we never send CoreX a string longer
 * than its columns.
 */
class StoreWebinarRegistrationRequest extends FormRequest
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
            // Collected as two inputs from day one. Splitting a full name later
            // guesses wrong on names like "Jan van der Merwe" — and the person
            // whose name we mangle is the one who told us it correctly.
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email:rfc', 'max:255'],
            // Required, not optional: it is what makes a registration a sales
            // lead rather than an email address.
            'company_name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
        ];
    }

    /**
     * Send a failed validation back to the webinar's own page.
     *
     * The default is the previous URL, which is the Referer header — withheld
     * by some browsers and privacy tools, and when it is missing the visitor
     * lands on the home page with no form, no input and no explanation.
     */
    protected function getRedirectUrl(): string
    {
        return $this->route('slug')
            ? route('webinars.show', $this->route('slug'))
            : parent::getRedirectUrl();
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'first_name' => 'first name',
            'last_name' => 'last name',
            'company_name' => 'company',
        ];
    }
}
