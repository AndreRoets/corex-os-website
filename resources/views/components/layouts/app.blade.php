@props([
    'title' => 'CoreX OS — The Real Estate Operating System',
    'description' => 'CoreX OS is the all-in-one operating system for a real estate agency — listings, deals, documents, e-signature, compliance and a domain AI in one source of truth. Book a demo.',
])
<!DOCTYPE html>
<html lang="en" class="light no-js scroll-pt-24">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#FFFFFF">
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

    <title>{{ $title }}</title>
    <meta name="description" content="{{ $description }}">

    <link rel="canonical" href="{{ url()->current() }}">

    {{-- Open Graph / Twitter --}}
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="CoreX OS">
    <meta property="og:title" content="{{ $title }}">
    <meta property="og:description" content="{{ $description }}">
    <meta property="og:image" content="{{ asset('images/corex-mark.png') }}">
    <meta name="twitter:card" content="summary">

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
</head>
<body class="min-h-screen antialiased overflow-x-hidden">
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
