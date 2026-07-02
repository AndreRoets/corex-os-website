{{-- Stylised CoreX OS product window — the "live-feel" hero visual. Pure HTML/CSS + a little Alpine. --}}
<div
    x-data="{
        step: 4,
        steps: ['Listing', 'Mandate', 'Marketed', 'Offer', 'Accepted', 'Registered', 'Paid'],
        running: false,
        run() {
            if (this.running || this.step >= this.steps.length) return;
            this.running = true;
            setTimeout(() => { this.step++; this.running = false; }, 1100);
        }
    }"
    class="relative rounded-xl border border-[color:var(--color-border)] bg-[color:var(--color-surface)] shadow-2xl shadow-black/40 overflow-hidden"
    role="img"
    aria-label="CoreX OS interface showing a property deal moving through its lifecycle at the click of a button."
>
    {{-- Title bar --}}
    <div class="flex items-center gap-2 border-b border-[color:var(--color-border)] bg-[color:var(--color-surface-2)] px-4 py-3">
        <span class="h-3 w-3 rounded-full bg-[#ff5f57]"></span>
        <span class="h-3 w-3 rounded-full bg-[#febc2e]"></span>
        <span class="h-3 w-3 rounded-full bg-[#28c840]"></span>
        <div class="ml-3 flex items-center gap-2 text-xs text-[color:var(--color-muted)]">
            <x-icon name="circle-dot" class="w-3.5 h-3.5 text-[color:var(--color-cyan)]" />
            <span class="font-mono">corex os</span>
            <span class="text-[color:var(--color-faint)]">/ deals / 14 Ridge Rd, Uvongo</span>
        </div>
    </div>

    <div class="grid grid-cols-[auto_1fr]">
        {{-- Mini sidebar --}}
        <aside class="hidden sm:flex w-14 flex-col items-center gap-1 border-r border-[color:var(--color-border)] bg-[color:var(--color-bg-soft)] py-3">
            @foreach (['building', 'users', 'handshake', 'agent', 'file-text', 'sparkles'] as $i => $ico)
                <span class="grid h-9 w-9 place-items-center rounded-md {{ $i === 2 ? 'bg-[color:var(--color-brand)]/15 text-[color:var(--color-brand-400)]' : 'text-[color:var(--color-faint)]' }}">
                    <x-icon :name="$ico" class="w-[18px] h-[18px]" />
                </span>
            @endforeach
        </aside>

        {{-- Main panel --}}
        <div class="p-4 sm:p-5">
            {{-- Linked pillars for this deal --}}
            <p class="text-[11px] font-mono uppercase tracking-[0.16em] text-[color:var(--color-faint)]">This deal links</p>
            <div class="mt-2 grid grid-cols-2 gap-2 sm:grid-cols-4">
                @php
                    $chips = [
                        ['building', 'Property', '14 Ridge Rd'],
                        ['users', 'Contact', 'M. Naidoo'],
                        ['handshake', 'Deal', 'Sale · R 2.95m'],
                        ['agent', 'Agent', 'T. Dlamini'],
                    ];
                @endphp
                @foreach ($chips as [$ico, $label, $val])
                    <div class="rounded-md border border-[color:var(--color-border)] bg-[color:var(--color-surface-2)] p-2.5">
                        <div class="flex items-center gap-1.5 text-[color:var(--color-brand-400)]">
                            <x-icon :name="$ico" class="w-3.5 h-3.5" />
                            <span class="text-[10px] font-mono uppercase tracking-wider">{{ $label }}</span>
                        </div>
                        <p class="mt-1 truncate text-xs font-medium text-ink">{{ $val }}</p>
                    </div>
                @endforeach
            </div>

            {{-- Lifecycle progress --}}
            <div class="mt-5 flex items-center justify-between">
                <p class="text-[11px] font-mono uppercase tracking-[0.16em] text-[color:var(--color-faint)]">Lifecycle</p>
                <span class="text-[11px] font-mono text-[color:var(--color-muted)]" x-text="Math.min(step, steps.length) + ' / ' + steps.length"></span>
            </div>
            <div class="mt-2 flex items-center gap-1.5">
                <template x-for="(s, i) in steps" :key="i">
                    <div class="h-1.5 flex-1 rounded-full transition-all duration-500"
                         :class="i < step ? 'bg-[color:var(--color-brand)]' : 'bg-[color:var(--color-border)]'"></div>
                </template>
            </div>
            <div class="mt-2 flex items-center justify-between text-[10px] text-[color:var(--color-muted)]">
                <span x-text="steps[Math.max(0, Math.min(step, steps.length) - 1)]"></span>
                <span class="text-[color:var(--color-faint)]" x-show="step < steps.length" x-text="'Next: ' + steps[Math.min(step, steps.length - 1)]"></span>
                <span class="text-[color:var(--color-brand-400)]" x-show="step >= steps.length" x-cloak>Complete</span>
            </div>

            {{-- The red button --}}
            <div class="mt-5 rounded-lg border border-[color:var(--color-border)] bg-[color:var(--color-bg-soft)] p-4">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <p class="text-xs text-[color:var(--color-muted)]">Advance to next stage</p>
                        <p class="text-sm font-medium text-ink" x-show="step < steps.length" x-text="'Move to ' + steps[Math.min(step, steps.length - 1)] + ' — next deadline set'"></p>
                        <p class="text-sm font-medium text-[color:var(--color-brand-400)]" x-show="step >= steps.length" x-cloak>Agent paid · deal closed</p>
                    </div>
                    <button
                        @click="run()"
                        :disabled="running || step >= steps.length"
                        class="relative grid h-14 w-14 shrink-0 place-items-center rounded-full bg-[#e11d48] text-white shadow-[0_10px_30px_-6px_rgba(225,29,72,0.7)] transition duration-300 hover:bg-[#f43f5e] disabled:opacity-50 disabled:cursor-not-allowed"
                        aria-label="Run next step"
                    >
                        <span class="absolute inset-0 rounded-full bg-[#e11d48] motion-safe:animate-ping opacity-40" x-show="!running && step < steps.length"></span>
                        <x-icon name="zap" class="w-6 h-6 relative" x-show="!running" />
                        <svg class="w-6 h-6 relative motion-safe:animate-spin" x-show="running" x-cloak viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2.5" opacity="0.3"/>
                            <path d="M21 12a9 9 0 0 0-9-9" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/>
                        </svg>
                    </button>
                </div>
                <p class="mt-3 flex items-center gap-1.5 text-[11px] text-[color:var(--color-faint)]">
                    <x-icon name="coffee" class="w-3.5 h-3.5" />
                    Click to advance — CoreX sets up what comes next.
                </p>
            </div>
        </div>
    </div>
</div>
