@php
    // Two ways to arrive here, one outcome. `throttled` means CoreX recognised a
    // repeat submit inside its 15-minute cooldown: they are registered, the
    // email they already have is the one that works, and they did nothing
    // wrong. So the only thing that changes is the wording — never the tone,
    // and never an error.
    $throttled = (bool) ($registered['throttled'] ?? false);
    $firstName = trim((string) ($registered['first_name'] ?? ''));
    $email = (string) ($registered['email'] ?? '');
@endphp

<x-layouts.app
    title="You're registered — CoreX OS"
    description="Your CoreX OS webinar registration is confirmed."
>
    <section class="relative overflow-hidden py-20 sm:py-28">
        <div class="pointer-events-none absolute inset-0 -z-10 bg-grid opacity-[0.3] [mask-image:radial-gradient(ellipse_55%_55%_at_50%_40%,black,transparent)]"></div>
        <div class="pointer-events-none absolute left-1/2 top-8 -z-10 h-[380px] w-[640px] -translate-x-1/2 glow-brand opacity-50"></div>

        <div class="mx-auto max-w-2xl px-5 sm:px-8">
            <div class="card p-7 text-center sm:p-10 shadow-2xl shadow-black/30">
                <span class="mx-auto grid h-14 w-14 place-items-center rounded-full bg-[color:var(--color-brand)]/15 text-[color:var(--color-brand)] ring-1 ring-inset ring-[color:var(--color-brand)]/30">
                    <x-icon name="check" class="w-7 h-7" />
                </span>

                <h1 class="mt-6 text-2xl font-semibold text-ink sm:text-3xl">
                    @if ($throttled)
                        You&rsquo;re already registered{{ $firstName !== '' ? ', '.$firstName : '' }}
                    @else
                        You&rsquo;re registered{{ $firstName !== '' ? ', '.$firstName : '' }}
                    @endif
                </h1>

                <p class="mt-3 text-sm leading-relaxed text-[color:var(--color-muted)]">
                    @if ($throttled)
                        We sent your confirmation a few minutes ago — check your inbox
                        @if ($email !== '') at <span class="font-medium text-ink">{{ $email }}</span>@endif.
                        There&rsquo;s no need to register again.
                    @else
                        Your confirmation is on its way
                        @if ($email !== '') to <span class="font-medium text-ink">{{ $email }}</span>@endif.
                    @endif
                </p>

                {{-- One email, and it carries three things people will go looking
                     for separately. Saying so here is what stops them emailing
                     us to ask where the other two are. --}}
                <div class="mt-8 rounded-md border border-[color:var(--color-border)] bg-[color:var(--color-bg-soft)] p-5 text-left">
                    <h2 class="text-sm font-semibold text-ink">One email, with everything in it</h2>
                    <ul class="mt-3 space-y-3">
                        @foreach ([
                            ['calendar', 'Your joining link', 'The link to the webinar itself. It is unique to you.'],
                            ['file-text', 'A calendar invite', 'Open the attachment to put it in your diary.'],
                            ['key', 'Your CoreX demo login', 'Explore the system in your own time, before and after the session.'],
                        ] as [$icon, $heading, $detail])
                            <li class="flex items-start gap-3">
                                <x-icon :name="$icon" class="mt-0.5 w-4 h-4 shrink-0 text-[color:var(--color-brand)]" />
                                <span class="text-sm">
                                    <span class="font-medium text-ink">{{ $heading }}</span>
                                    <span class="block text-[color:var(--color-muted)]">{{ $detail }}</span>
                                </span>
                            </li>
                        @endforeach
                    </ul>
                </div>

                {{-- The one thing worth warning them about. The access code is
                     generated once, delivered once, and stored nowhere anyone
                     can read it back — not by us and not by CoreX. Someone who
                     deletes that email cannot be helped, only re-registered. --}}
                <div class="mt-4 rounded-md border border-[color:var(--color-brand)]/30 bg-[color:var(--color-brand)]/5 p-5 text-left">
                    <p class="text-sm font-medium text-ink">Keep that email.</p>
                    <p class="mt-1.5 text-sm leading-relaxed text-[color:var(--color-muted)]">
                        It is the only copy of your demo access code — we cannot look it up or send it
                        again. If you lose it, just register here again and you&rsquo;ll be issued a fresh one.
                    </p>
                </div>

                <p class="mt-6 text-xs text-[color:var(--color-faint)]">
                    Nothing after a few minutes? Check your spam or junk folder — and add
                    <a href="mailto:info@corexweb.co.za" class="text-[color:var(--color-brand-400)] hover:text-ink transition duration-300">info@corexweb.co.za</a>
                    to your contacts so the reminder reaches you.
                </p>

                <div class="mt-8 flex flex-wrap justify-center gap-3">
                    <x-btn href="{{ route('home') }}" size="md">Explore CoreX OS</x-btn>
                    <x-btn href="{{ route('pricing') }}" variant="secondary" size="md">See pricing</x-btn>
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>
