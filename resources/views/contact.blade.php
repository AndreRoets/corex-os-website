@php
    $fieldBase = 'w-full rounded-md border bg-[color:var(--color-bg-soft)] px-3.5 py-2.5 text-sm text-ink placeholder:text-[color:var(--color-faint)] transition duration-300 focus:outline-none focus:ring-2 focus:ring-[color:var(--color-brand)]/40 focus:border-[color:var(--color-brand)]';
@endphp

<x-layouts.app
    :page="$page"
    title="Contact — CoreX OS"
    description="Get in touch with the CoreX OS team — questions, support, or anything else."
>
    <section class="relative overflow-hidden py-20 sm:py-28">
        <div class="pointer-events-none absolute inset-0 -z-10 bg-grid opacity-[0.3] [mask-image:radial-gradient(ellipse_60%_60%_at_50%_50%,black,transparent)]"></div>
        <div class="pointer-events-none absolute left-1/2 top-10 -z-10 h-[420px] w-[720px] -translate-x-1/2 glow-brand opacity-50"></div>

        <div class="mx-auto max-w-2xl px-5 sm:px-8">
            <x-section-heading eyebrow="Contact" align="left" title="Get in touch">
                Questions, support, or anything else — we&rsquo;ll get back to you within one business day.
            </x-section-heading>

            <div class="reveal mt-8">
                <div class="card p-6 sm:p-8 shadow-2xl shadow-black/30">
                    @if (session('contact_success'))
                        <div class="flex flex-col items-center py-8 text-center" role="status">
                            <span class="grid h-14 w-14 place-items-center rounded-full bg-[color:var(--color-brand)]/15 text-[color:var(--color-brand)] ring-1 ring-inset ring-[color:var(--color-brand)]/30">
                                <x-icon name="check" class="w-7 h-7" />
                            </span>
                            <h3 class="mt-5 text-xl font-semibold text-ink">Message received</h3>
                            <p class="mt-2 max-w-sm text-sm text-[color:var(--color-muted)]">{{ session('contact_success') }}</p>
                            <x-btn href="{{ route('contact') }}" variant="secondary" size="sm" class="mt-6">Send another</x-btn>
                        </div>
                    @else
                        <form
                            method="POST"
                            action="{{ route('contact.store') }}"
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
                                <label for="name" class="mb-1.5 block text-sm font-medium text-ink">Your name</label>
                                <input id="name" name="name" type="text" value="{{ old('name') }}" required autocomplete="name"
                                       class="{{ $fieldBase }} @error('name') border-[#e11d48] @else border-[color:var(--color-border)] @enderror" placeholder="Thabo Dlamini">
                                @error('name') <p class="mt-1.5 text-xs text-[#fb7185]">{{ $message }}</p> @enderror
                            </div>

                            <div class="grid gap-4 sm:grid-cols-2">
                                <div>
                                    <label for="email" class="mb-1.5 block text-sm font-medium text-ink">Email</label>
                                    <input id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="email"
                                           class="{{ $fieldBase }} @error('email') border-[#e11d48] @else border-[color:var(--color-border)] @enderror" placeholder="thabo@example.co.za">
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
                                <label for="message" class="mb-1.5 block text-sm font-medium text-ink">Message</label>
                                <textarea id="message" name="message" rows="5" required
                                          class="{{ $fieldBase }} resize-y @error('message') border-[#e11d48] @else border-[color:var(--color-border)] @enderror" placeholder="How can we help?">{{ old('message') }}</textarea>
                                @error('message') <p class="mt-1.5 text-xs text-[#fb7185]">{{ $message }}</p> @enderror
                            </div>

                            {{-- Honeypot: hidden from real visitors, catches bots that fill every field. --}}
                            <div class="hidden" aria-hidden="true">
                                <label for="company">Leave this field empty</label>
                                <input id="company" name="company" type="text" tabindex="-1" autocomplete="off">
                            </div>

                            <x-btn size="lg" class="w-full" type="submit" ::disabled="submitting" x-bind:class="submitting && 'opacity-70 pointer-events-none'">
                                <span x-show="!submitting" class="inline-flex items-center gap-2">
                                    Send message
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
    </section>
</x-layouts.app>
