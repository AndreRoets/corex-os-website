@php
    $links = [
        ['#pillars', 'Pillars'],
        ['#flows', 'Flows'],
        ['#ellie', 'Ellie AI'],
        ['#features', 'Modules'],
        ['#compliance', 'Compliance'],
        ['#control', 'Control'],
    ];
@endphp

<header
    x-data="{ scrolled: false }"
    @scroll.window="scrolled = window.scrollY > 12"
    class="fixed inset-x-0 top-0 z-50 transition duration-300"
    :class="scrolled
        ? 'backdrop-blur-xl bg-[color:color-mix(in_srgb,var(--color-bg)_78%,transparent)] border-b border-[color:var(--color-border)]'
        : 'border-b border-transparent'"
>
    <nav class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-5 sm:px-8 h-16" aria-label="Primary">
        <a href="{{ route('home') }}" class="flex items-center gap-2.5 shrink-0" aria-label="CoreX OS home">
            <x-brand-mark size="md" />
            <x-logo class="text-lg" />
        </a>

        <div class="hidden lg:flex items-center gap-1">
            @foreach ($links as [$href, $label])
                <a href="{{ $href }}"
                   @click.prevent="$store.site.revealSection('{{ \Illuminate\Support\Str::after($href, '#') }}')"
                   class="rounded-md px-3 py-2 text-sm text-[color:var(--color-muted)] hover:text-ink transition duration-300">{{ $label }}</a>
            @endforeach
        </div>

        <div class="flex items-center gap-2">
            {{-- Theme toggle --}}
            <button
                @click="$store.site.toggleTheme()"
                class="grid h-9 w-9 place-items-center rounded-md border border-[color:var(--color-border)] text-[color:var(--color-muted)] hover:text-ink hover:border-[color:var(--color-brand)] transition duration-300"
                :aria-label="$store.site.theme === 'dark' ? 'Switch to light theme' : 'Switch to dark theme'"
            >
                <x-icon name="sun" class="w-[18px] h-[18px]" x-show="$store.site.theme === 'dark'" />
                <x-icon name="moon" class="w-[18px] h-[18px]" x-show="$store.site.theme === 'light'" x-cloak />
            </button>

            <x-btn href="#demo" size="sm" class="hidden sm:inline-flex">Book a demo</x-btn>

            {{-- Mobile menu button --}}
            <button
                @click="$store.site.mobileNavOpen = true"
                class="lg:hidden grid h-9 w-9 place-items-center rounded-md border border-[color:var(--color-border)] text-ink"
                aria-label="Open menu"
            >
                <x-icon name="menu" class="w-5 h-5" />
            </button>
        </div>
    </nav>
</header>

{{-- Mobile nav overlay --}}
<div
    x-data
    x-show="$store.site.mobileNavOpen"
    x-cloak
    @keydown.escape.window="$store.site.mobileNavOpen = false"
    class="fixed inset-0 z-[60] lg:hidden"
    role="dialog"
    aria-modal="true"
    aria-label="Menu"
>
    <div
        x-show="$store.site.mobileNavOpen"
        x-transition.opacity
        @click="$store.site.mobileNavOpen = false"
        class="absolute inset-0 bg-black/60 backdrop-blur-sm"
    ></div>

    <div
        x-show="$store.site.mobileNavOpen"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="translate-x-full"
        x-trap.noscroll="$store.site.mobileNavOpen"
        class="absolute right-0 top-0 h-full w-[82%] max-w-sm bg-[color:var(--color-surface)] border-l border-[color:var(--color-border)] p-6 flex flex-col"
    >
        <div class="flex items-center justify-between">
            <span class="flex items-center gap-2.5">
                <x-brand-mark size="md" />
                <x-logo class="text-lg" />
            </span>
            <button @click="$store.site.mobileNavOpen = false" class="grid h-9 w-9 place-items-center rounded-md border border-[color:var(--color-border)] text-ink" aria-label="Close menu">
                <x-icon name="close" class="w-5 h-5" />
            </button>
        </div>

        <div class="mt-8 flex flex-col gap-1">
            @foreach ($links as [$href, $label])
                <a href="{{ $href }}" @click.prevent="$store.site.goToSection('{{ \Illuminate\Support\Str::after($href, '#') }}')" class="rounded-md px-3 py-3 text-base text-ink hover:bg-[color:var(--color-surface-2)] transition duration-300">{{ $label }}</a>
            @endforeach
        </div>

        <div class="mt-auto pt-6">
            <x-btn href="#demo" size="lg" class="w-full" @click="$store.site.mobileNavOpen = false">
                Book a demo
                <x-icon name="arrow-right" class="w-4 h-4" />
            </x-btn>
        </div>
    </div>
</div>
