<x-layouts.app
    title="Pricing — CoreX OS"
    description="Two plans, one upgrade path. CoreX Team is R450 per agent, flat, up to 10 agents. CoreX Agency is R1 495 base + R295 per agent with seats that get cheaper as you grow. Every module, Ellie AI and compliance included."
>
    {{-- Range slider styling, scoped to this page. --}}
    <style>
        .range-brand { -webkit-appearance: none; appearance: none; width: 100%; height: 0.5rem; border-radius: 9999px; outline: none; cursor: pointer; }
        .range-brand::-webkit-slider-thumb { -webkit-appearance: none; appearance: none; width: 1.6rem; height: 1.6rem; border-radius: 9999px; background: var(--color-brand); border: 3px solid var(--color-surface); box-shadow: 0 6px 18px -4px color-mix(in srgb, var(--color-brand) 75%, transparent); transition: transform .2s ease; }
        .range-brand::-webkit-slider-thumb:hover { transform: scale(1.12); }
        .range-brand::-moz-range-thumb { width: 1.6rem; height: 1.6rem; border-radius: 9999px; background: var(--color-brand); border: 3px solid var(--color-surface); box-shadow: 0 6px 18px -4px color-mix(in srgb, var(--color-brand) 75%, transparent); cursor: pointer; }
    </style>

    @php
        $teamFeatures = [
            ['layers', 'Full CoreX OS — all modules, all four pillars'],
            ['sparkles', 'Ellie domain AI included'],
            ['signature', 'DocuPerfect documents + e-signature included'],
            ['shield-check', 'FICA / POPIA / PPRA compliance engine'],
            ['link', 'Portal syndication — P24 &amp; Private Property'],
            ['building', 'Single branch'],
        ];

        $agencyFeatures = [
            ['branch', 'Multi-branch: Split Branches isolation'],
            ['building', 'Extra branches at R750 / branch / month'],
            ['key', 'Per-branch portal credentials &amp; dashboards'],
            ['lock', 'Role Manager at full agency scale'],
            ['sliders', 'Seats get cheaper as you grow (tiers below)'],
        ];

        $seatTiers = [
            ['1 – 10', 'R295', 'Entry rate — the same seat price the base fee is built around.'],
            ['11 – 20', 'R250', 'Every seat past ten drops automatically.'],
            ['21 +', 'R195', 'Volume rate for agencies running at scale.'],
        ];
    @endphp

    {{-- Hero --}}
    <section class="relative overflow-hidden pt-28 pb-12 sm:pt-36 sm:pb-16">
        <div class="pointer-events-none absolute inset-0 -z-10 bg-grid opacity-[0.3] [mask-image:radial-gradient(ellipse_70%_60%_at_50%_0%,black,transparent)]"></div>
        <div class="pointer-events-none absolute -top-32 left-1/2 -z-10 h-[460px] w-[760px] -translate-x-1/2 glow-brand opacity-60"></div>

        <div class="mx-auto max-w-3xl px-5 sm:px-8 text-center">
            <x-eyebrow icon="sliders" class="reveal justify-center">Pricing</x-eyebrow>
            <h1 class="reveal mt-6 text-4xl sm:text-5xl md:text-6xl font-semibold leading-[1.05] tracking-tight text-balance">
                Two plans,<br class="hidden sm:block">
                <span class="text-gradient">one upgrade path.</span>
            </h1>
            <p class="reveal mt-6 text-lg leading-relaxed text-[color:var(--color-muted)]">
                Both models — but as two products, not two prices for the same thing. Small agencies get a simple
                per-seat plan. Growing agencies get the platform plan. The whole operating system is in both.
            </p>
        </div>
    </section>

    {{-- Plan cards --}}
    <section class="relative pb-16 sm:pb-20">
        <div class="mx-auto max-w-6xl px-5 sm:px-8">
            <div class="grid gap-6 lg:grid-cols-2 lg:items-stretch">
                {{-- Team --}}
                <div class="reveal card relative flex flex-col p-7 sm:p-9">
                    <div class="flex items-center gap-3">
                        <span class="grid h-11 w-11 place-items-center rounded-md bg-[color:var(--color-surface-2)] text-[color:var(--color-brand-400)] ring-1 ring-inset ring-[color:var(--color-border)]">
                            <x-icon name="users" class="w-5 h-5" />
                        </span>
                        <div>
                            <h2 class="text-lg font-semibold text-ink">CoreX Team</h2>
                            <p class="font-mono text-xs uppercase tracking-[0.14em] text-[color:var(--color-faint)]">Up to 10 agents</p>
                        </div>
                    </div>

                    <div class="mt-7 flex items-baseline gap-2">
                        <span class="text-5xl font-semibold tracking-tight text-ink">R450</span>
                        <span class="text-sm text-[color:var(--color-muted)]">per agent / month — flat</span>
                    </div>
                    <p class="mt-3 font-mono text-[11px] uppercase tracking-[0.14em] text-[color:var(--color-brand-400)]">
                        One number. No tiers. No base fee. All included.
                    </p>

                    <ul class="mt-7 space-y-3">
                        @foreach ($teamFeatures as [$ico, $label])
                            <li class="flex items-start gap-3">
                                <span class="mt-0.5 grid h-6 w-6 shrink-0 place-items-center rounded-full bg-[color:color-mix(in_srgb,var(--color-brand)_12%,transparent)] text-[color:var(--color-brand)]">
                                    <x-icon name="check" class="w-3.5 h-3.5" />
                                </span>
                                <span class="text-sm leading-relaxed text-[color:var(--color-muted)]">{!! $label !!}</span>
                            </li>
                        @endforeach
                    </ul>

                    <div class="mt-8 rounded-lg border border-[color:var(--color-border)] bg-[color:var(--color-bg-soft)] p-4">
                        <p class="font-mono text-[11px] uppercase tracking-[0.14em] text-[color:var(--color-faint)]">Who it&rsquo;s for</p>
                        <p class="mt-1.5 text-sm leading-relaxed text-[color:var(--color-muted)]">
                            Solo agents and small agencies (1&nbsp;–&nbsp;10 agents) who want one simple number and instant entry.
                        </p>
                    </div>

                    <div class="mt-7 flex-1"></div>
                    <x-btn href="{{ route('home') }}#demo" size="lg" variant="secondary" class="w-full">
                        Start with Team
                        <x-icon name="arrow-right" class="w-4 h-4" />
                    </x-btn>
                </div>

                {{-- Agency --}}
                <div class="reveal card relative flex flex-col p-7 sm:p-9 border-[color:var(--color-brand)] ring-1 ring-[color:color-mix(in_srgb,var(--color-brand)_35%,transparent)]">
                    <span class="absolute right-6 top-6 rounded-full border border-[color:color-mix(in_srgb,var(--color-brand)_45%,var(--color-border))] bg-[color:color-mix(in_srgb,var(--color-brand)_10%,transparent)] px-3 py-1 font-mono text-[10px] uppercase tracking-[0.14em] text-[color:var(--color-brand-400)]">
                        Built to scale
                    </span>
                    <div class="flex items-center gap-3">
                        <span class="grid h-11 w-11 place-items-center rounded-md bg-[color:var(--color-surface-2)] text-[color:var(--color-brand-400)] ring-1 ring-inset ring-[color:var(--color-border)]">
                            <x-icon name="building" class="w-5 h-5" />
                        </span>
                        <div>
                            <h2 class="text-lg font-semibold text-ink">CoreX Agency</h2>
                            <p class="font-mono text-xs uppercase tracking-[0.14em] text-[color:var(--color-faint)]">From 10 agents</p>
                        </div>
                    </div>

                    <div class="mt-7 flex items-baseline gap-2 flex-wrap">
                        <span class="text-5xl font-semibold tracking-tight text-ink">R1&nbsp;495</span>
                        <span class="text-sm text-[color:var(--color-muted)]">base + R295 per agent / month</span>
                    </div>
                    <p class="mt-3 font-mono text-[11px] uppercase tracking-[0.14em] text-[color:var(--color-brand-400)]">
                        Platform fee + affordable seats. Built to scale.
                    </p>

                    <p class="mt-7 text-sm font-semibold text-ink">Everything in Team, plus:</p>
                    <ul class="mt-4 space-y-3">
                        @foreach ($agencyFeatures as [$ico, $label])
                            <li class="flex items-start gap-3">
                                <span class="mt-0.5 grid h-6 w-6 shrink-0 place-items-center rounded-full bg-[color:color-mix(in_srgb,var(--color-brand)_12%,transparent)] text-[color:var(--color-brand)]">
                                    <x-icon name="check" class="w-3.5 h-3.5" />
                                </span>
                                <span class="text-sm leading-relaxed text-[color:var(--color-muted)]">{!! $label !!}</span>
                            </li>
                        @endforeach
                    </ul>

                    <div class="mt-8 rounded-lg border border-[color:var(--color-border)] bg-[color:var(--color-bg-soft)] p-4">
                        <p class="font-mono text-[11px] uppercase tracking-[0.14em] text-[color:var(--color-faint)]">Who it&rsquo;s for</p>
                        <p class="mt-1.5 text-sm leading-relaxed text-[color:var(--color-muted)]">
                            Growing and multi-branch agencies (10+ agents) where the base fee spreads across many seats.
                        </p>
                    </div>

                    <div class="mt-7 flex-1"></div>
                    <x-btn href="{{ route('home') }}#demo" size="lg" class="w-full">
                        Talk to us about Agency
                        <x-icon name="arrow-right" class="w-4 h-4" />
                    </x-btn>
                </div>
            </div>
        </div>
    </section>

    {{-- Calculator --}}
    <section class="relative pb-20 sm:pb-28">
        <div class="mx-auto max-w-5xl px-5 sm:px-8">
            <div class="reveal mb-8 text-center">
                <x-eyebrow icon="sliders" class="justify-center">Size it to your team</x-eyebrow>
                <p class="mt-3 text-sm text-[color:var(--color-muted)]">
                    Slide to your headcount — we&rsquo;ll price both plans and point you at the one that costs less.
                </p>
            </div>

            <div
                class="reveal card overflow-hidden p-6 sm:p-10"
                x-data="{
                    agents: 8,
                    max: 40,
                    branches: 1,
                    annual: false,
                    teamRate: 450,
                    agencyBase: 1495,
                    branchRate: 750,
                    seatCost(n) {
                        const t1 = Math.min(n, 10) * 295;
                        const t2 = Math.min(Math.max(n - 10, 0), 10) * 250;
                        const t3 = Math.max(n - 20, 0) * 195;
                        return t1 + t2 + t3;
                    },
                    get isMaxed() { return this.agents >= this.max; },
                    get teamAvailable() { return this.agents <= 10; },
                    get teamMonthly() { return this.agents * this.teamRate; },
                    get extraBranches() { return Math.max(0, this.branches - 1); },
                    get agencyMonthly() { return this.agencyBase + this.seatCost(this.agents) + this.extraBranches * this.branchRate; },
                    get agencyPerAgent() { return this.seatCost(this.agents) / this.agents; },
                    get recommend() {
                        if (!this.teamAvailable) return 'agency';
                        return this.agencyMonthly < this.teamMonthly ? 'agency' : 'team';
                    },
                    mult() { return this.annual ? 10 : 1; },
                    period() { return this.annual ? '/yr' : '/mo'; },
                    fmt(n) { return 'R' + Math.round(n).toLocaleString('en-ZA'); },
                }"
            >
                {{-- Controls --}}
                <div class="flex flex-col gap-6">
                    <div class="flex items-end justify-between gap-3 sm:gap-4">
                        <div class="min-w-0">
                            <p class="font-mono text-xs uppercase tracking-[0.16em] text-[color:var(--color-faint)]">Agents</p>
                            <p class="mt-1 text-3xl sm:text-4xl font-semibold text-ink">
                                <span x-text="isMaxed ? '40+' : agents"></span>
                                <span class="text-base sm:text-lg font-normal text-[color:var(--color-muted)]">agent<span x-show="agents !== 1">s</span></span>
                            </p>
                        </div>
                        {{-- Billing toggle --}}
                        <div class="inline-flex shrink-0 rounded-md border border-[color:var(--color-border)] bg-[color:var(--color-surface)] p-1 text-sm">
                            <button type="button" @click="annual = false"
                                    class="rounded px-3.5 py-1.5 font-medium transition duration-300"
                                    :class="!annual ? 'bg-[color:var(--color-brand)] text-white' : 'text-[color:var(--color-muted)] hover:text-ink'">Monthly</button>
                            <button type="button" @click="annual = true"
                                    class="rounded px-3.5 py-1.5 font-medium transition duration-300"
                                    :class="annual ? 'bg-[color:var(--color-brand)] text-white' : 'text-[color:var(--color-muted)] hover:text-ink'">Annual</button>
                        </div>
                    </div>

                    <div>
                        <input
                            type="range" min="1" :max="max" step="1"
                            x-model.number="agents"
                            class="range-brand"
                            :style="`background: linear-gradient(to right, var(--color-brand) 0%, var(--color-brand) ${((agents - 1) / (max - 1)) * 100}%, var(--color-surface-2) ${((agents - 1) / (max - 1)) * 100}%, var(--color-surface-2) 100%)`"
                            aria-label="Number of agents"
                        >
                        <div class="mt-2 flex justify-between font-mono text-[11px] text-[color:var(--color-faint)]">
                            <span>1</span><span>10</span><span>20</span><span>40+</span>
                        </div>
                    </div>

                    {{-- Extra branches (Agency only) --}}
                    <div class="flex flex-wrap items-center justify-between gap-3 rounded-lg border border-[color:var(--color-border)] bg-[color:var(--color-bg-soft)] px-4 py-3">
                        <div class="flex items-center gap-3">
                            <span class="grid h-8 w-8 shrink-0 place-items-center rounded-md bg-[color:var(--color-surface-2)] text-[color:var(--color-brand-400)] ring-1 ring-inset ring-[color:var(--color-border)]">
                                <x-icon name="branch" class="w-4 h-4" />
                            </span>
                            <div>
                                <p class="text-sm font-medium text-ink">Branches</p>
                                <p class="text-xs text-[color:var(--color-muted)]">Agency only · first branch included · R750 each extra</p>
                            </div>
                        </div>
                        <div class="inline-flex items-center gap-1 rounded-md border border-[color:var(--color-border)] bg-[color:var(--color-surface)] p-1">
                            <button type="button" @click="branches = Math.max(1, branches - 1)" aria-label="Fewer branches"
                                    class="grid h-7 w-7 place-items-center rounded text-[color:var(--color-muted)] hover:text-ink hover:bg-[color:var(--color-surface-2)] transition">&minus;</button>
                            <span class="w-8 text-center font-mono text-sm text-ink" x-text="branches"></span>
                            <button type="button" @click="branches = Math.min(20, branches + 1)" aria-label="More branches"
                                    class="grid h-7 w-7 place-items-center rounded text-[color:var(--color-muted)] hover:text-ink hover:bg-[color:var(--color-surface-2)] transition">+</button>
                        </div>
                    </div>
                </div>

                <div class="my-8 h-px bg-[color:var(--color-border)]"></div>

                {{-- Results --}}
                <div class="grid gap-4 sm:grid-cols-2">
                    {{-- Team result --}}
                    <div class="relative rounded-xl border p-5 transition duration-300"
                         :class="recommend === 'team' && teamAvailable
                            ? 'border-[color:var(--color-brand)] bg-[color:color-mix(in_srgb,var(--color-brand)_7%,transparent)]'
                            : 'border-[color:var(--color-border)] bg-[color:var(--color-bg-soft)]'"
                         :style="teamAvailable ? '' : 'opacity:0.5'">
                        <div class="flex items-center justify-between gap-2">
                            <span class="text-sm font-semibold text-ink">CoreX Team</span>
                            <span x-show="recommend === 'team' && teamAvailable" x-cloak
                                  class="rounded-full bg-[color:var(--color-brand)] px-2.5 py-0.5 font-mono text-[10px] uppercase tracking-[0.12em] text-white">Best value</span>
                        </div>
                        <template x-if="teamAvailable">
                            <div>
                                <p class="mt-3 text-3xl font-semibold tracking-tight text-ink">
                                    <span x-text="fmt(teamMonthly * mult())"></span>
                                    <span class="text-sm font-normal text-[color:var(--color-muted)]" x-text="period()"></span>
                                </p>
                                <p class="mt-1.5 text-xs text-[color:var(--color-muted)]">
                                    R450 × <span x-text="agents"></span> agent<span x-show="agents !== 1">s</span> · flat, no base fee
                                </p>
                            </div>
                        </template>
                        <template x-if="!teamAvailable">
                            <p class="mt-3 text-sm leading-relaxed text-[color:var(--color-muted)]">
                                Team caps at 10 agents. Above that, the Agency platform plan takes over.
                            </p>
                        </template>
                    </div>

                    {{-- Agency result --}}
                    <div class="relative rounded-xl border p-5 transition duration-300"
                         :class="recommend === 'agency'
                            ? 'border-[color:var(--color-brand)] bg-[color:color-mix(in_srgb,var(--color-brand)_7%,transparent)]'
                            : 'border-[color:var(--color-border)] bg-[color:var(--color-bg-soft)]'">
                        <div class="flex items-center justify-between gap-2">
                            <span class="text-sm font-semibold text-ink">CoreX Agency</span>
                            <span x-show="recommend === 'agency'" x-cloak
                                  class="rounded-full bg-[color:var(--color-brand)] px-2.5 py-0.5 font-mono text-[10px] uppercase tracking-[0.12em] text-white">Best value</span>
                        </div>
                        <p class="mt-3 text-3xl font-semibold tracking-tight text-ink">
                            <span x-text="fmt(agencyMonthly * mult())"></span>
                            <span class="text-sm font-normal text-[color:var(--color-muted)]" x-text="period()"></span>
                        </p>
                        <p class="mt-1.5 text-xs text-[color:var(--color-muted)]">
                            R1&nbsp;495 base + <span x-text="fmt(seatCost(agents))"></span> seats<span x-show="extraBranches > 0" x-cloak> + <span x-text="fmt(extraBranches * branchRate)"></span> branches</span>
                        </p>
                        <p class="mt-1 text-xs text-[color:var(--color-faint)]">
                            blended <span x-text="fmt(agencyPerAgent)"></span> / agent
                        </p>
                    </div>
                </div>

                <div class="mt-8 flex flex-col items-center gap-3 sm:flex-row sm:justify-center">
                    <x-btn href="{{ route('home') }}#demo" size="lg" class="shrink-0">
                        Book a demo
                        <x-icon name="arrow-right" class="w-4 h-4" />
                    </x-btn>
                </div>
            </div>
        </div>
    </section>

    {{-- Agency seat tiers --}}
    <section class="relative py-20 sm:py-28 bg-[color:var(--color-bg-soft)] border-y border-[color:var(--color-border)]">
        <div class="mx-auto max-w-5xl px-5 sm:px-8">
            <x-section-heading
                eyebrow="Agency plan seat tiers"
                eyebrow-icon="sliders"
                title='Growth is <span class="text-gradient">rewarded automatically.</span>'
            >
                On the Agency plan, seats get cheaper as you add them — the rate steps down by band and blends across
                your team. No renegotiation, no request. It just happens as you grow.
            </x-section-heading>

            <div class="mt-14 grid gap-4 sm:grid-cols-3">
                @foreach ($seatTiers as $i => [$range, $price, $desc])
                    <div class="reveal card p-6">
                        <div class="flex items-center justify-between">
                            <p class="font-mono text-xs uppercase tracking-[0.14em] text-[color:var(--color-faint)]">Agents {{ $range }}</p>
                            @if ($i === 2)
                                <span class="rounded-full border border-[color:color-mix(in_srgb,var(--color-brand)_45%,var(--color-border))] bg-[color:color-mix(in_srgb,var(--color-brand)_10%,transparent)] px-2.5 py-0.5 font-mono text-[10px] uppercase tracking-[0.12em] text-[color:var(--color-brand-400)]">Best rate</span>
                            @endif
                        </div>
                        <p class="mt-4 text-4xl font-semibold tracking-tight text-ink">{{ $price }}<span class="text-base font-normal text-[color:var(--color-muted)]"> / agent</span></p>
                        <p class="mt-3 text-sm leading-relaxed text-[color:var(--color-muted)]">{{ $desc }}</p>
                    </div>
                @endforeach
            </div>

            <p class="reveal mt-8 text-center text-sm text-[color:var(--color-muted)]">
                Tiers are <span class="font-medium text-ink">graduated</span> — your first ten seats bill at R295, the
                next ten at R250, the rest at R195. Adding an agent always lowers your average, never your total.
            </p>
        </div>
    </section>

    {{-- Final CTA --}}
    <section class="relative overflow-hidden py-20 sm:py-28">
        <div class="pointer-events-none absolute left-1/2 top-0 -z-10 h-[360px] w-[680px] -translate-x-1/2 glow-brand opacity-50"></div>
        <div class="mx-auto max-w-3xl px-5 sm:px-8 text-center">
            <h2 class="reveal text-3xl sm:text-4xl font-semibold tracking-tight text-ink text-balance">
                See it on your own deals <span class="text-gradient">before you decide.</span>
            </h2>
            <p class="reveal mt-4 text-base leading-relaxed text-[color:var(--color-muted)]">
                A live walkthrough on scenarios you recognise — then the plan that fits where your agency is today,
                with a clear path to where it&rsquo;s going.
            </p>
            <div class="reveal mt-8 flex flex-wrap items-center justify-center gap-3">
                <x-btn href="{{ route('home') }}#demo" size="lg">
                    Book a demo
                    <x-icon name="arrow-right" class="w-4 h-4" />
                </x-btn>
                <x-btn href="{{ route('home') }}" size="lg" variant="secondary">Back to overview</x-btn>
            </div>
        </div>
    </section>
</x-layouts.app>
