@props(['eyebrow' => null, 'eyebrowIcon' => null, 'title', 'align' => 'center', 'level' => 'h2'])

{{-- `level` changes the element, never the look — a page whose heading this is
     passes h1 so it has exactly one. --}}
@php $tag = in_array($level, ['h1', 'h2', 'h3'], true) ? $level : 'h2'; @endphp

<div class="{{ $align === 'center' ? 'mx-auto max-w-2xl text-center' : 'max-w-2xl' }} reveal">
    @if ($eyebrow)
        <x-eyebrow :icon="$eyebrowIcon" class="mb-5">{{ $eyebrow }}</x-eyebrow>
    @endif
    <{{ $tag }} class="text-3xl sm:text-4xl md:text-[2.75rem] font-semibold tracking-tight leading-[1.1] text-ink text-balance">
        {!! $title !!}
    </{{ $tag }}>
    @if (! empty(trim($slot)))
        <p class="mt-5 text-base sm:text-lg text-[color:var(--color-muted)] leading-relaxed">
            {{ $slot }}
        </p>
    @endif
</div>
