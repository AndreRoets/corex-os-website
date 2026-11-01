@php
    $listings = [
        ['apartment', '01', 'Sandton', 'Penthouse, Katherine St.', 'R 7 200 000', '210 m²'],
        ['modern', '02', 'Hyde Park', 'Courtyard house', 'R 12 800 000', '480 m²'],
        ['apartment', '03', 'Rosebank', 'Studio, The Median', 'R 1 650 000', '48 m²'],
        ['modern', '04', 'Bryanston', 'Garden pavilion', 'R 9 950 000', '390 m²'],
    ];
    $mono = ['sky' => ['#d4d4d4', '#f5f5f5'], 'ground' => '#bdbdbd', 'wall' => '#ffffff', 'roof' => '#111111', 'glass' => '#3a3a3a', 'sun' => '#ffffff'];
@endphp

<div class="min-h-screen bg-white text-[#111] [font-family:'Space_Grotesk',sans-serif]">
    <header class="flex items-center justify-between border-b border-[#111] px-6 py-5 lg:px-12">
        <span class="text-lg font-bold tracking-tight">Grid&nbsp;&amp;&nbsp;Co.</span>
        <nav class="hidden gap-10 text-sm md:flex">
            <span>Index</span><span>For sale</span><span>To let</span><span>Studio</span><span>Contact</span>
        </nav>
        <span class="text-sm">(011) 000 0000</span>
    </header>

    {{-- Hero: type first --}}
    <section class="grid border-b border-[#111] lg:grid-cols-12">
        <div class="px-6 py-16 lg:col-span-7 lg:px-12 lg:py-24">
            <p class="text-sm">Residential property — Johannesburg North</p>
            <h1 class="mt-8 text-6xl font-light leading-[0.95] tracking-[-0.04em] md:text-8xl">Less<br>noise.<br><span class="font-bold">Better homes.</span></h1>
        </div>
        <div class="border-t border-[#111] lg:col-span-5 lg:border-l lg:border-t-0">
            <x-tpl-photo kind="modern" class="h-full min-h-[360px] w-full" :sky="$mono['sky']" :ground="$mono['ground']" :wall="$mono['wall']" :roof="$mono['roof']" :glass="$mono['glass']" :sun="$mono['sun']" />
        </div>
    </section>

    {{-- Filter row --}}
    <div class="flex flex-wrap border-b border-[#111] text-sm">
        @foreach (['All 24', 'Apartments 11', 'Houses 9', 'New developments 4'] as $i => $f)
            <span class="border-r border-[#111] px-6 py-4 {{ $i === 0 ? 'bg-[#111] text-white' : '' }}">{{ $f }}</span>
        @endforeach
        <span class="ml-auto px-6 py-4">Sort: Newest ↓</span>
    </div>

    {{-- Listings as an index --}}
    <section class="grid md:grid-cols-2 lg:grid-cols-4">
        @foreach ($listings as [$kind, $n, $area, $title, $price, $size])
            <article class="border-b border-r border-[#111]">
                <div class="aspect-square">
                    <x-tpl-photo :kind="$kind" :sky="$mono['sky']" :ground="$mono['ground']" :wall="$mono['wall']" :roof="$mono['roof']" :glass="$mono['glass']" :sun="$mono['sun']" />
                </div>
                <div class="border-t border-[#111] p-5">
                    <div class="flex justify-between text-xs"><span>{{ $n }}</span><span>{{ $area }}</span></div>
                    <h3 class="mt-6 text-xl font-medium tracking-tight">{{ $title }}</h3>
                    <div class="mt-2 flex justify-between text-sm text-[#555]"><span>{{ $size }}</span><span class="text-[#111]">{{ $price }}</span></div>
                </div>
            </article>
        @endforeach
    </section>

    <section class="grid gap-8 border-b border-[#111] px-6 py-20 md:grid-cols-3 lg:px-12">
        <h2 class="text-4xl font-light tracking-[-0.03em] md:col-span-2">We list fewer homes, and give each one the attention it deserves.</h2>
        <p class="self-end text-sm leading-relaxed text-[#555]">Four agents. One area. Every listing photographed, measured and marketed by us. Ask for the index of upcoming sales before they reach the portals.</p>
    </section>

    <footer class="flex justify-between px-6 py-8 text-xs lg:px-12">
        <span>© Grid &amp; Co. Property</span>
        <span>Instagram — LinkedIn</span>
    </footer>
</div>
