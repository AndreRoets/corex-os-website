@php
    $points = [
        ['users', 'Built for your agency', 'CoreX runs the way you run — your roles, your process, your data. The system belongs to the agency, not the other way around.'],
        ['sparkles', 'Your idea, taken seriously', 'Every request from a CoreX agency is reviewed, never dismissed. If it&rsquo;s buildable, it goes on the roadmap.'],
        ['layers', 'Built for you, shipped for all', 'When we build your feature, it strengthens the whole platform — so CoreX keeps compounding in value.'],
    ];
@endphp

<section id="open-development" class="relative overflow-hidden py-20 sm:py-28">
    <div class="pointer-events-none absolute right-0 top-0 -z-10 h-[420px] w-[560px] glow-brand opacity-40"></div>

    <div class="mx-auto max-w-7xl px-5 sm:px-8">
        <x-section-heading
            eyebrow="Built around your agency"
            eyebrow-icon="workflow"
            title='It&rsquo;s your agency&rsquo;s system — and it never stops <span class="text-gradient">getting better.</span>'
        >
            CoreX isn&rsquo;t rented software you bend to fit. It&rsquo;s built around your agency, on an open, continuous
            development model — the system you buy keeps growing with you.
        </x-section-heading>

        <div class="mt-14 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($points as $i => [$ico, $t, $d])
                <div class="reveal card p-6 transition duration-300 hover:-translate-y-1 hover:border-[color:var(--color-brand)]"
                     style="transition-delay: {{ $i * 70 }}ms">
                    <span class="grid h-11 w-11 place-items-center rounded-md bg-[color:var(--color-surface-2)] text-[color:var(--color-brand-400)] ring-1 ring-inset ring-[color:var(--color-border)]">
                        <x-icon :name="$ico" class="w-5 h-5" />
                    </span>
                    <h3 class="mt-5 text-base font-semibold text-ink">{!! $t !!}</h3>
                    <p class="mt-2 text-sm leading-relaxed text-[color:var(--color-muted)]">{!! $d !!}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>
