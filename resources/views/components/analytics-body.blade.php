@php
    $settings ??= \App\Models\SiteSetting::current();
@endphp

@if ($settings->gtm_container_id)
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{ $settings->gtm_container_id }}"
        height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
@endif

{!! $settings->body_scripts !!}
