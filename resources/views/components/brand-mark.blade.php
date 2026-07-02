@props(['size' => 'md'])

@php
    // Backgroundless logomark. The artwork is navy/blue on a light theme; on the
    // dark theme we swap to a lightened knockout so the mark stays legible.
    $sizes = [
        'sm' => 'h-8 w-8',
        'md' => 'h-9 w-9',
        'lg' => 'h-11 w-11',
    ];
    $cls = $sizes[$size] ?? $sizes['md'];
@endphp

<span {{ $attributes->merge(['class' => 'inline-grid shrink-0 place-items-center '.$cls]) }}>
    <img src="{{ asset('images/corex-mark-light.png') }}" alt="" width="512" height="512"
         class="block h-full w-full object-contain dark:hidden" decoding="async">
    <img src="{{ asset('images/corex-mark-dark.png') }}" alt="" width="512" height="512"
         class="hidden h-full w-full object-contain dark:block" decoding="async">
</span>
