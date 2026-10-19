@props([
    'title' => 'CoreX OS — The Real Estate Operating System',
    'description' => 'CoreX OS is the all-in-one operating system for a real estate agency — listings, deals, documents, e-signature, compliance and a domain AI in one source of truth. Book a demo.',
    'page' => null,
    // For pages with no `pages` row that must stay out of the index — the
    // closed and thank-you webinar pages. A row's own setting wins over this.
    'robots' => null,
])
@php
    use App\Support\CanonicalUrl;
    use App\Support\StructuredData;
    use Illuminate\Support\Str;

    // A matching `pages` row (see App\Support\PageRoutes) overrides the props
    // above wherever it has something to say, so SEO metadata can be edited
    // from the admin panel without touching this template or its callers.
    $metaTitle = $page?->meta_title ?: $title;
    $metaDescription = $page?->meta_description ?: $description;
    // Built on APP_URL, never the host the request came in on, and without the
    // query string, a trailing slash or /index.php — see CanonicalUrl.
    $canonical = $page?->canonical_url ?: CanonicalUrl::forRequest(request());
    $robotsContent = $page ? $page->robotsContent() : ($robots ?? 'index, follow');
    $ogTitle = $page?->og_title ?: $metaTitle;
    $ogDescription = $page?->og_description ?: $metaDescription;
    // The default card is a known 1200×630, so only it gets dimensions; an
    // admin-supplied image could be any size.
    $ogImage = $page?->og_image ?: CanonicalUrl::to('images/og-default.png');
    $ogImageIsDefault = ! $page?->og_image;
    $ogImageAlt = $ogImageIsDefault ? 'CoreX OS — The Real Estate Operating System' : $ogTitle;
    $ogType = $page?->og_type ?: 'website';
    $twitterCard = $page?->twitter_card ?: 'summary_large_image';
    $twitterTitle = $page?->twitter_title ?: $ogTitle;
    $twitterDescription = $page?->twitter_description ?: $ogDescription;
    $twitterImage = $page?->twitter_image ?: $ogImage;

    $structuredData = StructuredData::encode(StructuredData::graph(
        request()->route()?->getName(),
        $canonical,
        $page?->name ?: trim(Str::before($metaTitle, ' — ')),
    ));
@endphp
<!DOCTYPE html>
<html lang="en-ZA" class="light no-js scroll-pt-24">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#FFFFFF">
    <meta name="facebook-domain-verification" content="6rfh163dr8rbz0brukx5k0e0lmnbm4">
    <meta name="robots" content="{{ $robotsContent }}">
    <style>[x-cloak]{display:none!important}</style>

    {{-- Theme, applied before paint so there's no flash of the wrong one.
         Light is the default; dark is only used if the visitor chose it before. --}}
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
    <meta property="og:url" content="{{ $canonical }}">
    <meta property="og:locale" content="en_ZA">
    <meta property="og:image" content="{{ $ogImage }}">
    @if ($ogImageIsDefault)
        <meta property="og:image:width" content="1200">
        <meta property="og:image:height" content="630">
        <meta property="og:image:type" content="image/png">
    @endif
    <meta property="og:image:alt" content="{{ $ogImageAlt }}">
    <meta name="twitter:card" content="{{ $twitterCard }}">
    <meta name="twitter:title" content="{{ $twitterTitle }}">
    <meta name="twitter:description" content="{{ $twitterDescription }}">
    <meta name="twitter:image" content="{{ $twitterImage }}">
    <meta name="twitter:image:alt" content="{{ $ogImageAlt }}">

    {{-- Fonts: Inter (UI) + JetBrains Mono (mono/code accents) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">

    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/apple-touch-icon.png') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Generated from the price list, the page's questions and APP_URL — see
         App\Support\StructuredData. Encoded with JSON_HEX_TAG, so it is safe
         unescaped here. --}}
    <script type="application/ld+json">{!! $structuredData !!}</script>

    {{-- An admin's own JSON-LD for this page, in addition to the graph above. --}}
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
