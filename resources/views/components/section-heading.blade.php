@props(['eyebrow' => null, 'eyebrowIcon' => null, 'title', 'align' => 'center'])

<div class="{{ $align === 'center' ? 'mx-auto max-w-2xl text-center' : 'max-w-2xl' }} reveal">
    @if ($eyebrow)
        <x-eyebrow :icon="$eyebrowIcon" class="mb-5">{{ $eyebrow }}</x-eyebrow>
    @endif
    <h2 class="text-3xl sm:text-4xl md:text-[2.75rem] font-semibold tracking-tight leading-[1.1] text-ink text-balance">
        {!! $title !!}
    </h2>
    @if (! empty(trim($slot)))
        <p class="mt-5 text-base sm:text-lg text-[color:var(--color-muted)] leading-relaxed">
            {{ $slot }}
        </p>
    @endif
</div>
