@props(['steps', 'accent' => 'brand'])

@php
    $dot = $accent === 'cyan' ? 'var(--color-cyan)' : 'var(--color-brand)';
    $count = count($steps);
@endphp

{{-- Mobile: vertical timeline. --}}
<ol class="relative flex flex-col lg:hidden">
    @foreach ($steps as $i => [$ico, $title, $sub])
        <li class="group relative flex">
            @if (! $loop->last)
                <span class="absolute left-[19px] top-10 h-full w-px bg-gradient-to-b from-[color:var(--color-border)] to-transparent" aria-hidden="true"></span>
            @endif
            <div class="flex items-start gap-4 pb-8">
                <span class="relative z-10 grid h-10 w-10 shrink-0 place-items-center rounded-full border bg-[color:var(--color-surface)]"
                      style="border-color: color-mix(in srgb, {{ $dot }} 55%, var(--color-border));">
                    <x-icon :name="$ico" class="w-[18px] h-[18px]" style="color: {{ $dot }}" />
                </span>
                <div>
                    <span class="font-mono text-[11px] text-[color:var(--color-faint)]">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                    <h3 class="mt-0.5 text-sm font-semibold text-ink">{{ $title }}</h3>
                    <p class="mt-1 text-xs leading-snug text-[color:var(--color-muted)]">{{ $sub }}</p>
                </div>
            </div>
        </li>
    @endforeach
</ol>

{{-- Desktop: gentle auto-scrolling marquee. Steps are duplicated so the pan loops
     seamlessly; it pauses on hover and stops for reduced-motion users (who can then
     scroll it manually). --}}
<div class="marquee-viewport hidden lg:block overflow-hidden [mask-image:linear-gradient(to_right,transparent,black_5%,black_95%,transparent)]">
    <ol class="marquee-track relative flex w-max items-start" aria-label="Lifecycle stages">
        {{-- Continuous rail behind the nodes --}}
        <span class="absolute left-0 top-[19px] h-px w-full bg-[color:var(--color-border)]" aria-hidden="true"></span>

        @foreach (array_merge($steps, $steps) as $j => [$ico, $title, $sub])
            @php $dup = $j >= $count; @endphp
            <li @class(['group relative z-10 flex w-40 shrink-0 flex-col pr-4', 'marquee-dup' => $dup])
                @if ($dup) aria-hidden="true" @endif>
                <span class="relative z-10 grid h-10 w-10 shrink-0 place-items-center rounded-full border bg-[color:var(--color-surface)] transition duration-300 group-hover:-translate-y-0.5"
                      style="border-color: color-mix(in srgb, {{ $dot }} 55%, var(--color-border));">
                    <span class="absolute inset-0 rounded-full opacity-0 transition duration-300 group-hover:opacity-100"
                          style="box-shadow: 0 0 0 4px color-mix(in srgb, {{ $dot }} 18%, transparent);"></span>
                    <x-icon :name="$ico" class="w-[18px] h-[18px]" style="color: {{ $dot }}" />
                </span>
                <div class="mt-4">
                    <span class="font-mono text-[11px] text-[color:var(--color-faint)]">{{ str_pad(($j % $count) + 1, 2, '0', STR_PAD_LEFT) }}</span>
                    <h3 class="mt-0.5 text-sm font-semibold text-ink">{{ $title }}</h3>
                    <p class="mt-1 max-w-[9rem] text-xs leading-snug text-[color:var(--color-muted)]">{{ $sub }}</p>
                </div>
            </li>
        @endforeach
    </ol>
</div>
