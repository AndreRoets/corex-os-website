<!DOCTYPE html>
<html lang="en" class="light no-js">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#FFFFFF">

    {{-- This console renders registrant names, companies, emails and phone
         numbers. It is behind a login, but a login is not a reason to invite
         crawlers to keep trying the door. Analytics tags are deliberately not
         injected here — see resources/views/components/layouts/app.blade.php
         for where they belong instead. --}}
    <meta name="robots" content="noindex, nofollow, noarchive">
    <meta name="referrer" content="same-origin">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script>
        (function () {
            var dark = false;
            try { dark = localStorage.getItem('corex-theme') === 'dark'; } catch (e) {}
            var root = document.documentElement;
            root.classList.toggle('dark', dark);
            root.classList.toggle('light', !dark);
        })();
    </script>

    <title inertia>CoreX OS admin</title>

    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

    @routes
    @vite(['resources/css/app.css', 'resources/js/admin.jsx'])
    @inertiaHead
</head>
<body class="min-h-screen antialiased">
    @inertia
</body>
</html>
