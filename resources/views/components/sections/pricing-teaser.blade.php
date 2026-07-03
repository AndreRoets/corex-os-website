{{-- Compact band that routes to the dedicated pricing page. Sits just above the
     demo form so a price-conscious buyer can check cost before booking. --}}
<section class="relative overflow-hidden py-14 sm:py-16 border-t border-[color:var(--color-border)] bg-[color:var(--color-bg-soft)]">
    <div class="pointer-events-none absolute inset-0 -z-0 bg-dots opacity-[0.3] [mask-image:radial-gradient(ellipse_60%_60%_at_50%_50%,black,transparent)]"></div>

    <div class="relative mx-auto max-w-5xl px-5 sm:px-8">
        <div class="reveal card flex flex-col items-center gap-6 p-6 text-center sm:flex-row sm:justify-between sm:p-8 sm:text-left">
            <div class="max-w-xl">
                <x-eyebrow icon="sliders" class="mb-4">Simple pricing</x-eyebrow>
                <h2 class="text-xl font-semibold tracking-tight text-ink sm:text-2xl text-balance">
                    Two plans, <span class="text-gradient">one upgrade path.</span>
                </h2>
                <p class="mt-2 text-sm leading-relaxed text-[color:var(--color-muted)]">
                    Start at R450 an agent, flat — or move to the platform plan as you grow. Every module included in both.
                </p>
            </div>
            <x-btn href="{{ route('pricing') }}" size="lg" class="shrink-0">
                See pricing
                <x-icon name="arrow-right" class="w-4 h-4" />
            </x-btn>
        </div>
    </div>
</section>
