@php
    $listings = [
        ['beach', 'Ballito', 'Salt Rock beach house', 'R 6 450 000', 4, 3],
        ['modern', 'Umhlanga', 'Ridge-top family home', 'R 8 900 000', 5, 4],
        ['apartment', 'Zimbali', 'Lock-up-and-go apartment', 'R 3 150 000', 2, 2],
    ];
@endphp

<div class="min-h-screen bg-[#f4f9fb] text-[#16323d] [font-family:'DM_Sans',sans-serif]">
    <header class="flex items-center justify-between px-6 py-5 lg:px-14">
        <span class="flex items-center gap-2 text-xl [font-family:'DM_Serif_Display',serif]">
            <span class="grid h-9 w-9 place-items-center rounded-full bg-[#1f7a99] text-white">~</span>
            Saltwater Properties
        </span>
        <nav class="hidden gap-8 text-sm font-medium text-[#16323d]/70 md:flex">
            <span>Buy</span><span>Rent</span><span>Holiday lets</span><span>Sell with us</span>
        </nav>
        <span class="rounded-full bg-[#1f7a99] px-5 py-2.5 text-sm font-medium text-white">Free valuation</span>
    </header>

    {{-- Hero --}}
    <section class="px-6 lg:px-14">
        <div class="relative overflow-hidden rounded-[2.5rem]">
            <x-tpl-photo kind="beach" class="h-[520px] w-full" :sky="['#7cc3dd', '#e6f4f8']" ground="#f2d7b6" wall="#ffffff" roof="#1f7a99" glass="#16323d" sun="#fff7e0" />
            <div class="absolute inset-0 bg-gradient-to-r from-[#16323d]/70 via-[#16323d]/20 to-transparent"></div>
            <div class="absolute inset-y-0 left-0 flex max-w-xl flex-col justify-center px-10 text-white lg:px-16">
                <p class="text-sm font-medium uppercase tracking-[0.2em] text-[#f2d7b6]">KwaZulu-Natal North Coast</p>
                <h1 class="mt-4 text-5xl leading-[1.05] md:text-6xl [font-family:'DM_Serif_Display',serif]">Life is better by the sea.</h1>
                <p class="mt-5 text-lg text-white/85">Homes, apartments and holiday lets from Umhlanga to Salt Rock.</p>
            </div>
        </div>

        <div class="relative z-10 mx-auto -mt-10 flex max-w-4xl flex-wrap items-center gap-2 rounded-full bg-white p-2 shadow-[0_20px_50px_-20px_rgba(22,50,61,.35)]">
            @foreach (['Buy', 'Rent'] as $i => $tab)
                <span class="rounded-full px-5 py-3 text-sm font-medium {{ $i === 0 ? 'bg-[#1f7a99] text-white' : 'text-[#16323d]/60' }}">{{ $tab }}</span>
            @endforeach
            <span class="flex-1 px-4 text-sm text-[#16323d]/50">Town, suburb or estate</span>
            <span class="hidden px-4 text-sm text-[#16323d]/50 sm:block">Any price</span>
            <span class="rounded-full bg-[#f2d7b6] px-7 py-3 text-sm font-bold text-[#16323d]">Search</span>
        </div>
    </section>

    {{-- Listings --}}
    <section class="px-6 py-20 lg:px-14">
        <h2 class="text-center text-4xl [font-family:'DM_Serif_Display',serif]">Just listed on the coast</h2>
        <div class="mt-12 grid gap-8 md:grid-cols-3">
            @foreach ($listings as [$kind, $area, $title, $price, $beds, $baths])
                <article class="overflow-hidden rounded-3xl bg-white shadow-[0_10px_40px_-20px_rgba(22,50,61,.3)]">
                    <div class="relative h-56">
                        <x-tpl-photo :kind="$kind" :sky="['#8fd0e6', '#eaf6fa']" ground="#f2d7b6" wall="#ffffff" roof="#1f7a99" glass="#16323d" sun="#fff7e0" />
                        <span class="absolute left-4 top-4 rounded-full bg-white/90 px-3 py-1 text-xs font-bold text-[#1f7a99]">New</span>
                    </div>
                    <div class="p-6">
                        <p class="text-sm text-[#1f7a99]">{{ $area }}</p>
                        <h3 class="mt-1 text-xl [font-family:'DM_Serif_Display',serif]">{{ $title }}</h3>
                        <div class="mt-5 flex items-center justify-between border-t border-[#16323d]/10 pt-4 text-sm">
                            <span class="text-[#16323d]/60">{{ $beds }} beds · {{ $baths }} baths</span>
                            <span class="font-bold">{{ $price }}</span>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    </section>

    {{-- Valuation band --}}
    <section class="mx-6 mb-16 flex flex-col items-center justify-between gap-6 rounded-[2rem] bg-[#1f7a99] px-10 py-12 text-white md:flex-row lg:mx-14">
        <div>
            <h2 class="text-3xl [font-family:'DM_Serif_Display',serif]">Thinking of selling?</h2>
            <p class="mt-2 text-white/80">A free, no-obligation valuation from a local coastal specialist.</p>
        </div>
        <span class="rounded-full bg-white px-7 py-3.5 text-sm font-bold text-[#1f7a99]">Book my valuation</span>
    </section>

    <footer class="flex flex-wrap justify-between gap-4 px-6 pb-10 text-sm text-[#16323d]/60 lg:px-14">
        <span>Saltwater Properties · Ballito</span>
        <span>032 000 0000 · hello@saltwater.example</span>
    </footer>
</div>
