@php
    $principles = [
        ['layers', 'Integration is the moat', 'A bolt-on integration syncs two databases. CoreX has one. Competitors can copy a feature — they can&rsquo;t copy the graph underneath it.'],
        ['target', 'Best-in-class or rebuild', 'Every module has to beat the tool it replaces — DocuSign, Property24, Monday — not merely match it. If it can&rsquo;t, we rebuild until it does.'],
        ['zap', 'No shortcuts', 'Chrome-rendered legal documents. Real identity gates. Proper compliance. We do complicated so the agency can do simple.'],
        ['agent', 'Built for agents, not for screens', 'The computer work is automated so agents do the property work — listing, negotiating, closing. Software should disappear into the job.'],
        ['sparkles', 'AI enhances, never replaces', 'Ellie advises and drafts; people decide and sign. Automation removes the busywork, never the judgement.'],
        ['database', 'One source of truth', 'Enter it once. Every module, report and dashboard reads the same record. Nothing to reconcile, nothing to re-key.'],
    ];

    $faqs = [
        ['Is CoreX a CRM or a listing portal?', 'Neither on its own. CoreX is the operating system for the whole agency — listings, contacts, deals, documents, signing, compliance and AI in one graph. It replaces the CRM, the portal workflow and the document tools, rather than sitting alongside them.'],
        ['Can other agencies use it, or just Home Finders Coastal?', 'CoreX was built for Home Finders Coastal on the KZN South Coast and architected to be licensed to other agencies. It is multi-tenant by design — your agency&rsquo;s data, branding and branches stay your own.'],
        ['Does it work with Property24?', 'Yes. CoreX parses Property24 listing emails to extract detail and suburb automatically, and syndicates your listings back to the portal — while the deal itself lives in CoreX.'],
        ['Are the documents legally sound?', 'Documents are authored in HTML/Blade and rendered to PDF through real Chrome (Puppeteer) for legal-grade fidelity, with electronic signing (canvas capture, sequential signing, identity gates) or wet-ink scan &amp; flatten for paper.'],
        ['How does it handle South African compliance?', 'FICA, POPIA and PPRA/FFC tracking are built into the workflow. The system follows the Property Practitioners Act 22 of 2019 and treats the PPRA — not the dissolved EAAB — as the regulator.'],
    ];
@endphp

<section id="why" class="relative py-20 sm:py-28 bg-[color:var(--color-bg-soft)] border-y border-[color:var(--color-border)]">
    <div class="mx-auto max-w-7xl px-5 sm:px-8">
        <x-section-heading
            eyebrow="Why CoreX"
            eyebrow-icon="target"
            title='The operating principle behind <span class="text-gradient">the best system we can build.</span>'
        >
            CoreX isn&rsquo;t a bundle of features that happen to ship together. A few beliefs decide every line of it.
        </x-section-heading>

        <div class="mt-14 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($principles as $i => [$ico, $t, $d])
                <div class="reveal card p-6 transition duration-300 hover:-translate-y-1 hover:border-[color:var(--color-brand)]"
                     style="transition-delay: {{ ($i % 3) * 60 }}ms">
                    <span class="grid h-11 w-11 place-items-center rounded-md bg-[color:var(--color-surface-2)] text-[color:var(--color-brand-400)] ring-1 ring-inset ring-[color:var(--color-border)]">
                        <x-icon :name="$ico" class="w-5 h-5" />
                    </span>
                    <h3 class="mt-5 text-base font-semibold text-ink">{{ $t }}</h3>
                    <p class="mt-2 text-sm leading-relaxed text-[color:var(--color-muted)]">{!! $d !!}</p>
                </div>
            @endforeach
        </div>

        {{-- Pull quote --}}
        <figure class="reveal mx-auto mt-16 max-w-3xl text-center">
            <x-icon name="quote" class="mx-auto h-8 w-8 text-[color:color-mix(in_srgb,var(--color-brand)_60%,transparent)]" />
            <blockquote class="mt-4 text-2xl font-semibold leading-snug tracking-tight text-ink text-balance sm:text-3xl">
                The red button: the agent clicks, makes coffee, and the system has
                <span class="text-gradient">already done the work.</span>
            </blockquote>
            <figcaption class="mt-4 text-sm text-[color:var(--color-muted)]">The CoreX operating principle</figcaption>
        </figure>

        {{-- FAQ --}}
        <div class="mx-auto mt-20 max-w-3xl">
            <h3 class="reveal text-center text-xl font-semibold text-ink">Questions, answered</h3>
            <div class="mt-8 space-y-3" x-data="{ open: 0 }">
                @foreach ($faqs as $i => [$q, $a])
                    <div class="reveal card overflow-hidden">
                        <h4>
                            <button
                                @click="open === {{ $i }} ? open = null : open = {{ $i }}"
                                class="flex w-full items-center justify-between gap-4 px-5 py-4 text-left"
                                :aria-expanded="open === {{ $i }}"
                                aria-controls="faq-panel-{{ $i }}"
                            >
                                <span class="text-sm font-medium text-ink sm:text-base">{{ $q }}</span>
                                <x-icon name="chevron-down" class="w-5 h-5 shrink-0 text-[color:var(--color-muted)] transition-transform duration-300" ::class="open === {{ $i }} ? 'rotate-180' : ''" />
                            </button>
                        </h4>
                        <div id="faq-panel-{{ $i }}" x-show="open === {{ $i }}" x-collapse x-cloak>
                            <p class="px-5 pb-5 text-sm leading-relaxed text-[color:var(--color-muted)]">{!! $a !!}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
