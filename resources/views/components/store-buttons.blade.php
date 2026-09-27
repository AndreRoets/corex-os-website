@props([
    // 'lg' for the hero, 'md' for the repeat further down the page.
    'size' => 'lg',
])

@php
    $android = config('corex.mobile_app.android_url');
    $ios = config('corex.mobile_app.ios_url');

    $btn = 'group inline-flex items-center gap-3 rounded-xl border border-[color:var(--color-border)] bg-[color:var(--color-surface-2)] text-ink transition duration-300 hover:-translate-y-0.5 hover:border-[color:var(--color-brand)] focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[color:var(--color-brand)] '
        .($size === 'lg'
            ? 'w-full px-5 py-4 shadow-[0_10px_40px_-12px_rgba(0,0,0,0.45)] sm:w-auto sm:min-w-[15rem]'
            : 'w-full px-4 py-3 sm:w-auto sm:min-w-[13rem]');

    $eyebrowClass = 'block text-[0.7rem] font-mono uppercase tracking-[0.16em] text-[color:var(--color-faint)]';
    $labelClass = 'block font-semibold leading-tight '.($size === 'lg' ? 'text-lg' : 'text-base');
    $markClass = $size === 'lg' ? 'h-8 w-8 shrink-0' : 'h-7 w-7 shrink-0';
@endphp

<div {{ $attributes->merge(['class' => 'flex flex-col gap-3 sm:flex-row sm:flex-wrap']) }}>
    <a href="{{ $android }}" target="_blank" rel="noopener noreferrer" class="{{ $btn }}"
       aria-label="Download CoreX OS for Android on Google Play">
        {{-- Both store marks are drawn inline — no third-party badge images to
             go stale, and nothing extra to fetch. --}}
        <svg viewBox="0 0 24 24" class="{{ $markClass }}" aria-hidden="true">
            <path fill="#00A0FF" d="M3 2 L3 22 L12.5 12 Z"/>
            <path fill="#00E676" d="M3 2 L16 9.22 L12.5 12 Z"/>
            <path fill="#FFCE00" d="M12.5 12 L16 9.22 L21 12 L16 14.78 Z"/>
            <path fill="#FF3A44" d="M3 22 L16 14.78 L12.5 12 Z"/>
        </svg>
        <span class="text-left">
            <span class="{{ $eyebrowClass }}">Get it on</span>
            <span class="{{ $labelClass }}">Google Play</span>
        </span>
    </a>

    <a href="{{ $ios }}" target="_blank" rel="noopener noreferrer" class="{{ $btn }}"
       aria-label="Download CoreX OS for iPhone on the App Store">
        <svg viewBox="0 0 24 24" class="{{ $markClass }} text-ink" fill="currentColor" aria-hidden="true">
            <path d="M17.05 12.53c-.02-2.4 1.96-3.55 2.05-3.61-1.12-1.63-2.86-1.86-3.48-1.88-1.48-.15-2.89.87-3.64.87-.75 0-1.91-.85-3.14-.83-1.61.02-3.1.94-3.93 2.38-1.68 2.91-.43 7.22 1.2 9.58.8 1.16 1.75 2.46 3 2.41 1.21-.05 1.66-.78 3.12-.78 1.46 0 1.87.78 3.14.76 1.3-.02 2.12-1.18 2.91-2.34.92-1.34 1.3-2.64 1.32-2.71-.03-.01-2.53-.97-2.55-3.85z"/>
            <path d="M14.9 5.4c.66-.81 1.11-1.93.99-3.05-.95.04-2.11.64-2.8 1.44-.61.71-1.15 1.85-1.01 2.94 1.06.08 2.15-.54 2.82-1.33z"/>
        </svg>
        <span class="text-left">
            <span class="{{ $eyebrowClass }}">Download on the</span>
            <span class="{{ $labelClass }}">App Store</span>
        </span>
    </a>
</div>
