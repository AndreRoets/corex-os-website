@php
    $items = [
        ['shield-check', 'PPRA & FFC', 'Property Practitioners Regulatory Authority registration and Fidelity Fund Certificate status tracked per agent — flagged before it lapses.'],
        ['file-text', 'Property Practitioners Act', 'Workflows built around Act 22 of 2019 — mandates, disclosures and the practitioner obligations that come with every deal.'],
        ['users', 'FICA', 'Client due diligence captured at the point of the deal, tied to the Contact, and required before registration can proceed.'],
        ['database', 'POPIA', 'Consent recorded on every contact, data minimised by design, and access scoped by role across the agency.'],
    ];
@endphp

<section id="compliance" class="relative py-20 sm:py-28">
    <div class="mx-auto max-w-7xl px-5 sm:px-8">
        <div class="grid gap-14 lg:grid-cols-[0.9fr_1.1fr] lg:items-start">
            <x-section-heading
                eyebrow="Compliance, built in"
                eyebrow-icon="shield-check"
                align="left"
                title='Regulated by design — <span class="text-gradient">not by reminder.</span>'
            >
                South African property practice is heavily regulated. CoreX treats compliance as part of the workflow,
                so the right check happens at the right step and nothing reaches registration unchecked.
            </x-section-heading>

            <div class="grid gap-4 sm:grid-cols-2">
                @foreach ($items as $i => [$ico, $t, $d])
                    <div class="reveal card p-6 transition duration-300 hover:border-[color:var(--color-brand)]"
                         style="transition-delay: {{ ($i % 2) * 60 }}ms">
                        <span class="grid h-11 w-11 place-items-center rounded-md bg-[color:var(--color-surface-2)] text-[color:var(--color-brand-400)] ring-1 ring-inset ring-[color:var(--color-border)]">
                            <x-icon :name="$ico" class="w-5 h-5" />
                        </span>
                        <h3 class="mt-5 text-base font-semibold text-ink">{{ $t }}</h3>
                        <p class="mt-2 text-sm leading-relaxed text-[color:var(--color-muted)]">{{ $d }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
