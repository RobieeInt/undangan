<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <title>{{ $invitation->getCoupleName() }} — Undangan Pernikahan</title>
    <meta name="description" content="Undangan Pernikahan {{ $invitation->getCoupleName() }}">
    <meta property="og:title" content="Undangan: {{ $invitation->getCoupleName() }}">
    <meta property="og:image" content="{{ $invitation->cover_photo_url ?? asset('img/og-default.jpg') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;1,400&family=Cormorant+Garamond:ital,wght@0,300;0,400;1,400&family=Montserrat:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css">
    {{-- swiper removed, replaced by collage --}}

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    @livewireScriptConfig

    <style>
        :root {
            --cream: #FBF5DD; --sand: #E7E1B1; --forest: #306D29; --emerald: #0D530E;
        }
        body { background: var(--cream); font-family: 'Montserrat', sans-serif; overflow-x: hidden; }
        .font-serif-luxury { font-family: 'Playfair Display', serif; }
        .font-cormorant { font-family: 'Cormorant Garamond', serif; }
        .section-divider { display: flex; align-items: center; gap: 1rem; }
        .section-divider::before, .section-divider::after { content:''; flex:1; height:1px; background: linear-gradient(to right, transparent, var(--sand), transparent); }
        .ornament { color: var(--forest); opacity: 0.4; font-size: 1.5rem; }

        /* ── Global leaf canvas ── */
        #ei-global-canvas { position:fixed;inset:0;pointer-events:none;z-index:92;overflow:hidden; }
        /* ── Cover + global leaf animation ── */
        .ei-leaf { position: absolute; pointer-events: none; }
        @keyframes eiLeafFall {
            0%   { transform: translateY(-80px) translateX(0) rotate(0deg); opacity: 0; }
            10%  { opacity: 0.85; }
            88%  { opacity: 0.55; }
            100% { transform: translateY(110vh) translateX(var(--drift,40px)) rotate(var(--spin,360deg)); opacity: 0; }
        }
        /* Islamic rosette slow spin */
        @keyframes eiRotate {
            from { transform: rotate(0deg); }
            to   { transform: rotate(360deg); }
        }
        @keyframes eiPulse {
            0%, 100% { opacity: 0.06; transform: scale(1); }
            50%       { opacity: 0.12; transform: scale(1.04); }
        }
        .ei-rosette { animation: eiRotate 40s linear infinite, eiPulse 6s ease-in-out infinite; }
        /* Cover text stagger */
        @keyframes eiSlideUp {
            from { opacity: 0; transform: translateY(18px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .ei-in   { animation: eiSlideUp 0.9s ease-out both; }
        .ei-d1   { animation-delay: 0.25s; }
        .ei-d2   { animation-delay: 0.55s; }
        .ei-d3   { animation-delay: 0.85s; }
        .ei-d4   { animation-delay: 1.15s; }
        .ei-d5   { animation-delay: 1.45s; }
    </style>
</head>
<body x-data="{ opened: false }" @keydown.window.escape="opened = false"
      x-init="$store.invitation.initMusic('{{ $invitation->music_url }}', {{ $invitation->music_autoplay ? 'true' : 'false' }})">

{{-- ════════════════════════════════════════════ --}}
{{-- ENVELOPE OPENING SCREEN                      --}}
{{-- ════════════════════════════════════════════ --}}
<div data-coi-cover
     x-show="!opened" x-transition:leave="transition ease-in duration-500"
     x-transition:leave-start="opacity-100 scale-100"
     x-transition:leave-end="opacity-0 scale-95"
     class="fixed inset-0 z-[100] flex flex-col items-center justify-center bg-cream"
     style="background: linear-gradient(160deg, #FBF5DD 0%, #E7E1B1 100%);">

    {{-- Animated decorative elements --}}
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        {{-- Leaf particle canvas --}}
        <div class="absolute inset-0" id="ei-leaf-canvas"></div>

        {{-- Soft glow blobs --}}
        <div class="absolute -top-20 -left-20 w-80 h-80 rounded-full" style="background: radial-gradient(circle, rgba(48,109,41,0.07) 0%, transparent 70%)"></div>
        <div class="absolute -bottom-20 -right-20 w-80 h-80 rounded-full" style="background: radial-gradient(circle, rgba(48,109,41,0.09) 0%, transparent 70%)"></div>

        {{-- Islamic geometric rosette - large, very subtle, center background --}}
        <div class="absolute inset-0 flex items-center justify-center">
            <svg class="ei-rosette w-[380px] h-[380px]" viewBox="0 0 200 200" fill="none" style="opacity:0.07">
                <g transform="translate(100,100)">
                    <polygon points="0,-80 13.9,-76.8 26.4,-67.1 36,-52.9 40,-36 38.6,-18.1 32,-2 21.1,11.6 7.2,21.3 -7.2,21.3 -21.1,11.6 -32,-2 -38.6,-18.1 -40,-36 -36,-52.9 -26.4,-67.1 -13.9,-76.8" stroke="#306D29" stroke-width="1" fill="rgba(48,109,41,0.15)"/>
                    <polygon points="0,-60 10.4,-57.6 19.8,-50.3 27,-39.7 30,-27 29,-13.6 24,-1.5 15.9,8.7 5.4,16 -5.4,16 -15.9,8.7 -24,-1.5 -29,-13.6 -30,-27 -27,-39.7 -19.8,-50.3 -10.4,-57.6" stroke="#306D29" stroke-width="0.8" fill="rgba(48,109,41,0.1)"/>
                    @for($r = 0; $r < 8; $r++)
                    <line x1="0" y1="-80" x2="0" y2="-50" stroke="#306D29" stroke-width="0.6" transform="rotate({{ $r * 45 }})"/>
                    <circle cx="0" cy="-80" r="3" fill="#306D29" opacity=".4" transform="rotate({{ $r * 45 }})"/>
                    @endfor
                    <circle r="12" fill="rgba(48,109,41,0.2)" stroke="#306D29" stroke-width="0.8"/>
                    <circle r="6"  fill="rgba(48,109,41,0.3)"/>
                </g>
            </svg>
        </div>

        {{-- Floating leaf SVG static decorations (corners) --}}
        <svg class="absolute top-6 left-4 w-16 h-16 opacity-20" viewBox="0 0 60 60" fill="none" style="animation:eiLeafFall 12s ease-in-out 0s infinite;--drift:15px;--spin:30deg">
            <path d="M30 5 C10 10,5 35,15 50 C20 58,30 58,30 58 C30 58,40 58,45 50 C55 35,50 10,30 5Z" fill="#306D29"/>
            <path d="M30 10 C18 18,15 38,22 50 C25 55,30 55,30 55" stroke="#0D530E" stroke-width="1" fill="none"/>
            <path d="M30 10 C22 20,22 38,26 50" stroke="#0D530E" stroke-width="0.6" fill="none" opacity=".5"/>
            <path d="M30 10 C38 20,38 38,34 50" stroke="#0D530E" stroke-width="0.6" fill="none" opacity=".5"/>
        </svg>
        <svg class="absolute top-12 right-6 w-10 h-10 opacity-15" viewBox="0 0 60 60" fill="none" style="animation:eiLeafFall 9s ease-in-out -3s infinite;--drift:-12px;--spin:-40deg">
            <path d="M30 5 C10 10,5 35,15 50 C20 58,30 58,30 58 C30 58,40 58,45 50 C55 35,50 10,30 5Z" fill="#306D29"/>
            <path d="M30 10 C18 18,15 38,22 50 C25 55,30 55,30 55" stroke="#0D530E" stroke-width="1" fill="none"/>
        </svg>
        <svg class="absolute bottom-16 left-8 w-12 h-12 opacity-15" viewBox="0 0 60 60" fill="none" style="animation:eiLeafFall 15s ease-in-out -7s infinite;--drift:20px;--spin:60deg">
            <path d="M30 5 C10 10,5 35,15 50 C20 58,30 58,30 58 C30 58,40 58,45 50 C55 35,50 10,30 5Z" fill="#306D29"/>
            <path d="M30 10 C18 18,15 38,22 50 C25 55,30 55,30 55" stroke="#0D530E" stroke-width="1" fill="none"/>
        </svg>
        <svg class="absolute bottom-20 right-5 w-8 h-8 opacity-20" viewBox="0 0 60 60" fill="none" style="animation:eiLeafFall 11s ease-in-out -5s infinite;--drift:-18px;--spin:-50deg">
            <path d="M30 5 C10 10,5 35,15 50 C20 58,30 58,30 58 C30 58,40 58,45 50 C55 35,50 10,30 5Z" fill="#306D29"/>
            <path d="M30 10 C18 18,15 38,22 50 C25 55,30 55,30 55" stroke="#0D530E" stroke-width="1" fill="none"/>
        </svg>
    </div>

    <div class="text-center px-8 max-w-sm relative z-10">
        <div class="mb-8">
            <p class="ei-in ei-d1 font-cormorant text-forest/60 text-sm tracking-[0.3em] uppercase mb-4">The Wedding of</p>
            <h1 class="ei-in ei-d2 font-serif-luxury text-5xl text-forest leading-tight mb-2">
                {{ $invitation->groom_name }}
            </h1>
            <p class="ei-in ei-d2 font-cormorant text-2xl text-forest/50 italic my-2">&</p>
            <h1 class="ei-in ei-d3 font-serif-luxury text-5xl text-forest leading-tight">
                {{ $invitation->bride_name }}
            </h1>
        </div>

        @if($guest)
        <div class="ei-in ei-d3 mb-8 p-4 border border-forest/20 rounded-2xl bg-white/40">
            <p class="text-xs text-forest/60 uppercase tracking-widest mb-1">Kepada Yth.</p>
            <p class="font-serif-luxury text-lg text-forest">{{ $guest->name }}</p>
            @if($guest->allocated_seats > 1)
            <p class="text-xs text-gray-500 mt-1">({{ $guest->allocated_seats }} kursi)</p>
            @endif
        </div>
        @endif

        <button data-coi-btn @click="opened = true; $store.invitation.openEnvelope()"
                class="ei-in ei-d4 group relative inline-flex items-center gap-3 px-8 py-4 rounded-2xl text-cream font-medium text-sm tracking-wide shadow-lg hover:shadow-xl transition-all duration-300 active:scale-95"
                style="background: linear-gradient(135deg, #306D29, #0D530E)">
            <svg class="w-5 h-5 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
            Buka Undangan
        </button>

        <p class="ei-in ei-d5 text-xs text-forest/40 mt-6 font-cormorant italic">Sentuh untuk membuka</p>
    </div>
</div>

{{-- Global leaf canvas — always on, z-92, cover z-100 hides it until opened --}}
<div id="ei-global-canvas"></div>

{{-- ════════════════════════════════════════════ --}}
{{-- MAIN INVITATION CONTENT                      --}}
{{-- ════════════════════════════════════════════ --}}
<div data-coi-main
     x-show="opened" x-transition:enter="transition ease-out duration-700"
     x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
     style="padding-bottom:72px">

    {{-- HERO SECTION --}}
    <section id="nav-top" class="relative min-h-screen flex flex-col items-center justify-center overflow-hidden">
        {{-- Cover Photo --}}
        @if($invitation->cover_photo)
        <div class="absolute inset-0 overflow-hidden">
            <img src="{{ $invitation->cover_photo_url }}" alt="Cover"
                 class="w-full object-cover"
                 style="height:130%;top:-15%;position:absolute;left:0;right:0"
                 data-parallax-hero>
            <div class="absolute inset-0" style="background: linear-gradient(to bottom, rgba(48,109,41,0.3) 0%, rgba(13,83,14,0.7) 100%)"></div>
        </div>
        @else
        <div class="absolute inset-0" style="background: linear-gradient(160deg, #306D29 0%, #0D530E 100%)"></div>
        @endif

        <div class="relative z-10 text-center px-6 py-20">
            @if($invitation->opening_quote)
            <div class="mb-8 max-w-xs mx-auto" data-aos="fade-down">
                <p class="font-cormorant text-cream/80 text-sm italic leading-relaxed">"{{ $invitation->opening_quote }}"</p>
                @if($invitation->opening_quote_source)
                <p class="text-cream/50 text-xs mt-1">— {{ $invitation->opening_quote_source }}</p>
                @endif
            </div>
            @endif

            <p class="font-cormorant text-cream/70 text-sm tracking-[0.4em] uppercase mb-4" data-aos="fade-up">The Wedding of</p>
            <h1 class="font-serif-luxury text-6xl text-cream leading-none mb-3" data-aos="fade-up" data-aos-delay="100">
                {{ $invitation->groom_name }}
            </h1>
            <p class="font-cormorant text-3xl text-cream/60 italic" data-aos="fade-up" data-aos-delay="150">&amp;</p>
            <h1 class="font-serif-luxury text-6xl text-cream leading-none mt-3" data-aos="fade-up" data-aos-delay="200">
                {{ $invitation->bride_name }}
            </h1>

            {{-- Main event date --}}
            @if($events->isNotEmpty())
            @php $mainEvent = $events->first(); @endphp
            <div class="mt-10" data-aos="fade-up" data-aos-delay="300">
                <p class="font-cormorant text-cream/70 text-lg">
                    {{ \Carbon\Carbon::parse($mainEvent->date)->translatedFormat('l, d F Y') }}
                </p>
            </div>
            @endif

            {{-- Scroll indicator --}}
            <div class="mt-16 animate-bounce">
                <svg class="w-6 h-6 text-cream/50 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 9l-7 7-7-7"/>
                </svg>
            </div>
        </div>
    </section>

    {{-- COUPLE SECTION --}}
    <section id="nav-couple" class="py-16 px-6 bg-cream">
        <div class="max-w-lg mx-auto text-center">
            <div class="section-divider mb-8">
                <span class="ornament">✦</span>
            </div>

            <p class="font-cormorant text-forest/60 text-sm tracking-[0.3em] uppercase mb-10" data-aos="fade-up">Mempelai</p>

            <div class="grid grid-cols-2 gap-8">
                {{-- Groom --}}
                <div data-aos="fade-right">
                    @if($invitation->groom_photo)
                    <div class="w-32 h-32 rounded-full overflow-hidden mx-auto mb-4 ring-4 ring-sand/60 shadow-lg">
                        <img src="{{ $invitation->groom_photo_url }}" class="w-full h-full object-cover" alt="{{ $invitation->groom_name }}">
                    </div>
                    @else
                    <div class="w-32 h-32 rounded-full bg-gradient-to-br from-forest to-emerald mx-auto mb-4 flex items-center justify-center shadow-lg">
                        <span class="font-serif-luxury text-4xl text-cream">{{ substr($invitation->groom_name, 0, 1) }}</span>
                    </div>
                    @endif
                    <h3 class="font-serif-luxury text-xl text-forest mb-1">{{ $invitation->groom_name }}</h3>
                    @if($invitation->groom_full_name)
                    <p class="text-xs text-gray-500 mb-2">{{ $invitation->groom_full_name }}</p>
                    @endif
                    @if($invitation->groom_father || $invitation->groom_mother)
                    <p class="text-xs text-forest/60">Putra dari</p>
                    <p class="text-xs text-gray-600">{{ $invitation->groom_father }}{{ $invitation->groom_father && $invitation->groom_mother ? ' & ' : '' }}{{ $invitation->groom_mother }}</p>
                    @endif
                </div>

                {{-- Bride --}}
                <div data-aos="fade-left">
                    @if($invitation->bride_photo)
                    <div class="w-32 h-32 rounded-full overflow-hidden mx-auto mb-4 ring-4 ring-sand/60 shadow-lg">
                        <img src="{{ $invitation->bride_photo_url }}" class="w-full h-full object-cover" alt="{{ $invitation->bride_name }}">
                    </div>
                    @else
                    <div class="w-32 h-32 rounded-full bg-gradient-to-br from-forest to-emerald mx-auto mb-4 flex items-center justify-center shadow-lg">
                        <span class="font-serif-luxury text-4xl text-cream">{{ substr($invitation->bride_name, 0, 1) }}</span>
                    </div>
                    @endif
                    <h3 class="font-serif-luxury text-xl text-forest mb-1">{{ $invitation->bride_name }}</h3>
                    @if($invitation->bride_full_name)
                    <p class="text-xs text-gray-500 mb-2">{{ $invitation->bride_full_name }}</p>
                    @endif
                    @if($invitation->bride_father || $invitation->bride_mother)
                    <p class="text-xs text-forest/60">Putri dari</p>
                    <p class="text-xs text-gray-600">{{ $invitation->bride_father }}{{ $invitation->bride_father && $invitation->bride_mother ? ' & ' : '' }}{{ $invitation->bride_mother }}</p>
                    @endif
                </div>
            </div>
        </div>
    </section>

    {{-- EVENTS SECTION --}}
    @if($events->isNotEmpty())
    <section id="nav-events" class="py-16 px-6" style="background: linear-gradient(135deg, #306D29, #0D530E)">
        <div class="max-w-lg mx-auto">
            <p class="font-cormorant text-cream/70 text-sm tracking-[0.3em] uppercase text-center mb-10" data-aos="fade-up">Jadwal Acara</p>

            <div class="space-y-6">
                @foreach($events as $event)
                <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6 border border-white/20" data-aos="fade-up">
                    <h3 class="font-serif-luxury text-xl text-cream mb-2">{{ $event->name }}</h3>
                    <div class="space-y-1 text-cream/80 text-sm">
                        <p>📅 {{ \Carbon\Carbon::parse($event->date)->translatedFormat('l, d F Y') }}</p>
                        <p>🕐 {{ $event->time_start }}{{ $event->time_end ? ' — '.$event->time_end : '' }} WIB</p>
                        <p>📍 {{ $event->venue }}</p>
                        @if($event->venue_address)
                        <p class="text-cream/60 text-xs">{{ $event->venue_address }}</p>
                        @endif
                    </div>

                    <div class="flex gap-3 mt-4">
                        {{-- Countdown --}}
                        @php $targetDate = \Carbon\Carbon::parse($event->date)->toIso8601String(); @endphp
                        <div x-data="countdown('{{ $targetDate }}')" class="flex gap-2">
                            @foreach(['days'=>'Hari','hours'=>'Jam','minutes'=>'Mnt','seconds'=>'Dtk'] as $unit => $label)
                            <div class="text-center bg-white/10 rounded-xl px-3 py-2 min-w-[3rem]">
                                <div class="font-serif-luxury text-2xl text-cream" x-text="{{ $unit }}"></div>
                                <div class="text-xs text-cream/60">{{ $label }}</div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    @if($event->venue_maps_url)
                    <a href="{{ $event->venue_maps_url }}" target="_blank"
                       class="mt-4 flex items-center gap-2 text-cream/80 text-sm hover:text-cream transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        Buka Google Maps
                    </a>
                    @endif

                    {{-- Add to Calendar --}}
                    <div x-data="saveToCalendar({
                        title: '{{ addslashes($event->name . ' - ' . $invitation->getCoupleName()) }}',
                        date: '{{ $event->date }}',
                        time: '{{ $event->time_start }}',
                        description: '{{ addslashes('Pernikahan ' . $invitation->getCoupleName()) }}',
                        location: '{{ addslashes($event->venue . ' ' . $event->venue_address) }}'
                    })">
                        <button @click="addToCalendar()" class="mt-3 text-xs text-cream/60 hover:text-cream flex items-center gap-1 transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            Simpan ke Kalender
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- LOVE STORY --}}
    @if($invitation->story)
    <section class="py-16 px-6 bg-cream">
        <div class="max-w-lg mx-auto text-center">
            <div class="section-divider mb-8"><span class="ornament">✦</span></div>
            <p class="font-cormorant text-forest/60 text-sm tracking-[0.3em] uppercase mb-8" data-aos="fade-up">Cerita Kami</p>
            <p class="font-cormorant text-forest/80 text-lg leading-relaxed italic" data-aos="fade-up">{{ $invitation->story }}</p>
        </div>
    </section>
    @endif

    {{-- GALLERY --}}
    @if($galleries->isNotEmpty())
    <section id="nav-gallery" class="py-16 px-6 bg-white/40">
        <div class="max-w-lg mx-auto">
            <p class="font-cormorant text-forest/60 text-sm tracking-[0.3em] uppercase text-center mb-8" data-aos="fade-up">Galeri</p>
            @include('partials.gallery-collage', [
                'galleries'   => $galleries,
                'gcCellClass' => 'rounded-xl shadow-md',
                'gcGap'       => 6,
            ])
        </div>
    </section>
    @endif

    {{-- GIFT / REKENING --}}
    @if($gifts->isNotEmpty())
    <section class="py-16 px-6 bg-cream">
        <div class="max-w-lg mx-auto">
            <div class="section-divider mb-8"><span class="ornament">✦</span></div>
            <p class="font-cormorant text-forest/60 text-sm tracking-[0.3em] uppercase text-center mb-2" data-aos="fade-up">Hadiah Pernikahan</p>
            <p class="text-center text-xs text-gray-500 mb-8" data-aos="fade-up">Doa restu Anda adalah hadiah terbaik. Namun jika ingin berbagi kebaikan:</p>

            <div class="space-y-4">
                @foreach($gifts as $gift)
                <div class="card-luxury p-5" data-aos="fade-up">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-xl bg-forest/10 flex items-center justify-center flex-shrink-0">
                            @if($gift->type === 'qris')
                            <svg class="w-5 h-5 text-forest" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                            @else
                            <svg class="w-5 h-5 text-forest" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                            @endif
                        </div>
                        <div class="flex-1">
                            <p class="font-medium text-gray-800 text-sm">{{ $gift->label ?: ($gift->bank_name ?: 'QRIS') }}</p>
                            @if($gift->account_number)
                            <p class="font-mono text-forest text-lg font-semibold mt-1">{{ $gift->account_number }}</p>
                            <p class="text-xs text-gray-500">{{ $gift->account_name }}</p>
                            <button onclick="navigator.clipboard.writeText('{{ $gift->account_number }}')"
                                    class="mt-2 text-xs text-forest hover:text-emerald flex items-center gap-1 transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                Salin nomor
                            </button>
                            @endif
                            @if($gift->qris_image)
                            <img src="{{ $gift->qris_image_url }}" alt="QRIS" class="mt-3 max-w-[200px] rounded-xl shadow-sm">
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- RSVP --}}
    @if($invitation->is_open)
    <section class="py-16 px-6" style="--rsvp-accent:#306D29;--rsvp-accent-bg:rgba(48,109,41,0.1);--rsvp-gradient:linear-gradient(135deg,#306D29,#0D530E);--rsvp-label:rgba(48,109,41,0.8);--rsvp-border:rgba(217,210,142,0.6);--rsvp-input-bg:rgba(251,245,221,0.4);--rsvp-locked-bg:rgba(48,109,41,0.04)" style="background: linear-gradient(160deg, #FBF5DD, #E7E1B1)">
        <div class="max-w-lg mx-auto">
            <div class="section-divider mb-8"><span class="ornament">✦</span></div>
            <p class="font-cormorant text-forest/60 text-sm tracking-[0.3em] uppercase text-center mb-2" data-aos="fade-up">Konfirmasi Kehadiran</p>
            <p class="text-center text-xs text-gray-500 mb-8" data-aos="fade-up">Mohon konfirmasi kehadiran Anda sebelum {{ $invitation->rsvp_deadline ? \Carbon\Carbon::parse($invitation->rsvp_deadline)->translatedFormat('d F Y') : 'hari H' }}</p>

            <livewire:invitation.rsvp-form :invitation="$invitation" :guest="$guest" />
        </div>
    </section>
    @endif

    {{-- WISHES --}}
    <section class="py-16 px-6 bg-cream">
        <div class="max-w-lg mx-auto">
            <div class="section-divider mb-8"><span class="ornament">✦</span></div>
            <p class="font-cormorant text-forest/60 text-sm tracking-[0.3em] uppercase text-center mb-8" data-aos="fade-up">Ucapan & Doa</p>
            <livewire:invitation.guest-wishes :invitation="$invitation" />
        </div>
    </section>

    {{-- FOOTER --}}
    <footer class="py-10 text-center px-6" style="background: linear-gradient(135deg, #306D29, #0D530E)">
        <p class="font-cormorant text-cream/70 text-sm italic mb-2">Merupakan suatu kehormatan bagi kami atas kehadiran Anda</p>
        <p class="font-serif-luxury text-cream text-2xl">{{ $invitation->getCoupleName() }}</p>

        @if($show_watermark)
        <div class="mt-6">
            <p class="text-cream/40 text-xs">Dibuat dengan ❤ oleh</p>
            <a href="{{ config('app.url') }}" class="text-cream/60 text-xs hover:text-cream">Invora.id</a>
        </div>
        @endif
    </footer>

    @include('partials.invitation-navbar', ['navStyle' => 'green'])
