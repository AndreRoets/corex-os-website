@props(['icon' => null])

<span {{ $attributes->merge(['class' => 'inline-flex items-center gap-2 rounded-full border border-[color:var(--color-border)] bg-[color:var(--color-surface-2)] px-3 py-1 text-xs font-mono uppercase tracking-[0.18em] text-[color:var(--color-brand-400)]']) }}>
    @if ($icon)
        <x-icon :name="$icon" class="w-3.5 h-3.5" />
    @else
        <span class="w-1.5 h-1.5 rounded-full bg-[color:var(--color-brand)]"></span>
    @endif
    {{ $slot }}
</span>
