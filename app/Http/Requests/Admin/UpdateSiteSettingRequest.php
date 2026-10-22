<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSiteSettingRequest extends FormRequest
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
            'site_name' => ['nullable', 'string', 'max:255'],
            'default_meta_description' => ['nullable', 'string', 'max:500'],
            'default_og_image' => ['nullable', 'string', 'max:255'],
            'default_twitter_handle' => ['nullable', 'string', 'max:100'],

            'ga4_measurement_id' => ['nullable', 'string', 'max:50'],
            'gtm_container_id' => ['nullable', 'string', 'max:50'],
            'google_search_console_verification' => ['nullable', 'string', 'max:255'],
            'bing_site_verification' => ['nullable', 'string', 'max:255'],
            'google_ads_conversion_id' => ['nullable', 'string', 'max:50'],
            'google_ads_conversion_label' => ['nullable', 'string', 'max:100'],

            'head_scripts' => ['nullable', 'string'],
            'body_scripts' => ['nullable', 'string'],
            'robots_txt' => ['nullable', 'string'],
            'same_as' => ['nullable', 'string', 'max:5000'],

            'company_legal_name' => ['nullable', 'string', 'max:255'],
            'company_registration_number' => ['nullable', 'string', 'max:50'],
            'company_street_address' => ['nullable', 'string', 'max:255'],
            'company_locality' => ['nullable', 'string', 'max:100'],
            'company_region' => ['nullable', 'string', 'max:100'],
            'company_postal_code' => ['nullable', 'string', 'max:10'],
            'company_telephone' => ['nullable', 'string', 'max:30'],
            'company_founding_year' => ['nullable', 'integer', 'min:1900', 'max:'.date('Y')],
        ];
    }
}
