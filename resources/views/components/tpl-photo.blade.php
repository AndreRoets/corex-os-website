@props([
    // An illustrated stand-in for a listing photo, so the example templates
    // need no stock imagery. `kind` picks the building; the colours set the mood.
    'kind' => 'villa',
    'sky' => ['#9fc7e0', '#e8f1f6'],
    'ground' => '#7d9a6a',
    'wall' => '#f4efe6',
    'roof' => '#3b3b3b',
    'glass' => '#2c3e50',
    'sun' => '#ffffff',
])
@php $id = 'p'.\Illuminate\Support\Str::random(8); @endphp

<svg viewBox="0 0 400 260" preserveAspectRatio="xMidYMid slice" aria-hidden="true" {{ $attributes->merge(['class' => 'block h-full w-full']) }}>
    <defs>
        <linearGradient id="{{ $id }}" x1="0" y1="0" x2="0" y2="1">
            <stop offset="0" stop-color="{{ $sky[0] }}" />
            <stop offset="1" stop-color="{{ $sky[1] }}" />
        </linearGradient>
    </defs>
    <rect width="400" height="260" fill="url(#{{ $id }})" />
    <circle cx="318" cy="62" r="26" fill="{{ $sun }}" opacity=".75" />

    @switch($kind)
        @case('modern')
            <path d="M0 200 Q120 180 240 196 T400 188 V260 H0Z" fill="{{ $ground }}" opacity=".6" />
            <rect x="70" y="118" width="190" height="92" fill="{{ $wall }}" />
            <rect x="150" y="82" width="170" height="54" fill="{{ $wall }}" />
            <rect x="140" y="76" width="190" height="8" fill="{{ $roof }}" />
            <rect x="60" y="112" width="90" height="8" fill="{{ $roof }}" />
            <rect x="162" y="92" width="146" height="34" fill="{{ $glass }}" opacity=".85" />
            <rect x="84" y="134" width="56" height="62" fill="{{ $glass }}" opacity=".85" />
            <rect x="160" y="146" width="88" height="64" fill="{{ $glass }}" opacity=".7" />
            <rect x="40" y="208" width="320" height="10" fill="{{ $ground }}" />
            <rect x="96" y="214" width="190" height="14" fill="#7fc4d8" opacity=".8" />
            @break
        @case('cottage')
            <path d="M0 196 Q100 176 200 192 T400 186 V260 H0Z" fill="{{ $ground }}" />
            <rect x="110" y="128" width="180" height="84" fill="{{ $wall }}" />
            <path d="M96 132 L200 70 L304 132Z" fill="{{ $roof }}" />
            <rect x="238" y="80" width="18" height="34" fill="{{ $roof }}" />
            <rect x="184" y="160" width="32" height="52" fill="{{ $roof }}" opacity=".9" />
            <rect x="128" y="148" width="38" height="30" fill="{{ $glass }}" />
            <rect x="234" y="148" width="38" height="30" fill="{{ $glass }}" />
            <path d="M146 148 V178 M128 163 H166 M252 148 V178 M234 163 H272" stroke="{{ $wall }}" stroke-width="3" />
            <circle cx="62" cy="182" r="30" fill="{{ $ground }}" opacity=".9" />
            <circle cx="344" cy="186" r="24" fill="{{ $ground }}" opacity=".9" />
            @break
        @case('apartment')
            <rect x="40" y="96" width="70" height="164" fill="{{ $roof }}" opacity=".35" />
            <rect x="300" y="70" width="70" height="190" fill="{{ $roof }}" opacity=".35" />
            <rect x="130" y="40" width="150" height="220" fill="{{ $wall }}" />
            @for ($r = 0; $r < 7; $r++)
                @for ($c = 0; $c < 4; $c++)
                    <rect x="{{ 142 + $c * 34 }}" y="{{ 54 + $r * 28 }}" width="24" height="18" fill="{{ $glass }}" opacity="{{ ($r + $c) % 3 === 0 ? '.5' : '.9' }}" />
                @endfor
            @endfor
            <rect x="122" y="34" width="166" height="8" fill="{{ $roof }}" />
            @break
        @case('farm')
            <path d="M0 176 Q90 150 180 170 T400 160 V260 H0Z" fill="{{ $ground }}" opacity=".55" />
            <path d="M0 206 Q140 188 260 204 T400 198 V260 H0Z" fill="{{ $ground }}" />
            <rect x="120" y="136" width="150" height="70" fill="{{ $wall }}" />
            <path d="M108 140 L195 104 L282 140Z" fill="{{ $roof }}" />
            <rect x="110" y="140" width="170" height="10" fill="{{ $roof }}" opacity=".6" />
            <rect x="134" y="158" width="26" height="24" fill="{{ $glass }}" />
            <rect x="230" y="158" width="26" height="24" fill="{{ $glass }}" />
            <rect x="182" y="162" width="26" height="44" fill="{{ $roof }}" />
            <rect x="300" y="120" width="10" height="86" fill="{{ $roof }}" />
            <circle cx="305" cy="116" r="16" fill="none" stroke="{{ $roof }}" stroke-width="4" />
            @for ($i = 0; $i < 6; $i++)
                <path d="M{{ 20 + $i * 18 }} 236 v-18" stroke="{{ $roof }}" stroke-width="3" opacity=".6" />
            @endfor
            @break
        @case('beach')
            <rect y="170" width="400" height="40" fill="#5fb3c9" />
            <path d="M0 196 Q100 186 200 194 T400 190 V260 H0Z" fill="{{ $ground }}" />
            <rect x="200" y="118" width="150" height="80" fill="{{ $wall }}" />
            <rect x="190" y="110" width="170" height="10" fill="{{ $roof }}" />
            <rect x="214" y="134" width="122" height="44" fill="{{ $glass }}" opacity=".8" />
            <rect x="200" y="186" width="150" height="6" fill="{{ $roof }}" opacity=".5" />
            <path d="M92 200 Q96 150 84 112" stroke="#6b4f35" stroke-width="6" fill="none" />
            <path d="M84 112 q-34 4 -46 22 M84 112 q30 -6 46 12 M84 112 q-12 -26 -36 -30 M84 112 q16 -24 40 -24" stroke="#4f7d4a" stroke-width="8" fill="none" stroke-linecap="round" />
            @break
        @default
            <path d="M0 198 Q120 182 220 196 T400 190 V260 H0Z" fill="{{ $ground }}" />
            <rect x="60" y="120" width="280" height="86" fill="{{ $wall }}" />
            <path d="M44 124 L200 76 L356 124Z" fill="{{ $roof }}" />
            @for ($c = 0; $c < 5; $c++)
                <rect x="{{ 78 + $c * 52 }}" y="140" width="34" height="48" fill="{{ $glass }}" opacity=".9" />
            @endfor
            <rect x="60" y="200" width="280" height="8" fill="{{ $roof }}" opacity=".4" />
            <path d="M30 206 v-40 M370 206 v-46" stroke="{{ $roof }}" stroke-width="5" opacity=".7" />
            <circle cx="30" cy="160" r="14" fill="{{ $ground }}" />
            <circle cx="370" cy="154" r="16" fill="{{ $ground }}" />
    @endswitch
</svg>
