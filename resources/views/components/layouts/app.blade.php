@props([
    'title' => 'CoreX OS — The Real Estate Operating System',
    'description' => 'CoreX OS is the all-in-one operating system for a real estate agency — listings, deals, documents, e-signature, compliance and a domain AI in one source of truth. Book a demo.',
    'page' => null,
])
@php
    // A matching `pages` row (see App\Support\PageRoutes) overrides the props
    // above wherever it has something to say, so SEO metadata can be edited
    // from the admin panel without touching this template or its callers.
    $metaTitle = $page?->meta_title ?: $title;
    $metaDescription = $page?->meta_description ?: $description;
    $canonical = $page?->canonical_url ?: url()->current();
    $robotsContent = $page ? $page->robotsContent() : 'index, follow';
    $ogTitle = $page?->og_title ?: $metaTitle;
    $ogDescription = $page?->og_description ?: $metaDescription;
    $ogImage = $page?->og_image ?: asset('images/corex-mark.png');
    $ogType = $page?->og_type ?: 'website';
    $twitterCard = $page?->twitter_card ?: 'summary';
@endphp
<!DOCTYPE html>
<html lang="en" class="light no-js scroll-pt-24">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#FFFFFF">
    <meta name="facebook-domain-verification" content="6rfh163dr8rbz0brukx5k0e0lmnbm4">
    <meta name="robots" content="{{ $robotsContent }}">
    <style>[x-cloak]{display:none!important}</style>

    {{-- Theme, applied before paint so there's no flash of the wrong one.
         Light is the default; dark is only used if the visitor chose it before.
         The key is shared with /mobile-app, so the choice follows them across. --}}
    <script>
        (function () {
            var dark = false;
            try { dark = localStorage.getItem('corex-theme') === 'dark'; } catch (e) {}
            var root = document.documentElement;
            root.classList.toggle('dark', dark);
            root.classList.toggle('light', !dark);
            var meta = document.querySelector('meta[name="theme-color"]');
            if (meta) meta.setAttribute('content', dark ? '#050505' : '#FFFFFF');
        })();
    </script>

    <title>{{ $metaTitle }}</title>
    <meta name="description" content="{{ $metaDescription }}">
    @if ($page?->meta_keywords)
        <meta name="keywords" content="{{ $page->meta_keywords }}">
    @endif

    <link rel="canonical" href="{{ $canonical }}">

    {{-- Open Graph / Twitter --}}
    <meta property="og:type" content="{{ $ogType }}">
    <meta property="og:site_name" content="CoreX OS">
    <meta property="og:title" content="{{ $ogTitle }}">
    <meta property="og:description" content="{{ $ogDescription }}">
    <meta property="og:image" content="{{ $ogImage }}">
    <meta name="twitter:card" content="{{ $twitterCard }}">
    @if ($page?->twitter_title)
        <meta name="twitter:title" content="{{ $page->twitter_title }}">
    @endif
    @if ($page?->twitter_description)
        <meta name="twitter:description" content="{{ $page->twitter_description }}">
    @endif
    @if ($page?->twitter_image)
        <meta name="twitter:image" content="{{ $page->twitter_image }}">
    @endif

    {{-- Fonts: Inter (UI) + JetBrains Mono (mono/code accents) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">

    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/apple-touch-icon.png') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @verbatim
    <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "SoftwareApplication",
            "name": "CoreX OS",
            "applicationCategory": "BusinessApplication",
            "operatingSystem": "Web",
            "description": "The all-in-one operating system for a real estate agency — listings, contacts, deals, documents, e-signature, compliance and a domain AI in a single source of truth.",
            "offers": { "@type": "Offer", "priceCurrency": "ZAR" },
            "publisher": { "@type": "Organization", "name": "CoreX OS", "areaServed": "KZN South Coast, South Africa" }
        }
    </script>
    @endverbatim

    @if ($page?->json_ld)
        <script type="application/ld+json">{!! $page->json_ld !!}</script>
    @endif

    @if ($page?->head_scripts)
        {!! $page->head_scripts !!}
    @endif

    <x-analytics-head />
</head>
<body class="min-h-screen antialiased overflow-x-hidden">
    <x-analytics-body />

    <a href="#main" class="sr-only focus:not-sr-only focus:fixed focus:z-[100] focus:top-4 focus:left-4 focus:rounded-md focus:bg-[color:var(--color-brand)] focus:px-4 focus:py-2 focus:text-white focus:text-sm">
        Skip to content
    </a>

    <x-nav />

    <main id="main">
        {{ $slot }}
    </main>

    <x-footer />
</body>
</html>
