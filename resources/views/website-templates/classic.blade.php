@php
    $listings = [
        ['cottage', 'Faerie Glen', 'Family home with pool and flatlet', 'R 3 495 000', 4, 3, 2],
        ['villa', 'Silver Lakes', 'Golf estate home on a quiet close', 'R 5 750 000', 4, 4, 3],
        ['cottage', 'Lynnwood', 'Classic face-brick on a big stand', 'R 2 890 000', 3, 2, 2],
    ];
    $agents = [['TM', 'Thandi M.', 'Faerie Glen'], ['PV', 'Pieter v.d. M.', 'Silver Lakes'], ['RN', 'Rekha N.', 'Lynnwood']];
    $warm = ['sky' => ['#f3c9a8', '#fbf0e2'], 'ground' => '#8a9a6b', 'wall' => '#fbf6ee', 'roof' => '#b65a3a', 'glass' => '#2f3a35', 'sun' => '#fff3df'];
@endphp

<div class="min-h-screen bg-[#fbf6ee] text-[#2f3a35] [font-family:'Nunito_Sans',sans-serif]">
    <div class="bg-[#2f3a35] px-6 py-2 text-center text-xs text-[#fbf6ee]/80 lg:px-14">Serving Pretoria East families since 1998 · 012 000 0000</div>
    <header class="flex items-center justify-between px-6 py-6 lg:px-14">
        <span class="text-2xl font-semibold [font-family:'Fraunces',serif]">Hearth <span class="text-[#b65a3a]">Homes</span></span>
        <nav class="hidden gap-8 text-sm font-semibold md:flex">
            <span>Homes for sale</span><span>To rent</span><span>Our agents</span><span>Selling advice</span>
        </nav>
        <span class="rounded-lg bg-[#b65a3a] px-5 py-2.5 text-sm font-bold text-white">Call us</span>
    </header>

    {{-- Hero --}}
    <section class="grid items-center gap-12 px-6 pb-16 pt-6 lg:grid-cols-2 lg:px-14">
        <div>
            <h1 class="text-5xl font-semibold leading-[1.1] md:text-6xl [font-family:'Fraunces',serif]">Where your family's next chapter begins.</h1>
            <p class="mt-6 text-lg leading-relaxed text-[#2f3a35]/75">Good schools, safe streets and a garden for the dog. We know these suburbs because we live in them.</p>
            <div class="mt-8 rounded-xl border border-[#2f3a35]/15 bg-white p-4 shadow-sm">
                <div class="grid gap-3 text-sm sm:grid-cols-3">
                    <span class="rounded-lg bg-[#fbf6ee] px-4 py-3 text-[#2f3a35]/60">Suburb</span>
                    <span class="rounded-lg bg-[#fbf6ee] px-4 py-3 text-[#2f3a35]/60">Bedrooms</span>
                    <span class="rounded-lg bg-[#b65a3a] px-4 py-3 text-center font-bold text-white">Find a home</span>
                </div>
            </div>
        </div>
        <div class="relative">
            <div class="overflow-hidden rounded-t-[12rem] rounded-b-2xl">
                <x-tpl-photo kind="cottage" class="h-[460px] w-full" :sky="$warm['sky']" :ground="$warm['ground']" :wall="$warm['wall']" :roof="$warm['roof']" :glass="$warm['glass']" :sun="$warm['sun']" />
            </div>
            <div class="absolute -bottom-6 -left-6 rounded-xl bg-white px-5 py-4 shadow-lg">
                <p class="text-3xl font-semibold text-[#b65a3a] [font-family:'Fraunces',serif]">1 400+</p>
                <p class="text-xs font-semibold uppercase tracking-wider text-[#2f3a35]/60">Families moved home</p>
            </div>
        </div>
    </section>

    {{-- Listings --}}
    <section class="bg-white px-6 py-20 lg:px-14">
        <h2 class="text-4xl font-semibold [font-family:'Fraunces',serif]">Featured homes</h2>
        <div class="mt-10 grid gap-8 md:grid-cols-3">
            @foreach ($listings as [$kind, $area, $title, $price, $beds, $baths, $garages])
                <article class="overflow-hidden rounded-2xl border border-[#2f3a35]/10 bg-[#fbf6ee]">
                    <div class="h-56"><x-tpl-photo :kind="$kind" :sky="$warm['sky']" :ground="$warm['ground']" :wall="$warm['wall']" :roof="$warm['roof']" :glass="$warm['glass']" :sun="$warm['sun']" /></div>
                    <div class="p-6">
                        <p class="text-2xl font-semibold text-[#b65a3a] [font-family:'Fraunces',serif]">{{ $price }}</p>
                        <h3 class="mt-1 font-bold">{{ $title }}</h3>
                        <p class="text-sm text-[#2f3a35]/60">{{ $area }}, Pretoria</p>
                        <p class="mt-4 flex gap-4 border-t border-[#2f3a35]/10 pt-4 text-sm font-semibold"><span>{{ $beds }} bed</span><span>{{ $baths }} bath</span><span>{{ $garages }} garage</span></p>
                    </div>
                </article>
            @endforeach
        </div>
    </section>

    {{-- Agents --}}
    <section class="px-6 py-20 text-center lg:px-14">
        <h2 class="text-4xl font-semibold [font-family:'Fraunces',serif]">Meet your neighbours</h2>
        <p class="mt-3 text-[#2f3a35]/70">Each of our agents farms one suburb, and knows every street in it.</p>
        <div class="mt-10 flex flex-wrap justify-center gap-10">
            @foreach ($agents as [$initials, $name, $area])
                <div>
                    <span class="mx-auto grid h-24 w-24 place-items-center rounded-full bg-[#e9c9a8] text-2xl font-semibold text-[#b65a3a] [font-family:'Fraunces',serif]">{{ $initials }}</span>
                    <p class="mt-3 font-bold">{{ $name }}</p>
                    <p class="text-sm text-[#2f3a35]/60">{{ $area }}</p>
                </div>
            @endforeach
        </div>
    </section>

    <footer class="bg-[#2f3a35] px-6 py-8 text-sm text-[#fbf6ee]/70 lg:px-14">Hearth Homes · Pretoria East · A family agency</footer>
</div>
