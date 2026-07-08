@php
    $fieldBase = 'w-full rounded-md border bg-[color:var(--color-bg-soft)] px-3.5 py-2.5 text-sm text-ink placeholder:text-[color:var(--color-faint)] transition duration-300 focus:outline-none focus:ring-2 focus:ring-[color:var(--color-brand)]/40 focus:border-[color:var(--color-brand)]';
@endphp

<section id="demo" class="relative overflow-hidden py-20 sm:py-28">
    <div class="pointer-events-none absolute inset-0 -z-10 bg-grid opacity-[0.3] [mask-image:radial-gradient(ellipse_60%_60%_at_50%_50%,black,transparent)]"></div>
    <div class="pointer-events-none absolute left-1/2 top-10 -z-10 h-[420px] w-[720px] -translate-x-1/2 glow-brand opacity-50"></div>

    <div class="mx-auto max-w-6xl px-5 sm:px-8">
        <div class="grid gap-12 lg:grid-cols-[0.95fr_1.05fr] lg:items-center">
            {{-- Pitch --}}
            <div>
                <x-section-heading
                    eyebrow="Book a demo"
                    eyebrow-icon="arrow-right"
                    align="left"
                    title='See your agency <span class="text-gradient">run on one system.</span>'
                >
                    Tell us a little about your agency and we&rsquo;ll walk you through CoreX OS on your own deals —
                    from listing to payout, one button at a time.
                </x-section-heading>

                <ul class="mt-8 space-y-3">
                    @foreach ([
                        'A live walkthrough on real estate scenarios you recognise',
                        'See the four pillars and the flows in one place',
                        'Meet Ellie and the compliance-by-design workflow',
                        'No obligation — just the clearest 30 minutes in proptech',
                    ] as $point)
                        <li class="reveal flex items-start gap-3 text-sm text-[color:var(--color-muted)]">
                            <x-icon name="check" class="mt-0.5 w-4 h-4 shrink-0 text-[color:var(--color-brand)]" />
                            {{ $point }}
                        </li>
                    @endforeach
                </ul>

                <div class="reveal mt-8 flex flex-wrap gap-6 border-t border-[color:var(--color-border)] pt-6 text-sm">
                    <a href="mailto:info@corexweb.co.za" class="flex items-center gap-2 text-[color:var(--color-muted)] hover:text-ink transition duration-300">
                        <x-icon name="mail" class="w-4 h-4 text-[color:var(--color-brand-400)]" /> info@corexweb.co.za
                    </a>
                </div>
            </div>

            {{-- Form / success --}}
            <div class="reveal">
                <div class="card p-6 sm:p-8 shadow-2xl shadow-black/30">
                    @if (session('demo_success'))
                        <div class="flex flex-col items-center py-8 text-center" role="status">
                            <span class="grid h-14 w-14 place-items-center rounded-full bg-[color:var(--color-brand)]/15 text-[color:var(--color-brand)] ring-1 ring-inset ring-[color:var(--color-brand)]/30">
                                <x-icon name="check" class="w-7 h-7" />
                            </span>
                            <h3 class="mt-5 text-xl font-semibold text-ink">Request received</h3>
                            <p class="mt-2 max-w-sm text-sm text-[color:var(--color-muted)]">{{ session('demo_success') }}</p>
                            <x-btn href="#main" variant="secondary" size="sm" class="mt-6">Back to top</x-btn>
                        </div>
                    @else
                        <form
                            method="POST"
                            action="{{ route('demo.store') }}"
                            x-data="{ submitting: false }"
                            @submit="submitting = true"
                            class="space-y-4"
                            novalidate
                        >
                            @csrf

                            @if ($errors->any())
                                <div class="rounded-md border border-[#e11d48]/40 bg-[#e11d48]/10 px-4 py-3 text-sm text-[#fb7185]" role="alert">
                                    Please check the highlighted fields and try again.
                                </div>
                            @endif

                            <div class="grid gap-4 sm:grid-cols-2">
                                <div>
                                    <label for="name" class="mb-1.5 block text-sm font-medium text-ink">Your name</label>
                                    <input id="name" name="name" type="text" value="{{ old('name') }}" required autocomplete="name"
                                           class="{{ $fieldBase }} @error('name') border-[#e11d48] @else border-[color:var(--color-border)] @enderror" placeholder="Thabo Dlamini">
                                    @error('name') <p class="mt-1.5 text-xs text-[#fb7185]">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="agency" class="mb-1.5 block text-sm font-medium text-ink">Agency</label>
                                    <input id="agency" name="agency" type="text" value="{{ old('agency') }}" required autocomplete="organization"
                                           class="{{ $fieldBase }} @error('agency') border-[#e11d48] @else border-[color:var(--color-border)] @enderror" placeholder="Coastal Realty">
                                    @error('agency') <p class="mt-1.5 text-xs text-[#fb7185]">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <div class="grid gap-4 sm:grid-cols-2">
                                <div>
                                    <label for="email" class="mb-1.5 block text-sm font-medium text-ink">Work email</label>
                                    <input id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="email"
                                           class="{{ $fieldBase }} @error('email') border-[#e11d48] @else border-[color:var(--color-border)] @enderror" placeholder="thabo@coastalrealty.co.za">
                                    @error('email') <p class="mt-1.5 text-xs text-[#fb7185]">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="phone" class="mb-1.5 block text-sm font-medium text-ink">Phone <span class="text-[color:var(--color-faint)]">(optional)</span></label>
                                    <input id="phone" name="phone" type="tel" value="{{ old('phone') }}" autocomplete="tel"
                                           class="{{ $fieldBase }} @error('phone') border-[#e11d48] @else border-[color:var(--color-border)] @enderror" placeholder="+27 82 000 0000">
                                    @error('phone') <p class="mt-1.5 text-xs text-[#fb7185]">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <div>
                                <label for="agents" class="mb-1.5 block text-sm font-medium text-ink">Number of agents <span class="text-[color:var(--color-faint)]">(optional)</span></label>
                                <select id="agents" name="agents" class="{{ $fieldBase }} border-[color:var(--color-border)]">
                                    <option value="" @selected(old('agents') === null || old('agents') === '')>Select a range…</option>
                                    @foreach (['1–5', '6–15', '16–40', '40+'] as $range)
                                        <option value="{{ $range }}" @selected(old('agents') === $range)>{{ $range }} agents</option>
                                    @endforeach
                                </select>
                                @error('agents') <p class="mt-1.5 text-xs text-[#fb7185]">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="message" class="mb-1.5 block text-sm font-medium text-ink">Anything you&rsquo;d like to see? <span class="text-[color:var(--color-faint)]">(optional)</span></label>
                                <textarea id="message" name="message" rows="3"
                                          class="{{ $fieldBase }} resize-y @error('message') border-[#e11d48] @else border-[color:var(--color-border)] @enderror" placeholder="We currently juggle a listing portal, a signing tool and spreadsheets…">{{ old('message') }}</textarea>
                                @error('message') <p class="mt-1.5 text-xs text-[#fb7185]">{{ $message }}</p> @enderror
                            </div>

                            {{-- Honeypot: hidden from users, catches bots. --}}
                            <div class="hidden" aria-hidden="true">
                                <label for="website">Leave this field empty</label>
                                <input id="website" name="website" type="text" tabindex="-1" autocomplete="off">
                            </div>

                            <div>
                                <label class="flex items-start gap-3 text-sm text-[color:var(--color-muted)]">
                                    <input name="consent" type="checkbox" value="1" @checked(old('consent')) required
                                           class="mt-0.5 h-4 w-4 shrink-0 rounded border-[color:var(--color-border)] bg-[color:var(--color-bg-soft)] text-[color:var(--color-brand)] focus:ring-[color:var(--color-brand)]/40">
                                    <span>I agree to be contacted about a CoreX OS demo. We&rsquo;ll only use your details for that — in line with POPIA.</span>
                                </label>
                                @error('consent') <p class="mt-1.5 text-xs text-[#fb7185]">{{ $message }}</p> @enderror
                            </div>

                            <x-btn size="lg" class="w-full" type="submit" ::disabled="submitting" x-bind:class="submitting && 'opacity-70 pointer-events-none'">
                                <span x-show="!submitting" class="inline-flex items-center gap-2">
                                    Book my demo
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
                                We reply within one business day. No spam, ever.
                            </p>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
