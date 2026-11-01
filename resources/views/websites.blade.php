<x-layouts.app
    :page="$page"
    title="Real Estate Website Design, Free With CoreX | CoreX OS"
    description="Every CoreX plan includes an agency website, designed for you. See live agency sites and six example real estate website templates, or ask for something entirely your own."
>
    @php
        // Each step names only what FeatureInventory backs up: the Public
        // Website API, website API keys and the portal leads inbox.
        $steps = [
            ['coffee', 'Tell us about your agency', 'Your brand, your areas, the sites you like and the ones you don\'t. Bring a logo, or we\'ll work from your name.'],
            ['sparkles', 'We design it', 'A design made for you, from scratch or starting from one of the examples below. You see it before it goes live.'],
            ['link', 'Connected to CoreX', 'Listings, agents, branches and articles come from CoreX, and website enquiries land in the same inbox as your portal leads.'],
        ];

        $iconTile = 'grid h-11 w-11 shrink-0 place-items-center rounded-md bg-[color:var(--color-surface-2)] text-[color:var(--color-brand-400)] ring-1 ring-inset ring-[color:var(--color-border)]';

        // The templates are designed at desktop width and framed scaled down.
        $frameWidth = 1280;
    @endphp

    {{-- Hero --}}
    <section class="relative overflow-hidden pt-28 pb-16 sm:pt-36 sm:pb-20">
        <div class="pointer-events-none absolute inset-0 -z-10 bg-grid opacity-[0.3] [mask-image:radial-gradient(ellipse_70%_60%_at_50%_0%,black,transparent)]"></div>
        <div class="pointer-events-none absolute -top-32 left-1/2 -z-10 h-[460px] w-[760px] -translate-x-1/2 glow-brand opacity-60"></div>

        <div class="mx-auto max-w-3xl px-5 text-center sm:px-8">
            <x-eyebrow icon="globe" class="reveal justify-center">Agency websites</x-eyebrow>
            <h1 class="reveal mt-6 text-4xl font-semibold leading-[1.05] tracking-tight text-balance sm:text-5xl md:text-6xl">
                A real estate website,<br class="hidden sm:block">
                <span class="text-gradient">designed for your agency. Free.</span>
            </h1>
            <p class="reveal mt-6 text-lg leading-relaxed text-[color:var(--color-muted)]">
                Every CoreX plan includes your agency website, designed by our team at no extra cost.
                See the agency sites already live, browse a few example styles, then tell us what you have in mind.
            </p>
            <div class="reveal mt-8 flex flex-wrap items-center justify-center gap-3">
                <x-btn href="{{ route('home') }}#demo" size="lg">
                    Book a demo
                    <x-icon name="arrow-right" class="w-4 h-4" />
                </x-btn>
                <x-btn href="#templates" size="lg" variant="outline">See example templates</x-btn>
            </div>
        </div>
    </section>

    {{-- Live real estate websites --}}
    <section id="live" class="relative border-y border-[color:var(--color-border)] bg-[color:var(--color-bg-soft)] py-20 sm:py-28">
        <div class="pointer-events-none absolute inset-0 bg-dots opacity-[0.4] [mask-image:radial-gradient(ellipse_60%_50%_at_50%_40%,black,transparent)]"></div>

        <div class="relative mx-auto max-w-6xl px-5 sm:px-8">
            <x-section-heading eyebrow="Live" eyebrow-icon="circle-dot" title='Live real estate <span class="text-gradient">websites</span>'>
                Agency websites we have designed, running today.
            </x-section-heading>

            <div class="mt-14 grid gap-5 {{ count($liveSites) > 1 ? 'md:grid-cols-2' : 'mx-auto max-w-3xl' }}">
                @foreach ($liveSites as $site)
                    <a href="{{ $site['url'] }}" target="_blank" rel="noopener"
                       class="reveal card group block overflow-hidden transition duration-300 hover:-translate-y-0.5 hover:border-[color:var(--color-brand)]">
                        {{-- Browser chrome around the address, not a screenshot:
                             it stays true whatever the site looks like this week. --}}
                        <div class="flex items-center gap-2 border-b border-[color:var(--color-border)] bg-[color:var(--color-surface-2)] px-4 py-3">
                            <span class="h-2.5 w-2.5 rounded-full bg-[#ff5f57]"></span>
                            <span class="h-2.5 w-2.5 rounded-full bg-[#febc2e]"></span>
                            <span class="h-2.5 w-2.5 rounded-full bg-[#28c840]"></span>
                            <span class="ml-3 flex flex-1 items-center gap-2 rounded bg-[color:var(--color-surface)] px-3 py-1 font-mono text-xs text-[color:var(--color-muted)] ring-1 ring-inset ring-[color:var(--color-border)]">
                                <x-icon name="lock" class="h-3 w-3" />
                                {{ $site['domain'] }}
                            </span>
                        </div>
                        <div class="flex flex-col gap-6 p-6 sm:flex-row sm:items-center sm:justify-between sm:p-8">
                            <div>
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-500/10 px-2.5 py-1 text-xs font-medium text-emerald-600 ring-1 ring-inset ring-emerald-500/30 dark:text-emerald-400">
                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                    Live
                                </span>
                                <h3 class="mt-4 text-xl font-semibold text-ink">{{ $site['name'] }}</h3>
                                <p class="text-sm text-[color:var(--color-brand-400)]">{{ $site['tagline'] }}</p>
                                <p class="mt-2 text-sm leading-relaxed text-[color:var(--color-muted)]">{{ $site['description'] }}</p>
                            </div>
                            <span class="inline-flex shrink-0 items-center gap-2 text-sm font-medium text-ink group-hover:text-[color:var(--color-brand-400)]">
                                Visit site
                                <x-icon name="arrow-right" class="h-4 w-4 transition-transform duration-300 group-hover:translate-x-0.5" />
                            </span>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Example templates --}}
    <section id="templates" class="relative py-20 sm:py-28">
        <div class="mx-auto max-w-6xl px-5 sm:px-8">
            <x-section-heading eyebrow="Examples" eyebrow-icon="layers" title='Six styles, <span class="text-gradient">to get you thinking</span>'>
                Each one is a fictional agency, there to show the range. Open any of them full size.
            </x-section-heading>

            {{-- The point of the page: these are examples, not a menu. --}}
            <div class="reveal mx-auto mt-10 flex max-w-3xl gap-4 rounded-md border border-[color:color-mix(in_srgb,var(--color-brand)_40%,var(--color-border))] bg-[color:color-mix(in_srgb,var(--color-brand)_7%,var(--color-surface))] p-5 sm:p-6">
                <span class="{{ $iconTile }}">
                    <x-icon name="sparkles" class="w-5 h-5" />
                </span>
                <div>
                    <h3 class="text-base font-semibold text-ink">We can design anything</h3>
                    <p class="mt-1 text-sm leading-relaxed text-[color:var(--color-muted)]">
                        These templates are only examples. You don't have to pick one: we can start from one, mix a few,
                        or design something entirely your own around your brand.
                    </p>
                </div>
            </div>

            <div class="mt-14 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($templates as $key => $tpl)
                    @php $previewUrl = route('websites.template', $key); @endphp
                    <article class="reveal card group flex flex-col overflow-hidden" style="transition-delay: {{ $loop->index * 60 }}ms">
                        {{-- The real template, framed at desktop width and scaled to the card. --}}
                        <a href="{{ $previewUrl }}" target="_blank" rel="noopener" aria-label="Open the {{ $tpl['name'] }} example full size"
                           class="relative block aspect-[16/11] overflow-hidden border-b border-[color:var(--color-border)] bg-[color:var(--color-surface-2)]"
                           x-data="{ scale: 0.28 }"
                           x-init="new ResizeObserver(([entry]) => scale = entry.contentRect.width / {{ $frameWidth }}).observe($el)">
                            <iframe
                                src="{{ route('websites.template', [$key, 'frame' => 1]) }}"
                                title="{{ $tpl['name'] }} example template"
                                loading="lazy"
                                tabindex="-1"
                                aria-hidden="true"
                                scrolling="no"
                                class="pointer-events-none absolute left-0 top-0 origin-top-left border-0"
                                style="width: {{ $frameWidth }}px; height: {{ $frameWidth * 11 / 16 }}px; transform: scale(0.28)"
                                :style="`width: {{ $frameWidth }}px; height: {{ $frameWidth * 11 / 16 }}px; transform: scale(${scale})`"
                            ></iframe>
                            <span class="absolute inset-0 grid place-items-center bg-black/0 opacity-0 transition duration-300 group-hover:bg-black/35 group-hover:opacity-100">
                                <span class="inline-flex items-center gap-2 rounded-md bg-white px-4 py-2 text-sm font-medium text-[#0b1220]">
                                    <x-icon name="eye" class="h-4 w-4" />
                                    Full preview
                                </span>
                            </span>
                        </a>
                        <div class="flex flex-1 flex-col p-6">
                            <div class="flex items-center justify-between gap-3">
                                <span class="font-mono text-xs uppercase tracking-[0.16em] text-[color:var(--color-faint)]">{{ $tpl['style'] }}</span>
                                <span class="flex gap-1" aria-hidden="true">
                                    @foreach ($tpl['palette'] as $colour)
                                        <span class="h-3.5 w-3.5 rounded-full ring-1 ring-inset ring-black/10" style="background-color: {{ $colour }}"></span>
                                    @endforeach
                                </span>
                            </div>
                            <h3 class="mt-3 text-base font-semibold text-ink">{{ $tpl['name'] }}</h3>
                            <p class="mt-1 flex-1 text-sm leading-relaxed text-[color:var(--color-muted)]">{{ $tpl['description'] }}</p>
                            <a href="{{ $previewUrl }}" target="_blank" rel="noopener"
                               class="mt-5 inline-flex items-center gap-2 text-sm font-medium text-ink hover:text-[color:var(--color-brand-400)] transition duration-300">
                                Open full preview
                                <x-icon name="arrow-right" class="h-4 w-4" />
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    {{-- How it works --}}
    <section class="relative border-y border-[color:var(--color-border)] bg-[color:var(--color-bg-soft)] py-20 sm:py-28">
        <div class="relative mx-auto max-w-6xl px-5 sm:px-8">
            <x-section-heading eyebrow="How it works" eyebrow-icon="workflow" title='From brief to live, <span class="text-gradient">included in your plan</span>'>
                No design fee and no separate website contract. It comes with CoreX.
            </x-section-heading>

            <ol class="mt-14 grid gap-5 md:grid-cols-3">
                @foreach ($steps as $i => [$ico, $title, $desc])
                    <li class="reveal card p-6" style="transition-delay: {{ $i * 60 }}ms">
                        <div class="flex items-center justify-between">
                            <span class="{{ $iconTile }}">
                                <x-icon :name="$ico" class="w-5 h-5" />
                            </span>
                            <span class="font-mono text-xs text-[color:var(--color-faint)]">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                        </div>
                        <h3 class="mt-5 text-base font-semibold text-ink">{{ $title }}</h3>
                        <p class="mt-1 text-sm leading-relaxed text-[color:var(--color-muted)]">{{ $desc }}</p>
                    </li>
                @endforeach
            </ol>
        </div>
    </section>

    {{-- Call to action --}}
    <section class="relative overflow-hidden py-20 sm:py-28">
        <div class="pointer-events-none absolute inset-0 -z-10 bg-grid opacity-[0.25] [mask-image:radial-gradient(ellipse_60%_70%_at_50%_0%,black,transparent)]"></div>
        <div class="pointer-events-none absolute left-1/2 top-0 -z-10 h-[360px] w-[680px] -translate-x-1/2 glow-brand opacity-50"></div>

        <div class="mx-auto max-w-3xl px-5 text-center sm:px-8">
            <h2 class="reveal text-3xl font-semibold tracking-tight text-ink text-balance sm:text-4xl">
                Have a look in mind? <span class="text-gradient">We'll build it.</span>
            </h2>
            <p class="reveal mt-4 text-base leading-relaxed text-[color:var(--color-muted)]">
                Send us a site you like, a sketch or just your logo. Your website comes with every CoreX plan.
            </p>
            <div class="reveal mt-8 flex flex-wrap items-center justify-center gap-3">
                <x-btn href="{{ route('home') }}#demo" size="lg">
                    Book a demo
                    <x-icon name="arrow-right" class="w-4 h-4" />
                </x-btn>
                <x-btn href="{{ route('contact') }}" size="lg" variant="outline">Talk to us</x-btn>
            </div>
        </div>
    </section>
</x-layouts.app>
