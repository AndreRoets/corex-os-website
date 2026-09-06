<?php

namespace App\Http\Requests\Admin;

use App\Support\PageRoutes;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePageRequest extends FormRequest
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
        $page = $this->route('page');

        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => [
                'required',
                'string',
                'max:255',
                'alpha_dash',
                Rule::unique('pages', 'slug')->ignore($page),
                Rule::notIn(PageRoutes::reservedSlugs()),
            ],
            'is_active' => ['boolean'],

            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'meta_keywords' => ['nullable', 'string', 'max:255'],
            'canonical_url' => ['nullable', 'url', 'max:255'],

            'robots_index' => ['boolean'],
            'robots_follow' => ['boolean'],

            'og_title' => ['nullable', 'string', 'max:255'],
            'og_description' => ['nullable', 'string', 'max:500'],
            'og_image' => ['nullable', 'string', 'max:255'],
            'og_type' => ['nullable', 'string', 'max:50'],

            'twitter_card' => ['nullable', 'string', 'max:50'],
            'twitter_title' => ['nullable', 'string', 'max:255'],
            'twitter_description' => ['nullable', 'string', 'max:500'],
            'twitter_image' => ['nullable', 'string', 'max:255'],

            'json_ld' => ['nullable', 'string'],
            'head_scripts' => ['nullable', 'string'],

            'sitemap_priority' => ['nullable', 'string', 'max:10'],
            'sitemap_frequency' => ['nullable', Rule::in(['always', 'hourly', 'daily', 'weekly', 'monthly', 'yearly', 'never'])],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'slug.not_in' => 'That slug is reserved for a system route and cannot be used.',
            'slug.alpha_dash' => 'The slug may only contain letters, numbers, dashes and underscores.',
        ];
    }
}
