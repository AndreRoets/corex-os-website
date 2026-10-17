<x-layouts.app
    :page="$page"
    title="Everything is included — CoreX OS"
    description="Everything CoreX OS does, in one place. Listings, deals, contacts, presentations, compliance, portals and more, all included on every plan. No add-ons, no unlocking modules."
>
    @php

        $total = array_sum(array_map('count', $categories));

        // The six headline areas for the first screen. Each names an existing
        // $categories key, so the tiles and the full list can never disagree.
        $headline = [
            ['building', 'Listings', 'Every listing, every portal, one click.'],
            ['handshake', 'Deals & Pipeline', 'From offer to settlement, tracked end to end.'],
            ['users', 'Contacts & CRM', 'Every buyer and seller, with consent on record.'],
            ['presentation', 'Presentations & CMA', 'Seller presentations built from live data.'],
            ['shield-check', 'Compliance', 'FICA status on every contact, always current.'],
            ['link', 'Portals & Syndication', 'Property24 and PrivateProperty, straight from the app.'],
        ];

        // What the usual platform does, as the comparison. Kept generic on purpose:
        // this is a positioning line, not a claim about any one competitor.
        $theirs = [
            'A base plan, then an upgrade to unlock modules',
            'Paid add-ons for compliance, e-signature and documents',
            'Extra fees per module, per branch or per integration',
            'A sales call before you can use the feature you need',
        ];
    @endphp

    {{-- Entrance animation for the hero, played once when the page opens. The
         hero uses its own classes rather than .reveal, which waits for scroll. --}}
    <style>
        @keyframes features-rise {
            from { opacity: 0; transform: translateY(22px); filter: blur(6px); }
            to   { opacity: 1; transform: none; filter: blur(0); }
        }
        @keyframes features-glow-in {
            from { opacity: 0; transform: translate(-50%, 12px) scale(0.92); }
            to   { opacity: 0.7; transform: translate(-50%, 0) scale(1); }
        }
        @keyframes features-tile-in {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: none; }
        }
        .fx-rise { opacity: 0; animation: features-rise 900ms cubic-bezier(.2,.7,.2,1) forwards; }
        .fx-glow { animation: features-glow-in 1400ms cubic-bezier(.2,.7,.2,1) both; }
        .fx-tile { opacity: 0; animation: features-tile-in 700ms cubic-bezier(.2,.7,.2,1) forwards; }

        @media (prefers-reduced-motion: reduce) {
            .fx-rise, .fx-tile { opacity: 1; animation: none; }
            .fx-glow { animation: none; opacity: 0.7; }
        }
    </style>

    {{-- Hero: one message, one number, one action --}}
    <section class="relative overflow-hidden pt-28 pb-16 sm:pt-40 sm:pb-24">
        <div class="pointer-events-none absolute inset-0 -z-10 bg-grid opacity-[0.3] [mask-image:radial-gradient(ellipse_70%_60%_at_50%_0%,black,transparent)]"></div>
        <div class="fx-glow pointer-events-none absolute -top-32 left-1/2 -z-10 h-[520px] w-[820px] glow-brand"></div>

        <div class="mx-auto max-w-4xl px-5 sm:px-8 text-center">
            <p class="fx-rise font-mono text-xs uppercase tracking-[0.2em] text-[color:var(--color-brand-400)]" style="animation-delay: 150ms">No tiers · No add-ons · No upsells</p>

            <h1 class="fx-rise mt-6 text-5xl sm:text-6xl md:text-7xl font-semibold leading-[1.02] tracking-tight text-balance" style="animation-delay: 300ms">
                Everything is included.<br>
                <span class="text-gradient">Always.</span>
            </h1>

            <div class="fx-rise mt-10 inline-flex items-baseline gap-3" style="animation-delay: 550ms">
                <span id="features-count" data-count="{{ $total }}" class="text-7xl sm:text-8xl font-semibold tracking-tight text-ink tabular-nums">{{ $total }}</span>
                <span class="text-left text-sm leading-snug text-[color:var(--color-muted)]">features<br>on every agency</span>
            </div>

            <div class="fx-rise mt-10 flex flex-wrap justify-center gap-3" style="animation-delay: 750ms">
                <x-btn href="{{ route('home') }}#demo" size="lg">Book a demo</x-btn>
                <x-btn href="#all-features" size="lg" variant="ghost">See every feature</x-btn>
            </div>
        </div>
    </section>

    {{-- Headline areas: the focal points --}}
    <section class="mx-auto max-w-6xl px-5 sm:px-8 pb-20 sm:pb-28">
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($headline as $i => [$icon, $area, $line])
                <a href="#{{ Str::slug($area) }}"
                   class="fx-tile card group relative flex flex-col overflow-hidden p-7 transition duration-300 hover:-translate-y-1 hover:border-[color:var(--color-brand)]"
                   style="animation-delay: {{ 900 + $i * 90 }}ms">
                    <div class="pointer-events-none absolute -right-10 -top-10 h-28 w-28 glow-brand opacity-0 transition duration-300 group-hover:opacity-70"></div>
                    <span class="grid h-12 w-12 place-items-center rounded-md bg-[color:var(--color-surface-2)] text-[color:var(--color-brand-400)] ring-1 ring-inset ring-[color:var(--color-border)]">
                        <x-icon :name="$icon" class="w-5 h-5" />
                    </span>
                    <h2 class="mt-6 text-xl font-semibold text-ink">{{ $area }}</h2>
                    <p class="mt-2 text-sm leading-relaxed text-[color:var(--color-muted)]">{{ $line }}</p>
                    <span class="mt-6 font-mono text-xs text-[color:var(--color-faint)]">{{ count($categories[$area]) }} features</span>
                </a>
            @endforeach
        </div>
    </section>

    {{-- The poke: the usual model vs. ours, kept to one short band --}}
    <section class="border-y border-[color:var(--color-border)] bg-[color:var(--color-bg-soft)]">
        <div class="mx-auto grid max-w-6xl gap-10 px-5 py-14 sm:px-8 md:grid-cols-2 md:py-20">
            <div class="reveal">
                <p class="font-mono text-xs uppercase tracking-wider text-[color:var(--color-faint)]">Other platforms</p>
                <ul class="mt-5 space-y-3">
                    @foreach ($theirs as $line)
                        <li class="flex items-start gap-3 text-base text-[color:var(--color-muted)]">
                            <x-icon name="x-alt" class="mt-1 w-4 h-4 shrink-0 text-[color:var(--color-faint)]" />
                            <span>{{ $line }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="reveal">
                <p class="font-mono text-xs uppercase tracking-wider text-[color:var(--color-brand-400)]">CoreX OS</p>
                <p class="mt-5 text-3xl font-semibold leading-tight tracking-tight text-ink text-balance">
                    Every module, every agency, <span class="text-gradient">from day one.</span>
                </p>
                <p class="mt-4 text-base leading-relaxed text-[color:var(--color-muted)]">
                    Compliance, e-signature, documents, Ellie AI, portal syndication and a free agency website. Nothing to upgrade to.
                </p>
            </div>
        </div>
    </section>

    {{-- The full list: closed by default so the page stays short --}}
    <section id="all-features" class="mx-auto max-w-4xl scroll-mt-24 px-5 sm:px-8 py-20 sm:py-28">
        <div class="reveal text-center">
            <h2 class="text-3xl sm:text-4xl font-semibold tracking-tight text-ink text-balance">
                The full list. <span class="text-gradient">Ticked, every one.</span>
            </h2>
            <p class="mt-4 text-sm text-[color:var(--color-muted)]">Open an area to see everything in it.</p>
        </div>

        <div class="mt-12 space-y-3">
            @foreach ($categories as $name => $items)
                <details id="{{ Str::slug($name) }}" class="reveal card group scroll-mt-24 overflow-hidden">
                    <summary class="flex cursor-pointer list-none items-center justify-between gap-4 p-5 transition duration-300 hover:bg-[color:var(--color-surface-2)]">
                        <span class="flex items-baseline gap-3">
                            <span class="text-base font-semibold text-ink">{{ $name }}</span>
                            <span class="font-mono text-xs text-[color:var(--color-faint)]">{{ count($items) }}</span>
                        </span>
                        <x-icon name="chevron-down" class="w-4 h-4 shrink-0 text-[color:var(--color-muted)] transition duration-300 group-open:rotate-180" />
                    </summary>

                    <ul class="grid gap-4 border-t border-[color:var(--color-border)] p-5 sm:grid-cols-2">
                        @foreach ($items as [$featureName, $featureDesc])
                            <li class="flex items-start gap-3">
                                <x-icon name="check" class="mt-0.5 w-4 h-4 shrink-0 text-[color:var(--color-brand)]" />
                                <div>
                                    <p class="text-sm font-medium text-ink">{{ $featureName }}</p>
                                    <p class="mt-0.5 text-xs leading-relaxed text-[color:var(--color-muted)]">{{ $featureDesc }}</p>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </details>
            @endforeach
        </div>
    </section>

    {{-- Counts the feature total up from zero as the hero rises in. Shows the
         final number straight away when motion is reduced. --}}
    <script>
        (function () {
            var counter = document.getElementById('features-count');
            var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            if (counter && !reduce) {
                var end = parseInt(counter.dataset.count, 10) || 0;
                var start = null;
                var duration = 1100;
                counter.textContent = '0';

                function step(now) {
                    if (start === null) start = now;
                    var t = Math.min((now - start) / duration, 1);
                    var eased = 1 - Math.pow(1 - t, 3);
                    counter.textContent = Math.round(end * eased);
                    if (t < 1) requestAnimationFrame(step);
                }
                // Start after the hero's number has begun to rise in.
                setTimeout(function () { requestAnimationFrame(step); }, 550);
            }
        })();
    </script>

    {{-- A headline tile opens its area in the full list, rather than landing on a closed one. --}}
    <script>
        (function () {
            function openFromHash() {
                var target = document.getElementById(location.hash.slice(1));
                if (target && target.tagName === 'DETAILS') target.open = true;
            }
            window.addEventListener('hashchange', openFromHash);
            openFromHash();
        })();
    </script>

    {{-- Closing call to action --}}
    <section class="relative overflow-hidden border-t border-[color:var(--color-border)]">
        <div class="pointer-events-none absolute inset-0 -z-10 glow-brand opacity-40"></div>
        <div class="mx-auto max-w-3xl px-5 sm:px-8 py-24 sm:py-32 text-center">
            <h2 class="reveal text-4xl sm:text-5xl font-semibold tracking-tight text-ink text-balance">
                Everything is included. <span class="text-gradient">Always.</span>
            </h2>
            <p class="reveal mt-5 text-base sm:text-lg leading-relaxed text-[color:var(--color-muted)]">
                Book a demo and we’ll walk through the modules your agency will use most.
            </p>
            <div class="reveal mt-9 flex flex-wrap justify-center gap-3">
                <x-btn href="{{ route('home') }}#demo" size="lg">Book a demo</x-btn>
                <x-btn href="{{ route('pricing') }}" size="lg" variant="ghost">See pricing</x-btn>
            </div>
        </div>
    </section>
</x-layouts.app>
