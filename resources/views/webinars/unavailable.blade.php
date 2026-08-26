{{--
    CoreX could not be reached, or refused our token.

    That is a fault on our side — a revoked token, a wrong host, a timeout. The
    visitor gets an apology and nothing technical: the detail is already in the
    log, with a hint about which of those it was, for whoever fixes it.
--}}
<x-layouts.app
    title="Something went wrong — CoreX OS"
    description="We could not load this page just now."
>
    <section class="relative overflow-hidden py-24 sm:py-32">
        <div class="pointer-events-none absolute inset-0 -z-10 bg-grid opacity-[0.25] [mask-image:radial-gradient(ellipse_50%_50%_at_50%_40%,black,transparent)]"></div>

        <div class="mx-auto max-w-xl px-5 text-center sm:px-8">
            <h1 class="text-2xl font-semibold text-ink sm:text-3xl">Something went wrong</h1>

            <p class="mt-4 text-sm leading-relaxed text-[color:var(--color-muted)]">
                We couldn&rsquo;t load this webinar just now. It&rsquo;s us, not you — please try again
                in a few minutes.
            </p>

            <div class="mt-8 flex flex-wrap justify-center gap-3">
                <x-btn href="{{ url()->current() }}" size="md">Try again</x-btn>
                <x-btn href="{{ route('home') }}" variant="secondary" size="md">Back to CoreX OS</x-btn>
            </div>

            <p class="mt-8 text-xs text-[color:var(--color-faint)]">
                Still stuck? Email
                <a href="mailto:info@corexweb.co.za" class="hover:text-ink transition duration-300">info@corexweb.co.za</a>
                and we&rsquo;ll register you by hand.
            </p>
        </div>
    </section>
</x-layouts.app>