</div>{{-- end opened --}}

{{-- MUSIC CONTROL --}}
@if($invitation->music_url)
<div x-show="opened" class="fixed bottom-20 right-4 z-[80]">
    <button @click="$store.invitation.toggleMusic()"
            class="w-12 h-12 rounded-full flex items-center justify-center shadow-xl border-2 border-white/40"
            style="background: rgba(48,109,41,0.9); backdrop-filter: blur(8px)">
        <svg x-show="!$store.invitation.musicPlaying" class="w-5 h-5 text-cream" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M9.383 3.076A1 1 0 0110 4v12a1 1 0 01-1.707.707L4.586 13H2a1 1 0 01-1-1V8a1 1 0 011-1h2.586l3.707-3.707a1 1 0 011.09-.217zM14.657 2.929a1 1 0 011.414 0A9.972 9.972 0 0119 10a9.972 9.972 0 01-2.929 7.071 1 1 0 01-1.414-1.414A7.971 7.971 0 0017 10c0-2.21-.894-4.208-2.343-5.657a1 1 0 010-1.414zm-2.829 2.828a1 1 0 011.415 0A5.983 5.983 0 0115 10a5.984 5.984 0 01-1.757 4.243 1 1 0 01-1.415-1.415A3.984 3.984 0 0013 10a3.983 3.983 0 00-1.172-2.828 1 1 0 010-1.415z" clip-rule="evenodd"/>
        </svg>
        <svg x-show="$store.invitation.musicPlaying" class="w-5 h-5 text-cream" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zM7 8a1 1 0 012 0v4a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v4a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
        </svg>
    </button>
    @if($invitation->music_name)
    <div class="absolute right-14 top-1/2 -translate-y-1/2 bg-white/90 backdrop-blur-sm rounded-full px-3 py-1.5 shadow-sm"
         x-show="$store.invitation.musicPlaying">
        <p class="text-xs text-forest truncate max-w-[120px]">♪ {{ $invitation->music_name }}</p>
    </div>
    @endif
