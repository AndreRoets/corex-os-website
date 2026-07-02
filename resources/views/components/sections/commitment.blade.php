@php
    $flow = [
        ['mail', 'You ask', 'A CoreX agency requests a feature.'],
        ['search', 'We scope', 'We map it onto the four pillars.'],
        ['zap', 'We build', 'To the same standard as everything else.'],
        ['refresh', 'It ships', 'To your CoreX — and the whole platform.'],
    ];
@endphp

<section id="promise" class="relative border-y border-[color:var(--color-border)] bg-[color:var(--color-bg-soft)] py-14 sm:py-20">
    <div class="pointer-events-none absolute inset-0 -z-0 bg-dots opacity-[0.35] [mask-image:radial-gradient(ellipse_60%_60%_at_50%_50%,black,transparent)]"></div>

    <div class="relative mx-auto max-w-7xl px-5 sm:px-8">
        <div class="mx-auto max-w-2xl text-center reveal">
            <x-eyebrow icon="branch" class="mb-5">Open development</x-eyebrow>
            <h2 class="text-2xl sm:text-3xl md:text-4xl font-semibold tracking-tight text-ink text-balance">
                Ask for a feature. <span class="text-gradient">We build it.</span>
            </h2>
            <p class="mt-4 text-base text-[color:var(--color-muted)] leading-relaxed">
                CoreX is built around your agency on an open, continuous development model. If your agency needs
                something, tell us — we won&rsquo;t say no.
            </p>
        </div>

        {{-- Horizontal flow --}}
        <ol class="mt-12 grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($flow as $i => [$ico, $t, $d])
                <li class="reveal relative" style="transition-delay: {{ $i * 70 }}ms">
                    <div class="card h-full p-5">
                        <div class="flex items-center gap-3">
                            <span class="grid h-10 w-10 shrink-0 place-items-center rounded-full border border-[color:color-mix(in_srgb,var(--color-brand)_55%,var(--color-border))] bg-[color:var(--color-surface-2)] text-[color:var(--color-brand-400)]">
                                <x-icon :name="$ico" class="w-[18px] h-[18px]" />
                            </span>
                            <span class="font-mono text-[11px] text-[color:var(--color-faint)]">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                        </div>
                        <h3 class="mt-4 text-sm font-semibold text-ink">{{ $t }}</h3>
                        <p class="mt-1 text-xs leading-relaxed text-[color:var(--color-muted)]">{{ $d }}</p>
                    </div>

                    {{-- Arrow to the next step (desktop, sits in the gap) --}}
                    @unless ($loop->last)
                        <span class="absolute -right-2.5 top-1/2 z-10 hidden -translate-y-1/2 lg:block text-[color:var(--color-brand-400)]" aria-hidden="true">
                            <x-icon name="arrow-right" class="w-4 h-4" />
                        </span>
                    @endunless
                </li>
            @endforeach
        </ol>

        {{-- Pinned promise --}}
        <div class="reveal mx-auto mt-6 flex max-w-2xl items-center justify-center gap-2.5 rounded-md border border-[color:color-mix(in_srgb,var(--color-brand)_35%,transparent)] bg-[color:color-mix(in_srgb,var(--color-brand)_8%,transparent)] px-5 py-4 text-center">
            <x-icon name="check" class="w-5 h-5 shrink-0 text-[color:var(--color-brand)]" />
            <p class="text-sm font-medium text-ink sm:text-base">We&rsquo;ll never say no to a good idea. If it&rsquo;s buildable, we build it.</p>
        </div>
    </div>
</section>
