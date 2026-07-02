{{-- Interactive permission matrix — roles as columns, capabilities as rows.
     Demo only (no backend); tapping a cell toggles the grant. --}}
<div class="card overflow-hidden shadow-xl shadow-black/20"
     x-data="{
         roles: ['Principal', 'Agency admin', 'Branch manager', 'Agent'],
         caps: [
             { name: 'View across all branches', grants: [true, true, false, false] },
             { name: 'Create &amp; edit deals',   grants: [true, true, true, true] },
             { name: 'Delete records',            grants: [true, true, false, false] },
             { name: 'Manage users &amp; roles',  grants: [true, true, false, false] },
             { name: 'Branch oversight',          grants: [true, false, true, false] },
             { name: 'Configure settings',        grants: [true, true, false, false] },
         ],
     }">
    <div class="flex items-center justify-between border-b border-[color:var(--color-border)] bg-[color:var(--color-surface-2)] px-4 py-3">
        <div class="flex items-center gap-2 text-[color:var(--color-brand-400)]">
            <x-icon name="key" class="w-4 h-4" />
            <span class="font-mono text-xs uppercase tracking-[0.14em]">Role Manager</span>
        </div>
        <span class="hidden sm:block text-[11px] text-[color:var(--color-faint)]">Tap a cell to toggle</span>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full border-collapse text-sm">
            <thead>
                <tr class="border-b border-[color:var(--color-border)]">
                    <th scope="col" class="px-4 py-3 text-left text-[11px] font-medium uppercase tracking-wider text-[color:var(--color-faint)]">Capability</th>
                    <template x-for="r in roles" :key="r">
                        <th scope="col" class="px-2 py-3 text-center">
                            <span class="block text-[11px] font-semibold leading-tight text-ink" x-text="r"></span>
                        </th>
                    </template>
                </tr>
            </thead>
            <tbody>
                <template x-for="(cap, ri) in caps" :key="cap.name">
                    <tr class="border-b border-[color:var(--color-border-soft)] last:border-0">
                        <th scope="row" class="px-4 py-2.5 text-left text-xs font-medium text-[color:var(--color-muted)]" x-html="cap.name"></th>
                        <template x-for="(g, ci) in cap.grants" :key="ci">
                            <td class="px-2 py-2 text-center">
                                <button type="button"
                                        @click="cap.grants[ci] = !cap.grants[ci]"
                                        :aria-pressed="cap.grants[ci] ? 'true' : 'false'"
                                        :aria-label="(cap.grants[ci] ? 'Revoke ' : 'Grant ') + cap.name.replace('&amp;','and') + ' for ' + roles[ci]"
                                        class="mx-auto grid h-7 w-7 place-items-center rounded-md transition duration-300"
                                        :class="cap.grants[ci]
                                            ? 'bg-[color:var(--color-brand)]/15 text-[color:var(--color-brand-400)] ring-1 ring-inset ring-[color:var(--color-brand)]/40'
                                            : 'text-[color:var(--color-faint)] hover:bg-[color:var(--color-surface-2)]'">
                                    <svg x-show="cap.grants[ci]" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
                                    <span x-show="!cap.grants[ci]" class="h-px w-2.5 rounded bg-current" aria-hidden="true"></span>
                                </button>
                            </td>
                        </template>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>

    <div class="flex flex-wrap items-center gap-1.5 border-t border-[color:var(--color-border)] px-4 py-3">
        <span class="mr-1 text-[11px] text-[color:var(--color-faint)]">Every key is separate:</span>
        @foreach (['View', 'Create', 'Edit', 'Delete', 'Manage', 'Oversight'] as $key)
            <span class="rounded border border-[color:var(--color-border)] bg-[color:var(--color-bg-soft)] px-2 py-0.5 font-mono text-[10px] text-[color:var(--color-muted)]">{{ $key }}</span>
        @endforeach
    </div>
</div>
