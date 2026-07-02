<section class="relative overflow-hidden pt-28 pb-20 sm:pt-36 sm:pb-28">
    {{-- Backdrop --}}
    <div class="pointer-events-none absolute inset-0 -z-10 bg-grid opacity-[0.35] [mask-image:radial-gradient(ellipse_70%_60%_at_50%_0%,black,transparent)]"></div>
    <div class="pointer-events-none absolute -top-40 left-1/2 -z-10 h-[520px] w-[820px] -translate-x-1/2 glow-brand opacity-70"></div>

    <div class="mx-auto max-w-7xl px-5 sm:px-8">
        <div class="grid items-center gap-14 lg:grid-cols-[1.05fr_0.95fr]">
            {{-- Copy --}}
            <div class="max-w-2xl">
                <x-eyebrow icon="layers" class="reveal">The real estate operating system</x-eyebrow>

                <h1 class="reveal mt-6 text-4xl sm:text-5xl md:text-6xl font-semibold leading-[1.05] tracking-tight text-balance">
                    One system to run the<br class="hidden sm:block">
                    <span class="text-gradient">entire agency.</span>
                </h1>

                <p class="reveal mt-6 max-w-xl text-lg leading-relaxed text-[color:var(--color-muted)]">
                    CoreX OS is the all-in-one operating system for a real estate agency — listings, deals,
                    documents, signatures, compliance and a domain AI in a single source of truth.
                    <span class="text-ink">We do complicated so you can do simple.</span>
                </p>

                <div class="reveal mt-9 flex flex-wrap items-center gap-3">
                    <x-btn href="#demo" size="lg">
                        Book a demo
                        <x-icon name="arrow-right" class="w-4 h-4" />
                    </x-btn>
                    <x-btn href="#flows" size="lg" variant="secondary">
                        See how it flows
                    </x-btn>
                </div>

                <dl class="reveal mt-12 grid max-w-lg grid-cols-3 gap-6 border-t border-[color:var(--color-border)] pt-8">
                    <div>
                        <dt class="font-mono text-2xl font-semibold text-ink">4</dt>
                        <dd class="mt-1 text-xs text-[color:var(--color-muted)]">Connected pillars, one graph</dd>
                    </div>
                    <div>
                        <dt class="font-mono text-2xl font-semibold text-ink">1</dt>
                        <dd class="mt-1 text-xs text-[color:var(--color-muted)]">Source of truth, zero re-entry</dd>
                    </div>
                    <div>
                        <dt class="font-mono text-2xl font-semibold text-ink">0</dt>
                        <dd class="mt-1 text-xs text-[color:var(--color-muted)]">Tools to switch between</dd>
                    </div>
                </dl>
            </div>

            {{-- Product visual --}}
            <div class="reveal relative">
                <div class="pointer-events-none absolute -inset-6 -z-10 glow-brand opacity-60"></div>
                <x-hero-visual />
            </div>
        </div>

        {{-- Replaces logo cloud (no fake logos): the tools CoreX replaces --}}
        <div class="reveal mt-16 sm:mt-20">
            <p class="text-center text-xs font-mono uppercase tracking-[0.2em] text-[color:var(--color-faint)]">
                Built to replace the stack agencies juggle today
            </p>
            <div class="mt-5 flex flex-wrap items-center justify-center gap-x-8 gap-y-3 text-sm text-[color:var(--color-muted)]">
                @foreach (['Property24', 'DocuSign', 'Monday', 'A generic CRM', 'Spreadsheets', 'Standalone FICA tools'] as $tool)
                    <span class="flex items-center gap-2">
                        <x-icon name="x-alt" class="w-3.5 h-3.5 text-[color:var(--color-faint)]" />
                        {{ $tool }}
                    </span>
                @endforeach
            </div>
        </div>
    </div>
</section>
