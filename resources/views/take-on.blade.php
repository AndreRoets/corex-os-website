<x-layouts.app
    :page="$page"
    title="Moving to CoreX — how take-on works"
    description="How agencies move to CoreX OS: no take-on fee, your onboarding month free, and we handle the migration from your current system."
>
    @php
        // $months comes from PageController::takeOn() — this month and the two after it.
        $timeline = [
            ['Month 1', $months[0], 'You sign up', 'Sign the CoreX agreement and give notice to your current system.'],
            ['Month 2', $months[1], 'Onboarding month', 'We migrate your data, set up your agency and train your team.'],
            ['Month 3', $months[2], 'First billed month', "You're live on CoreX. Billing starts, monthly in advance."],
        ];

        $promises = [
            ['No take-on fee', "We don't charge to set you up or move your data."],
            ['One month free', 'The month after you sign is your onboarding month, on us.'],
            ['We do the migration', 'No import templates for you to fill in. We pull, map and load your data.'],
        ];

        $goLiveSteps = [
            ['Sign up with CoreX', 'Short agreement, no take-on fee.'],
            ['Give notice to your current CRM', 'Cancel with your current system or feed provider. Your Property24 and Private Property subscriptions stay as they are.'],
            ['We contact Property24', 'We request the transfer document from Property24 on your behalf.'],
            ['You sign the Property24 document', "Once it's signed, Property24 releases your listing data to us."],
            ['We migrate and set you up', 'Listings imported from Property24. Contacts and other records exported from your current system and mapped into CoreX. Branches, users, branding and documents configured.'],
            ['Train and go live', 'Your team is trained, your portal feeds run from CoreX, and billing starts the following month.'],
        ];

        $trialSteps = [
            ['Sign up with CoreX', 'Same agreement, same free month, no take-on fee.'],
            ['Use CoreX inside your agency', 'Your team works in CoreX for the month: contacts, properties, documents, e-signing and reporting.'],
            ['Decide', "When you're ready, give notice to your current CRM and we follow the go-live steps to switch your portal feeds over."],
        ];

        $corexHandles = [
            'Requesting your listing data from Property24',
            'Importing your listings and linking them to the right agents and branches',
            'Exporting contacts and other records from your current system and mapping them into CoreX',
            'Setting up branches, users, roles and agency settings',
            'Your branding, document templates and e-sign setup',
            'Switching your portal feeds to CoreX',
            'Training your team',
        ];

        $youHandle = [
            'Signing the CoreX agreement',
            'Giving notice to your current CRM or feed provider',
            'Signing the Property24 transfer document',
            'Giving us access to export from your current system',
            "Your team's time for training",
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
    @endphp

    {{-- Hero. Starts below the fixed header so the header keeps its usual
         page background and contrast at the top of the page. --}}
    <section class="relative mt-16 overflow-hidden bg-[color:var(--color-navy)] py-16 text-white sm:py-24">
        <div class="mx-auto max-w-5xl px-5 sm:px-8">
            <div class="mx-auto max-w-3xl text-center">
                <h1 class="text-4xl font-semibold leading-[1.05] tracking-tight text-balance sm:text-5xl md:text-6xl">
                    Moving to CoreX costs you <span class="text-[color:var(--color-cyan)]">nothing to start.</span>
                </h1>
                <p class="mt-6 text-lg leading-relaxed text-white/75">
                    No take-on fee. Your onboarding month is free while we move your listings, contacts and documents
                    across and set CoreX up for your agency. You only pay once you're running.
                </p>
            </div>

            <ol class="mt-12 grid overflow-hidden rounded-md border border-white/15 sm:mt-16 sm:grid-cols-3" aria-label="Your first three months">
                @foreach ($timeline as $i => [$step, $month, $title, $desc])
                    @php $free = $i === 1; @endphp
                    <li class="relative p-6 sm:p-7 {{ $free ? 'bg-[color:var(--color-brand)] text-[color:var(--color-navy)]' : 'bg-white/[0.04]' }} {{ $i > 0 ? 'border-t border-white/15 sm:border-t-0 sm:border-l' : '' }}">
                        @if ($free)
                            <span class="absolute right-5 top-5 rounded-md bg-[color:var(--color-navy)] px-2.5 py-1 text-sm font-semibold text-white sm:right-6 sm:top-6">R0</span>
                        @endif
                        <p class="text-sm {{ $free ? 'font-medium' : 'text-white/60' }}">{{ $step }}</p>
                        <p class="mt-1 text-2xl font-semibold tracking-tight sm:text-3xl" data-month="{{ $i + 1 }}">{{ $month }}</p>
                        <p class="mt-5 font-semibold">{{ $title }}</p>
                        <p class="mt-1.5 text-sm leading-relaxed {{ $free ? '' : 'text-white/70' }}">{{ $desc }}</p>
                    </li>
                @endforeach
            </ol>

            <p class="mt-6 text-center text-sm text-white/70">Sign up this month and the whole of next month is free.</p>
        </div>
    </section>

    {{-- Three promises --}}
    <section class="border-b border-[color:var(--color-border)] py-12 sm:py-16">
        <h2 class="sr-only">Our take-on promises</h2>
        <div class="mx-auto grid max-w-6xl divide-y divide-[color:var(--color-border)] px-5 sm:px-8 md:grid-cols-3 md:divide-x md:divide-y-0">
            @foreach ($promises as [$title, $desc])
                <div class="py-6 md:px-8 md:py-2 md:first:pl-0 md:last:pr-0">
                    <h3 class="text-lg font-semibold tracking-tight text-ink">{{ $title }}</h3>
                    <p class="mt-2 text-sm leading-relaxed text-[color:var(--color-muted)]">{{ $desc }}</p>
                </div>
            @endforeach
        </div>
    </section>

    {{-- Two ways to start --}}
    <section class="py-20 sm:py-28">
        <div class="mx-auto max-w-6xl px-5 sm:px-8">
            <x-section-heading title="Two ways to start">
                Switch over straight away, or use your free month to run CoreX inside your agency before you leave your
                current system. Either way, the month after you sign is free.
            </x-section-heading>

            <div class="mt-14 grid items-start gap-6 lg:grid-cols-2">
                @foreach ([
                    ['Go live straight away', 'For agencies ready to switch. Your notice month with your current provider runs alongside your free onboarding month.', $goLiveSteps, true],
                    ['Try it first', 'For agencies who want to see CoreX working in their own office before they commit to leaving their current system.', $trialSteps, false],
                ] as [$title, $sub, $steps, $primary])
                    <div class="rounded-md bg-[color:var(--color-surface)] p-6 sm:p-8 {{ $primary ? 'border-2 border-[color:var(--color-brand)]' : 'border border-[color:var(--color-border)]' }}">
                        <h3 class="text-xl font-semibold tracking-tight text-ink">{{ $title }}</h3>
                        <p class="mt-2 text-sm leading-relaxed text-[color:var(--color-muted)]">{{ $sub }}</p>

                        <ol class="mt-8">
                            @foreach ($steps as $n => [$stepTitle, $stepDesc])
                                <li class="relative flex gap-4 pb-7 last:pb-0">
                                    @unless ($loop->last)
                                        <span class="absolute left-4 top-9 bottom-1 w-px -translate-x-1/2 bg-[color:var(--color-border)]" aria-hidden="true"></span>
                                    @endunless
                                    <span class="relative grid h-8 w-8 shrink-0 place-items-center rounded-full text-sm font-semibold {{ $primary ? 'bg-[color:var(--color-brand)] text-[color:var(--color-navy)]' : 'border border-[color:var(--color-border)] bg-[color:var(--color-surface-2)] text-ink' }}">{{ $n + 1 }}</span>
                                    <div class="pt-1">
                                        <p class="font-medium text-ink">{{ $stepTitle }}</p>
                                        <p class="mt-1 text-sm leading-relaxed text-[color:var(--color-muted)]">{{ $stepDesc }}</p>
                                    </div>
                                </li>
                            @endforeach
                        </ol>

                        @unless ($primary)
                            <p class="mt-8 rounded-md border border-amber-400/60 bg-amber-50 p-4 text-sm leading-relaxed text-amber-900 dark:border-amber-400/30 dark:bg-amber-400/10 dark:text-amber-100">
                                During the trial, CoreX can't feed your listings to Property24 or Private Property. The
                                portals don't allow the same stock to come from two systems, so portal feeds switch over
                                only once you've left your current CRM. Billing starts from the month after your free month.
                            </p>
                        @endunless
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Who does what --}}
    <section class="border-y border-[color:var(--color-border)] bg-[color:var(--color-bg-soft)] py-20 sm:py-28">
        <div class="mx-auto max-w-5xl px-5 sm:px-8">
            <x-section-heading title="Who does what">
                We do the complicated part so your agency does the simple part.
            </x-section-heading>

            <div class="mt-14 grid gap-12 md:grid-cols-2 md:gap-16">
                @foreach (['CoreX handles' => $corexHandles, 'You handle' => $youHandle] as $heading => $items)
                    <div>
                        <h3 class="text-lg font-semibold tracking-tight text-ink">{{ $heading }}</h3>
                        <ul class="mt-4 divide-y divide-[color:var(--color-border)] border-y border-[color:var(--color-border)]">
                            @foreach ($items as $item)
                                <li class="py-3 text-sm leading-relaxed text-[color:var(--color-muted)]">{{ $item }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>

            <div class="mt-14 flex flex-col gap-4 border-t border-[color:var(--color-border)] pt-10 sm:flex-row sm:items-center sm:gap-8">
                <p class="shrink-0 text-5xl font-semibold tracking-tight text-ink sm:text-6xl">4,753</p>
                <p class="max-w-xl text-sm leading-relaxed text-[color:var(--color-muted)]">
                    Listings moved onto CoreX in a single day when our own multi-branch agency went live. CoreX is built
                    and run inside a working estate agency, so the take-on process is one we've been through ourselves.
                </p>
            </div>
        </div>
    </section>

    {{-- FAQ. Native <details> — opens and closes with Enter or Space, no script. --}}
    <section class="py-20 sm:py-28">
        <div class="mx-auto max-w-3xl px-5 sm:px-8">
            <x-section-heading title="Questions agencies ask" />

            <div class="mt-12 divide-y divide-[color:var(--color-border)] border-y border-[color:var(--color-border)]">
                @foreach ($faqs as [$question, $answer])
                    <details class="group">
                        <summary class="flex cursor-pointer list-none items-center justify-between gap-4 py-5 font-medium text-ink [&::-webkit-details-marker]:hidden">
                            {{ $question }}
                            <x-icon name="chevron-down" class="h-5 w-5 shrink-0 text-[color:var(--color-muted)] transition-transform duration-300 group-open:rotate-180" />
                        </summary>
                        <p class="pb-5 pr-9 text-sm leading-relaxed text-[color:var(--color-muted)]">{!! $answer !!}</p>
                    </details>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Call to action --}}
    <section class="bg-[color:var(--color-navy)] py-20 text-white sm:py-28">
        <div class="mx-auto max-w-3xl px-5 text-center sm:px-8">
            <h2 class="text-3xl font-semibold tracking-tight text-balance sm:text-4xl">Ready to move to CoreX?</h2>
            <p class="mt-4 text-base leading-relaxed text-white/75">
                Talk to us about your agency and we'll plan your onboarding month with you.
            </p>
            <div class="mt-8 flex flex-wrap items-center justify-center gap-3">
                <x-btn href="{{ route('contact') }}" size="lg">
                    Book a call
                    <x-icon name="arrow-right" class="w-4 h-4" />
                </x-btn>
                <a href="{{ route('pricing') }}"
                   class="inline-flex items-center justify-center gap-2 rounded-md border border-white/40 px-6 py-3 text-base font-medium text-white transition duration-300 hover:border-white hover:bg-white/10 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[color:var(--color-brand)]">
                    See pricing
                </a>
            </div>
        </div>
    </section>
</x-layouts.app>
