<section id="ellie" class="relative overflow-hidden py-20 sm:py-28">
    <div class="pointer-events-none absolute right-0 top-0 -z-10 h-[420px] w-[520px] glow-brand opacity-40"></div>

    <div class="mx-auto max-w-7xl px-5 sm:px-8">
        <div class="grid items-center gap-14 lg:grid-cols-2">
            {{-- Copy --}}
            <div>
                <x-section-heading
                    eyebrow="Ellie · Domain AI"
                    eyebrow-icon="sparkles"
                    align="left"
                    title='Not a chatbot. A real estate <span class="text-gradient">operations specialist.</span>'
                >
                    Ellie is embedded in CoreX and fluent in South African property practice — the Property Practitioners
                    Act, FICA, POPIA and CPA. Ask her about the law or your agency&rsquo;s playbook, by voice or chat.
                </x-section-heading>

                <ul class="mt-8 space-y-4">
                    @foreach ([
                        ['shield-check', 'Knows the law', 'PPA, FICA, POPIA and CPA — she reasons within the Property Practitioners Act, not generic web answers.'],
                        ['database', 'Grounded in a knowledge base', 'Ellie answers from a curated knowledge base of SA property practice and your agency&rsquo;s own playbook — searched and cited, not guessed.'],
                        ['mic', 'Voice &amp; chat', 'Ask out loud between viewings or type at your desk — same specialist either way.'],
                        ['users', 'Ellie advises, humans decide', 'She never makes automated changes. Ellie recommends; the agent stays in control.'],
                    ] as [$ico, $t, $d])
                        <li class="reveal flex items-start gap-4">
                            <span class="grid h-10 w-10 shrink-0 place-items-center rounded-md bg-[color:var(--color-surface-2)] text-[color:var(--color-brand-400)] ring-1 ring-inset ring-[color:var(--color-border)]">
                                <x-icon :name="$ico" class="w-5 h-5" />
                            </span>
                            <div>
                                <p class="font-medium text-ink">{!! $t !!}</p>
                                <p class="mt-1 text-sm leading-relaxed text-[color:var(--color-muted)]">{!! $d !!}</p>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- Chat mock --}}
            <div class="reveal relative">
                <div class="pointer-events-none absolute -inset-6 -z-10 glow-brand opacity-50"></div>
                <div class="card overflow-hidden shadow-2xl shadow-black/40">
                    <div class="flex items-center justify-between border-b border-[color:var(--color-border)] bg-[color:var(--color-surface-2)] px-4 py-3">
                        <div class="flex items-center gap-2.5">
                            <span class="grid h-8 w-8 place-items-center rounded-full bg-gradient-to-br from-[color:var(--color-cyan)] to-[color:var(--color-brand)]">
                                <x-icon name="sparkles" class="w-4 h-4 text-[#04121a]" />
                            </span>
                            <div>
                                <p class="text-sm font-semibold text-ink">Ellie</p>
                                <p class="text-[11px] text-[color:var(--color-faint)]">Real estate operations specialist</p>
                            </div>
                        </div>
                        <span class="flex items-center gap-1.5 rounded-full border border-[color:var(--color-border)] px-2 py-1 text-[10px] font-mono uppercase tracking-wider text-[color:var(--color-brand-400)]">
                            <span class="h-1.5 w-1.5 rounded-full bg-[color:var(--color-brand)] motion-safe:animate-pulse"></span> Online
                        </span>
                    </div>

                    <div class="space-y-4 p-5">
                        {{-- user --}}
                        <div class="flex justify-end">
                            <p class="max-w-[80%] rounded-lg rounded-br-sm bg-[color:var(--color-brand)] px-3.5 py-2.5 text-sm text-white">
                                What FICA documents do we need for a cash buyer who&rsquo;s an individual?
                            </p>
                        </div>
                        {{-- ellie --}}
                        <div class="flex gap-2.5">
                            <span class="mt-0.5 grid h-7 w-7 shrink-0 place-items-center rounded-full bg-gradient-to-br from-[color:var(--color-cyan)] to-[color:var(--color-brand)]">
                                <x-icon name="sparkles" class="w-3.5 h-3.5 text-[#04121a]" />
                            </span>
                            <div class="max-w-[85%] rounded-lg rounded-bl-sm border border-[color:var(--color-border)] bg-[color:var(--color-bg-soft)] px-3.5 py-3 text-sm text-ink">
                                <p>For an individual cash buyer, FICA due diligence typically calls for:</p>
                                <ul class="mt-2 space-y-1.5 text-[color:var(--color-muted)]">
                                    <li class="flex items-start gap-2"><x-icon name="check" class="mt-0.5 w-3.5 h-3.5 text-[color:var(--color-brand)]" /> A certified copy of a green bar-coded or smart ID.</li>
                                    <li class="flex items-start gap-2"><x-icon name="check" class="mt-0.5 w-3.5 h-3.5 text-[color:var(--color-brand)]" /> Proof of residential address, under three months old.</li>
                                    <li class="flex items-start gap-2"><x-icon name="check" class="mt-0.5 w-3.5 h-3.5 text-[color:var(--color-brand)]" /> Proof of the source of funds for the purchase.</li>
                                </ul>
                                <p class="mt-2 text-[11px] text-[color:var(--color-faint)]">Drawn from FICA client due-diligence guidance — confirm against your agency&rsquo;s risk policy.</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 border-t border-[color:var(--color-border)] px-4 py-3">
                        <div class="flex flex-1 items-center gap-2 rounded-md border border-[color:var(--color-border)] bg-[color:var(--color-bg-soft)] px-3 py-2 text-sm text-[color:var(--color-faint)]">
                            Ask Ellie anything…
                        </div>
                        <span class="grid h-9 w-9 place-items-center rounded-md border border-[color:var(--color-border)] text-[color:var(--color-muted)]">
                            <x-icon name="mic" class="w-[18px] h-[18px]" />
                        </span>
                        <span class="grid h-9 w-9 place-items-center rounded-md bg-[color:var(--color-brand)] text-white">
                            <x-icon name="arrow-right" class="w-[18px] h-[18px]" />
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
