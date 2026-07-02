@props(['class' => ''])

{{-- Wordmark: "CoreX" in ink, "Os" in cyan. --}}
<span {{ $attributes->merge(['class' => 'font-sans font-bold tracking-tight select-none '.$class]) }}>
    <span class="text-ink">CoreX</span><span class="text-[color:var(--color-cyan)]"> Os</span>
</span>
