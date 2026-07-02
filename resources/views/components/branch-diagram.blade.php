{{-- Branch-isolation diagram. The principal sits above and sees all branches;
     "Split branches" toggles a second isolation layer between branch data. --}}
<div class="card p-5 sm:p-6 shadow-xl shadow-black/20" x-data="{ split: true }">
    <div class="flex items-center justify-between gap-3">
        <div class="flex items-center gap-2 text-[color:var(--color-brand-400)]">
            <x-icon name="branch" class="w-4 h-4" />
            <span class="font-mono text-xs uppercase tracking-[0.14em]">Branch isolation</span>
        </div>
        <button type="button" role="switch" :aria-checked="split ? 'true' : 'false'" @click="split = !split"
                class="group flex items-center gap-2 text-xs font-medium text-[color:var(--color-muted)]">
            <span>Split branches</span>
            <span class="relative h-5 w-9 rounded-full transition duration-300"
                  :class="split ? 'bg-[color:var(--color-brand)]' : 'bg-[color:var(--color-surface-2)] ring-1 ring-inset ring-[color:var(--color-border)]'">
                <span class="absolute top-0.5 h-4 w-4 rounded-full bg-white transition-all duration-300"
                      :class="split ? 'left-[18px]' : 'left-0.5'"></span>
            </span>
        </button>
    </div>

    {{-- Principal --}}
    <div class="mt-5 flex items-center gap-3 rounded-md border border-[color:var(--color-border)] bg-[color:var(--color-bg-soft)] px-4 py-3">
        <span class="grid h-9 w-9 place-items-center rounded-md bg-[color:var(--color-surface-2)] text-[color:var(--color-brand-400)] ring-1 ring-inset ring-[color:var(--color-border)]">
            <x-icon name="eye" class="w-4 h-4" />
        </span>
        <div class="min-w-0">
            <p class="text-sm font-semibold text-ink">Principal</p>
            <p class="truncate text-xs text-[color:var(--color-muted)]">Sees all branches · “View as branch” switcher</p>
        </div>
    </div>

    {{-- Connector --}}
    <div class="mx-auto h-4 w-px bg-[color:var(--color-border)]" aria-hidden="true"></div>

    {{-- Branches --}}
    <div class="relative grid grid-cols-2 gap-3">
        @foreach (['Branch A', 'Branch B'] as $branch)
            <div class="rounded-md border border-[color:var(--color-border)] bg-[color:var(--color-bg-soft)] p-3">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-ink">{{ $branch }}</span>
                    <span class="inline-flex items-center gap-1 rounded px-1.5 py-0.5 text-[10px] font-mono transition duration-300"
                          :class="split ? 'text-[color:var(--color-brand-400)] bg-[color:var(--color-brand)]/10' : 'text-[color:var(--color-faint)] bg-[color:var(--color-surface-2)]'">
                        <span x-show="split"><x-icon name="lock" class="w-3 h-3" /></span>
                        <span x-show="!split" x-cloak><x-icon name="link" class="w-3 h-3" /></span>
                        <span x-text="split ? 'Isolated' : 'Shared'"></span>
                    </span>
                </div>
                <div class="mt-2 grid grid-cols-4 gap-1">
                    @foreach (['building', 'users', 'handshake', 'file-text'] as $ico)
                        <span class="grid place-items-center rounded border border-[color:var(--color-border)] bg-[color:var(--color-surface)] py-1.5 text-[color:var(--color-muted)]">
                            <x-icon :name="$ico" class="w-3.5 h-3.5" />
                        </span>
                    @endforeach
                </div>
            </div>
        @endforeach

        {{-- Isolation wall between branches --}}
        <div x-show="split" x-transition.opacity
             class="pointer-events-none absolute inset-y-2 left-1/2 flex -translate-x-1/2 items-center" aria-hidden="true">
            <span class="h-full w-px bg-gradient-to-b from-transparent via-[color:var(--color-brand)] to-transparent opacity-70"></span>
        </div>
    </div>

    <p class="mt-4 flex items-start gap-2 text-xs leading-relaxed text-[color:var(--color-muted)]">
        <x-icon name="check" class="mt-0.5 w-3.5 h-3.5 shrink-0 text-[color:var(--color-brand)]" />
        <span x-text="split
            ? 'Isolated — Branch A can’t see Branch B’s contacts, properties, deals or documents.'
            : 'Open — data is shared across every branch in the agency.'"></span>
    </p>
</div>
