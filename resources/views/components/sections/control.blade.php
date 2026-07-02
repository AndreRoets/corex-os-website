@php
    $configurable = [
        'Property types', 'Listing statuses', 'Mandate types', 'Contact types',
        'Deal types', 'Pipeline stages', 'Document categories', 'Compliance checklist items',
    ];

    $settingsGroups = ['Company & Branches', 'Users & Roles', 'Properties', 'Contacts', 'Deals', 'Documents', 'Compliance', 'System'];

    $faqs = [
        ['Is CoreX a CRM or a listing portal?', 'Neither on its own. CoreX is the operating system for the whole agency — listings, contacts, deals, documents, signing, compliance and AI in one graph. It replaces the CRM, the portal workflow and the document tools, rather than sitting alongside them.'],
        ['Can any agency use CoreX?', 'CoreX was built for all real estate agencies and architected to be licensed to any of them. It is multi-tenant by design — your agency&rsquo;s data, branding and branches stay your own.'],
        ['Does it work with Property24?', 'Yes. CoreX parses Property24 listing emails to extract detail and suburb automatically, and syndicates your listings back to the portal — while the deal itself lives in CoreX. Each branch can even syndicate under its own portal credentials.'],
        ['Are the documents legally sound?', 'Documents are authored in HTML/Blade and rendered to PDF through real Chrome (Puppeteer) for legal-grade fidelity, with electronic signing (canvas capture, sequential signing, identity gates) or wet-ink scan &amp; flatten for paper.'],
        ['How does it handle South African compliance?', 'FICA, POPIA and PPRA/FFC tracking are built into the workflow. The system follows the Property Practitioners Act 22 of 2019 and treats the PPRA — not the dissolved EAAB — as the regulator.'],
    ];
@endphp

