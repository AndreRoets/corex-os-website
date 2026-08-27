@php
    use App\Support\Sast;

    $contact = config('corex.contact_email');

    // Registration can end before the webinar does — a cut-off set by the team,
    // or the webinar simply having started. The wording changes, the offer
    // doesn't: write to us and we will see what we can do.
    $closedAt = Sast::parse($webinar['registration_closes_at'] ?? null);
    $expired = $closedAt !== null;
@endphp

{{--
    Closed for registration.

    CoreX answers "archived", "already started", "past the cut-off" and "never
    existed" identically, so nobody can map the sales calendar by probing slugs.
    That is the right call, and it means this one page serves all of them —
    which is why it is a calm state and not an error page. Nothing has gone
    wrong; the door is shut, and there is a person behind it.
--}}
<x-layouts.app
    title="Registration closed — CoreX OS"
    description="Registration for this CoreX OS webinar has closed."
>
    <section class="relative overflow-hidden py-24 sm:py-32">
        <div class="pointer-events-none absolute inset-0 -z-10 bg-grid opacity-[0.25] [mask-image:radial-gradient(ellipse_50%_50%_at_50%_40%,black,transparent)]"></div>

        <div class="mx-auto max-w-xl px-5 text-center sm:px-8">
            <span class="mx-auto grid h-14 w-14 place-items-center rounded-full bg-[color:var(--color-surface-2)] text-[color:var(--color-muted)] ring-1 ring-inset ring-[color:var(--color-border)]">
                <x-icon name="calendar" class="w-7 h-7" />
            </span>

            <h1 class="mt-6 text-2xl font-semibold text-ink sm:text-3xl">
                Registration has closed
            </h1>

            @if (! empty($webinar['title']))
                <p class="mt-2 text-sm font-medium text-[color:var(--color-muted)]">
                    {{ $webinar['title'] }}
                </p>
            @endif

            <p class="mt-4 text-sm leading-relaxed text-[color:var(--color-muted)]">
                @if ($expired)
                    Sign-ups for this webinar closed on
                    <span class="font-medium text-ink">{{ $closedAt->format('j F Y \a\t H:i') }} SAST</span>.
                @else
                    This webinar is no longer open for sign-ups &mdash; it may have already taken place,
                    or registration may have closed.
                @endif
            </p>

            {{-- The point of the page. Someone who got here still wants in. --}}
            <div class="mt-8 rounded-md border border-[color:var(--color-brand)]/30 bg-[color:var(--color-brand)]/5 p-6">
                <p class="text-sm font-medium text-ink">Still interested?</p>
                <p class="mt-2 text-sm leading-relaxed text-[color:var(--color-muted)]">
                    Email us and we&rsquo;ll let you know about the next one &mdash; or arrange a walkthrough
                    just for you.
                </p>
                <a href="mailto:{{ $contact }}?subject={{ rawurlencode('Webinar registration — '.($webinar['title'] ?? 'CoreX OS')) }}"
                   class="mt-4 inline-flex items-center gap-2 text-sm font-medium text-[color:var(--color-brand-400)] transition duration-300 hover:text-ink">
                    <x-icon name="mail" class="w-4 h-4" />
                    {{ $contact }}
                </a>
            </div>

            @if (session('webinar_registered'))
                <p class="mt-6 text-sm text-[color:var(--color-muted)]">
                    If you registered earlier, your joining details are already in your inbox.
                </p>
            @endif

            <div class="mt-8 flex flex-wrap justify-center gap-3">
                <x-btn href="{{ route('home') }}" size="md">Explore CoreX OS</x-btn>
                <x-btn href="{{ route('home') }}#demo" variant="secondary" size="md">Book a demo instead</x-btn>
            </div>
        </div>
    </section>
</x-layouts.app>
