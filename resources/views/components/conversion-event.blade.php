@props([
    // The GA4 event to send. `generate_lead` is one GA4 already understands
    // as a conversion, so it needs no setup on the analytics side.
    'event' => 'generate_lead',
    // Which form produced it, so contact and demo leads can be told apart.
    'form',
])

@php
    $settings = \App\Models\SiteSetting::current();
    $adsSendTo = $settings->google_ads_conversion_id && $settings->google_ads_conversion_label
        ? $settings->google_ads_conversion_id.'/'.$settings->google_ads_conversion_label
        : null;
@endphp

{{-- Rendered on the success view only, after the redirect, so a page refresh
     re-fires nothing: the session flash that shows the success is gone by
     then and so is this. --}}
@if ($settings->ga4_measurement_id || $settings->gtm_container_id)
    <script>
        (function () {
            window.dataLayer = window.dataLayer || [];
            // For Google Tag Manager triggers.
            window.dataLayer.push({ event: 'lead_submitted', form: @json($form) });

            if (typeof gtag === 'function') {
                gtag('event', @json($event), { form: @json($form) });
                @if ($adsSendTo)
                gtag('event', 'conversion', { send_to: @json($adsSendTo, JSON_UNESCAPED_SLASHES) });
                @endif
            }
        })();
    </script>
@endif
