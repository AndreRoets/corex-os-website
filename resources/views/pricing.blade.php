<x-layouts.app
    :page="$page"
    title="Pricing — CoreX OS"
    description="One price per band, unlimited agents within it. From R450 a month for one agent to R5,095 for up to 30, set automatically by your active agents. Agency website, data take-on, portal feeds, e-sign and training included, no setup fees."
>
    {{-- Range slider styling, scoped to this page. --}}
    <style>
        .range-brand { -webkit-appearance: none; appearance: none; width: 100%; height: 0.5rem; border-radius: 9999px; outline: none; cursor: pointer; }
        .range-brand::-webkit-slider-thumb { -webkit-appearance: none; appearance: none; width: 1.6rem; height: 1.6rem; border-radius: 9999px; background: var(--color-brand); border: 3px solid var(--color-surface); box-shadow: 0 6px 18px -4px color-mix(in srgb, var(--color-brand) 75%, transparent); transition: transform .2s ease; }
        .range-brand::-webkit-slider-thumb:hover { transform: scale(1.12); }
        .range-brand::-moz-range-thumb { width: 1.6rem; height: 1.6rem; border-radius: 9999px; background: var(--color-brand); border: 3px solid var(--color-surface); box-shadow: 0 6px 18px -4px color-mix(in srgb, var(--color-brand) 75%, transparent); cursor: pointer; }

        /* Price ladder carousel. Without JS it is a plain sideways-scrolling row. */
        .ladder-track { scroll-snap-type: x mandatory; scroll-behavior: smooth; overscroll-behavior-x: contain; scrollbar-width: none; }
        .ladder-track::-webkit-scrollbar { display: none; }
        .ladder-slide { scroll-snap-align: center; transition: transform .45s cubic-bezier(.2,.8,.2,1), opacity .45s, border-color .45s, box-shadow .45s; }
        .ladder-slide.is-dim { opacity: .62; transform: scale(.94); }
        .ladder-slide.is-on { border-color: var(--color-brand); box-shadow: 0 18px 40px -20px color-mix(in srgb, var(--color-brand) 70%, transparent); }
        .ladder-live { animation: ladder-blink 1.6s infinite; }
        @keyframes ladder-blink { 50% { opacity: .25; } }
        @media (prefers-reduced-motion: reduce) {
            .ladder-track { scroll-behavior: auto; }
            .ladder-slide { transition: none; }
            .ladder-live { animation: none; }
        }
    </style>

    @php
        // The published price list. Each band is [from, to, monthly price in rand].
        // The calculator below reads the same array, so the two can't disagree.
        $bands = [
            [1, 1, 450],
            [2, 2, 900],
            [3, 5, 1495],
            [6, 10, 2495],
            [11, 15, 3295],
            [16, 20, 3995],
            [21, 25, 4595],
            [26, 30, 5095],
        ];
        $branchRate = 750;
        $quoteFrom = 31;
        $rand = fn (int|float $n) => 'R'.number_format($n);

        $included = [
            ['globe', 'Agency website'],
            ['database', 'Data take-on from your current system'],
            ['link', 'Property24 &amp; Private Property feeds'],
            ['signature', 'E-sign'],
            ['key', 'Rentals'],
            ['users', 'Unlimited training and support'],
            ['calendar', 'Free take-on month — billing starts on the 1st of the month after go-live'],
        ];

        // After an enquiry (sent, or bounced back with errors) the calculator
        // reopens on the 31+ enquiry box rather than the default headcount.
        $enquiryOpen = session()->has('pricing_enquiry_success') || old('enquiry_form');
        $fieldBase = 'w-full rounded-md border bg-[color:var(--color-bg-soft)] px-3.5 py-2.5 text-sm text-ink placeholder:text-[color:var(--color-faint)] transition duration-300 focus:outline-none focus:ring-2 focus:ring-[color:var(--color-brand)]/40 focus:border-[color:var(--color-brand)]';
    @endphp

    {{-- Hero --}}
    <section class="relative overflow-hidden pt-28 pb-12 sm:pt-36 sm:pb-16">
        <div class="pointer-events-none absolute inset-0 -z-10 bg-grid opacity-[0.3] [mask-image:radial-gradient(ellipse_70%_60%_at_50%_0%,black,transparent)]"></div>
        <div class="pointer-events-none absolute -top-32 left-1/2 -z-10 h-[460px] w-[760px] -translate-x-1/2 glow-brand opacity-60"></div>

        <div class="mx-auto max-w-3xl px-5 sm:px-8 text-center">
            <x-eyebrow icon="sliders" class="reveal justify-center">Pricing</x-eyebrow>
            <h1 class="reveal mt-6 text-4xl sm:text-5xl md:text-6xl font-semibold leading-[1.05] tracking-tight text-balance">
                One price per band,<br class="hidden sm:block">
                <span class="text-gradient">unlimited agents within it.</span>
            </h1>
            <p class="reveal mt-6 text-lg leading-relaxed text-[color:var(--color-muted)]">
                There&rsquo;s no plan to choose. Your band is set automatically by the number of active agents each
                month, and every module is included at every size.
            </p>
        </div>
    </section>

    {{-- Price ladder --}}
    <section class="relative pb-16 sm:pb-20">
        <div class="mx-auto max-w-5xl px-5 sm:px-8">
            {{-- One band in focus at a time. It advances on its own while on screen,
                 and pauses on hover, focus or touch (or never starts, for reduced motion). --}}
            <div
                class="reveal"
                x-data="{
                    cur: 0,
                    paused: false,
                    visible: false,
                    reduce: window.matchMedia('(prefers-reduced-motion: reduce)').matches,
                    slides() { return this.$refs.track.querySelectorAll('[data-slide]'); },
                    go(i) {
                        const s = this.slides();
                        i = (i + s.length) % s.length;
                        const t = this.$refs.track;
                        t.scrollTo({ left: s[i].offsetLeft - (t.clientWidth - s[i].clientWidth) / 2, behavior: this.reduce ? 'auto' : 'smooth' });
                        this.cur = i;
                    },
                    sync() {
                        const t = this.$refs.track, mid = t.scrollLeft + t.clientWidth / 2;
                        let best = 0, bd = Infinity;
                        this.slides().forEach((el, j) => { const d = Math.abs(el.offsetLeft + el.clientWidth / 2 - mid); if (d < bd) { bd = d; best = j; } });
                        this.cur = best;
                    },
                    init() {
                        this.paused = this.reduce;
                        new IntersectionObserver(([e]) => { this.visible = e.isIntersecting; }, { threshold: 0.4 }).observe(this.$el);
                        setInterval(() => { if (!this.paused && this.visible && !document.hidden) this.go(this.cur + 1); }, 3200);
                    },
                }"
                x-on:mouseenter="paused = true"
                x-on:mouseleave="paused = reduce"
                x-on:focusin="paused = true"
                x-on:touchstart.passive="paused = true"
            >
                <div
                    x-ref="track"
                    x-on:scroll.debounce.80ms="sync()"
                    x-on:keydown.arrow-right.prevent="go(cur + 1)"
                    x-on:keydown.arrow-left.prevent="go(cur - 1)"
                    tabindex="0"
                    aria-label="Price bands — scroll sideways"
                    class="ladder-track relative grid grid-flow-col auto-cols-[min(260px,78%)] gap-4 overflow-x-auto px-1 pt-4 pb-5"
                >
                    @foreach ($bands as [$from, $to, $price])
                        <div data-slide class="ladder-slide card flex flex-col gap-2 p-6"
                             :class="cur === {{ $loop->index }} ? 'is-on' : 'is-dim'">
                            <p class="flex flex-wrap items-baseline gap-2 border-b border-[color:var(--color-border)] pb-3.5 mb-1.5 leading-none">
                                <span class="text-[42px] font-bold tracking-[-0.035em] tabular-nums text-ink"
                                      :class="cur === {{ $loop->index }} ? 'text-gradient' : ''">{{ $from === $to ? $from : $from.'–'.$to }}</span>
                                <span class="text-[15px] font-medium text-[color:var(--color-muted)]">agent{{ $to === 1 ? '' : 's' }}</span>
                            </p>
                            <p class="text-[26px] font-semibold tracking-tight tabular-nums text-ink">{{ $rand($price) }}<span class="text-[13px] font-normal tracking-normal text-[color:var(--color-muted)]"> / month</span></p>
                            <p class="text-sm text-[color:var(--color-muted)]">
                                @if ($from === $to)
                                    {{ $rand($price / $to) }} per agent{{ $to > 1 ? ' each' : '' }}
                                @else
                                    Flat · {{ $rand(round($price / $to)) }} per agent at {{ $to }}
                                @endif
                            </p>
                            @if ($loop->last)
                                <span class="mt-1.5 self-start rounded-full border border-[color:color-mix(in_srgb,var(--color-brand)_45%,var(--color-border))] bg-[color:color-mix(in_srgb,var(--color-brand)_10%,transparent)] px-2.5 py-0.5 font-mono text-[10px] uppercase tracking-[0.12em] text-[color:var(--color-brand-400)]">Best rate</span>
                            @else
                                <span class="mt-1.5 font-mono text-[11px] uppercase tracking-[0.12em] text-[color:var(--color-brand-400)]">Unlimited within band</span>
                            @endif
                        </div>
                    @endforeach

                    {{-- 31+: priced around the agency, so no figure is published. --}}
                    <div data-slide class="ladder-slide card flex flex-col gap-2 p-6 border-dashed bg-[color:color-mix(in_srgb,var(--color-brand)_6%,var(--color-surface))]"
                         :class="cur === {{ count($bands) }} ? 'is-on' : 'is-dim'">
                        <p class="flex flex-wrap items-baseline gap-2 border-b border-[color:var(--color-border)] pb-3.5 mb-1.5 leading-none">
                            <span class="text-[42px] font-bold tracking-[-0.035em] tabular-nums text-ink"
                                  :class="cur === {{ count($bands) }} ? 'text-gradient' : ''">{{ $quoteFrom }}+</span>
                            <span class="text-[15px] font-medium text-[color:var(--color-muted)]">agents</span>
                        </p>
                        <p class="text-[26px] font-semibold tracking-tight text-ink">On quotation</p>
                        <p class="text-sm text-[color:var(--color-muted)]">Priced around your headcount and branches.</p>
                        <x-btn href="#calculator" size="md" class="mt-3" x-on:click="$dispatch('pricing-quote')">
                            Contact us
                            <x-icon name="arrow-right" class="w-4 h-4" />
                        </x-btn>
                    </div>
                </div>

                {{-- Controls --}}
                <div class="flex items-center justify-between gap-3">
                    <div class="flex flex-wrap gap-1.5">
                        @foreach (range(0, count($bands)) as $i)
                            <button type="button" x-on:click="go({{ $i }})" aria-label="Show band {{ $i + 1 }}"
                                    class="h-2 rounded-full transition-all duration-300"
                                    :class="cur === {{ $i }} ? 'w-6 bg-[color:var(--color-brand)]' : 'w-2 bg-[color:var(--color-border)]'"></button>
                        @endforeach
                    </div>
                    <span class="hidden sm:inline-flex items-center gap-1.5 text-xs text-[color:var(--color-faint)]" x-cloak x-show="!reduce">
                        <span class="h-1.5 w-1.5 rounded-full" :class="paused ? 'bg-[color:var(--color-faint)]' : 'ladder-live bg-[color:var(--color-brand)]'"></span>
                        <span x-text="paused ? 'Paused' : 'Auto-playing'"></span>
                    </span>
                    <div class="flex gap-2">
                        <button type="button" x-on:click="go(cur - 1)" aria-label="Previous band"
                                class="grid h-10 w-10 place-items-center rounded-full border border-[color:var(--color-border)] bg-[color:var(--color-surface)] text-ink transition duration-300 hover:-translate-y-0.5 hover:border-[color:var(--color-brand)]">
                            <x-icon name="arrow-right" class="w-4 h-4 rotate-180" />
                        </button>
                        <button type="button" x-on:click="go(cur + 1)" aria-label="Next band"
                                class="grid h-10 w-10 place-items-center rounded-full border border-[color:var(--color-border)] bg-[color:var(--color-surface)] text-ink transition duration-300 hover:-translate-y-0.5 hover:border-[color:var(--color-brand)]">
                            <x-icon name="arrow-right" class="w-4 h-4" />
                        </button>
                    </div>
                </div>
            </div>

            <p class="reveal mt-6 text-center text-sm text-[color:var(--color-muted)]">
                Additional branches are {{ $rand($branchRate) }} / branch / month — your first branch is included.
            </p>

            {{-- Everything included --}}
            <div class="reveal card mt-10 p-7 sm:p-9">
                <div class="flex items-center gap-3">
                    <span class="grid h-11 w-11 place-items-center rounded-md bg-[color:var(--color-surface-2)] text-[color:var(--color-brand-400)] ring-1 ring-inset ring-[color:var(--color-border)]">
                        <x-icon name="layers" class="w-5 h-5" />
                    </span>
                    <h2 class="text-lg font-semibold text-ink">Everything included, no setup fees</h2>
                </div>
                <ul class="mt-7 grid gap-3 sm:grid-cols-2">
                    @foreach ($included as [$ico, $label])
                        <li class="flex items-start gap-3">
                            <span class="mt-0.5 grid h-6 w-6 shrink-0 place-items-center rounded-full bg-[color:color-mix(in_srgb,var(--color-brand)_12%,transparent)] text-[color:var(--color-brand)]">
                                <x-icon name="check" class="w-3.5 h-3.5" />
                            </span>
                            <span class="text-sm leading-relaxed text-[color:var(--color-muted)]">{!! $label !!}</span>
                        </li>
                    @endforeach
                </ul>
                <p class="mt-8 border-t border-[color:var(--color-border)] pt-5 text-xs leading-relaxed text-[color:var(--color-faint)]">
                    Prices are per month, billed on the number of active agents that month. They will be VAT-inclusive
                    once RR Technologies registers for VAT — there is no price increase for VAT.
                </p>
            </div>
        </div>
    </section>

    {{-- Calculator --}}
    <section id="calculator" class="relative scroll-mt-24 pb-20 sm:pb-28">
        <div class="mx-auto max-w-5xl px-5 sm:px-8">
            <div class="reveal mb-8 text-center">
                <x-eyebrow icon="sliders" class="justify-center">Size it to your team</x-eyebrow>
                <p class="mt-3 text-sm text-[color:var(--color-muted)]">
                    Slide to your headcount — we&rsquo;ll show your band and your monthly total.
                    From {{ $quoteFrom }} agents, we&rsquo;ll quote you directly.
                </p>
            </div>

            <div
                class="reveal card overflow-hidden p-6 sm:p-10"
                x-data="{
                    agents: {{ $enquiryOpen ? $quoteFrom : 8 }},
                    max: {{ $quoteFrom }},
                    branches: 1,
                    bands: @js($bands),
                    branchRate: {{ $branchRate }},
                    get isMaxed() { return this.agents >= this.max; },
                    get band() { return this.bands.find(([from, to]) => this.agents >= from && this.agents <= to) ?? this.bands[this.bands.length - 1]; },
                    get bandLabel() { const [from, to] = this.band; return from === to ? from + ' agent' + (from === 1 ? '' : 's') : from + ' – ' + to + ' agents'; },
                    get bandPrice() { return this.band[2]; },
                    get extraBranches() { return Math.max(0, this.branches - 1); },
                    get monthly() { return this.bandPrice + this.extraBranches * this.branchRate; },
                    get perAgent() { return this.bandPrice / this.agents; },
                    fmt(n) { return 'R' + Math.round(n).toLocaleString('en-US'); },
                }"
                x-on:pricing-quote.window="agents = max"
            >
                {{-- Controls --}}
                <div class="flex flex-col gap-6">
                    <div class="flex items-end justify-between gap-3 sm:gap-4">
                        <div class="min-w-0">
                            <p class="font-mono text-xs uppercase tracking-[0.16em] text-[color:var(--color-faint)]">Active agents</p>
                            <p class="mt-1 text-3xl sm:text-4xl font-semibold text-ink">
                                <span x-text="isMaxed ? max + '+' : agents"></span>
                                <span class="text-base sm:text-lg font-normal text-[color:var(--color-muted)]">agent<span x-show="agents !== 1">s</span></span>
                            </p>
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
                        {{-- Tick labels sit under the thumb's centre at that value (the thumb is
                             1.6rem wide, so its centre travels from 0.8rem to 100% - 0.8rem).
                             Each one jumps the slider there. 30 is left out: it would sit on top of 31+. --}}
                        <div class="relative mt-2 h-5 font-mono text-[11px] text-[color:var(--color-faint)]">
                            @foreach ([1, 5, 10, 15, 20, 25, $quoteFrom] as $tick)
                                <button type="button" x-on:click="agents = {{ $tick }}"
                                        aria-label="Set to {{ $tick }}{{ $tick === $quoteFrom ? '+' : '' }} agents"
                                        class="absolute top-0 -translate-x-1/2 rounded px-1 tabular-nums transition duration-300 hover:text-ink"
                                        :class="agents === {{ $tick }} ? 'text-[color:var(--color-brand-400)]' : ''"
                                        style="left: calc(0.8rem + (100% - 1.6rem) * {{ ($tick - 1) / ($quoteFrom - 1) }})">{{ $tick }}{{ $tick === $quoteFrom ? '+' : '' }}</button>
                            @endforeach
                        </div>
                    </div>

                    {{-- Extra branches --}}
                    <div class="flex flex-wrap items-center justify-between gap-3 rounded-lg border border-[color:var(--color-border)] bg-[color:var(--color-bg-soft)] px-4 py-3">
                        <div class="flex items-center gap-3">
                            <span class="grid h-8 w-8 shrink-0 place-items-center rounded-md bg-[color:var(--color-surface-2)] text-[color:var(--color-brand-400)] ring-1 ring-inset ring-[color:var(--color-border)]">
                                <x-icon name="branch" class="w-4 h-4" />
                            </span>
                            <div>
                                <p class="text-sm font-medium text-ink">Branches</p>
                                <p class="text-xs text-[color:var(--color-muted)]">First branch included · {{ $rand($branchRate) }} / month each additional</p>
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

                {{-- 31+ agents: priced on quotation, so the numbers give way to an enquiry box. --}}
                <div x-show="isMaxed" x-cloak>
                    <div class="grid gap-8 lg:grid-cols-[0.8fr_1.2fr] lg:items-start">
                        <div>
                            <p class="font-mono text-[11px] uppercase tracking-[0.14em] text-[color:var(--color-brand-400)]">{{ $quoteFrom }}+ agents · on quotation</p>
                            <h3 class="mt-3 text-2xl font-semibold tracking-tight text-ink text-balance">
                                At this size, we price it <span class="text-gradient">around your agency.</span>
                            </h3>
                            <p class="mt-3 text-sm leading-relaxed text-[color:var(--color-muted)]">
                                From {{ $quoteFrom }} agents your headcount, branch count and rollout all shape the number. Tell us
                                where you are and we&rsquo;ll come back with a quote — not a calculator.
                            </p>
                            <ul class="mt-6 space-y-2.5">
                                @foreach ([
                                    'A price set around your headcount and branches',
                                    'Multi-branch setup and per-branch onboarding',
                                    'Migration from your current systems',
                                    'Reply within one business day',
                                ] as $point)
                                    <li class="flex items-start gap-3 text-sm text-[color:var(--color-muted)]">
                                        <x-icon name="check" class="mt-0.5 w-4 h-4 shrink-0 text-[color:var(--color-brand)]" />
                                        {{ $point }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <div class="rounded-xl border border-[color:var(--color-border)] bg-[color:var(--color-bg-soft)] p-5 sm:p-6">
                            @if (session('pricing_enquiry_success'))
                                <div class="flex flex-col items-center py-6 text-center" role="status">
                                    <span class="grid h-14 w-14 place-items-center rounded-full bg-[color:var(--color-brand)]/15 text-[color:var(--color-brand)] ring-1 ring-inset ring-[color:var(--color-brand)]/30">
                                        <x-icon name="check" class="w-7 h-7" />
                                    </span>
                                    <h3 class="mt-5 text-xl font-semibold text-ink">Enquiry received</h3>
                                    <p class="mt-2 max-w-sm text-sm text-[color:var(--color-muted)]">{{ session('pricing_enquiry_success') }}</p>
                                </div>
                            @else
                                <form
                                    method="POST"
                                    action="{{ route('pricing.enquire') }}"
                                    x-data="{ submitting: false }"
                                    @submit="submitting = true"
                                    class="space-y-4"
                                    novalidate
                                >
                                    @csrf
                                    <input type="hidden" name="enquiry_form" value="1">
                                    <input type="hidden" name="branches" :value="branches">

                                    @if ($errors->any())
                                        <div class="rounded-md border border-[#e11d48]/40 bg-[#e11d48]/10 px-4 py-3 text-sm text-[#fb7185]" role="alert">
                                            Please check the highlighted fields and try again.
                                        </div>
                                    @endif

                                    <div class="grid gap-4 sm:grid-cols-2">
                                        <div>
                                            <label for="enq-name" class="mb-1.5 block text-sm font-medium text-ink">Your name</label>
                                            <input id="enq-name" name="name" type="text" value="{{ old('name') }}" required autocomplete="name"
                                                   class="{{ $fieldBase }} @error('name') border-[#e11d48] @else border-[color:var(--color-border)] @enderror" placeholder="Thabo Dlamini">
                                            @error('name') <p class="mt-1.5 text-xs text-[#fb7185]">{{ $message }}</p> @enderror
                                        </div>
                                        <div>
                                            <label for="enq-agency" class="mb-1.5 block text-sm font-medium text-ink">Agency</label>
                                            <input id="enq-agency" name="agency" type="text" value="{{ old('agency') }}" required autocomplete="organization"
                                                   class="{{ $fieldBase }} @error('agency') border-[#e11d48] @else border-[color:var(--color-border)] @enderror" placeholder="Coastal Realty">
                                            @error('agency') <p class="mt-1.5 text-xs text-[#fb7185]">{{ $message }}</p> @enderror
                                        </div>
                                    </div>

                                    <div class="grid gap-4 sm:grid-cols-2">
                                        <div>
                                            <label for="enq-email" class="mb-1.5 block text-sm font-medium text-ink">Work email</label>
                                            <input id="enq-email" name="email" type="email" value="{{ old('email') }}" required autocomplete="email"
                                                   class="{{ $fieldBase }} @error('email') border-[#e11d48] @else border-[color:var(--color-border)] @enderror" placeholder="thabo@coastalrealty.co.za">
                                            @error('email') <p class="mt-1.5 text-xs text-[#fb7185]">{{ $message }}</p> @enderror
                                        </div>
                                        <div>
                                            <label for="enq-phone" class="mb-1.5 block text-sm font-medium text-ink">Phone <span class="text-[color:var(--color-faint)]">(optional)</span></label>
                                            <input id="enq-phone" name="phone" type="tel" value="{{ old('phone') }}" autocomplete="tel"
                                                   class="{{ $fieldBase }} @error('phone') border-[#e11d48] @else border-[color:var(--color-border)] @enderror" placeholder="+27 82 000 0000">
                                            @error('phone') <p class="mt-1.5 text-xs text-[#fb7185]">{{ $message }}</p> @enderror
                                        </div>
                                    </div>

                                    <div>
                                        <label for="enq-agents" class="mb-1.5 block text-sm font-medium text-ink">Roughly how many agents?</label>
                                        <input id="enq-agents" name="agents" type="text" value="{{ old('agents') }}" inputmode="numeric"
                                               class="{{ $fieldBase }} @error('agents') border-[#e11d48] @else border-[color:var(--color-border)] @enderror" placeholder="e.g. 65">
                                        @error('agents') <p class="mt-1.5 text-xs text-[#fb7185]">{{ $message }}</p> @enderror
                                    </div>

                                    <div>
                                        <label for="enq-message" class="mb-1.5 block text-sm font-medium text-ink">Anything we should know? <span class="text-[color:var(--color-faint)]">(optional)</span></label>
                                        <textarea id="enq-message" name="message" rows="3"
                                                  class="{{ $fieldBase }} resize-y @error('message') border-[#e11d48] @else border-[color:var(--color-border)] @enderror" placeholder="Branches, provinces, what you run today…">{{ old('message') }}</textarea>
                                        @error('message') <p class="mt-1.5 text-xs text-[#fb7185]">{{ $message }}</p> @enderror
                                    </div>

                                    {{-- Honeypot: hidden from users, catches bots. --}}
                                    <div class="hidden" aria-hidden="true">
                                        <label for="enq-website">Leave this field empty</label>
                                        <input id="enq-website" name="website" type="text" tabindex="-1" autocomplete="off">
                                    </div>

                                    <div>
                                        <label class="flex items-start gap-3 text-sm text-[color:var(--color-muted)]">
                                            <input name="consent" type="checkbox" value="1" @checked(old('consent')) required
                                                   class="mt-0.5 h-4 w-4 shrink-0 rounded border-[color:var(--color-border)] bg-[color:var(--color-bg-soft)] text-[color:var(--color-brand)] focus:ring-[color:var(--color-brand)]/40">
                                            <span>I agree to be contacted about CoreX OS pricing. We&rsquo;ll only use your details for that — in line with POPIA.</span>
                                        </label>
                                        @error('consent') <p class="mt-1.5 text-xs text-[#fb7185]">{{ $message }}</p> @enderror
                                    </div>

                                    <x-btn size="lg" class="w-full" type="submit" ::disabled="submitting" x-bind:class="submitting && 'opacity-70 pointer-events-none'">
                                        <span x-show="!submitting" class="inline-flex items-center gap-2">
                                            Enquire about {{ $quoteFrom }}+ agents
                                            <x-icon name="arrow-right" class="w-4 h-4" />
                                        </span>
                                        <span x-show="submitting" x-cloak class="inline-flex items-center gap-2">
                                            <svg class="w-4 h-4 motion-safe:animate-spin" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                                <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2.5" opacity="0.3"/>
                                                <path d="M21 12a9 9 0 0 0-9-9" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/>
                                            </svg>
                                            Sending…
                                        </span>
                                    </x-btn>

                                    <p class="text-center text-xs text-[color:var(--color-faint)]">
                                        Or email <a href="mailto:{{ config('mail.demo.address') }}" class="text-[color:var(--color-muted)] hover:text-ink transition duration-300">{{ config('mail.demo.address') }}</a>. No spam, ever.
                                    </p>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Results --}}
                <div class="grid gap-4 sm:grid-cols-2" x-show="!isMaxed">
                    {{-- Band --}}
                    <div class="relative rounded-xl border border-[color:var(--color-border)] bg-[color:var(--color-bg-soft)] p-5 transition duration-300">
                        <span class="text-sm font-semibold text-ink">Your band</span>
                        <p class="mt-3 text-3xl font-semibold tracking-tight text-ink" x-text="bandLabel"></p>
                        <p class="mt-1.5 text-xs text-[color:var(--color-muted)]">
                            <span x-text="fmt(bandPrice)"></span> / month · set automatically by your active agents
                        </p>
                    </div>

                    {{-- Monthly total --}}
                    <div class="relative rounded-xl border border-[color:var(--color-brand)] bg-[color:color-mix(in_srgb,var(--color-brand)_7%,transparent)] p-5 transition duration-300">
                        <span class="text-sm font-semibold text-ink">Monthly total</span>
                        <p class="mt-3 text-3xl font-semibold tracking-tight text-ink">
                            <span x-text="fmt(monthly)"></span>
                            <span class="text-sm font-normal text-[color:var(--color-muted)]">/mo</span>
                        </p>
                        <p class="mt-1.5 text-xs text-[color:var(--color-muted)]">
                            <span x-text="fmt(bandPrice)"></span> band<span x-show="extraBranches > 0" x-cloak> + <span x-text="fmt(extraBranches * branchRate)"></span> additional branch<span x-show="extraBranches > 1">es</span></span>
                        </p>
                        <p class="mt-1 text-xs text-[color:var(--color-faint)]">
                            <span x-text="fmt(perAgent)"></span> per agent
                        </p>
                    </div>
                </div>

                <div class="mt-8 flex flex-col items-center gap-3 sm:flex-row sm:justify-center" x-show="!isMaxed">
                    <x-btn href="{{ route('home') }}#demo" size="lg" class="shrink-0">
                        Book a demo
                        <x-icon name="arrow-right" class="w-4 h-4" />
                    </x-btn>
                </div>
            </div>
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
                A live walkthrough on scenarios you recognise — then one price that moves with your agency as it
                grows, with nothing to renegotiate.
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
