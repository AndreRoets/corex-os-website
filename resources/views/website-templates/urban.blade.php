@php
    $listings = [
        ['apartment', 'Maboneng', 'Loft w/ rooftop', 'R 1 290 000', 'bg-[#ff6b9a]'],
        ['apartment', 'Braamfontein', 'Studio, 9th floor', 'R 7 800 /pm', 'bg-[#6bd3ff]'],
        ['modern', 'Melville', 'Converted warehouse', 'R 2 450 000', 'bg-[#8cff6b]'],
    ];
@endphp

<div class="min-h-screen bg-[#fff8e1] text-[#141414] [font-family:'Archivo',sans-serif]">
    <header class="flex items-center justify-between border-b-4 border-[#141414] bg-[#ffd400] px-6 py-4 lg:px-12">
        <span class="text-3xl [font-family:'Archivo_Black',sans-serif]">BLOK.</span>
        <nav class="hidden gap-3 text-sm font-semibold uppercase md:flex">
            @foreach (['Buy', 'Rent', 'New builds', 'Sell'] as $item)
                <span class="border-2 border-[#141414] bg-white px-4 py-1.5">{{ $item }}</span>
            @endforeach
        </nav>
        <span class="border-2 border-[#141414] bg-[#141414] px-4 py-2 text-sm font-semibold uppercase text-[#ffd400]">List with us</span>
    </header>

    {{-- Hero --}}
    <section class="grid gap-8 px-6 py-14 lg:grid-cols-2 lg:px-12">
        <div class="flex flex-col justify-center">
            <p class="inline-block self-start -rotate-2 border-2 border-[#141414] bg-[#ff6b9a] px-3 py-1 text-sm font-semibold uppercase">Joburg city living</p>
            <h1 class="mt-6 text-6xl uppercase leading-[0.9] md:text-8xl [font-family:'Archivo_Black',sans-serif]">Your first<br><span class="inline-block bg-[#ffd400] px-2 leading-[0.9]">place</span><br>starts here.</h1>
            <div class="mt-8 flex max-w-lg border-4 border-[#141414] bg-white shadow-[8px_8px_0_#141414]">
                <span class="flex-1 px-5 py-4 text-[#141414]/50">Suburb, building or vibe…</span>
                <span class="border-l-4 border-[#141414] bg-[#141414] px-6 py-4 font-semibold uppercase text-[#ffd400]">Go</span>
            </div>
        </div>
        <div class="border-4 border-[#141414] shadow-[12px_12px_0_#141414]">
            <x-tpl-photo kind="apartment" class="h-[440px] w-full" :sky="['#ffd400', '#fff1a6']" ground="#141414" wall="#ffffff" roof="#141414" glass="#141414" sun="#ff6b9a" />
        </div>
    </section>

    {{-- Marquee strip --}}
    <div class="overflow-hidden border-y-4 border-[#141414] bg-[#141414] py-3 text-xl uppercase whitespace-nowrap text-[#ffd400] [font-family:'Archivo_Black',sans-serif]">
        ★ No-nonsense listings ★ Bond-ready in a week ★ Pet-friendly rentals ★ Rooftops ★ Fibre-ready ★ No-nonsense listings ★
    </div>

    {{-- Listings --}}
    <section class="px-6 py-16 lg:px-12">
        <h2 class="text-5xl uppercase [font-family:'Archivo_Black',sans-serif]">Hot right now</h2>
        <div class="mt-10 grid gap-8 md:grid-cols-3">
            @foreach ($listings as [$kind, $area, $title, $price, $tint])
                <article class="border-4 border-[#141414] bg-white shadow-[8px_8px_0_#141414]">
                    <div class="h-52 border-b-4 border-[#141414] {{ $tint }}">
                        <x-tpl-photo :kind="$kind" :sky="['transparent', 'transparent']" ground="#141414" wall="#ffffff" roof="#141414" glass="#141414" sun="#ffd400" />
                    </div>
                    <div class="p-5">
                        <span class="border-2 border-[#141414] px-2 py-0.5 text-xs font-semibold uppercase">{{ $area }}</span>
                        <h3 class="mt-4 text-2xl uppercase [font-family:'Archivo_Black',sans-serif]">{{ $title }}</h3>
                        <p class="mt-2 text-lg font-semibold">{{ $price }}</p>
                    </div>
                </article>
            @endforeach
        </div>
    </section>

    <footer class="flex flex-wrap justify-between gap-4 border-t-4 border-[#141414] bg-[#ffd400] px-6 py-6 font-semibold uppercase lg:px-12">
        <span class="[font-family:'Archivo_Black',sans-serif]">BLOK.</span>
        <span>@blokrealty · 010 000 0000</span>
    </footer>
</div>
