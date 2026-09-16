<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'message' => ['required', 'string', 'max:5000'],

            // A field a real visitor never sees or fills in. A bot that fills
            // every field it finds trips `max:0`; a human leaves it exactly
            // the empty string `present` requires, so the failure never shows.
            'company' => ['present', 'max:0'],
        ];
    }
}
