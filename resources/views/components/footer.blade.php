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
            ['/pricing', 'Pricing'],
            ['#control', 'Total control'],
            ['#open-development', 'Open development'],
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
                    <x-brand-mark size="md" />
                    <x-logo class="text-lg" />
                </a>
                <p class="mt-4 max-w-xs text-sm leading-relaxed text-[color:var(--color-muted)]">
                    The real estate operating system. One system, one source of truth —
                    <span class="text-ink">built for real estate agencies</span> and licensed to grow with them.
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
                                @if (\Illuminate\Support\Str::startsWith($href, '#'))
                                    @php $t = \Illuminate\Support\Str::after($href, '#'); @endphp
                                    <a href="{{ route('home') }}#{{ $t }}"
                                       x-data
                                       @click="if (document.getElementById('{{ $t }}')) { $event.preventDefault(); $store.site.revealSection('{{ $t }}') }"
                                       class="text-sm text-[color:var(--color-muted)] hover:text-ink transition duration-300">{{ $label }}</a>
                                @else
                                    <a href="{{ $href }}" class="text-sm text-[color:var(--color-muted)] hover:text-ink transition duration-300">{{ $label }}</a>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>

        <div class="mt-14 flex flex-col-reverse items-start gap-4 border-t border-[color:var(--color-border)] pt-8 sm:flex-row sm:items-center sm:justify-between">
            <p class="text-xs text-[color:var(--color-faint)]">
                &copy; {{ date('Y') }} CoreX OS, a product of R R Technologies. All rights reserved.
            </p>
            <p class="text-xs text-[color:var(--color-faint)]">
                Regulated real estate practice · PPRA · Property Practitioners Act 22 of 2019
            </p>
        </div>
    </div>
</footer>