</div>
@endif

{{-- WATERMARK --}}
@if($show_watermark)
<div x-show="opened" class="watermark pointer-events-auto">
    <a href="{{ config('app.url') }}" target="_blank" class="hover:text-forest transition-colors">
        ❤ Invora.id
    </a>
</div>
@endif

@include('partials.cinematic-opening')
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    AOS.init({ duration: 900, once: true, offset: 50 });

    // Auto-play music if configured
    @if($invitation->music_autoplay)
    document.addEventListener('alpine:initialized', () => {
        // Will be triggered after envelope opens via store
    });
    @endif

    // ── Falling Leaves (cover + global canvas) ───────────────────
    (function () {
        var COLORS = [
            ['#306D29','#0D530E'],
            ['#4a8c40','#1a6b1b'],
            ['#5a9e50','#2d7a2e'],
            ['#2d6324','#0a4a0b'],
            ['#6aaf60','#3a8c3b'],
        ];

        function mkLeaf(color, vein) {
            return '<svg viewBox="0 0 40 60" style="width:100%;height:100%" fill="none">'
                 + '<path d="M20 3 C5 8,2 30,10 48 C14 56,20 57,20 57 C20 57,26 56,30 48 C38 30,35 8,20 3Z" fill="' + color + '"/>'
                 + '<path d="M20 8 C12 18,11 38,17 52" stroke="' + vein + '" stroke-width="0.8" fill="none" opacity=".6"/>'
                 + '<path d="M20 8 C15 22,15 40,18 52" stroke="' + vein + '" stroke-width="0.4" fill="none" opacity=".4"/>'
                 + '<path d="M20 8 C25 22,25 40,22 52" stroke="' + vein + '" stroke-width="0.4" fill="none" opacity=".4"/>'
                 + '</svg>';
        }

        function spawnLeaves(canvasId, N) {
            var c = document.getElementById(canvasId);
            if (!c) return;
            for (var i = 0; i < N; i++) {
                var col   = COLORS[i % COLORS.length];
                var size  = 12 + Math.random() * 22;
                var dur   = 12 + Math.random() * 16;
                var del   = -(Math.random() * dur);
                var left  = Math.random() * 100;
                var drift = (Math.random() - 0.5) * 100;
                var spin  = 120 + Math.random() * 420;
                var el    = document.createElement('div');
                el.className = 'ei-leaf';
                el.innerHTML = mkLeaf(col[0], col[1]);
                el.style.cssText =
                    'width:'  + size + 'px;'
                  + 'height:' + (size * 1.5) + 'px;'
                  + 'left:'   + left + '%;'
                  + 'top:-80px;'
                  + '--drift:' + drift + 'px;'
                  + '--spin:'  + spin  + 'deg;'
                  + 'animation:eiLeafFall ' + dur + 's linear ' + del + 's infinite;';
                c.appendChild(el);
            }
        }

        spawnLeaves('ei-leaf-canvas',   14); // cover screen
        spawnLeaves('ei-global-canvas', 12); // main content overlay
    })();
</script>
</body>
</html>
