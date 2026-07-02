@php
    $sale = [
        ['target', 'Property identified', 'Prospect logged'],
        ['presentation', 'Listing appointment', 'Seller presentation'],
        ['signature', 'Mandate signed', 'Sole / open mandate'],
        ['globe', 'Property marketed', 'Syndicated to Property24'],
        ['file-text', 'Offer received', 'OTP captured'],
        ['shield-check', 'Offer accepted', 'Deed of Sale · FICA'],
        ['check', 'Registered', 'Transfer complete'],
        ['chart', 'Commission calculated', 'Splits & VAT'],
        ['handshake', 'Agent paid', 'Payout released'],
    ];

    $rental = [
        ['signature', 'Rental mandate', 'Landlord signed on'],
        ['users', 'Tenant found', 'Lease · deposit · FICA'],
        ['circle-dot', 'Lease active', 'Invoicing & statements'],
        ['refresh', 'Renewal', 'Escalation applied'],
        ['scan', 'Exit inspection', 'Deposit reconciled'],
        ['globe', 'Re-listing', 'Back to market'],
    ];
@endphp

<section id="flows" class="relative py-20 sm:py-28">
    <div class="mx-auto max-w-7xl px-5 sm:px-8"
         x-data="{ track: 'sale' }">
        <x-section-heading
            eyebrow="Flows"
            eyebrow-icon="workflow"
            title='The whole lifecycle, <span class="text-gradient">one click at a time.</span>'
        >
            CoreX models real estate as a continuous lifecycle. Within a deal, the pipeline advances stage by stage —
            downstream steps activate, deadlines land on your calendar, and progress is RAG-flagged as you go.
        </x-section-heading>

        {{-- Track switch --}}
        <div class="reveal mt-10 flex justify-center">
            <div role="tablist" aria-label="Lifecycle track" class="inline-flex rounded-md border border-[color:var(--color-border)] bg-[color:var(--color-surface)] p-1">
                <button role="tab" :aria-selected="track === 'sale'" @click="track = 'sale'"
                        class="rounded px-4 py-2 text-sm font-medium transition duration-300"
                        :class="track === 'sale' ? 'bg-[color:var(--color-brand)] text-white' : 'text-[color:var(--color-muted)] hover:text-ink'">
                    Sale lifecycle
                </button>
                <button role="tab" :aria-selected="track === 'rental'" @click="track = 'rental'"
                        class="rounded px-4 py-2 text-sm font-medium transition duration-300"
                        :class="track === 'rental' ? 'bg-[color:var(--color-brand)] text-white' : 'text-[color:var(--color-muted)] hover:text-ink'">
                    Rental lifecycle
                </button>
            </div>
        </div>

        {{-- Sale track --}}
        <div x-show="track === 'sale'" x-transition.opacity.duration.300ms class="reveal mt-12">
            <x-flow-track :steps="$sale" accent="brand" />
        </div>

        {{-- Rental track --}}
        <div x-show="track === 'rental'" x-transition.opacity.duration.300ms class="reveal mt-12" x-cloak>
            <x-flow-track :steps="$rental" accent="cyan" />
        </div>

        <p class="reveal mt-10 flex items-center justify-center gap-2 text-center text-sm text-[color:var(--color-muted)]">
            <x-icon name="zap" class="w-4 h-4 text-[color:var(--color-brand)]" />
            Advance the pipeline stage by stage — every step tracked, dated and RAG-flagged from the same graph.
        </p>
    </div>
</section>
