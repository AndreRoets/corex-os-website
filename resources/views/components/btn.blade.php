@props(['href' => null, 'variant' => 'primary', 'size' => 'md'])

@php
    $base = 'inline-flex items-center justify-center gap-2 font-medium rounded-md transition duration-300 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[color:var(--color-brand)] disabled:opacity-60 disabled:pointer-events-none';

    $sizes = [
        'xs' => 'text-xs px-3 py-1.5',
        'sm' => 'text-sm px-3.5 py-2',
        'md' => 'text-sm px-5 py-2.5',
        'lg' => 'text-base px-6 py-3',
    ];

    $variants = [
        'primary' => 'bg-[color:var(--color-brand)] text-white shadow-[0_8px_30px_-8px_color-mix(in_srgb,var(--color-brand)_60%,transparent)] hover:bg-[color:var(--color-brand-600)] hover:-translate-y-0.5',
        'secondary' => 'bg-[color:var(--color-surface-2)] text-ink border border-[color:var(--color-border)] hover:border-[color:var(--color-brand)] hover:-translate-y-0.5',
        'ghost' => 'text-ink hover:bg-[color:var(--color-surface-2)]',
        'outline' => 'border border-[color:var(--color-border)] text-ink hover:border-[color:var(--color-brand)] hover:text-[color:var(--color-brand-400)]',
    ];

    $classes = $base.' '.($sizes[$size] ?? $sizes['md']).' '.($variants[$variant] ?? $variants['primary']);
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</a>
@else
    <button {{ $attributes->merge(['class' => $classes, 'type' => 'button']) }}>{{ $slot }}</button>
@endif
