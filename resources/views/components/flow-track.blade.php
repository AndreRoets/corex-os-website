@props(['steps', 'accent' => 'brand'])

@php
    $dot = $accent === 'cyan' ? 'var(--color-cyan)' : 'var(--color-brand)';
@endphp

{{-- Horizontal on large screens (scrollable), vertical rail on small screens. --}}
<ol class="relative flex flex-col gap-0 lg:flex-row lg:gap-0 lg:overflow-x-auto lg:pb-4 [scrollbar-width:thin]">
    @foreach ($steps as $i => [$ico, $title, $sub])
        <li class="group relative flex lg:flex-1 lg:min-w-[9.5rem] lg:flex-col">
            {{-- Connector line --}}
            @if (! $loop->last)
                {{-- vertical (mobile) --}}
                <span class="absolute left-[19px] top-10 h-full w-px bg-gradient-to-b from-[color:var(--color-border)] to-transparent lg:hidden" aria-hidden="true"></span>
                {{-- horizontal (desktop) --}}
                <span class="absolute hidden lg:block left-1/2 top-[19px] h-px w-full bg-gradient-to-r from-[color:var(--color-border)] to-transparent" aria-hidden="true"></span>
            @endif

            <div class="flex items-start gap-4 pb-8 lg:flex-col lg:items-start lg:gap-0 lg:pb-0 lg:pr-6">
                <span class="relative z-10 grid h-10 w-10 shrink-0 place-items-center rounded-full border bg-[color:var(--color-surface)] text-[color:var(--color-ink)] transition duration-300 group-hover:-translate-y-0.5"
                      style="border-color: color-mix(in srgb, {{ $dot }} 55%, var(--color-border));">
                    <span class="absolute inset-0 rounded-full opacity-0 transition duration-300 group-hover:opacity-100"
                          style="box-shadow: 0 0 0 4px color-mix(in srgb, {{ $dot }} 18%, transparent);"></span>
                    <x-icon :name="$ico" class="w-[18px] h-[18px]" style="color: {{ $dot }}" />
                </span>

                <div class="lg:mt-4">
                    <div class="flex items-center gap-2">
                        <span class="font-mono text-[11px] text-[color:var(--color-faint)]">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                    </div>
                    <h3 class="mt-0.5 text-sm font-semibold text-ink">{{ $title }}</h3>
                    <p class="mt-1 text-xs leading-snug text-[color:var(--color-muted)] lg:max-w-[9rem]">{{ $sub }}</p>
                </div>
            </div>
        </li>
    @endforeach
</ol>
