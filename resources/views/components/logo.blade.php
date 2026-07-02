@props(['class' => ''])

{{-- Wordmark logo: "corex" white/ink, "os" cyan. --}}
<span {{ $attributes->merge(['class' => 'font-mono font-semibold tracking-tight lowercase select-none '.$class]) }}>
    <span class="text-ink">corex</span><span class="text-[color:var(--color-cyan)]"> os</span>
</span>
