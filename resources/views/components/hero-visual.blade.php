{{-- The CoreX data web: every module wired to the ones it feeds. Drag to spin, click a module.
     Behaviour lives in the `dataWeb` Alpine component (resources/js/app.js); styling in app.css. --}}
<div
    x-data="dataWeb"
    class="relative rounded-xl border border-[color:var(--color-border)] bg-[color:var(--color-surface)] shadow-2xl shadow-black/40 overflow-hidden"
>
    {{-- Title bar --}}
    <div class="flex items-center gap-2 border-b border-[color:var(--color-border)] bg-[color:var(--color-surface-2)] px-4 py-3">
        <span class="h-3 w-3 rounded-full bg-[#ff5f57]"></span>
        <span class="h-3 w-3 rounded-full bg-[#febc2e]"></span>
        <span class="h-3 w-3 rounded-full bg-[#28c840]"></span>
        <div class="ml-3 flex items-center gap-2 text-xs text-[color:var(--color-muted)]">
            <x-icon name="circle-dot" class="w-3.5 h-3.5 text-[color:var(--color-cyan)]" />
            <span class="font-mono">corex os</span>
            <span class="text-[color:var(--color-faint)]">/ graph / one source of truth</span>
        </div>
    </div>

    {{-- The ring --}}
    <div class="px-2 pt-3">
        <svg
            class="web-svg"
            viewBox="0 0 640 450"
            role="group"
            aria-label="The CoreX modules in a ring. Select a module to see which other modules its data updates."
            @pointerdown="onDown($event)"
            @pointermove.window="onMove($event)"
            @pointerup.window="onUp()"
            @pointercancel.window="onUp()"
            @click="onClick($event)"
            @keydown="onKey($event)"
        >
            <g x-ref="edgeLayer"></g>
            <g x-ref="pulseLayer"></g>
            <g x-ref="nodeLayer"></g>
        </svg>
    </div>

    {{-- What the touched module ripples into --}}
    <div class="border-t border-[color:var(--color-border)] bg-[color:var(--color-bg-soft)] p-4 sm:p-5">
        <div class="flex items-center justify-between gap-3">
            <p class="text-[11px] font-mono uppercase tracking-[0.16em] text-[color:var(--color-faint)]">
                Ripple
            </p>
            <div class="flex items-center gap-3">
                <span
                    class="text-[11px] font-mono text-[color:var(--color-muted)]"
                    x-text="linked + ' modules · ' + (features[sel] || []).length + ' features'"
                ></span>
                <button
                    type="button"
                    @click="spin()"
                    class="rounded-md border border-[color:var(--color-border)] px-2.5 py-1 text-[11px] font-medium text-[color:var(--color-muted)] transition hover:border-[color:var(--color-brand)] hover:text-[color:var(--color-brand-400)]"
                >
                    Spin
                </button>
            </div>
        </div>

        {{-- Fixed heights: the card must not resize as the visitor moves between
             modules, whose chips and ripple copy differ in length. --}}
        <div class="mt-3 flex h-[5.75rem] flex-wrap content-start gap-1.5 overflow-hidden sm:h-[3.75rem]">
            <template x-for="f in (features[sel] || [])" :key="f">
                <span
                    class="h-fit rounded-md border border-[color:var(--color-border)] bg-[color:var(--color-surface-2)] px-2 py-1 text-[11px] text-[color:var(--color-muted)]"
                    x-text="f"
                ></span>
            </template>
        </div>

        <p
            class="mt-3 h-[7rem] overflow-hidden text-sm leading-relaxed text-[color:var(--color-muted)] sm:h-[5.5rem] [&_b]:font-medium [&_b]:text-ink"
            x-html="story[sel]"
        ></p>
    </div>
</div>
