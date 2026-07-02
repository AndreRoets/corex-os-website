@php
    $pillars = [
        ['building', 'Property', 'The physical asset', 'Residential, commercial or land — every listing, lease and valuation hangs off it.'],
        ['users', 'Contact', 'Any person', 'Buyer, seller, landlord, tenant or attorney — one profile, every relationship.'],
        ['handshake', 'Deal', 'Any transaction', 'Sale, rental, renewal or referral — the thread that moves money and status.'],
        ['agent', 'Agent', 'The practitioner', 'Agent or principal — FFC, commission splits and performance in one place.'],
    ];

    $combos = [
        ['A listing', ['Property', 'Agent']],
        ['A lease', ['Property', 'Contact', 'Deal', 'Agent']],
        ['A commission payout', ['Deal', 'Agent']],
        ['A FICA check', ['Contact', 'Deal']],
    ];
@endphp

<section id="pillars" class="relative py-20 sm:py-28 bg-[color:var(--color-bg-soft)] border-y border-[color:var(--color-border)]">
    <div class="pointer-events-none absolute inset-0 -z-0 bg-dots opacity-[0.4] [mask-image:radial-gradient(ellipse_60%_50%_at_50%_40%,black,transparent)]"></div>

    <div class="relative mx-auto max-w-7xl px-5 sm:px-8">
        <x-section-heading
            eyebrow="The four pillars"
            eyebrow-icon="layers"
            title='Four core entities. <span class="text-gradient">One connected graph.</span>'
        >
            Everything in CoreX connects to four things. No module is an island — every action enriches the whole graph.
            Integration isn&rsquo;t a feature here, it&rsquo;s the architecture.
        </x-section-heading>

        {{-- Pillars --}}
        <div class="mt-14 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($pillars as $i => [$ico, $name, $sub, $desc])
                <div class="reveal group card relative overflow-hidden p-6 transition duration-300 hover:-translate-y-1 hover:border-[color:var(--color-brand)]"
                     style="transition-delay: {{ $i * 60 }}ms">
                    <div class="pointer-events-none absolute -right-10 -top-10 h-28 w-28 glow-brand opacity-0 transition duration-300 group-hover:opacity-70"></div>
                    <div class="flex items-center justify-between">
                        <span class="grid h-11 w-11 place-items-center rounded-md bg-[color:var(--color-surface-2)] text-[color:var(--color-brand-400)] ring-1 ring-inset ring-[color:var(--color-border)]">
                            <x-icon :name="$ico" class="w-5 h-5" />
                        </span>
                        <span class="font-mono text-xs text-[color:var(--color-faint)]">0{{ $i + 1 }}</span>
                    </div>
                    <h3 class="mt-5 text-xl font-semibold text-ink">{{ $name }}</h3>
                    <p class="mt-0.5 text-xs font-mono uppercase tracking-[0.14em] text-[color:var(--color-brand-400)]">{{ $sub }}</p>
                    <p class="mt-3 text-sm leading-relaxed text-[color:var(--color-muted)]">{{ $desc }}</p>
                </div>
            @endforeach
        </div>

        {{-- Combinations --}}
        <div class="reveal mt-6 card p-6 sm:p-8">
            <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                <div class="max-w-md">
                    <h3 class="flex items-center gap-2 text-lg font-semibold text-ink">
                        <x-icon name="link" class="w-5 h-5 text-[color:var(--color-brand-400)]" />
                        Every record is a combination
                    </h3>
                    <p class="mt-2 text-sm leading-relaxed text-[color:var(--color-muted)]">
                        A listing links Property + Agent. A lease links all four. The links are the value —
                        and the moat no bolt-on integration can copy.
                    </p>
                </div>

                <div class="grid gap-3 sm:grid-cols-2 lg:min-w-[26rem]">
                    @foreach ($combos as [$label, $parts])
                        <div class="rounded-md border border-[color:var(--color-border)] bg-[color:var(--color-bg-soft)] p-3.5">
                            <p class="text-sm font-medium text-ink">{{ $label }}</p>
                            <div class="mt-2 flex flex-wrap gap-1.5">
                                @foreach ($parts as $p)
                                    <span class="rounded border border-[color:var(--color-border)] bg-[color:var(--color-surface)] px-2 py-0.5 font-mono text-[11px] text-[color:var(--color-brand-400)]">{{ $p }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
