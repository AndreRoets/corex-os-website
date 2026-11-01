@php
    $listings = [
        ['farm', 'Nottingham Road', 'Trout farm with cottages', 'R 14 500 000', '86 ha'],
        ['farm', 'Howick', 'Smallholding with stables', 'R 4 950 000', '5.2 ha'],
        ['cottage', 'Balgowan', 'Stone cottage on a lifestyle estate', 'R 3 200 000', '1.1 ha'],
    ];
    $veld = ['sky' => ['#c9d8c0', '#f1f0e4'], 'ground' => '#5f7a4f', 'wall' => '#efe8d8', 'roof' => '#3f5a3c', 'glass' => '#2b3a2a', 'sun' => '#f3d58c'];
@endphp

<div class="min-h-screen bg-[#eef0e6] text-[#26321f] [font-family:'Work_Sans',sans-serif]">
    <header class="flex items-center justify-between px-6 py-6 lg:px-14">
        <span class="text-2xl italic [font-family:'Lora',serif]">Veld <span class="not-italic text-[#c99a4b]">&amp;</span> Vine</span>
        <nav class="hidden gap-8 text-sm md:flex">
            <span>Farms</span><span>Smallholdings</span><span>Lifestyle estates</span><span>Area guides</span>
        </nav>
        <span class="rounded-sm border border-[#3f5a3c] px-5 py-2 text-sm">Arrange a visit</span>
    </header>

    {{-- Hero --}}
    <section class="relative mx-6 overflow-hidden rounded-sm lg:mx-14">
        <x-tpl-photo kind="farm" class="h-[560px] w-full" :sky="['#b8cfc0', '#f4ecd4']" :ground="$veld['ground']" :wall="$veld['wall']" :roof="$veld['roof']" :glass="$veld['glass']" :sun="$veld['sun']" />
        <div class="absolute inset-0 bg-gradient-to-t from-[#26321f]/80 via-transparent to-transparent"></div>
        <div class="absolute bottom-0 left-0 max-w-2xl p-10 text-[#f1f0e4] lg:p-14">
            <p class="text-sm uppercase tracking-[0.25em] text-[#f3d58c]">KZN Midlands</p>
            <h1 class="mt-4 text-5xl leading-tight md:text-6xl [font-family:'Lora',serif]">Room to breathe, land to grow.</h1>
            <p class="mt-4 text-lg text-[#f1f0e4]/85">Working farms, smallholdings and country homes, sold by people who know the land.</p>
        </div>
    </section>

    {{-- Property type tiles --}}
    <section class="grid gap-4 px-6 py-12 sm:grid-cols-4 lg:px-14">
        @foreach ([['Working farms', 18], ['Smallholdings', 27], ['Lifestyle estates', 12], ['Country cottages', 9]] as [$type, $count])
            <div class="border-t-2 border-[#3f5a3c] pt-4">
                <p class="text-xl [font-family:'Lora',serif]">{{ $type }}</p>
                <p class="text-sm text-[#26321f]/60">{{ $count }} available</p>
            </div>
        @endforeach
    </section>

    {{-- Listings --}}
    <section class="px-6 pb-20 lg:px-14">
        <h2 class="text-4xl [font-family:'Lora',serif]">On the market this season</h2>
        <div class="mt-10 grid gap-10 md:grid-cols-3">
            @foreach ($listings as [$kind, $area, $title, $price, $land])
                <article>
                    <div class="h-64 overflow-hidden rounded-sm">
                        <x-tpl-photo :kind="$kind" :sky="$veld['sky']" :ground="$veld['ground']" :wall="$veld['wall']" :roof="$veld['roof']" :glass="$veld['glass']" :sun="$veld['sun']" />
                    </div>
                    <div class="mt-5 flex items-start justify-between gap-4">
                        <div>
                            <h3 class="text-xl [font-family:'Lora',serif]">{{ $title }}</h3>
                            <p class="mt-1 text-sm text-[#26321f]/60">{{ $area }}</p>
                        </div>
                        <span class="rounded-sm bg-[#3f5a3c] px-2.5 py-1 text-xs text-[#f1f0e4]">{{ $land }}</span>
                    </div>
                    <p class="mt-3 font-medium text-[#8a6a2f]">{{ $price }}</p>
                </article>
            @endforeach
        </div>
    </section>

    <section class="bg-[#3f5a3c] px-6 py-16 text-[#f1f0e4] lg:px-14">
        <blockquote class="mx-auto max-w-3xl text-center text-2xl italic leading-relaxed [font-family:'Lora',serif]">
            “They understood water rights, zoning and what makes a farm work. We sold in six weeks.”
            <footer class="mt-5 text-sm not-italic text-[#f3d58c] [font-family:'Work_Sans',sans-serif]">The Dlamini family, Rosetta</footer>
        </blockquote>
    </section>

    <footer class="px-6 py-8 text-sm text-[#26321f]/60 lg:px-14">Veld &amp; Vine Country Property · Howick, KZN</footer>
</div>
