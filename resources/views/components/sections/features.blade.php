@php
    // Feature grid. `span` controls the bento layout on large screens.
    $features = [
        [
            'icon' => 'building', 'name' => 'Listings', 'span' => 'lg:col-span-2',
            'desc' => 'Multi-agent listings with a Property24 email parser that extracts suburb and detail automatically, then syndicates straight back to the portal.',
            'tags' => ['P24 parser', 'Suburb extraction', 'Syndication'],
        ],
        [
            'icon' => 'users', 'name' => 'Contacts', 'span' => '',
            'desc' => 'The people graph — every buyer, seller, landlord and tenant with FICA/POPIA consent, contact types and tags.',
            'tags' => ['Consent', 'Types & tags'],
        ],
        [
            'icon' => 'handshake', 'name' => 'Deals & Agency Tracker', 'span' => '',
            'desc' => 'Commission calculator, branch dashboards and a live deal register — the whole agency&rsquo;s pipeline in one view.',
            'tags' => ['Commission calc', 'Branch dashboards'],
        ],
        [
            'icon' => 'file-text', 'name' => 'DocuPerfect', 'span' => 'lg:col-span-2',
            'desc' => 'Web documents authored in HTML/Blade and rendered to PDF through real Chrome (Puppeteer) for legal-grade fidelity. Linked fields write back to all four pillars.',
            'tags' => ['Chrome-rendered PDF', 'Linked fields', 'Write-back'],
        ],
        [
            'icon' => 'signature', 'name' => 'E-Signature', 'span' => '',
            'desc' => 'Electronic signing with canvas capture, sequential signing and identity gates — plus wet-ink scan &amp; flatten for paper.',
            'tags' => ['Sequential', 'Identity gates', 'Wet-ink'],
        ],
        [
            'icon' => 'presentation', 'name' => 'Presentations', 'span' => '',
            'desc' => 'Puppeteer-rendered listing and seller presentations, generated from live data — never rebuilt by hand.',
            'tags' => ['Listing', 'Seller'],
        ],
        [
            'icon' => 'shield-check', 'name' => 'Compliance', 'span' => '',
            'desc' => 'FICA, POPIA and PPRA/FFC tracking built into the workflow — not a separate checklist you forget.',
            'tags' => ['FICA', 'POPIA', 'PPRA/FFC'],
        ],
        [
            'icon' => 'search', 'name' => 'Prospecting & Market Intelligence', 'span' => 'lg:col-span-2',
            'desc' => 'Tracked properties and portal-scraping intelligence that tells you what&rsquo;s really moving in the suburb, with CMA tooling to support your pricing.',
            'tags' => ['Tracked properties', 'Portal intelligence', 'CMA tooling'],
        ],
        [
            'icon' => 'calendar', 'name' => 'Calendar', 'span' => '',
            'desc' => 'Appointments, mandates and inspections on one shared agency calendar.',
            'tags' => [],
        ],
        [
            'icon' => 'monitor', 'name' => 'TV Display', 'span' => '',
            'desc' => 'Office screens that show live listings, deals and milestones.',
            'tags' => [],
        ],
        [
            'icon' => 'globe', 'name' => 'Client Portal', 'span' => '',
            'desc' => 'A branded client portal where buyers and sellers see their matched properties, manage their consent, and stay connected to their agent.',
            'tags' => ['Core Matches', 'Consent'],
        ],
        [
            'icon' => 'smartphone', 'name' => 'Mobile', 'span' => '',
            'desc' => 'Much of CoreX on-the-go — capture contacts, manage matches, check your calendar and talk to Ellie from anywhere.',
            'tags' => [],
        ],
    ];
@endphp

<section id="features" class="relative py-20 sm:py-28 bg-[color:var(--color-bg-soft)] border-y border-[color:var(--color-border)]">
    <div class="mx-auto max-w-7xl px-5 sm:px-8">
        <x-section-heading
            eyebrow="Modules"
            eyebrow-icon="layers"
            title='Every tool an agency needs, <span class="text-gradient">rebuilt to work as one.</span>'
        >
            These aren&rsquo;t roadmap promises — they&rsquo;re live modules that share the same graph. Each one is best-in-class
            on its own, and unbeatable together.
        </x-section-heading>

        <div class="mt-14 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($features as $i => $f)
                <article class="reveal group card relative flex flex-col overflow-hidden p-6 transition duration-300 hover:-translate-y-1 hover:border-[color:var(--color-brand)] {{ $f['span'] }}"
                         style="transition-delay: {{ ($i % 3) * 60 }}ms">
                    <div class="pointer-events-none absolute -right-10 -top-10 h-28 w-28 glow-brand opacity-0 transition duration-300 group-hover:opacity-70"></div>
                    <span class="grid h-11 w-11 place-items-center rounded-md bg-[color:var(--color-surface-2)] text-[color:var(--color-brand-400)] ring-1 ring-inset ring-[color:var(--color-border)]">
                        <x-icon :name="$f['icon']" class="w-5 h-5" />
                    </span>
                    <h3 class="mt-5 text-lg font-semibold text-ink">{{ $f['name'] }}</h3>
                    <p class="mt-2 text-sm leading-relaxed text-[color:var(--color-muted)]">{!! $f['desc'] !!}</p>

                    @if (! empty($f['tags']))
                        <div class="mt-4 flex flex-wrap gap-1.5">
                            @foreach ($f['tags'] as $tag)
                                <span class="rounded border border-[color:var(--color-border)] bg-[color:var(--color-bg-soft)] px-2 py-0.5 font-mono text-[11px] text-[color:var(--color-muted)]">{{ $tag }}</span>
                            @endforeach
                        </div>
                    @endif
                </article>
            @endforeach
        </div>
    </div>
</section>
