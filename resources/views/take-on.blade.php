<x-layouts.app
    :page="$page"
    title="Moving to CoreX — how take-on works"
    description="How agencies move to CoreX OS: no take-on fee, your onboarding month free, and we handle the migration from your current system."
>
    @php
        // $months comes from PageController::takeOn() — this month and the two after it.
        $timeline = [
            ['signature', 'Month 1', $months[0], 'You sign up', 'Sign the CoreX agreement and give notice to your current system.'],
            ['refresh', 'Month 2', $months[1], 'Onboarding month', 'We migrate your data, set up your agency and train your team.'],
            ['zap', 'Month 3', $months[2], 'First billed month', "You're live on CoreX. Billing starts, monthly in advance."],
        ];

        $promises = [
            ['rand', 'No take-on fee', "We don't charge to set you up or move your data."],
            ['calendar', 'One month free', 'The month after you sign is your onboarding month, on us.'],
            ['database', 'We do the migration', 'No import templates for you to fill in. We pull, map and load your data.'],
        ];

        $paths = [
            [
                'zap',
                'Go live straight away',
                'For agencies ready to switch. Your notice month with your current provider runs alongside your free onboarding month.',
                [
                    ['Sign up with CoreX', 'Short agreement, no take-on fee.'],
                    ['Give notice to your current CRM', 'Cancel with your current system or feed provider. Your Property24 and Private Property subscriptions stay as they are.'],
                    ['We contact Property24', 'We request the transfer document from Property24 on your behalf.'],
                    ['You sign the Property24 document', "Once it's signed, Property24 releases your listing data to us."],
                    ['We migrate and set you up', 'Listings imported from Property24. Contacts and other records exported from your current system and mapped into CoreX. Branches, users, branding and documents configured.'],
                    ['Train and go live', 'Your team is trained, your portal feeds run from CoreX, and billing starts the following month.'],
                ],
                true,
            ],
            [
                'eye',
                'Try it first',
                'For agencies who want to see CoreX working in their own office before they commit to leaving their current system.',
                [
                    ['Sign up with CoreX', 'Same agreement, same free month, no take-on fee.'],
                    ['Use CoreX inside your agency', 'Your team works in CoreX for the month: contacts, properties, documents, e-signing and reporting.'],
                    ['Decide', "When you're ready, give notice to your current CRM and we follow the go-live steps to switch your portal feeds over."],
                ],
                false,
            ],
        ];

        $split = [
            ['layers', 'CoreX handles', 'check', [
                'Requesting your listing data from Property24',
                'Importing your listings and linking them to the right agents and branches',
                'Exporting contacts and other records from your current system and mapping them into CoreX',
                'Setting up branches, users, roles and agency settings',
                'Your branding, document templates and e-sign setup',
                'Switching your portal feeds to CoreX',
                'Training your team',
            ]],
            ['users', 'You handle', 'circle-dot', [
                'Signing the CoreX agreement',
                'Giving notice to your current CRM or feed provider',
                'Signing the Property24 transfer document',
                'Giving us access to export from your current system',
                "Your team's time for training",
            ]],
        ];

        $linkClass = 'font-medium text-ink underline decoration-[color:var(--color-brand)] decoration-2 underline-offset-4 hover:text-[color:var(--color-brand-600)] transition duration-300';

        // Raw HTML answers are authored here, never user input.
        $faqs = [
            ['Is there really no take-on fee?', 'Yes. There is no setup, onboarding or migration fee. Your onboarding month is free too.'],
            ['When does billing start?', 'On the first day of the month after your free onboarding month. Sign up in September, October is free, and your first billed month is November. CoreX is billed monthly in advance, based on your active users that month. See the <a href="'.route('pricing').'" class="'.$linkClass.'">price list</a> for current rates.'],
            ['Do I need to cancel my Property24 or Private Property subscription?', 'No. You only give notice to your current CRM or listing feed provider. Your portal subscriptions stay with the portals; CoreX simply becomes the system that feeds your listings to them.'],
            ["Why can't my listings go to the portals during a trial?", "Property24 and Private Property don't allow the same listing to arrive from two different systems. While your current CRM is still feeding them, CoreX can't. Once you've left, we switch your feeds over."],
            ['What data do you bring across?', "Your listings come from Property24, which supplies the master listing data for every agency. Contacts and other records are exported from your current system and mapped into CoreX by our team. You don't fill in import spreadsheets."],
            ['How long does the move take?', "The listing import itself takes hours, not weeks. Most of the onboarding month is setup, document templates and training, and waiting on Property24 to release your data once you've signed."],
            ['Am I locked into a long contract?', "No. CoreX runs month to month after sign-up, with 30 days' written notice to cancel."],
        ];

        $brandBorder = 'border-[color:color-mix(in_srgb,var(--color-brand)_55%,var(--color-border))]';
        $iconTile = 'grid h-11 w-11 shrink-0 place-items-center rounded-md bg-[color:var(--color-surface-2)] text-[color:var(--color-brand-400)] ring-1 ring-inset ring-[color:var(--color-border)]';
    @endphp

    {{-- Hero --}}
    <section class="relative overflow-hidden pt-28 pb-16 sm:pt-36 sm:pb-20">
        <div class="pointer-events-none absolute inset-0 -z-10 bg-grid opacity-[0.3] [mask-image:radial-gradient(ellipse_70%_60%_at_50%_0%,black,transparent)]"></div>
        <div class="pointer-events-none absolute -top-32 left-1/2 -z-10 h-[460px] w-[760px] -translate-x-1/2 glow-brand opacity-60"></div>

        <div class="mx-auto max-w-6xl px-5 sm:px-8">
            <div class="mx-auto max-w-3xl text-center">
                <x-eyebrow icon="refresh" class="reveal justify-center">Moving to CoreX</x-eyebrow>
                <h1 class="reveal mt-6 text-4xl font-semibold leading-[1.05] tracking-tight text-balance sm:text-5xl md:text-6xl">
                    Moving to CoreX costs you<br class="hidden sm:block">
                    <span class="text-gradient">nothing to start.</span>
                </h1>
                <p class="reveal mt-6 text-lg leading-relaxed text-[color:var(--color-muted)]">
                    No take-on fee. Your onboarding month is free while we move your listings, contacts and documents
                    across and set CoreX up for your agency. You only pay once you're running.
                </p>
            </div>

            {{-- Three-month timeline --}}
            <ol class="mt-14 grid gap-4 sm:mt-16 md:grid-cols-3" aria-label="Your first three months">
                @foreach ($timeline as $i => [$ico, $step, $month, $title, $desc])
                    @php $free = $i === 1; @endphp
                    <li class="reveal relative" style="transition-delay: {{ $i * 70 }}ms">
                        <div class="relative h-full overflow-hidden rounded-md border p-6 sm:p-7 {{ $free
                            ? 'border-[color:var(--color-brand)] bg-[color:color-mix(in_srgb,var(--color-brand)_7%,var(--color-surface))] shadow-[0_18px_50px_-22px_color-mix(in_srgb,var(--color-brand)_70%,transparent)]'
                            : 'border-[color:var(--color-border)] bg-[color:var(--color-surface)]' }}">
                            @if ($free)
                                <div class="pointer-events-none absolute -right-12 -top-12 h-36 w-36 glow-brand opacity-70"></div>
                            @endif
                            <div class="relative flex items-center justify-between">
                                <span class="{{ $iconTile }}">
                                    <x-icon :name="$ico" class="w-5 h-5" />
                                </span>
                                @if ($free)
                                    <span class="rounded-full border {{ $brandBorder }} bg-[color:var(--color-surface)] px-3 py-1 text-sm font-semibold text-[color:var(--color-brand-600)] dark:text-[color:var(--color-brand-400)]">R0</span>
                                @endif
                            </div>
                            <p class="relative mt-5 font-mono text-xs text-[color:var(--color-faint)]">{{ $step }}</p>
                            <p class="relative mt-1 text-2xl font-semibold tracking-tight text-ink" data-month="{{ $i + 1 }}">{{ $month }}</p>
                            <p class="relative mt-4 text-sm font-semibold text-ink">{{ $title }}</p>
                            <p class="relative mt-1 text-sm leading-relaxed text-[color:var(--color-muted)]">{{ $desc }}</p>
                        </div>

                        @unless ($loop->last)
                            <span class="absolute -right-4 top-1/2 z-10 hidden -translate-y-1/2 text-[color:var(--color-brand-400)] md:block" aria-hidden="true">
                                <x-icon name="arrow-right" class="w-4 h-4" />
                            </span>
                        @endunless
                    </li>
                @endforeach
            </ol>

            <div class="reveal mx-auto mt-6 flex max-w-2xl items-center justify-center gap-2.5 rounded-md border border-[color:color-mix(in_srgb,var(--color-brand)_35%,transparent)] bg-[color:color-mix(in_srgb,var(--color-brand)_8%,transparent)] px-5 py-4 text-center">
                <x-icon name="check" class="w-5 h-5 shrink-0 text-[color:var(--color-brand)]" />
                <p class="text-sm font-medium text-ink sm:text-base">Sign up this month and the whole of next month is free.</p>
            </div>
        </div>
    </section>

    {{-- Three promises --}}
    <section class="relative border-y border-[color:var(--color-border)] bg-[color:var(--color-bg-soft)] py-14 sm:py-16">
        <div class="pointer-events-none absolute inset-0 bg-dots opacity-[0.35] [mask-image:radial-gradient(ellipse_60%_60%_at_50%_50%,black,transparent)]"></div>
        <h2 class="sr-only">Our take-on promises</h2>

        <div class="relative mx-auto grid max-w-6xl divide-y divide-[color:var(--color-border)] px-5 sm:px-8 md:grid-cols-3 md:divide-x md:divide-y-0">
            @foreach ($promises as $i => [$ico, $title, $desc])
                <div class="reveal flex gap-4 py-6 first:pt-0 last:pb-0 md:px-8 md:py-0 md:first:pl-0 md:last:pr-0" style="transition-delay: {{ $i * 70 }}ms">
                    <span class="{{ $iconTile }}">
                        <x-icon :name="$ico" class="w-5 h-5" />
                    </span>
                    <div>
                        <h3 class="text-base font-semibold text-ink">{{ $title }}</h3>
                        <p class="mt-1 text-sm leading-relaxed text-[color:var(--color-muted)]">{{ $desc }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- Two ways to start --}}
    <section class="relative py-20 sm:py-28">
        <div class="mx-auto max-w-6xl px-5 sm:px-8">
            <x-section-heading eyebrow="Getting started" eyebrow-icon="branch" title='Two ways <span class="text-gradient">to start</span>'>
                Switch over straight away, or use your free month to run CoreX inside your agency before you leave your
                current system. Either way, the month after you sign is free.
            </x-section-heading>

            <div class="mt-14 grid items-start gap-6 lg:grid-cols-2">
                @foreach ($paths as $p => [$ico, $title, $sub, $steps, $primary])
                    <div class="reveal relative overflow-hidden rounded-md bg-[color:var(--color-surface)] p-6 sm:p-8 {{ $primary ? 'border-2 border-[color:var(--color-brand)]' : 'border border-[color:var(--color-border)]' }}"
                         style="transition-delay: {{ $p * 80 }}ms">
                        @if ($primary)
                            <div class="pointer-events-none absolute -right-16 -top-16 h-44 w-44 glow-brand opacity-60"></div>
                        @endif

                        <div class="relative flex items-start gap-4">
                            <span class="{{ $iconTile }}">
                                <x-icon :name="$ico" class="w-5 h-5" />
                            </span>
                            <div>
                                <h3 class="text-xl font-semibold tracking-tight text-ink">{{ $title }}</h3>
                                <p class="mt-1.5 text-sm leading-relaxed text-[color:var(--color-muted)]">{{ $sub }}</p>
                            </div>
                        </div>

                        <ol class="relative mt-8 border-t border-[color:var(--color-border)] pt-8">
                            @foreach ($steps as $n => [$stepTitle, $stepDesc])
                                <li class="relative flex gap-4 pb-7 last:pb-0">
                                    @unless ($loop->last)
                                        <span class="absolute left-5 top-11 bottom-1 w-px -translate-x-1/2 bg-[color:var(--color-border)]" aria-hidden="true"></span>
                                    @endunless
                                    <span class="relative grid h-10 w-10 shrink-0 place-items-center rounded-full border bg-[color:var(--color-surface-2)] font-mono text-xs font-semibold {{ $primary ? $brandBorder.' text-[color:var(--color-brand-600)] dark:text-[color:var(--color-brand-400)]' : 'border-[color:var(--color-border)] text-[color:var(--color-muted)]' }}">{{ str_pad($n + 1, 2, '0', STR_PAD_LEFT) }}</span>
                                    <div class="pt-2">
                                        <p class="text-sm font-semibold text-ink">{{ $stepTitle }}</p>
                                        <p class="mt-1 text-sm leading-relaxed text-[color:var(--color-muted)]">{{ $stepDesc }}</p>
                                    </div>
                                </li>
                            @endforeach
                        </ol>

                        @unless ($primary)
                            <div class="relative mt-8 space-y-3 rounded-md border border-amber-400/60 bg-amber-50 p-4 text-sm leading-relaxed text-amber-900 dark:border-amber-400/30 dark:bg-amber-400/10 dark:text-amber-100">
                                <p>
                                    <span class="font-semibold">Your trial is your free onboarding month.</span> It starts
                                    once you've signed the CoreX agreement, and billing starts the month after, whether or
                                    not you've switched over from your current CRM.
                                </p>
                                <p>
                                    During the trial, CoreX can't feed your listings to Property24 or Private Property. The
                                    portals don't allow the same stock to come from two systems, so portal feeds switch over
                                    only once you've left your current CRM.
                                </p>
                            </div>
                        @endunless
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Who does what --}}
    <section class="relative border-y border-[color:var(--color-border)] bg-[color:var(--color-bg-soft)] py-20 sm:py-28">
        <div class="pointer-events-none absolute inset-0 bg-dots opacity-[0.4] [mask-image:radial-gradient(ellipse_60%_50%_at_50%_40%,black,transparent)]"></div>

        <div class="relative mx-auto max-w-6xl px-5 sm:px-8">
            <x-section-heading eyebrow="Take-on" eyebrow-icon="handshake" title='Who does <span class="text-gradient">what</span>'>
                We do the complicated part so your agency does the simple part.
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

    {{-- FAQ. Native <details> — opens and closes with Enter or Space, no script. --}}
    <section class="relative py-20 sm:py-28">
        <div class="mx-auto max-w-3xl px-5 sm:px-8">
            <x-section-heading eyebrow="FAQ" eyebrow-icon="search" title='Questions <span class="text-gradient">agencies ask</span>' />

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
                Ready to move <span class="text-gradient">to CoreX?</span>
            </h2>
            <p class="reveal mt-4 text-base leading-relaxed text-[color:var(--color-muted)]">
                Talk to us about your agency and we'll plan your onboarding month with you.
            </p>
            <div class="reveal mt-8 flex flex-wrap items-center justify-center gap-3">
                <x-btn href="{{ route('contact') }}" size="lg">
                    Book a call
                    <x-icon name="arrow-right" class="w-4 h-4" />
                </x-btn>
                <x-btn href="{{ route('pricing') }}" size="lg" variant="outline">See pricing</x-btn>
            </div>
        </div>
    </section>
</x-layouts.app>
