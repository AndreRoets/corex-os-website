@php
    use App\Support\Sast;

    $fieldBase = 'w-full rounded-md border bg-[color:var(--color-bg-soft)] px-3.5 py-2.5 text-sm text-ink placeholder:text-[color:var(--color-faint)] transition duration-300 focus:outline-none focus:ring-2 focus:ring-[color:var(--color-brand)]/40 focus:border-[color:var(--color-brand)]';

    $startsAt = Sast::parse($webinar['starts_at'] ?? null);
    $duration = $webinar['duration_minutes'] ?? null;
@endphp

<x-layouts.app
    :title="($webinar['title'] ?? 'CoreX OS webinar').' — CoreX OS'"
    :description="\Illuminate\Support\Str::limit(strip_tags($webinar['description'] ?? 'Register for a live CoreX OS webinar.'), 155)"
>
    <section class="relative overflow-hidden py-16 sm:py-24">
        <div class="pointer-events-none absolute inset-0 -z-10 bg-grid opacity-[0.3] [mask-image:radial-gradient(ellipse_60%_60%_at_50%_50%,black,transparent)]"></div>
        <div class="pointer-events-none absolute left-1/2 top-10 -z-10 h-[420px] w-[720px] -translate-x-1/2 glow-brand opacity-50"></div>

        <div class="mx-auto max-w-6xl px-5 sm:px-8">
            <div class="grid gap-12 lg:grid-cols-[1fr_1fr] lg:items-start">

                {{-- What the webinar is. Every word of this comes from CoreX on
                     this request, so the page can never drift from the record
                     that actually governs the demo access people are given. --}}
                <div>
                    <x-eyebrow icon="arrow-right">Live webinar</x-eyebrow>

                    <h1 class="mt-4 text-3xl font-bold tracking-tight text-ink sm:text-4xl">
                        {{ $webinar['title'] ?? 'CoreX OS webinar' }}
                    </h1>

                    @if ($startsAt)
                        <div class="mt-6 flex flex-wrap items-center gap-x-6 gap-y-3 text-sm">
                            <span class="flex items-center gap-2 font-medium text-ink">
                                <x-icon name="check" class="w-4 h-4 text-[color:var(--color-brand)]" />
                                {{ $startsAt->format('l, j F Y') }}
                            </span>
                            <span class="text-[color:var(--color-muted)]">
                                @if ($duration)
                                    {{ $startsAt->format('H:i') }}&ndash;{{ $startsAt->addMinutes((int) $duration)->format('H:i') }} SAST
                                @else
                                    {{ $startsAt->format('H:i') }} SAST
                                @endif
                            </span>
                        </div>
                    @endif

                    @if (! empty($webinar['description']))
                        <div class="mt-6 space-y-4 text-sm leading-relaxed text-[color:var(--color-muted)]">
                            @foreach (preg_split('/\R{2,}/', trim($webinar['description'])) as $paragraph)
                                <p>{{ $paragraph }}</p>
                            @endforeach
                        </div>
                    @endif

                    <div class="mt-8 rounded-md border border-[color:var(--color-border)] bg-[color:var(--color-surface)] p-5">
                        <h2 class="text-sm font-semibold text-ink">What you get when you register</h2>
                        <ul class="mt-3 space-y-2.5">
                            @foreach ([
                                'Your joining link, emailed to you',
                                'A calendar invite so it does not get lost',
                                'A login to the CoreX demo system to explore on your own',
                            ] as $point)
                                <li class="flex items-start gap-3 text-sm text-[color:var(--color-muted)]">
                                    <x-icon name="check" class="mt-0.5 w-4 h-4 shrink-0 text-[color:var(--color-brand)]" />
                                    {{ $point }}
                                </li>
                            @endforeach
                        </ul>

                        @if ($endsAt = Sast::parse($webinar['demo_access_ends_at'] ?? null))
                            <p class="mt-4 border-t border-[color:var(--color-border)] pt-4 text-xs text-[color:var(--color-faint)]">
                                Demo access runs until the end of {{ $endsAt->format('j F Y') }}.
                            </p>
                        @endif
                    </div>
                </div>

                {{-- The form. Four fields, and a phone number if they want to
                     give one. It posts to us, never to CoreX directly — the
                     token that talks to CoreX stays on the server. --}}
                <div>
                    <div class="card p-6 sm:p-8 shadow-2xl shadow-black/30">
                        <h2 class="text-xl font-semibold text-ink">Save your seat</h2>
                        <p class="mt-1.5 text-sm text-[color:var(--color-muted)]">
                            We&rsquo;ll email your joining link and your demo login.
                        </p>

                        @if (session('webinar_error'))
                            <div class="mt-5 rounded-md border border-[#e11d48]/40 bg-[#e11d48]/10 px-4 py-3 text-sm text-[#fb7185]" role="alert">
                                {{ session('webinar_error') }}
                            </div>
                        @endif

                        <form
                            method="POST"
                            action="{{ route('webinars.register', $slug) }}"
                            x-data="{ submitting: false }"
                            @submit="submitting = true"
                            class="mt-6 space-y-4"
                            novalidate
                        >
                            @csrf

                            <div class="grid gap-4 sm:grid-cols-2">
                                <div>
                                    <label for="first_name" class="mb-1.5 block text-sm font-medium text-ink">First name</label>
                                    <input id="first_name" name="first_name" type="text" value="{{ old('first_name') }}" required autocomplete="given-name"
                                           class="{{ $fieldBase }} @error('first_name') border-[#e11d48] @else border-[color:var(--color-border)] @enderror" placeholder="Jane">
                                    @error('first_name') <p class="mt-1.5 text-xs text-[#fb7185]">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="last_name" class="mb-1.5 block text-sm font-medium text-ink">Last name</label>
                                    <input id="last_name" name="last_name" type="text" value="{{ old('last_name') }}" required autocomplete="family-name"
                                           class="{{ $fieldBase }} @error('last_name') border-[#e11d48] @else border-[color:var(--color-border)] @enderror" placeholder="van der Merwe">
                                    @error('last_name') <p class="mt-1.5 text-xs text-[#fb7185]">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <div>
                                <label for="email" class="mb-1.5 block text-sm font-medium text-ink">Email</label>
                                <input id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="email"
                                       class="{{ $fieldBase }} @error('email') border-[#e11d48] @else border-[color:var(--color-border)] @enderror" placeholder="jane@acme.co.za">
                                @error('email') <p class="mt-1.5 text-xs text-[#fb7185]">{{ $message }}</p> @enderror
                                <p class="mt-1.5 text-xs text-[color:var(--color-faint)]">This is where your joining link and demo login are sent.</p>
                            </div>

                            <div>
                                <label for="company_name" class="mb-1.5 block text-sm font-medium text-ink">Company</label>
                                <input id="company_name" name="company_name" type="text" value="{{ old('company_name') }}" required autocomplete="organization"
                                       class="{{ $fieldBase }} @error('company_name') border-[#e11d48] @else border-[color:var(--color-border)] @enderror" placeholder="Acme Properties">
                                @error('company_name') <p class="mt-1.5 text-xs text-[#fb7185]">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="phone" class="mb-1.5 block text-sm font-medium text-ink">
                                    Phone <span class="text-[color:var(--color-faint)]">(optional)</span>
                                </label>
                                <input id="phone" name="phone" type="tel" value="{{ old('phone') }}" autocomplete="tel"
                                       class="{{ $fieldBase }} @error('phone') border-[#e11d48] @else border-[color:var(--color-border)] @enderror" placeholder="+27 82 000 0000">
                                @error('phone') <p class="mt-1.5 text-xs text-[#fb7185]">{{ $message }}</p> @enderror
                            </div>

                            <x-btn type="submit" size="lg" class="w-full" x-bind:disabled="submitting">
                                <span x-show="!submitting">Register for the webinar</span>
                                <span x-show="submitting" x-cloak>Registering&hellip;</span>
                            </x-btn>

                            <p class="text-center text-xs text-[color:var(--color-faint)]">
                                We use your details to send your joining link and demo access, and to follow up about CoreX OS.
                            </p>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </section>
</x-layouts.app>
