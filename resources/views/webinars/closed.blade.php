{{--
    Closed for registration.

    CoreX answers "archived", "already started" and "never existed" identically,
    so that nobody can map the sales calendar by probing slugs. That is the
    right call, and it means this one page has to serve all three — which is why
    it is a plain, calm state and not an error page. Nothing has gone wrong; the
    door is simply shut.
--}}
<x-layouts.app
    title="Registration closed — CoreX OS"
    description="This CoreX OS webinar is closed for registration."
>
    <section class="relative overflow-hidden py-24 sm:py-32">
        <div class="pointer-events-none absolute inset-0 -z-10 bg-grid opacity-[0.25] [mask-image:radial-gradient(ellipse_50%_50%_at_50%_40%,black,transparent)]"></div>

        <div class="mx-auto max-w-xl px-5 text-center sm:px-8">
            <span class="mx-auto grid h-14 w-14 place-items-center rounded-full bg-[color:var(--color-surface-2)] text-[color:var(--color-muted)] ring-1 ring-inset ring-[color:var(--color-border)]">
                <x-icon name="calendar" class="w-7 h-7" />
            </span>

            <h1 class="mt-6 text-2xl font-semibold text-ink sm:text-3xl">
                @if (! empty($webinar['title']))
                    {{ $webinar['title'] }}
                @else
                    This webinar is closed for registration
                @endif
            </h1>

            <p class="mt-4 text-sm leading-relaxed text-[color:var(--color-muted)]">
                @if (! empty($webinar['title']))
                    This webinar is closed for registration.
                @endif
                It may have already taken place, or sign-ups may have closed.
                If you were sent this link and think it should still be open, let us know.
            </p>

            @if (session('webinar_registered'))
                <p class="mt-4 text-sm text-[color:var(--color-muted)]">
                    If you registered earlier, your joining details are already in your inbox.
                </p>
            @endif

            <div class="mt-8 flex flex-wrap justify-center gap-3">
                <x-btn href="{{ route('home') }}" size="md">Explore CoreX OS</x-btn>
                <x-btn href="{{ route('home') }}#demo" variant="secondary" size="md">Book a demo instead</x-btn>
            </div>

            <p class="mt-8 text-xs text-[color:var(--color-faint)]">
                <a href="mailto:info@corexweb.co.za" class="hover:text-ink transition duration-300">info@corexweb.co.za</a>
            </p>
        </div>
    </section>
</x-layouts.app>
