<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDemoRequest extends FormRequest
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
            'agency' => ['required', 'string', 'min:2', 'max:160'],
            'email' => ['required', 'email:rfc', 'max:180'],
            'phone' => ['nullable', 'string', 'max:40'],
            'agents' => ['nullable', 'string', 'max:40'],
            'message' => ['nullable', 'string', 'max:2000'],
            'consent' => ['accepted'],
            // Honeypot — must stay empty. Bots tend to fill every field.
            'website' => ['nullable', 'prohibited'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'agents' => 'number of agents',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'consent.accepted' => 'Please agree to be contacted so we can book your demo.',
            'website.prohibited' => 'Something went wrong. Please try again.',
        ];
    }
}
