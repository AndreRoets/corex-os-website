@php
    $fieldBase = 'w-full rounded-md border bg-[color:var(--color-bg-soft)] px-3.5 py-2.5 text-sm text-ink placeholder:text-[color:var(--color-faint)] transition duration-300 focus:outline-none focus:ring-2 focus:ring-[color:var(--color-brand)]/40 focus:border-[color:var(--color-brand)]';

    // The credentials live in config/corex.php, and the email that follows a
    // submission reads them from the same place — the page and the email can
    // never drift apart.
    $demoEmail = config('corex.mobile_app.demo_email');
    $demoPassword = config('corex.mobile_app.demo_password');

    // Set by MobileDemoAccessController after a successful submission. The
    // credentials are only revealed when this is present.
    $granted = session('mobile_demo_name');
@endphp

<x-layouts.app
    :page="$page"
    title="Mobile app — CoreX OS"
    description="Download the CoreX OS mobile app for Android or iPhone, and try it right now with a demo login — listings, deals, contacts and documents in your pocket."
>
    {{-- ─────────────────────────── Hero ─────────────────────────── --}}
    <section class="relative overflow-hidden py-20 sm:py-28">
        <div class="pointer-events-none absolute inset-0 -z-10 bg-grid opacity-[0.3] [mask-image:radial-gradient(ellipse_60%_60%_at_50%_50%,black,transparent)]"></div>
        <div class="pointer-events-none absolute left-1/2 top-10 -z-10 h-[420px] w-[720px] -translate-x-1/2 glow-brand opacity-50"></div>

        <div class="mx-auto max-w-4xl px-5 text-center sm:px-8">
            <x-eyebrow icon="smartphone" class="mb-5">Mobile app</x-eyebrow>

            <h1 class="text-4xl sm:text-5xl md:text-[3.5rem] font-semibold tracking-tight leading-[1.05] text-ink text-balance">
                Your agency, <span class="text-gradient">in your pocket.</span>
            </h1>

            <p class="reveal mx-auto mt-6 max-w-2xl text-base sm:text-lg text-[color:var(--color-muted)] leading-relaxed">
                Listings, deals, contacts and every document filed against them &mdash; on the phone you already
                have with you, at the showhouse, in the car, on the pavement outside a valuation.
            </p>

            {{-- The point of this page. Nothing competes with it. --}}
            <x-store-buttons size="lg" class="reveal mt-10 justify-center" />

            <p class="reveal mt-5 text-sm text-[color:var(--color-faint)]">
                Free with every CoreX OS seat. Android 8 and up, iOS 15 and up.
            </p>

            <a href="#demo-login" class="reveal mt-8 inline-flex items-center gap-2 text-sm font-medium text-[color:var(--color-brand-400)] hover:text-ink transition duration-300">
                Want to try it before you sign up? Get a demo login
                <x-icon name="arrow-down" class="w-4 h-4" />
            </a>
        </div>
    </section>

    {{-- ──────────────────── Demo login (gated) ──────────────────── --}}
    <section id="demo-login" class="relative overflow-hidden border-y border-[color:var(--color-border)] bg-[color:var(--color-surface-2)]/40 py-20 sm:py-28">
        <div class="mx-auto max-w-6xl px-5 sm:px-8">
            <div class="grid gap-12 lg:grid-cols-[0.9fr_1.1fr] lg:items-start">
                {{-- Pitch --}}
                <div>
                    <x-section-heading
                        eyebrow="Try it now"
                        eyebrow-icon="key"
                        align="left"
                        title='Take the app for a <span class="text-gradient">drive first.</span>'
                    >
                        Leave your name and email and we&rsquo;ll show you a working login on the spot &mdash; and send
                        the same details to your inbox so you still have them tomorrow.
                    </x-section-heading>

                    <ul class="mt-8 space-y-3">
                        @foreach ([
                            'A shared demo agency, stocked with realistic listings, deals and documents',
                            'Nothing in it is real, so change whatever you like',
                            'No card, no trial clock, no install on your agency data',
                            'The login lands in your inbox as well as on this page',
                        ] as $point)
                            <li class="reveal flex items-start gap-3 text-sm text-[color:var(--color-muted)]">
                                <span class="mt-0.5 grid h-5 w-5 shrink-0 place-items-center rounded-full bg-[color:var(--color-brand)]/15 text-[color:var(--color-brand)]">
                                    <x-icon name="check" class="w-3 h-3" />
                                </span>
                                <span>{{ $point }}</span>
                            </li>
                        @endforeach
                    </ul>

                    <div class="reveal mt-8 flex flex-wrap gap-6 border-t border-[color:var(--color-border)] pt-6 text-sm">
                        <a href="{{ route('contact') }}" class="flex items-center gap-2 text-[color:var(--color-muted)] hover:text-ink transition duration-300">
                            <x-icon name="mail" class="w-4 h-4 text-[color:var(--color-brand-400)]" /> Trouble signing in? Tell us
                        </a>
                        <a href="{{ route('home') }}#demo" class="flex items-center gap-2 text-[color:var(--color-muted)] hover:text-ink transition duration-300">
                            <x-icon name="presentation" class="w-4 h-4 text-[color:var(--color-brand-400)]" /> Or book a walkthrough
                        </a>
                    </div>
                </div>

                {{-- Form, or the revealed credentials --}}
                <div class="reveal">
                    <div class="card p-6 sm:p-8 shadow-2xl shadow-black/30">
                        @if ($granted)
                            {{-- Revealed only after a submission. The flash is gone on the
                                 next request, which is exactly why the email is also sent. --}}
                            <div role="status">
                                <div class="flex items-start gap-4">
                                    <span class="grid h-11 w-11 shrink-0 place-items-center rounded-full bg-[color:var(--color-brand)]/15 text-[color:var(--color-brand)] ring-1 ring-inset ring-[color:var(--color-brand)]/30">
                                        <x-icon name="check" class="w-6 h-6" />
                                    </span>
                                    <div>
                                        <h3 class="text-xl font-semibold text-ink">Here you go, {{ $granted }}</h3>
                                        <p class="mt-1 text-sm text-[color:var(--color-muted)]">
                                            @if (session('mobile_demo_emailed'))
                                                We&rsquo;ve emailed these to you as well &mdash; check your inbox, and your spam folder if it isn&rsquo;t there.
                                            @else
                                                Copy these down now &mdash; our mail server wouldn&rsquo;t take the email, so write to us if you lose them.
                                            @endif
                                        </p>
                                    </div>
                                </div>

                                <dl class="mt-7 space-y-3"
                                    x-data="{
                                        copied: null,
                                        copy(value, key) {
                                            /* clipboard is unavailable on http:// and in old browsers —
                                               the value is on screen either way, so a failure is silent. */
                                            if (! navigator.clipboard) return;
                                            navigator.clipboard.writeText(value).then(() => {
                                                this.copied = key;
                                                setTimeout(() => { if (this.copied === key) this.copied = null }, 1800);
                                            }).catch(() => {});
                                        },
                                    }"
                                >
                                    @foreach ([['key' => 'email', 'label' => 'Email', 'value' => $demoEmail], ['key' => 'password', 'label' => 'Password', 'value' => $demoPassword]] as $field)
                                        <div class="rounded-md border border-[color:var(--color-border)] bg-[color:var(--color-bg-soft)] px-4 py-3">
                                            <dt class="text-[0.7rem] font-mono uppercase tracking-[0.16em] text-[color:var(--color-faint)]">{{ $field['label'] }}</dt>
                                            <dd class="mt-1 flex items-center justify-between gap-3">
                                                <span class="min-w-0 break-all font-mono text-sm text-ink select-all">{{ $field['value'] }}</span>
                                                <button type="button"
                                                        @click="copy(@js($field['value']), @js($field['key']))"
                                                        class="shrink-0 rounded-md border border-[color:var(--color-border)] px-2.5 py-1.5 text-xs font-medium text-[color:var(--color-muted)] transition duration-300 hover:border-[color:var(--color-brand)] hover:text-ink">
                                                    <span x-text="copied === @js($field['key']) ? 'Copied' : 'Copy'">Copy</span>
                                                </button>
                                            </dd>
                                        </div>
                                    @endforeach
                                </dl>

                                <div class="mt-7 border-t border-[color:var(--color-border-soft)] pt-6">
                                    <p class="text-sm font-medium text-ink">Now install the app and sign in</p>
                                    <x-store-buttons size="md" class="mt-4" />
                                </div>

                                <p class="mt-6 text-xs text-[color:var(--color-faint)]">
                                    Everyone trying the demo shares this one agency, so you may see someone else&rsquo;s
                                    edits alongside yours. Please don&rsquo;t put anything real in it.
                                </p>
                            </div>

                            <x-conversion-event form="mobile-demo" />
                        @else
                            <div class="mb-6 flex items-start gap-4">
                                <span class="grid h-11 w-11 shrink-0 place-items-center rounded-full border border-[color:var(--color-border)] bg-[color:var(--color-bg-soft)] text-[color:var(--color-brand)]">
                                    <x-icon name="lock" class="w-5 h-5" />
                                </span>
                                <div>
                                    <h3 class="text-xl font-semibold text-ink">Get the demo login</h3>
                                    <p class="mt-1 text-sm text-[color:var(--color-muted)]">
                                        Two fields, then the details appear right here.
                                    </p>
                                </div>
                            </div>

                            <form
                                method="POST"
                                action="{{ route('mobile-app.demo') }}"
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

                                <div>
                                    <label for="demo-name" class="mb-1.5 block text-sm font-medium text-ink">Your name</label>
                                    <input id="demo-name" name="name" type="text" value="{{ old('name') }}" required autocomplete="name"
                                           class="{{ $fieldBase }} @error('name') border-[#e11d48] @else border-[color:var(--color-border)] @enderror" placeholder="Thabo Dlamini">
                                    @error('name') <p class="mt-1.5 text-xs text-[#fb7185]">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label for="demo-email" class="mb-1.5 block text-sm font-medium text-ink">Email</label>
                                    <input id="demo-email" name="email" type="email" value="{{ old('email') }}" required autocomplete="email"
                                           class="{{ $fieldBase }} @error('email') border-[#e11d48] @else border-[color:var(--color-border)] @enderror" placeholder="thabo@example.co.za">
                                    @error('email') <p class="mt-1.5 text-xs text-[#fb7185]">{{ $message }}</p> @enderror
                                    <p class="mt-1.5 text-xs text-[color:var(--color-faint)]">This is where we send the login.</p>
                                </div>

                                <div class="grid gap-4 sm:grid-cols-2">
                                    <div>
                                        <label for="demo-agency" class="mb-1.5 block text-sm font-medium text-ink">Agency <span class="text-[color:var(--color-faint)]">(optional)</span></label>
                                        <input id="demo-agency" name="agency" type="text" value="{{ old('agency') }}" autocomplete="organization"
                                               class="{{ $fieldBase }} @error('agency') border-[#e11d48] @else border-[color:var(--color-border)] @enderror" placeholder="Ridge Realty">
                                        @error('agency') <p class="mt-1.5 text-xs text-[#fb7185]">{{ $message }}</p> @enderror
                                    </div>
                                    <div>
                                        <label for="demo-phone" class="mb-1.5 block text-sm font-medium text-ink">Phone <span class="text-[color:var(--color-faint)]">(optional)</span></label>
                                        <input id="demo-phone" name="phone" type="tel" value="{{ old('phone') }}" autocomplete="tel"
                                               class="{{ $fieldBase }} @error('phone') border-[#e11d48] @else border-[color:var(--color-border)] @enderror" placeholder="+27 82 000 0000">
                                        @error('phone') <p class="mt-1.5 text-xs text-[#fb7185]">{{ $message }}</p> @enderror
                                    </div>
                                </div>

                                {{-- Honeypot: hidden from real visitors, catches bots that fill every field. --}}
                                <div class="hidden" aria-hidden="true">
                                    <label for="demo-website">Leave this field empty</label>
                                    <input id="demo-website" name="website" type="text" tabindex="-1" autocomplete="off">
                                </div>

                                <div>
                                    <label class="flex items-start gap-3 text-sm text-[color:var(--color-muted)]">
                                        <input name="consent" type="checkbox" value="1" @checked(old('consent')) required
                                               class="mt-0.5 h-4 w-4 shrink-0 rounded border-[color:var(--color-border)] bg-[color:var(--color-bg-soft)] text-[color:var(--color-brand)] focus:ring-[color:var(--color-brand)]/40">
                                        <span>Email me the demo login, and you may contact me about CoreX OS. We&rsquo;ll only use your details for that &mdash; in line with POPIA.</span>
                                    </label>
                                    @error('consent') <p class="mt-1.5 text-xs text-[#fb7185]">{{ $message }}</p> @enderror
                                </div>

                                <x-btn size="lg" class="w-full" type="submit" ::disabled="submitting" x-bind:class="submitting && 'opacity-70 pointer-events-none'">
                                    <span x-show="!submitting" class="inline-flex items-center gap-2">
                                        Reveal the demo login
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
                                    No spam, ever. One email with the login, and only what you ask for after that.
                                </p>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ─────────────────── What it does on a phone ─────────────────── --}}
    <section class="relative py-20 sm:py-28">
        <div class="mx-auto max-w-6xl px-5 sm:px-8">
            <x-section-heading
                eyebrow="On the phone"
                eyebrow-icon="layers"
                title='The parts of CoreX OS you need <span class="text-gradient">away from a desk.</span>'
            >
                The app is not a second system. It reads and writes the same records the web app does, so what you
                change on a pavement is what the office sees.
            </x-section-heading>

            <div class="mt-14 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ([
                    ['icon' => 'building', 'title' => 'Listings', 'body' => 'The spec, the description, the photos and the dates for every property on your books — and a tap through to the public listing.'],
                    ['icon' => 'workflow', 'title' => 'Deals', 'body' => 'Where each deal stands, what happens next, and who is waiting on whom. Move a stage from wherever you took the call.'],
                    ['icon' => 'users', 'title' => 'Contacts', 'body' => 'Buyers, sellers and everyone around them, with the history attached, so you walk in knowing the last conversation.'],
                    ['icon' => 'file-text', 'title' => 'Documents', 'body' => 'Every file filed against a listing or a deal, read and downloaded on the spot. Filing itself stays on the web, on purpose.'],
                    ['icon' => 'sparkles', 'title' => 'Ellie', 'body' => 'Ask in plain language and get an answer out of your own data, instead of scrolling for it in a car park.'],
                    ['icon' => 'scan', 'title' => 'QR codes', 'body' => 'Generate the code for a listing and put it on the board, so a passer-by lands on the property, not your home page.'],
                ] as $item)
                    <div class="reveal card p-6">
                        <span class="grid h-11 w-11 place-items-center rounded-md border border-[color:var(--color-border)] bg-[color:var(--color-bg-soft)] text-[color:var(--color-brand)]">
                            <x-icon :name="$item['icon']" class="w-5 h-5" />
                        </span>
                        <h3 class="mt-5 text-lg font-semibold text-ink">{{ $item['title'] }}</h3>
                        <p class="mt-2 text-sm leading-relaxed text-[color:var(--color-muted)]">{{ $item['body'] }}</p>
                    </div>
                @endforeach
            </div>

            <div class="reveal mt-12 card flex flex-col items-start gap-4 p-6 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-start gap-4">
                    <span class="grid h-11 w-11 shrink-0 place-items-center rounded-md border border-[color:var(--color-border)] bg-[color:var(--color-bg-soft)] text-[color:var(--color-brand)]">
                        <x-icon name="shield-check" class="w-5 h-5" />
                    </span>
                    <div>
                        <h3 class="text-base font-semibold text-ink">Your agency&rsquo;s colours, your agency&rsquo;s rules</h3>
                        <p class="mt-1 text-sm text-[color:var(--color-muted)]">
                            The app is white-labelled per agency, and an agent only ever sees what their role allows.
                            The demo agency above shows the CoreX defaults.
                        </p>
                    </div>
                </div>
                <x-btn href="{{ route('pricing') }}" variant="secondary" size="sm" class="shrink-0">See pricing</x-btn>
            </div>
        </div>
    </section>

    {{-- ───────────────────────── Closing CTA ───────────────────────── --}}
    <section class="relative overflow-hidden border-t border-[color:var(--color-border)] py-20 sm:py-24">
        <div class="pointer-events-none absolute left-1/2 top-0 -z-10 h-[360px] w-[680px] -translate-x-1/2 glow-brand opacity-40"></div>

        <div class="mx-auto max-w-3xl px-5 text-center sm:px-8">
            <h2 class="text-3xl sm:text-4xl font-semibold tracking-tight leading-[1.1] text-ink text-balance">
                Install it, then sign in with the demo.
            </h2>
            <p class="reveal mx-auto mt-5 max-w-xl text-base text-[color:var(--color-muted)]">
                Five minutes on your own phone tells you more than any screenshot we could put here.
            </p>

            <x-store-buttons size="lg" class="reveal mt-9 justify-center" />

            <p class="reveal mt-6 text-sm text-[color:var(--color-faint)]">
                Rather see it on your own deals? <a href="{{ route('home') }}#demo" class="font-medium text-[color:var(--color-brand-400)] hover:text-ink transition duration-300">Book a 30-minute walkthrough</a>.
            </p>
        </div>
    </section>
</x-layouts.app>
