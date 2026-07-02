<section id="problem" class="relative py-20 sm:py-28">
    <div class="mx-auto max-w-7xl px-5 sm:px-8">
        <x-section-heading
            eyebrow="The problem"
            eyebrow-icon="x-alt"
            align="left"
            title='An agency runs on <span class="text-gradient">five disconnected tools.</span> The data pays the price.'
        >
            A portal here, accounting there, signatures somewhere else, and spreadsheets holding it all together.
            The same deal gets re-typed four times, nothing reconciles, and no one can answer a simple question with confidence.
        </x-section-heading>

        <div class="mt-14 grid gap-6 lg:grid-cols-2 lg:items-stretch">
            {{-- Before --}}
            <div class="reveal card p-6 sm:p-8">
                <div class="flex items-center gap-2 text-[color:var(--color-faint)]">
                    <span class="h-2 w-2 rounded-full bg-[#e11d48]"></span>
                    <span class="font-mono text-xs uppercase tracking-[0.16em]">Today · a stack of islands</span>
                </div>

                <div class="mt-6 grid grid-cols-2 gap-3">
                    @php
                        $islands = [
                            ['Property24', 'Listings & leads'],
                            ['Sage', 'Accounting & commission'],
                            ['DocuSign', 'Signatures'],
                            ['Spreadsheets', 'Deals, FICA, everything else'],
                        ];
                    @endphp
                    @foreach ($islands as [$tool, $desc])
                        <div class="rounded-md border border-dashed border-[color:var(--color-border)] bg-[color:var(--color-bg-soft)] p-4">
                            <p class="text-sm font-medium text-ink">{{ $tool }}</p>
                            <p class="mt-1 text-xs text-[color:var(--color-faint)]">{{ $desc }}</p>
                        </div>
                    @endforeach
                </div>

                <ul class="mt-6 space-y-3">
                    @foreach ([
                        'The same client and property re-entered in every tool',
                        'No single source of truth — numbers never agree',
                        'Compliance tracked by memory and manual reminders',
                        'Hours of admin between every step of a deal',
                    ] as $pain)
                        <li class="flex items-start gap-3 text-sm text-[color:var(--color-muted)]">
                            <x-icon name="x-alt" class="mt-0.5 w-4 h-4 shrink-0 text-[#e11d48]" />
                            {{ $pain }}
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- After --}}
            <div class="reveal relative card overflow-hidden p-6 sm:p-8 ring-1 ring-[color:color-mix(in_srgb,var(--color-brand)_35%,transparent)]">
                <div class="pointer-events-none absolute -right-16 -top-16 h-52 w-52 glow-brand opacity-60"></div>
                <div class="flex items-center gap-2 text-[color:var(--color-brand-400)]">
                    <span class="h-2 w-2 rounded-full bg-[color:var(--color-brand)]"></span>
                    <span class="font-mono text-xs uppercase tracking-[0.16em]">With CoreX · one operating system</span>
                </div>

                <div class="mt-6 rounded-lg border border-[color:var(--color-border)] bg-[color:var(--color-bg-soft)] p-5">
                    <div class="flex items-center gap-2">
                        <span class="grid h-8 w-8 place-items-center rounded-md bg-[color:var(--color-navy)]">
                            <span class="font-mono text-sm font-bold text-[color:var(--color-cyan)]">C</span>
                        </span>
                        <div>
                            <x-logo class="text-base" />
                            <p class="text-[11px] text-[color:var(--color-faint)]">Listings · Contacts · Deals · Docs · Signing · Compliance · AI</p>
                        </div>
                    </div>
                    <div class="mt-4 grid grid-cols-4 gap-2">
                        @foreach (['building', 'users', 'handshake', 'agent'] as $ico)
                            <span class="grid place-items-center rounded-md border border-[color:var(--color-border)] bg-[color:var(--color-surface)] py-2.5 text-[color:var(--color-brand-400)]">
                                <x-icon :name="$ico" class="w-4 h-4" />
                            </span>
                        @endforeach
                    </div>
                </div>

                <ul class="mt-6 space-y-3">
                    @foreach ([
                        'Enter data once — it flows to every module automatically',
                        'One graph, one truth: every report reconciles by design',
                        'FICA, POPIA and PPRA compliance tracked as you work',
                        'Hours of admin become a single button press',
                    ] as $win)
                        <li class="flex items-start gap-3 text-sm text-ink">
                            <x-icon name="check" class="mt-0.5 w-4 h-4 shrink-0 text-[color:var(--color-brand)]" />
                            {{ $win }}
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</section>
