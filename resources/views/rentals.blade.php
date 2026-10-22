<x-layouts.app
    :page="$page"
    title="Rental Management Software & CRM, South Africa | CoreX OS"
    description="The rental CRM for South African letting agents: online tenant applications and vetting, leases and renewals, lease notices, inspections and maintenance."
>
    @php
        // Each step names only what FeatureInventory's Rentals list backs up.
        $lifecycle = [
            ['file-text', 'Apply', 'A private link with autosave, document uploads and signed credit check consent, behind a PIN or ID check if you want one.'],
            ['shield-check', 'Review and approve', 'Your checklist, your named approvers. Highlight documents, capture income and expenses, then approve, decline or ask for more.'],
            ['signature', 'Sign the lease', 'The approved application becomes a lease, e-signed from your template in order: agent, tenants, landlords.'],
            ['scan', 'Move in', 'Photo inspection and inventory, signed through a link, with a read-only report link for the tenant.'],
            ['sliders', 'Maintain', 'Faults from staff, tenants or landlords become work orders. Contractors quote and close jobs through a secure link.'],
            ['refresh', 'Renew or move out', 'Renewals, breach and vacate notices, then a move-in vs move-out comparison with findings you can charge to the tenant.'],
        ];

        $split = [
            ['layers', 'CoreX handles', 'check', [
                'Applications, review, approval and credit check consent',
                'Leases, renewals, month-to-month and escalations on record',
                'Breach, vacate and Consumer Protection Act lease-expiry notices',
                'Move-in, move-out, routine and interim inspections and inventories',
                'Move-in vs move-out findings, each markable as a tenant charge',
                'Faults, work orders, contractor quotes and landlord approvals',
                'Landlords and tenants in the client app, and in the same CRM as your sales',
            ]],
            ['rand', 'Your accounting system handles', 'circle-dot', [
                'Collecting rent, and tracking arrears',
                'Your trust account and deposit investment',
                'Paying landlords and contractors',
                'Municipal and utility recharges',
                'Landlord and tenant financial statements',
            ]],
        ];

        $connected = [
            ['users', 'One contact list', 'Tenants, landlords, buyers and sellers in one CRM, with a stage-by-stage rentals board alongside the buyer pipeline.'],
            ['chart', 'Command centre and reports', 'A rentals command centre, rental reports to print, PDF or export, property rental history and landlord activity.'],
            ['calendar', 'Lease dates on the calendar', 'Lease expiry and escalation dates land on the same calendar as mandates, FICA expiry and deal deadlines.'],
            ['smartphone', 'Landlords and tenants in the app', 'Switched on per person. Tenants and landlords log faults; landlords approve quotes above your spending limit.'],
            ['shield-check', 'PPRA inspection pack', 'Sampled rental files are bundled into the pack the agency hands to the regulator.'],
            ['database', 'Bulk take-on, including reOS', 'Bring your existing rentals across in bulk, including an import from reOS.'],
        ];

        $linkClass = 'font-medium text-ink underline decoration-[color:var(--color-brand)] decoration-2 underline-offset-4 hover:text-[color:var(--color-brand-600)] transition duration-300';

        // Raw HTML answers, authored in RentalsFaq and never user input. The
        // page's FAQPage structured data is built from the same list.
        $faqs = \App\Support\RentalsFaq::items($linkClass);

        $iconTile = 'grid h-11 w-11 shrink-0 place-items-center rounded-md bg-[color:var(--color-surface-2)] text-[color:var(--color-brand-400)] ring-1 ring-inset ring-[color:var(--color-border)]';
    @endphp

    {{-- Hero --}}
    <section class="relative overflow-hidden pt-28 pb-16 sm:pt-36 sm:pb-20">
        <div class="pointer-events-none absolute inset-0 -z-10 bg-grid opacity-[0.3] [mask-image:radial-gradient(ellipse_70%_60%_at_50%_0%,black,transparent)]"></div>
        <div class="pointer-events-none absolute -top-32 left-1/2 -z-10 h-[460px] w-[760px] -translate-x-1/2 glow-brand opacity-60"></div>

        <div class="mx-auto max-w-6xl px-5 sm:px-8">
            <div class="mx-auto max-w-3xl text-center">
                <x-eyebrow icon="building" class="reveal justify-center">Rental CRM</x-eyebrow>
                <h1 class="reveal mt-6 text-4xl font-semibold leading-[1.05] tracking-tight text-balance sm:text-5xl md:text-6xl">
                    Rental management software for<br class="hidden sm:block">
                    <span class="text-gradient">South African letting agents.</span>
                </h1>
                <p class="reveal mt-6 text-lg leading-relaxed text-[color:var(--color-muted)]">
                    CoreX OS runs your whole rental desk, from the first application to the move-out inspection,
                    in the same system as your listings, sales and compliance. Every rental feature is on every plan,
                    from R{{ number_format(\App\Support\Pricing::lowestMonthly()) }} a month.
                </p>
                <div class="reveal mt-8 flex flex-wrap items-center justify-center gap-3">
                    <x-btn href="{{ route('home') }}#demo" size="lg">
                        Book a demo
                        <x-icon name="arrow-right" class="w-4 h-4" />
                    </x-btn>
                    <x-btn href="{{ route('pricing') }}" size="lg" variant="outline">See pricing</x-btn>
                </div>
            </div>

            {{-- The rental lifecycle --}}
            <ol class="mt-14 grid gap-4 sm:mt-16 sm:grid-cols-2 lg:grid-cols-3" aria-label="The rental lifecycle in CoreX">
                @foreach ($lifecycle as $i => [$ico, $title, $desc])
                    <li class="reveal card relative h-full p-6" style="transition-delay: {{ $i * 60 }}ms">
                        <div class="flex items-center justify-between">
                            <span class="{{ $iconTile }}">
                                <x-icon :name="$ico" class="w-5 h-5" />
                            </span>
                            <span class="font-mono text-xs text-[color:var(--color-faint)]">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                        </div>
                        <h2 class="mt-5 text-base font-semibold text-ink">{{ $title }}</h2>
                        <p class="mt-1 text-sm leading-relaxed text-[color:var(--color-muted)]">{{ $desc }}</p>
                    </li>
                @endforeach
            </ol>
        </div>
    </section>

    {{-- Every rental feature, from the same inventory as /features --}}
    <section class="relative border-y border-[color:var(--color-border)] bg-[color:var(--color-bg-soft)] py-20 sm:py-28">
        <div class="pointer-events-none absolute inset-0 bg-dots opacity-[0.4] [mask-image:radial-gradient(ellipse_60%_50%_at_50%_40%,black,transparent)]"></div>

        <div class="relative mx-auto max-w-6xl px-5 sm:px-8">
            <x-section-heading eyebrow="Rentals" eyebrow-icon="check" title='Every rental feature, <span class="text-gradient">on every plan</span>'>
                The full list, exactly as it appears on our <a href="{{ route('features') }}" class="{{ $linkClass }}">features page</a>.
            </x-section-heading>

            <ul class="mt-14 grid gap-x-10 border-t border-[color:var(--color-border)] md:grid-cols-2">
                @foreach ($features as [$name, $desc])
                    <li class="flex gap-3 border-b border-[color:var(--color-border)] py-4">
                        <x-icon name="check" class="mt-0.5 w-4 h-4 shrink-0 text-[color:var(--color-brand)]" />
                        <div>
                            <h3 class="text-sm font-semibold text-ink">{{ $name }}</h3>
                            <p class="mt-1 text-sm leading-relaxed text-[color:var(--color-muted)]">{{ $desc }}</p>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    </section>

    {{-- What CoreX does and does not do --}}
    <section class="relative py-20 sm:py-28">
        <div class="mx-auto max-w-6xl px-5 sm:px-8">
            <x-section-heading eyebrow="Scope" eyebrow-icon="target" title='A rental CRM, <span class="text-gradient">not an accounting package</span>'>
                CoreX runs the letting workflow. It does not handle money: keep reOS or the rental accounting
                system you already trust, and CoreX runs everything around it, with the payment reference on each lease.
            </x-section-heading>

            <div class="mt-14 grid items-start gap-5 md:grid-cols-2">
                @foreach ($split as $s => [$ico, $heading, $bullet, $items])
                    <div class="reveal card p-6 sm:p-8" style="transition-delay: {{ $s * 80 }}ms">
                        <div class="flex items-center gap-3">
                            <span class="{{ $iconTile }}">
                                <x-icon :name="$ico" class="w-5 h-5" />
                            </span>
                            <h3 class="text-lg font-semibold text-ink">{{ $heading }}</h3>
                        </div>
                        <ul class="mt-6 divide-y divide-[color:var(--color-border)] border-t border-[color:var(--color-border)]">
                            @foreach ($items as $item)
                                <li class="flex gap-3 py-3 text-sm leading-relaxed text-[color:var(--color-muted)]">
                                    <x-icon :name="$bullet" class="mt-0.5 w-4 h-4 shrink-0 {{ $s === 0 ? 'text-[color:var(--color-brand)]' : 'text-[color:var(--color-faint)]' }}" />
                                    {{ $item }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Connected to the rest of the agency --}}
    <section class="relative border-y border-[color:var(--color-border)] bg-[color:var(--color-bg-soft)] py-20 sm:py-28">
        <div class="relative mx-auto max-w-6xl px-5 sm:px-8">
            <x-section-heading eyebrow="One system" eyebrow-icon="workflow" title='Rentals, connected to <span class="text-gradient">the whole agency</span>'>
                Letting agencies and sales agencies with a rental desk run both from one source of truth. No second
                login, no re-typing a tenant who later buys.
            </x-section-heading>

            <div class="mt-14 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($connected as $i => [$ico, $title, $desc])
                    <div class="reveal card p-6" style="transition-delay: {{ $i * 60 }}ms">
                        <span class="{{ $iconTile }}">
                            <x-icon :name="$ico" class="w-5 h-5" />
                        </span>
                        <h3 class="mt-5 text-base font-semibold text-ink">{{ $title }}</h3>
                        <p class="mt-1 text-sm leading-relaxed text-[color:var(--color-muted)]">{{ $desc }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- FAQ. Native <details> — opens and closes with Enter or Space, no script. --}}
    <section class="relative py-20 sm:py-28">
        <div class="mx-auto max-w-3xl px-5 sm:px-8">
            <x-section-heading eyebrow="FAQ" eyebrow-icon="search" title='Questions <span class="text-gradient">letting agents ask</span>' />

            <div class="mt-12 space-y-3">
                @foreach ($faqs as $i => [$question, $answer])
                    <details class="reveal group rounded-md border border-[color:var(--color-border)] bg-[color:var(--color-surface)] transition-colors duration-300 open:border-[color:color-mix(in_srgb,var(--color-brand)_45%,var(--color-border))]"
                             style="transition-delay: {{ $i * 40 }}ms">
                        <summary class="flex cursor-pointer list-none items-center justify-between gap-4 rounded-md px-5 py-4 text-sm font-semibold text-ink transition-[background-color] duration-300 hover:bg-[color:var(--color-surface-2)] sm:px-6 sm:text-base [&::-webkit-details-marker]:hidden">
                            {{ $question }}
                            <x-icon name="chevron-down" class="h-5 w-5 shrink-0 text-[color:var(--color-muted)] transition-transform duration-300 group-open:rotate-180 group-open:text-[color:var(--color-brand)]" />
                        </summary>
                        <p class="px-5 pb-5 pr-12 text-sm leading-relaxed text-[color:var(--color-muted)] sm:px-6 sm:pr-14">{!! $answer !!}</p>
                    </details>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Call to action --}}
    <section class="relative overflow-hidden border-t border-[color:var(--color-border)] py-20 sm:py-28">
        <div class="pointer-events-none absolute inset-0 -z-10 bg-grid opacity-[0.25] [mask-image:radial-gradient(ellipse_60%_70%_at_50%_0%,black,transparent)]"></div>
        <div class="pointer-events-none absolute left-1/2 top-0 -z-10 h-[360px] w-[680px] -translate-x-1/2 glow-brand opacity-50"></div>

        <div class="mx-auto max-w-3xl px-5 text-center sm:px-8">
            <h2 class="reveal text-3xl font-semibold tracking-tight text-ink text-balance sm:text-4xl">
                See your rental desk <span class="text-gradient">run on CoreX.</span>
            </h2>
            <p class="reveal mt-4 text-base leading-relaxed text-[color:var(--color-muted)]">
                We'll walk through an application, an inspection and a work order on a live system.
            </p>
            <div class="reveal mt-8 flex flex-wrap items-center justify-center gap-3">
                <x-btn href="{{ route('home') }}#demo" size="lg">
                    Book a demo
                    <x-icon name="arrow-right" class="w-4 h-4" />
                </x-btn>
                <x-btn href="{{ route('take-on') }}" size="lg" variant="outline">How moving works</x-btn>
            </div>
        </div>
    </section>
</x-layouts.app>
