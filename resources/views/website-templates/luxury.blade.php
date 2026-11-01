@php
    $listings = [
        ['villa', 'Constantia', 'Vineyard Manor', 'R 38 500 000', '6 bed · 7 bath · 1.2 ha'],
        ['modern', 'Clifton', 'The Glass House', 'R 54 000 000', '5 bed · 5 bath · sea views'],
        ['beach', 'Llandudno', 'Ocean Pavilion', 'R 41 950 000', '4 bed · 5 bath · beachfront'],
    ];
    $night = ['sky' => ['#1a1712', '#4a3d2a'], 'ground' => '#2a261e', 'wall' => '#e9dfcb', 'roof' => '#15130f', 'glass' => '#c8a96a', 'sun' => '#c8a96a'];
@endphp

<div class="min-h-screen bg-[#0c0b09] text-[#f5efe4] [font-family:'Jost',sans-serif]">
    {{-- Header --}}
    <header class="absolute inset-x-0 z-10 flex items-center justify-between px-6 py-6 lg:px-14">
        <span class="text-2xl tracking-[0.3em] [font-family:'Cormorant_Garamond',serif]">AURUM</span>
        <nav class="hidden gap-10 text-xs uppercase tracking-[0.25em] text-[#f5efe4]/80 md:flex">
            <span>Portfolio</span><span>Developments</span><span>Private Office</span><span>Journal</span>
        </nav>
        <span class="border border-[#c8a96a] px-5 py-2 text-xs uppercase tracking-[0.25em] text-[#c8a96a]">Enquire</span>
    </header>

    {{-- Hero --}}
    <section class="relative h-[640px] overflow-hidden">
        <x-tpl-photo kind="villa" class="absolute inset-0 h-full w-full opacity-70" :sky="$night['sky']" :ground="$night['ground']" :wall="$night['wall']" :roof="$night['roof']" :glass="$night['glass']" :sun="$night['sun']" />
        <div class="absolute inset-0 bg-gradient-to-t from-[#0c0b09] via-[#0c0b09]/30 to-[#0c0b09]/60"></div>
        <div class="relative flex h-full flex-col items-center justify-center px-6 text-center">
            <p class="text-xs uppercase tracking-[0.4em] text-[#c8a96a]">Cape Town · Winelands · Atlantic Seaboard</p>
            <h1 class="mt-6 max-w-4xl text-5xl font-light leading-[1.05] md:text-7xl [font-family:'Cormorant_Garamond',serif]">
                Exceptional homes,<br><em class="text-[#c8a96a]">discreetly</em> represented.
            </h1>
            <div class="mt-12 flex w-full max-w-2xl border border-[#c8a96a]/50 bg-[#0c0b09]/70 text-left text-sm backdrop-blur">
                <span class="flex-1 px-6 py-4 text-[#f5efe4]/60">Area, estate or reference</span>
                <span class="hidden border-l border-[#c8a96a]/30 px-6 py-4 text-[#f5efe4]/60 sm:block">Price range</span>
                <span class="bg-[#c8a96a] px-8 py-4 text-xs uppercase tracking-[0.25em] text-[#0c0b09]">Search</span>
            </div>
        </div>
    </section>

    {{-- Listings --}}
    <section class="px-6 py-20 lg:px-14">
        <div class="flex items-end justify-between border-b border-[#c8a96a]/30 pb-6">
            <h2 class="text-4xl font-light [font-family:'Cormorant_Garamond',serif]">The Collection</h2>
            <span class="text-xs uppercase tracking-[0.25em] text-[#c8a96a]">View all 42 &rarr;</span>
        </div>
        <div class="mt-12 grid gap-10 md:grid-cols-3">
            @foreach ($listings as [$kind, $area, $title, $price, $facts])
                <article>
                    <div class="aspect-[4/5] overflow-hidden">
                        <x-tpl-photo :kind="$kind" :sky="['#2b2419', '#7a6648']" ground="#3a3326" wall="#efe6d4" roof="#1b1813" glass="#c8a96a" sun="#e8d3a6" />
                    </div>
                    <p class="mt-6 text-xs uppercase tracking-[0.3em] text-[#c8a96a]">{{ $area }}</p>
                    <h3 class="mt-2 text-3xl font-light [font-family:'Cormorant_Garamond',serif]">{{ $title }}</h3>
                    <p class="mt-3 text-sm text-[#f5efe4]/60">{{ $facts }}</p>
                    <p class="mt-4 text-lg tracking-wide">{{ $price }}</p>
                </article>
            @endforeach
        </div>
    </section>

    {{-- Private office --}}
    <section class="grid items-center gap-12 border-y border-[#c8a96a]/20 px-6 py-20 md:grid-cols-2 lg:px-14">
        <div>
            <p class="text-xs uppercase tracking-[0.4em] text-[#c8a96a]">Private Office</p>
            <h2 class="mt-5 text-4xl font-light leading-tight md:text-5xl [font-family:'Cormorant_Garamond',serif]">Off-market sales for clients who value privacy above all.</h2>
        </div>
        <div class="grid grid-cols-3 gap-6 text-center">
            @foreach ([['R 2.1bn', 'Sold since 2009'], ['38', 'Off-market sales'], ['12', 'Specialists']] as [$n, $l])
                <div class="border border-[#c8a96a]/30 px-4 py-8">
                    <p class="text-3xl text-[#c8a96a] [font-family:'Cormorant_Garamond',serif]">{{ $n }}</p>
                    <p class="mt-2 text-[11px] uppercase tracking-[0.2em] text-[#f5efe4]/60">{{ $l }}</p>
                </div>
            @endforeach
        </div>
    </section>

    <footer class="flex flex-wrap items-center justify-between gap-4 px-6 py-10 text-xs uppercase tracking-[0.25em] text-[#f5efe4]/50 lg:px-14">
        <span class="tracking-[0.3em] text-[#f5efe4] [font-family:'Cormorant_Garamond',serif] text-lg">AURUM</span>
        <span>Constantia · Clifton · Franschhoek</span>
    </footer>
</div>
