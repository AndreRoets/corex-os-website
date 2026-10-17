<x-layouts.app
    :page="$page"
    title="Everything is included — CoreX OS"
    description="Everything CoreX OS does, in one place. Listings, deals, contacts, presentations, compliance, portals and more, all included on every plan. No add-ons, no unlocking modules."
>
    @php
        // One entry per feature. Keep the copy customer-facing; add a feature here
        // to list it, remove it to drop it from the page. Only features that are
        // live and complete belong here.
        $categories = [
            'Listings' => [
                ['My Listings', 'Browse, search and open the agency’s property listings in one place.'],
                ['Add a new listing', 'Create a listing through a step-by-step wizard, with drafts saved as you go.'],
                ['Imported stock from other agencies', 'View listings imported from other agencies, alongside your own stock.'],
                ['Publish a listing live', 'Move a listing from draft to live so it appears on the market.'],
                ['Mark a listing as sold', 'Record a sale against a property in a couple of clicks.'],
                ['Listing live preview', 'See exactly how a listing will look to the public before or after it goes live.'],
                ['Listing editor on mobile', 'View and edit listing details, spaces, linked contacts and compliance status from your phone.'],
            ],
            'Seller Outreach' => [
                ['Seller live link', 'Give a seller a private page that tracks how their property is performing. Revoke it any time.'],
                ['Seller outreach messages', 'Compose and send pitch messages to sellers, or queue them to go out later.'],
                ['Outreach history and opt-outs', 'See every outreach a contact has received, its outcome, and their opt-out or opt-in status.'],
                ['Prospecting outreach', 'Pitch a prospecting listing, link or unlink sellers, mark a primary seller and manage phone numbers.'],
                ['Seller information requests', 'Ask a seller to fill in their details, including by WhatsApp link.'],
                ['Public outreach page', 'A landing page for every outreach message, with opt-out, opt-in, unsubscribe and the agent’s card.'],
            ],
            'Presentations & CMA' => [
                ['Presentation builder', 'Create a seller presentation, add documents and links, and generate it from live data.'],
                ['Presentation analysis and pricing review', 'Review comparable sales and competitor listings, adjust condition and size, and set the recommended price band.'],
                ['Seller presentation page', 'A shareable presentation page for sellers that records views, captures their details and lets them request a refresh.'],
                ['Refresh requests', 'See when a seller asks for a refreshed presentation, and acknowledge, resolve or decline each one.'],
                ['Presentation outcomes', 'See how presentations turned into mandates, and where they were lost.'],
                ['Presentation analytics', 'Follow the full funnel from presentation views to leads to outcomes.'],
                ['CMA tool', 'A standalone comparative market analysis to value any property.'],
            ],
            'Marketing' => [
                ['AI ad copy', 'Generate platform-specific advertising text for a property from its listing facts.'],
                ['Social media posting', 'Post a property advertisement to your connected social media accounts.'],
                ['Social account connections and insights', 'Connect social accounts, choose a page, refresh post statistics and disconnect when you need to.'],
            ],
            'Prospecting & Intelligence' => [
                ['Tracked properties', 'Track properties that aren’t listed yet, with their history and owner information.'],
                ['Promote to a listing', 'Turn a tracked property into a full listing in your agency’s stock.'],
                ['Notes on tracked properties', 'Add, edit and delete notes on any tracked property.'],
                ['Opportunities board', 'A ranked list of properties and listings your agency could pursue, with detail for each.'],
                ['Daily prospecting queue', 'Your day’s list of listings to contact and follow up.'],
                ['Market pulse', 'Market statistics for areas and price segments, built from Property24 data.'],
                ['Deeds capture', 'Review deed-registry captures, promote them into your records, dismiss them or resolve conflicts.'],
            ],
            'Maps' => [
                ['Property and market pins', 'See listings, sold properties and tracked properties as pins on an interactive map.'],
                ['Click-through summary cards', 'Open a summary card for any pin: a sold property, an active listing or a scheme owner.'],
                ['Presentation pins', 'See the comparables and competitor properties used in a presentation, on the map.'],
                ['Saved map searches', 'Save a map search and reopen it whenever you need it.'],
                ['Follow-up activity on the map', 'Log follow-up activity against any pin.'],
            ],
            'Tools & Calculators' => [
                ['Property evaluation certificates', 'Prepare a signed valuation certificate, send it for authorisation, download it and share a public view link.'],
            ],
            'Website & Public Search' => [
                ['Agency website listing selection', 'Choose which listings appear on your agency website, and activate, deactivate or refresh them.'],
            ],
            'Portals & Syndication' => [
                ['Property24 publishing', 'Publish a listing to Property24, submit it, check its status, and deactivate or reactivate it.'],
                ['PrivateProperty publishing', 'Publish to PrivateProperty, manage show days and visibility, add a video and check status.'],
                ['Syndication status panel', 'See which portals a listing is published to, and its status on each.'],
            ],
            'Contacts & CRM' => [
                ['Contact records', 'One contact list for sellers, buyers, tenants and everyone else you deal with. Add, edit and archive.'],
                ['Street and complex address search', 'Find contacts by street or complex address, and print the results as a PDF.'],
                ['Duplicate and existing-address warnings', 'Get warned before saving a contact or property address that is already in your records.'],
                ['Contact notes', 'Add, edit and remove dated notes on any contact.'],
                ['Contact documents', 'Upload, view, download, tag and delete documents stored against a contact.'],
                ['Consent to be contacted', 'Record when a contact has agreed to be contacted, and withdraw that consent.'],
                ['Touch log and birthday reminders', 'Log when you’ve contacted someone, set birthday reminders and count the channels you use.'],
                ['Tags, types, sources and labels', 'Keep your own lists of contact tags, types, lead sources and phone or email labels.'],
                ['Linked properties, companies and testimonials', 'Attach a contact to properties and to a company or representative with a role, and record client testimonials.'],
                ['Client app login', 'Create, reset, sign out or remove a client’s login to the client app.'],
                ['Core Matches', 'Match buyers and sellers to properties, track each match, reassign it, share it (including by WhatsApp) and convert it into a deal.'],
            ],
            'Compliance' => [
                ['FICA status on every contact', 'See at a glance whether a contact’s FICA pack is complete, expiring or outstanding, and request or view it from the same screen.'],
            ],
            'Client Portals' => [
                ['Client sign-up by QR code', 'A client scans your agent QR code in the app, registers and is linked to you.'],
                ['Shared match page for buyers', 'Send a buyer a link to their matched properties, where they can give feedback and ask to be re-engaged.'],
            ],
            'Deals & Pipeline' => [
                ['Deal register', 'Every deal in the agency in one register, with search and filters.'],
                ['Pipeline overview and CSV export', 'A summary of deals on the pipeline board for managers, and a CSV export of the deal list.'],
                ['Deal capture, editing and archiving', 'Capture a deal through a guided wizard, then view, edit and archive it.'],
                ['Pipeline steps and stage changes', 'Complete and approve pipeline steps, upload step documents, adjust due dates and confirm suggested stage moves.'],
                ['Deal documents and sending', 'Upload deal documents, choose who receives them, send them out and cancel a send.'],
                ['Secure document links', 'Send parties a link to view or download deal documents, protected by a one-time code.'],
                ['Work orders and certificates of compliance', 'Prepare and send work authorisations and certificate-of-compliance requests to service providers.'],
                ['Supplier and attorney directory', 'Keep your conveyancers, attorneys and service providers with their contacts and service types, and attach them to deals.'],
                ['Service types', 'Define the types of service your suppliers can be linked to.'],
                ['Pipeline templates', 'Design the deal pipeline your agency uses, with templates, steps, ordering and a master template.'],
                ['Settlement and payslip printing', 'Work out the money split on a deal, then print a settlement statement and an agent payslip.'],
            ],
        ];

        $total = array_sum(array_map('count', $categories));

        // The six headline areas for the first screen. Each names an existing
        // $categories key, so the tiles and the full list can never disagree.
        $headline = [
            ['building', 'Listings', 'Every listing, every portal, one click.'],
            ['handshake', 'Deals & Pipeline', 'From offer to settlement, tracked end to end.'],
            ['users', 'Contacts & CRM', 'Every buyer and seller, with consent on record.'],
            ['presentation', 'Presentations & CMA', 'Seller presentations built from live data.'],
            ['shield-check', 'Compliance', 'FICA status on every contact, always current.'],
            ['link', 'Portals & Syndication', 'Property24 and PrivateProperty, straight from the app.'],
        ];

        // What the usual platform does, as the comparison. Kept generic on purpose:
        // this is a positioning line, not a claim about any one competitor.
        $theirs = [
            'A base plan, then an upgrade to unlock modules',
            'Paid add-ons for compliance, e-signature and documents',
            'Extra fees per module, per branch or per integration',
            'A sales call before you can use the feature you need',
        ];
    @endphp

    {{-- Entrance animation for the hero, played once when the page opens. The
         hero uses its own classes rather than .reveal, which waits for scroll. --}}
    <style>
        @keyframes features-rise {
            from { opacity: 0; transform: translateY(22px); filter: blur(6px); }
            to   { opacity: 1; transform: none; filter: blur(0); }
        }
        @keyframes features-glow-in {
            from { opacity: 0; transform: translate(-50%, 12px) scale(0.92); }
            to   { opacity: 0.7; transform: translate(-50%, 0) scale(1); }
        }
        @keyframes features-tile-in {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: none; }
        }
        .fx-rise { opacity: 0; animation: features-rise 900ms cubic-bezier(.2,.7,.2,1) forwards; }
        .fx-glow { animation: features-glow-in 1400ms cubic-bezier(.2,.7,.2,1) both; }
        .fx-tile { opacity: 0; animation: features-tile-in 700ms cubic-bezier(.2,.7,.2,1) forwards; }

        @media (prefers-reduced-motion: reduce) {
            .fx-rise, .fx-tile { opacity: 1; animation: none; }
            .fx-glow { animation: none; opacity: 0.7; }
        }
    </style>

    {{-- Hero: one message, one number, one action --}}
    <section class="relative overflow-hidden pt-28 pb-16 sm:pt-40 sm:pb-24">
        <div class="pointer-events-none absolute inset-0 -z-10 bg-grid opacity-[0.3] [mask-image:radial-gradient(ellipse_70%_60%_at_50%_0%,black,transparent)]"></div>
        <div class="fx-glow pointer-events-none absolute -top-32 left-1/2 -z-10 h-[520px] w-[820px] glow-brand"></div>

        <div class="mx-auto max-w-4xl px-5 sm:px-8 text-center">
            <p class="fx-rise font-mono text-xs uppercase tracking-[0.2em] text-[color:var(--color-brand-400)]" style="animation-delay: 150ms">No tiers · No add-ons · No upsells</p>

            <h1 class="fx-rise mt-6 text-5xl sm:text-6xl md:text-7xl font-semibold leading-[1.02] tracking-tight text-balance" style="animation-delay: 300ms">
                Everything is included.<br>
                <span class="text-gradient">Always.</span>
            </h1>

            <div class="fx-rise mt-10 inline-flex items-baseline gap-3" style="animation-delay: 550ms">
                <span id="features-count" data-count="{{ $total }}" class="text-7xl sm:text-8xl font-semibold tracking-tight text-ink tabular-nums">{{ $total }}</span>
                <span class="text-left text-sm leading-snug text-[color:var(--color-muted)]">features<br>on every agency</span>
            </div>

            <div class="fx-rise mt-10 flex flex-wrap justify-center gap-3" style="animation-delay: 750ms">
                <x-btn href="{{ route('home') }}#demo" size="lg">Book a demo</x-btn>
                <x-btn href="#all-features" size="lg" variant="ghost">See every feature</x-btn>
            </div>
        </div>
    </section>

    {{-- Headline areas: the focal points --}}
    <section class="mx-auto max-w-6xl px-5 sm:px-8 pb-20 sm:pb-28">
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($headline as $i => [$icon, $area, $line])
                <a href="#{{ Str::slug($area) }}"
                   class="fx-tile card group relative flex flex-col overflow-hidden p-7 transition duration-300 hover:-translate-y-1 hover:border-[color:var(--color-brand)]"
                   style="animation-delay: {{ 900 + $i * 90 }}ms">
                    <div class="pointer-events-none absolute -right-10 -top-10 h-28 w-28 glow-brand opacity-0 transition duration-300 group-hover:opacity-70"></div>
                    <span class="grid h-12 w-12 place-items-center rounded-md bg-[color:var(--color-surface-2)] text-[color:var(--color-brand-400)] ring-1 ring-inset ring-[color:var(--color-border)]">
                        <x-icon :name="$icon" class="w-5 h-5" />
                    </span>
                    <h2 class="mt-6 text-xl font-semibold text-ink">{{ $area }}</h2>
                    <p class="mt-2 text-sm leading-relaxed text-[color:var(--color-muted)]">{{ $line }}</p>
                    <span class="mt-6 font-mono text-xs text-[color:var(--color-faint)]">{{ count($categories[$area]) }} features</span>
                </a>
            @endforeach
        </div>
    </section>

    {{-- The poke: the usual model vs. ours, kept to one short band --}}
    <section class="border-y border-[color:var(--color-border)] bg-[color:var(--color-bg-soft)]">
        <div class="mx-auto grid max-w-6xl gap-10 px-5 py-14 sm:px-8 md:grid-cols-2 md:py-20">
            <div class="reveal">
                <p class="font-mono text-xs uppercase tracking-wider text-[color:var(--color-faint)]">Other platforms</p>
                <ul class="mt-5 space-y-3">
                    @foreach ($theirs as $line)
                        <li class="flex items-start gap-3 text-base text-[color:var(--color-muted)]">
                            <x-icon name="x-alt" class="mt-1 w-4 h-4 shrink-0 text-[color:var(--color-faint)]" />
                            <span>{{ $line }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="reveal">
                <p class="font-mono text-xs uppercase tracking-wider text-[color:var(--color-brand-400)]">CoreX OS</p>
                <p class="mt-5 text-3xl font-semibold leading-tight tracking-tight text-ink text-balance">
                    Every module, every agency, <span class="text-gradient">from day one.</span>
                </p>
                <p class="mt-4 text-base leading-relaxed text-[color:var(--color-muted)]">
                    Compliance, e-signature, documents, Ellie AI, portal syndication and a free agency website. Nothing to upgrade to.
                </p>
            </div>
        </div>
    </section>

    {{-- The full list: closed by default so the page stays short --}}
    <section id="all-features" class="mx-auto max-w-4xl scroll-mt-24 px-5 sm:px-8 py-20 sm:py-28">
        <div class="reveal text-center">
            <h2 class="text-3xl sm:text-4xl font-semibold tracking-tight text-ink text-balance">
                The full list. <span class="text-gradient">Ticked, every one.</span>
            </h2>
            <p class="mt-4 text-sm text-[color:var(--color-muted)]">Open an area to see everything in it.</p>
        </div>

        <div class="mt-12 space-y-3">
            @foreach ($categories as $name => $items)
                <details id="{{ Str::slug($name) }}" class="reveal card group scroll-mt-24 overflow-hidden">
                    <summary class="flex cursor-pointer list-none items-center justify-between gap-4 p-5 transition duration-300 hover:bg-[color:var(--color-surface-2)]">
                        <span class="flex items-baseline gap-3">
                            <span class="text-base font-semibold text-ink">{{ $name }}</span>
                            <span class="font-mono text-xs text-[color:var(--color-faint)]">{{ count($items) }}</span>
                        </span>
                        <x-icon name="chevron-down" class="w-4 h-4 shrink-0 text-[color:var(--color-muted)] transition duration-300 group-open:rotate-180" />
                    </summary>

                    <ul class="grid gap-4 border-t border-[color:var(--color-border)] p-5 sm:grid-cols-2">
                        @foreach ($items as [$featureName, $featureDesc])
                            <li class="flex items-start gap-3">
                                <x-icon name="check" class="mt-0.5 w-4 h-4 shrink-0 text-[color:var(--color-brand)]" />
                                <div>
                                    <p class="text-sm font-medium text-ink">{{ $featureName }}</p>
                                    <p class="mt-0.5 text-xs leading-relaxed text-[color:var(--color-muted)]">{{ $featureDesc }}</p>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </details>
            @endforeach
        </div>
    </section>

    {{-- Counts the feature total up from zero as the hero rises in. Shows the
         final number straight away when motion is reduced. --}}
    <script>
        (function () {
            var counter = document.getElementById('features-count');
            var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            if (counter && !reduce) {
                var end = parseInt(counter.dataset.count, 10) || 0;
                var start = null;
                var duration = 1100;
                counter.textContent = '0';

                function step(now) {
                    if (start === null) start = now;
                    var t = Math.min((now - start) / duration, 1);
                    var eased = 1 - Math.pow(1 - t, 3);
                    counter.textContent = Math.round(end * eased);
                    if (t < 1) requestAnimationFrame(step);
                }
                // Start after the hero's number has begun to rise in.
                setTimeout(function () { requestAnimationFrame(step); }, 550);
            }
        })();
    </script>

    {{-- A headline tile opens its area in the full list, rather than landing on a closed one. --}}
    <script>
        (function () {
            function openFromHash() {
                var target = document.getElementById(location.hash.slice(1));
                if (target && target.tagName === 'DETAILS') target.open = true;
            }
            window.addEventListener('hashchange', openFromHash);
            openFromHash();
        })();
    </script>

    {{-- Closing call to action --}}
    <section class="relative overflow-hidden border-t border-[color:var(--color-border)]">
        <div class="pointer-events-none absolute inset-0 -z-10 glow-brand opacity-40"></div>
        <div class="mx-auto max-w-3xl px-5 sm:px-8 py-24 sm:py-32 text-center">
            <h2 class="reveal text-4xl sm:text-5xl font-semibold tracking-tight text-ink text-balance">
                Everything is included. <span class="text-gradient">Always.</span>
            </h2>
            <p class="reveal mt-5 text-base sm:text-lg leading-relaxed text-[color:var(--color-muted)]">
                Book a demo and we’ll walk through the modules your agency will use most.
            </p>
            <div class="reveal mt-9 flex flex-wrap justify-center gap-3">
                <x-btn href="{{ route('home') }}#demo" size="lg">Book a demo</x-btn>
                <x-btn href="{{ route('pricing') }}" size="lg" variant="ghost">See pricing</x-btn>
            </div>
        </div>
    </section>
</x-layouts.app>