<section id="control" class="relative py-20 sm:py-28 bg-[color:var(--color-bg-soft)] border-y border-[color:var(--color-border)]">
    <div class="mx-auto max-w-7xl px-5 sm:px-8">
        <x-section-heading
            eyebrow="Total control"
            eyebrow-icon="lock"
            title='Your agency. Your rules. <span class="text-gradient">Your system.</span>'
        >
            CoreX doesn&rsquo;t lock you into someone else&rsquo;s workflow. You configure the system to fit exactly how you run
            your business — and control precisely who can see and do what. Configured by you, not hardcoded by us.
        </x-section-heading>

        {{-- Story 1 — Role Manager + permission matrix --}}
        <div class="mt-16 grid gap-10 lg:grid-cols-2 lg:items-center">
            <div class="reveal">
                <p class="font-mono text-xs uppercase tracking-[0.18em] text-[color:var(--color-brand-400)]">01 · The Role Manager</p>
                <h3 class="mt-3 text-2xl font-semibold tracking-tight text-ink">Granular permissions, down to the last capability</h3>
                <p class="mt-3 text-[15px] leading-relaxed text-[color:var(--color-muted)]">
                    Define your own roles — principal, agency admin, branch manager, agent, or anything custom — and tick
                    precisely what each one can do. Every capability is a separate key: <span class="text-ink">view, create, edit, delete, manage, oversight</span>. Nothing is all-or-nothing.
                </p>
                <ul class="mt-5 space-y-3">
                    @foreach ([
                        'A branch manager can be given oversight of their branch — stalled deals, expiring mandates, ignored notifications — but nothing agency-wide.',
                        'An agent sees only their own work; a principal sees everything.',
                        'Every new capability ships with its own permission keys — gated in the sidebar, on the route and in the controller.',
                    ] as $point)
                        <li class="flex items-start gap-3 text-sm text-[color:var(--color-muted)]">
                            <x-icon name="check" class="mt-0.5 w-4 h-4 shrink-0 text-[color:var(--color-brand)]" />
                            {{ $point }}
                        </li>
                    @endforeach
                </ul>
                <p class="mt-5 rounded-md border border-[color:var(--color-border)] bg-[color:var(--color-surface)] px-4 py-3 text-sm text-ink">
                    The <span class="text-[color:var(--color-brand-400)]">agency admin</span> — not the software vendor — decides who touches what. The Role Manager is the single source of truth.
                </p>
            </div>
            <div class="reveal">
                <x-permission-matrix />
            </div>
        </div>

        {{-- Story 2 — Multi-tenancy & branch isolation + diagram --}}
        <div class="mt-16 grid gap-10 lg:grid-cols-2 lg:items-center">
            <div class="reveal lg:order-2">
                <p class="font-mono text-xs uppercase tracking-[0.18em] text-[color:var(--color-brand-400)]">02 · Multi-tenancy &amp; branches</p>
                <h3 class="mt-3 text-2xl font-semibold tracking-tight text-ink">Hard data boundaries — as open or walled-off as you choose</h3>
                <p class="mt-3 text-[15px] leading-relaxed text-[color:var(--color-muted)]">
                    Every agency is a hard, structural boundary — one agency can never see another&rsquo;s data. Running multiple
                    branches? Switch on <span class="text-ink">Split Branches</span> for a second isolation layer so Branch A&rsquo;s
                    agents can&rsquo;t see Branch B&rsquo;s contacts, properties, deals, documents or tasks.
                </p>
                <ul class="mt-5 space-y-3">
                    @foreach ([
                        ['refresh', 'Principal opt-in and reversible — ships off by default, flip it on or off at will with no data loss either way.'],
                        ['eye', 'Principals see across all branches, and a “View as Branch” switcher drops them into any branch and back out cleanly.'],
                        ['handshake', 'Cross-branch deals can be shared (originator + co-branch) with commission splits tracked.'],
                        ['globe', 'Per-branch portal syndication — each branch pushes to Property24 / Private Property on its own credentials, or the agency’s.'],
                    ] as [$ico, $point])
                        <li class="flex items-start gap-3 text-sm text-[color:var(--color-muted)]">
                            <x-icon :name="$ico" class="mt-0.5 w-4 h-4 shrink-0 text-[color:var(--color-brand-400)]" />
                            {{ $point }}
                        </li>
                    @endforeach
                </ul>
                <p class="mt-5 text-sm font-medium text-ink">One agency, many branches — perfectly partitioned, or wide open. The principal chooses.</p>
            </div>
            <div class="reveal lg:order-1">
                <x-branch-diagram />
            </div>
        </div>

        {{-- Stories 3 & 4 — feature cards --}}
        <div class="mt-16 grid gap-4 lg:grid-cols-2">
            {{-- 3 · Nothing is hardcoded --}}
            <div class="reveal card p-6 sm:p-7">
                <div class="flex items-center gap-3">
                    <span class="grid h-11 w-11 place-items-center rounded-md bg-[color:var(--color-surface-2)] text-[color:var(--color-brand-400)] ring-1 ring-inset ring-[color:var(--color-border)]">
                        <x-icon name="sliders" class="w-5 h-5" />
                    </span>
                    <div>
                        <p class="font-mono text-xs uppercase tracking-[0.18em] text-[color:var(--color-brand-400)]">03 · Nothing hardcoded</p>
                        <h3 class="text-lg font-semibold text-ink">The agency configures its own language</h3>
                    </div>
                </div>
                <p class="mt-4 text-sm leading-relaxed text-[color:var(--color-muted)]">
                    Every dropdown, status, type and category comes from your own settings — never a fixed list baked into the
                    code. Change any of them anytime, no developer required.
                </p>
                <div class="mt-4 flex flex-wrap gap-1.5">
                    @foreach ($configurable as $item)
                        <span class="rounded border border-[color:var(--color-border)] bg-[color:var(--color-bg-soft)] px-2 py-0.5 font-mono text-[11px] text-[color:var(--color-muted)]">{{ $item }}</span>
                    @endforeach
                </div>
                <p class="mt-5 text-xs text-[color:var(--color-faint)]">One settings area, grouped by module:</p>
                <div class="mt-2 flex flex-wrap gap-1.5">
                    @foreach ($settingsGroups as $group)
                        <span class="inline-flex items-center gap-1 text-xs text-[color:var(--color-muted)]">
                            <span class="h-1 w-1 rounded-full bg-[color:var(--color-brand)]"></span>{{ $group }}
                        </span>
                    @endforeach
                </div>
                <p class="mt-5 text-sm font-medium text-ink">CoreX adapts to your process — you don&rsquo;t adapt to CoreX.</p>
            </div>

            {{-- 4 · Full audit & recoverability --}}
            <div class="reveal card p-6 sm:p-7">
                <div class="flex items-center gap-3">
                    <span class="grid h-11 w-11 place-items-center rounded-md bg-[color:var(--color-surface-2)] text-[color:var(--color-brand-400)] ring-1 ring-inset ring-[color:var(--color-border)]">
                        <x-icon name="archive" class="w-5 h-5" />
                    </span>
                    <div>
                        <p class="font-mono text-xs uppercase tracking-[0.18em] text-[color:var(--color-brand-400)]">04 · Audit &amp; recovery</p>
                        <h3 class="text-lg font-semibold text-ink">Nothing is ever truly lost</h3>
                    </div>
                </div>
                <p class="mt-4 text-sm leading-relaxed text-[color:var(--color-muted)]">
                    Total control means total accountability — every action is reversible and traceable.
                </p>
                <ul class="mt-4 space-y-3">
                    @foreach ([
                        ['undo', 'Soft deletes', 'Every “delete” archives the record instead of destroying it — an admin can recover it.'],
                        ['shield-check', 'Attributable log', 'Actions are logged and tied to the person who took them.'],
                        ['users', 'Nudge, don’t override', 'Managers can prompt agents on outstanding items without silently taking over the work.'],
                    ] as [$ico, $t, $d])
                        <li class="flex items-start gap-3">
                            <span class="mt-0.5 grid h-8 w-8 shrink-0 place-items-center rounded-md bg-[color:var(--color-surface-2)] text-[color:var(--color-brand-400)] ring-1 ring-inset ring-[color:var(--color-border)]">
                                <x-icon :name="$ico" class="w-4 h-4" />
                            </span>
                            <span class="text-sm text-[color:var(--color-muted)]"><span class="font-medium text-ink">{{ $t }}.</span> {{ $d }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

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
