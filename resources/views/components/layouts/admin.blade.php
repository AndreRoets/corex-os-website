@props([
    'title' => 'Webinars',
    'heading' => null,
])

<!DOCTYPE html>
<html lang="en" class="light no-js">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#FFFFFF">

    {{-- This console renders registrant names, companies, emails and phone
         numbers. It is behind a login, but a login is not a reason to invite
         crawlers to keep trying the door. --}}
    <meta name="robots" content="noindex, nofollow, noarchive">
    <meta name="referrer" content="same-origin">
    <style>[x-cloak]{display:none!important}</style>

    <script>
        (function () {
            var dark = false;
            try { dark = localStorage.getItem('corex-theme') === 'dark'; } catch (e) {}
            var root = document.documentElement;
            root.classList.toggle('dark', dark);
            root.classList.toggle('light', !dark);
        })();
    </script>

    <title>{{ $title }} — CoreX OS admin</title>

    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen antialiased">

    @auth
        <header class="border-b border-[color:var(--color-border)] bg-[color:var(--color-surface)]">
            <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-5 py-3.5 sm:px-8">
                <div class="flex items-center gap-6">
                    <a href="{{ route('admin.webinars.index') }}" class="flex items-center gap-2.5">
                        <x-logo class="text-lg" />
                        <span class="font-mono text-[11px] uppercase tracking-[0.18em] text-[color:var(--color-faint)]">Webinars</span>
                    </a>
                </div>

                <div class="flex items-center gap-4">
                    <span class="hidden text-xs text-[color:var(--color-faint)] sm:inline">{{ auth()->user()->email }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-xs font-medium text-[color:var(--color-muted)] transition duration-300 hover:text-ink">
                            Sign out
                        </button>
                    </form>
                </div>
            </div>
        </header>
    @endauth

    <main class="mx-auto max-w-7xl px-5 py-8 sm:px-8 sm:py-10">
        @if ($heading)
            <div class="mb-6">{{ $heading }}</div>
        @endif

        @if (session('admin_status'))
            <div class="mb-6 rounded-md border border-[color:var(--color-brand)]/40 bg-[color:var(--color-brand)]/10 px-4 py-3 text-sm text-ink" role="status">
                {{ session('admin_status') }}
            </div>
        @endif

        @if (session('admin_error'))
            <div class="mb-6 rounded-md border border-[#e11d48]/40 bg-[#e11d48]/10 px-4 py-3 text-sm text-[#fb7185]" role="alert">
                {{ session('admin_error') }}
            </div>
        @endif

        {{ $slot }}
    </main>

    <footer class="mx-auto max-w-7xl px-5 pb-10 sm:px-8">
        <p class="border-t border-[color:var(--color-border)] pt-5 text-xs text-[color:var(--color-faint)]">
            Webinars, registrants and demo access all live in CoreX OS. This console reads and writes them
            live — nothing on this page is stored on the website.
        </p>
    </footer>

</body>
</html>
