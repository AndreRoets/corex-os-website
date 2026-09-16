<?php

namespace App\Http\Requests;

use App\Models\ContactRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * The contact page. The same shape as StoreDemoRequest wherever the two ask
 * the same question, because both land in the same inbox and the person
 * reading them should not have to learn two formats.
 */
class StoreContactRequest extends FormRequest
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
            'phone' => ['nullable', 'string', 'max:40'],
            'agency' => ['nullable', 'string', 'max:160'],
            'topic' => ['nullable', Rule::in(array_keys(ContactRequest::TOPICS))],
            'message' => ['required', 'string', 'min:2', 'max:5000'],
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
            'consent.accepted' => 'Please agree to be contacted so we can reply to you.',
            'website.prohibited' => 'Something went wrong. Please try again.',
        ];
    }
}
