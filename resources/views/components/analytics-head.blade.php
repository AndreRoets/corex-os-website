@php
    $settings ??= \App\Models\SiteSetting::current();
@endphp

@if ($settings->google_search_console_verification)
    <meta name="google-site-verification" content="{{ $settings->google_search_console_verification }}">
@endif

@if ($settings->gtm_container_id)
    <script>
        (function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
        j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
        'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','{{ $settings->gtm_container_id }}');
    </script>
@endif

@if ($settings->ga4_measurement_id)
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $settings->ga4_measurement_id }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '{{ $settings->ga4_measurement_id }}');
        @if ($settings->google_ads_conversion_id)
        gtag('config', '{{ $settings->google_ads_conversion_id }}');
        @endif
    </script>
@endif

{{-- Raw snippets an admin pasted in — pixels, extra tags, whatever the marketing
     team needs next without a code change. Trusted content: only an admin who is
     already past the login can set this. --}}
{!! $settings->head_scripts !!}
