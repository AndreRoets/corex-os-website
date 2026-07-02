@props(['target', 'label', 'icon' => null])

{{--
    Mobile accordion wrapper. On mobile it renders a tappable header bar and
    collapses the wrapped section; tapping expands it (accordion — one open at a
    time). On desktop (lg+) the wrapper is `display:contents`, the header is
    hidden and the section is always shown, so the layout is unchanged.

    Usage: wrap a full <x-sections.*/> that already renders its own <section id>.
    The `target` must match that section's id so the mobile menu can open it.
--}}
<div class="lg:contents" x-data>
    {{-- Mobile-only accordion header --}}
    <button
        type="button"
        @click="$store.site.toggleSection('{{ $target }}')"
        :aria-expanded="$store.site.openSection === '{{ $target }}'"
        aria-controls="{{ $target }}"
        class="lg:hidden w-full flex items-center justify-between gap-3 px-5 sm:px-8 py-5 text-left border-b border-[color:var(--color-border)] bg-[color:var(--color-bg-soft)]"
    >
        <span class="flex items-center gap-3 text-base font-semibold text-ink">
            @if ($icon)
                <span class="grid h-9 w-9 shrink-0 place-items-center rounded-md bg-[color:var(--color-surface-2)] text-[color:var(--color-brand-400)] ring-1 ring-inset ring-[color:var(--color-border)]">
                    <x-icon :name="$icon" class="w-[18px] h-[18px]" />
                </span>
            @endif
            {{ $label }}
        </span>
        <x-icon
            name="chevron-down"
            class="w-5 h-5 shrink-0 text-[color:var(--color-muted)] transition-transform duration-300"
            x-bind:class="$store.site.openSection === '{{ $target }}' && 'rotate-180'"
        />
    </button>

    {{-- Collapsed on mobile, always shown on desktop. --}}
    <div x-show="$store.site.isOpen('{{ $target }}')" x-collapse>
        {{ $slot }}
    </div>
</div>
