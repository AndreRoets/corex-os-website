{{-- A full-size example template. The websites page frames this same page,
     scaled down, with ?frame=1, which drops the bar along the top. --}}
@php $framed = request()->boolean('frame'); @endphp
<!DOCTYPE html>
<html lang="en-ZA">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{-- A fictional agency: never something search should list. --}}
    <meta name="robots" content="noindex, follow">
    <title>{{ $template['name'] }}, a {{ strtolower($template['style']) }} website template | CoreX OS</title>
    <link rel="canonical" href="{{ route('websites') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?{{ $template['fonts'] }}{{ $framed ? '' : '&family=Inter:wght@400;500;600' }}&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">

    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen antialiased {{ $framed ? 'overflow-hidden' : '' }}">
    @unless ($framed)
        <div class="sticky top-0 z-50 flex flex-wrap items-center justify-between gap-x-4 gap-y-1 bg-[#0b1220] px-4 py-2.5 font-[Inter,sans-serif] text-xs text-slate-300 sm:px-6 sm:text-sm">
            <p>
                <span class="font-semibold text-white">Example template:</span>
                {{ $template['name'] }} is a fictional agency. We design any look you want.
            </p>
            <a href="{{ route('websites') }}#templates" class="font-medium text-[#38bdf8] hover:text-white">&larr; Back to CoreX agency websites</a>
        </div>
    @endunless

    @include('website-templates.'.$key)
</body>
</html>
