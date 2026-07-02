@php
    $cols = [
        'Platform' => [
            ['#pillars', 'Four Pillars'],
            ['#flows', 'Flows'],
            ['#features', 'Modules'],
            ['#ellie', 'Ellie AI'],
            ['#compliance', 'Compliance'],
        ],
        'Modules' => [
            ['#features', 'Listings'],
            ['#features', 'Deals & Agency Tracker'],
            ['#features', 'DocuPerfect'],
            ['#features', 'E-Signature'],
            ['#features', 'Prospecting'],
        ],
        'Company' => [
            ['#why', 'Why CoreX'],
            ['#demo', 'Book a demo'],
            ['mailto:hello@corexos.co.za', 'Contact'],
        ],
    ];
@endphp

<footer class="relative border-t border-[color:var(--color-border)] bg-[color:var(--color-bg-soft)]">
    <div class="mx-auto max-w-7xl px-5 sm:px-8 py-16">
        <div class="grid gap-12 lg:grid-cols-[1.4fr_1fr_1fr_1fr]">
            <div>
                <a href="{{ route('home') }}" class="flex items-center gap-2.5" aria-label="CoreX OS home">
                    <span class="grid h-8 w-8 place-items-center rounded-md bg-[color:var(--color-navy)] ring-1 ring-inset ring-[color:color-mix(in_srgb,var(--color-cyan)_45%,transparent)]">
                        <span class="font-mono text-sm font-bold text-[color:var(--color-cyan)]">C</span>
                    </span>
                    <x-logo class="text-lg" />
                </a>
                <p class="mt-4 max-w-xs text-sm leading-relaxed text-[color:var(--color-muted)]">
                    The real estate operating system. One system, one source of truth — built for
                    <span class="text-ink">Home Finders Coastal</span> and licensed to agencies.
                </p>
                <p class="mt-5 flex items-center gap-2 text-sm text-[color:var(--color-faint)]">
                    <x-icon name="map-pin" class="w-4 h-4" />
                    KZN South Coast, South Africa
                </p>
            </div>

            @foreach ($cols as $heading => $items)
                <div>
                    <h3 class="font-mono text-xs uppercase tracking-[0.18em] text-[color:var(--color-faint)]">{{ $heading }}</h3>
                    <ul class="mt-4 space-y-2.5">
                        @foreach ($items as [$href, $label])
                            <li>
                                <a href="{{ $href }}" class="text-sm text-[color:var(--color-muted)] hover:text-ink transition duration-300">{{ $label }}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>

        <div class="mt-14 flex flex-col-reverse items-start gap-4 border-t border-[color:var(--color-border)] pt-8 sm:flex-row sm:items-center sm:justify-between">
            <p class="text-xs text-[color:var(--color-faint)]">
                &copy; {{ date('Y') }} CoreX OS · Home Finders Coastal. All rights reserved.
            </p>
            <p class="text-xs text-[color:var(--color-faint)]">
                Regulated real estate practice · PPRA · Property Practitioners Act 22 of 2019
            </p>
        </div>
    </div>
</footer>
