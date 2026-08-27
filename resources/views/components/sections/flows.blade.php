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
@endphp

<section id="flows" class="relative py-20 sm:py-28">
    <div class="mx-auto max-w-7xl px-5 sm:px-8">
        <x-section-heading
            eyebrow="Flows"
            eyebrow-icon="workflow"
            title='The whole lifecycle, <span class="text-gradient">one click at a time.</span>'
        >
            CoreX models real estate as a continuous lifecycle. Within a deal, the pipeline advances stage by stage &mdash;
            downstream steps activate, deadlines land on your calendar, and progress is RAG-flagged as you go.
        </x-section-heading>

        {{-- Sale track --}}
        <div class="reveal mt-12">
            <x-flow-track :steps="$sale" accent="brand" />
        </div>

        <p class="reveal mt-10 flex items-center justify-center gap-2 text-center text-sm text-[color:var(--color-muted)]">
            <x-icon name="zap" class="w-4 h-4 text-[color:var(--color-brand)]" />
            Advance the pipeline stage by stage &mdash; every step tracked, dated and RAG-flagged from the same graph.
        </p>
    </div>
</section>
