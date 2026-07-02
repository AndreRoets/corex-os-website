@props(['target', 'label', 'icon' => null, 'deep' => false])

{{--
    Accordion wrapper.

    Default (deep = false): collapses on MOBILE only. On desktop (lg+) the wrapper
    is `display:contents`, the header is hidden and the section is always shown —
    these are the persuasive "spine" sections.

    deep = true: collapses on BOTH mobile and desktop, collapsed by default on
    desktop. Used for reference-heavy sections (Modules, Compliance, Control) so the
    desktop page keeps a short spine and the depth stays one tap away.

    Usage: wrap a full <x-sections.*/> that renders its own <section id>. The
    `target` must match that section's id so the nav can open + scroll to it.
--}}
<div class="{{ $deep ? '' : 'lg:contents' }}" x-data>
    {{-- Header bar. Mobile: always. Desktop: only for deep sections. --}}
    <button
        type="button"
        @click="$store.site.toggleSection('{{ $target }}')"
        :aria-expanded="$store.site.isOpen('{{ $target }}')"
        aria-controls="{{ $target }}"
        class="{{ $deep ? 'lg:border-t lg:border-[color:var(--color-border)]' : 'lg:hidden' }} w-full border-b border-[color:var(--color-border)] bg-[color:var(--color-bg-soft)] transition-colors duration-300 hover:bg-[color:var(--color-surface-2)]"
    >
        <span class="mx-auto flex w-full max-w-7xl items-center justify-between gap-3 px-5 sm:px-8 py-5">
            <span class="flex items-center gap-3 text-base font-semibold text-ink">
                @if ($icon)
                    <span class="grid h-9 w-9 shrink-0 place-items-center rounded-md bg-[color:var(--color-surface-2)] text-[color:var(--color-brand-400)] ring-1 ring-inset ring-[color:var(--color-border)]">
                        <x-icon :name="$icon" class="w-[18px] h-[18px]" />
                    </span>
                @endif
                {{ $label }}
            </span>
            <span class="flex items-center gap-2.5 text-[color:var(--color-muted)]">
                <span class="hidden font-mono text-[11px] uppercase tracking-[0.14em] text-[color:var(--color-faint)] sm:inline"
                      x-text="$store.site.isOpen('{{ $target }}') ? 'Collapse' : 'Expand'"></span>
                <x-icon
                    name="chevron-down"
                    class="w-5 h-5 shrink-0 transition-transform duration-300"
                    x-bind:class="$store.site.isOpen('{{ $target }}') && 'rotate-180'"
                />
            </span>
        </span>
    </button>

    {{-- Collapsed on mobile always; on desktop only when deep. --}}
    <div x-show="$store.site.isOpen('{{ $target }}')" x-collapse>
        {{ $slot }}
    </div>
</div>
