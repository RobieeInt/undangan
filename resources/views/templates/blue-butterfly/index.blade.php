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
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400&family=Great+Vibes&family=Nunito:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css">
    {{-- swiper removed, replaced by collage --}}

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    @livewireScriptConfig

    <style>
        :root {
            --bb-navy:   #1e3a5f;
            --bb-blue:   #2563eb;
            --bb-sky:    #38bdf8;
            --bb-light:  #eff6ff;
            --bb-pale:   #dbeafe;
            --bb-gold:   #c8a96e;
            --bb-white:  #ffffff;
        }
        * { box-sizing: border-box; }
        body {
            background: var(--bb-light);
            font-family: 'Nunito', sans-serif;
            overflow-x: hidden;
            color: var(--bb-navy);
        }
        .font-great   { font-family: 'Great Vibes', cursive; }
        .font-cormo   { font-family: 'Cormorant Garamond', serif; }

        /* ── Static butterfly SVG decorations (cover/hero/footer) ── */
        .butterfly {
            position: absolute;
            pointer-events: none;
            opacity: 0.16;
            animation: flutter 5s ease-in-out infinite;
        }
        @keyframes flutter {
            0%, 100% { transform: rotate(-4deg) translateY(0px); }
            25%       { transform: rotate(0deg)  translateY(-8px); }
            50%       { transform: rotate(4deg)  translateY(-14px); }
            75%       { transform: rotate(0deg)  translateY(-8px); }
        }
        /* Realistic wing-flap for static butterfly decorations */
        .butterfly .bwl {
            transform-box: fill-box;
            transform-origin: right center;
            animation: bflyStatL 0.52s ease-in-out infinite;
        }
        .butterfly .bwr {
            transform-box: fill-box;
            transform-origin: left center;
            animation: bflyStatR 0.52s ease-in-out infinite;
        }
        @keyframes bflyStatL {
            0%, 100% { transform: scaleX(1); }
            50%       { transform: scaleX(0.07); }
        }
        @keyframes bflyStatR {
            0%, 100% { transform: scaleX(1); }
            50%       { transform: scaleX(0.07); }
        }

        /* ── Animated flying butterflies ────────────────────────── */
        #butterfly-canvas { position:fixed;inset:0;pointer-events:none;z-index:92;overflow:hidden; }
        /* Vertical wave wrapper — positions the butterfly's flight altitude */
        .bfly-wrap {
            position:absolute;left:0;width:100%;
            animation: bflyWave var(--wdur,4s) ease-in-out var(--wdel,0s) infinite;
        }
        /* Horizontal flight layer */
        .bfly-go {
            display:inline-block;
            animation: bflyFlight var(--fdur,14s) linear var(--fdel,0s) infinite;
        }
        /* Wing flap — transform-box:fill-box makes transform-origin relative to each <g> bounding box */
        .bfly-svg .bwl { transform-box:fill-box; transform-origin:right center; animation:bwFlapL var(--fs,0.45s) ease-in-out infinite; }
        .bfly-svg .bwr { transform-box:fill-box; transform-origin:left center;  animation:bwFlapR var(--fs,0.45s) ease-in-out infinite; }
        @keyframes bwFlapL { 0%,100%{ transform:scaleX(1);    } 50%{ transform:scaleX(0.06); } }
        @keyframes bwFlapR { 0%,100%{ transform:scaleX(1);    } 50%{ transform:scaleX(0.06); } }
        @keyframes bflyFlight {
            0%   { transform:translateX(-200px); opacity:0; }
            7%   { opacity:var(--bop,0.8); }
            93%  { opacity:var(--bop,0.8); }
            100% { transform:translateX(calc(100vw + 200px)); opacity:0; }
        }
        @keyframes bflyWave {
            0%,100% { transform:translateY(0); }
            33%     { transform:translateY(var(--wy1,-25px)); }
            66%     { transform:translateY(var(--wy2,18px)); }
        }

        /* ── Divider ── */
        .bb-divider {
            display: flex; align-items: center; gap: 1rem;
        }
        .bb-divider::before, .bb-divider::after {
            content: ''; flex: 1; height: 1px;
            background: linear-gradient(to right, transparent, var(--bb-sky), transparent);
        }

        /* ── Section backgrounds ── */
        .bg-bb-hero   { background: linear-gradient(160deg, var(--bb-navy) 0%, #0f2444 100%); }
        .bg-bb-events { background: linear-gradient(135deg, #1e40af 0%, #1e3a5f 100%); }
        .bg-bb-rsvp   { background: linear-gradient(160deg, #eff6ff 0%, #dbeafe 100%); }

        /* ── Envelope screen ── */
        .envelope-screen {
            background: linear-gradient(160deg, #dbeafe 0%, #bfdbfe 40%, #93c5fd 100%);
        }

        /* ── Countdown box ── */
        .countdown-box {
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255,255,255,0.25);
            border-radius: 12px;
        }

        /* ── Gift card ── */
        .gift-card {
            background: white;
            border-radius: 20px;
            border: 1.5px solid var(--bb-pale);
            box-shadow: 0 4px 24px rgba(37,99,235,0.07);
        }

        /* ── RSVP inputs ── */
        .rsvp-input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1.5px solid #bfdbfe;
            border-radius: 12px;
            background: white;
            font-family: 'Nunito', sans-serif;
            font-size: 0.875rem;
            outline: none;
            transition: border-color 0.2s;
        }
        .rsvp-input:focus { border-color: var(--bb-blue); }

        /* ── Music pill ── */
        .music-pill {
            background: rgba(30,58,95,0.85);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.2);
        }

        /* ── Watermark ── */
        .watermark {
            position: fixed; bottom: 10px; left: 50%;
            transform: translateX(-50%);
            font-size: 10px; color: rgba(30,58,95,0.4);
            z-index: 40; white-space: nowrap;
        }
    </style>
</head>
<body x-data="{ opened: false }" @keydown.window.escape="opened = false"
      x-init="$store.invitation.initMusic('{{ $invitation->music_url }}', {{ $invitation->music_autoplay ? 'true' : 'false' }})">

{{-- ══════════════════════════════════════════════ --}}
{{-- ENVELOPE OPENING SCREEN                        --}}
{{-- ══════════════════════════════════════════════ --}}
<div data-coi-cover
     x-show="!opened"
     x-transition:leave="transition ease-in duration-500"
     x-transition:leave-start="opacity-100 scale-100"
     x-transition:leave-end="opacity-0 scale-95"
     class="fixed inset-0 z-[100] flex flex-col items-center justify-center envelope-screen overflow-hidden">

    {{-- Realistic floating butterfly decorations --}}
    <svg class="butterfly w-24 h-24" style="top:8%;left:5%;animation-delay:-2s" viewBox="0 0 100 65" fill="none">
        <g class="bwl">
            <path d="M50 35 C36 12,8 9,10 27 C12 42,36 42,50 35Z" fill="#2563eb"/>
            <path d="M50 35 C30 38,7 52,12 60 C19 65,38 52,50 35Z" fill="#1d4ed8"/>
            <path d="M50 35 C38 18,16 13,18 27 C20 35,36 38,50 35Z" fill="#93c5fd" opacity=".28"/>
        </g>
        <g class="bwr">
            <path d="M50 35 C64 12,92 9,90 27 C88 42,64 42,50 35Z" fill="#2563eb"/>
            <path d="M50 35 C70 38,93 52,88 60 C81 65,62 52,50 35Z" fill="#1d4ed8"/>
            <path d="M50 35 C62 18,84 13,82 27 C80 35,64 38,50 35Z" fill="#93c5fd" opacity=".28"/>
        </g>
        <ellipse cx="50" cy="38" rx="2.5" ry="11" fill="#1e3a5f"/>
        <circle cx="50" cy="25" r="3" fill="#1e3a5f"/>
        <path d="M50 24 Q43 15 40 9" stroke="#1e3a5f" stroke-width="1" fill="none" stroke-linecap="round"/>
        <path d="M50 24 Q57 15 60 9" stroke="#1e3a5f" stroke-width="1" fill="none" stroke-linecap="round"/>
        <circle cx="40" cy="9" r="2" fill="#1e3a5f"/>
        <circle cx="60" cy="9" r="2" fill="#1e3a5f"/>
    </svg>
    <svg class="butterfly w-16 h-16" style="top:15%;right:8%;animation-delay:-4s" viewBox="0 0 100 65" fill="none">
        <g class="bwl">
            <path d="M50 35 C36 12,8 9,10 27 C12 42,36 42,50 35Z" fill="#38bdf8"/>
            <path d="M50 35 C30 38,7 52,12 60 C19 65,38 52,50 35Z" fill="#0284c7"/>
            <path d="M50 35 C38 18,16 13,18 27 C20 35,36 38,50 35Z" fill="#bae6fd" opacity=".28"/>
        </g>
        <g class="bwr">
            <path d="M50 35 C64 12,92 9,90 27 C88 42,64 42,50 35Z" fill="#38bdf8"/>
            <path d="M50 35 C70 38,93 52,88 60 C81 65,62 52,50 35Z" fill="#0284c7"/>
            <path d="M50 35 C62 18,84 13,82 27 C80 35,64 38,50 35Z" fill="#bae6fd" opacity=".28"/>
        </g>
        <ellipse cx="50" cy="38" rx="2.5" ry="11" fill="#0c4a6e"/>
        <circle cx="50" cy="25" r="3" fill="#0c4a6e"/>
        <path d="M50 24 Q43 15 40 9" stroke="#0c4a6e" stroke-width="1" fill="none" stroke-linecap="round"/>
        <path d="M50 24 Q57 15 60 9" stroke="#0c4a6e" stroke-width="1" fill="none" stroke-linecap="round"/>
        <circle cx="40" cy="9" r="2" fill="#0c4a6e"/>
        <circle cx="60" cy="9" r="2" fill="#0c4a6e"/>
    </svg>
    <svg class="butterfly w-20 h-20" style="bottom:12%;right:5%;animation-delay:-1s" viewBox="0 0 100 65" fill="none">
        <g class="bwl">
            <path d="M50 35 C36 12,8 9,10 27 C12 42,36 42,50 35Z" fill="#3b82f6"/>
            <path d="M50 35 C30 38,7 52,12 60 C19 65,38 52,50 35Z" fill="#1e40af"/>
            <path d="M50 35 C38 18,16 13,18 27 C20 35,36 38,50 35Z" fill="#93c5fd" opacity=".3"/>
        </g>
        <g class="bwr">
            <path d="M50 35 C64 12,92 9,90 27 C88 42,64 42,50 35Z" fill="#3b82f6"/>
            <path d="M50 35 C70 38,93 52,88 60 C81 65,62 52,50 35Z" fill="#1e40af"/>
            <path d="M50 35 C62 18,84 13,82 27 C80 35,64 38,50 35Z" fill="#93c5fd" opacity=".3"/>
        </g>
        <ellipse cx="50" cy="38" rx="2.5" ry="11" fill="#1e3a5f"/>
        <circle cx="50" cy="25" r="3" fill="#1e3a5f"/>
        <path d="M50 24 Q43 15 40 9" stroke="#1e3a5f" stroke-width="1" fill="none" stroke-linecap="round"/>
        <path d="M50 24 Q57 15 60 9" stroke="#1e3a5f" stroke-width="1" fill="none" stroke-linecap="round"/>
        <circle cx="40" cy="9" r="2" fill="#1e3a5f"/>
        <circle cx="60" cy="9" r="2" fill="#1e3a5f"/>
    </svg>
    <svg class="butterfly w-12 h-12" style="bottom:20%;left:8%;animation-delay:-3s" viewBox="0 0 100 65" fill="none">
        <g class="bwl">
            <path d="M50 35 C36 12,8 9,10 27 C12 42,36 42,50 35Z" fill="#93c5fd"/>
            <path d="M50 35 C30 38,7 52,12 60 C19 65,38 52,50 35Z" fill="#60a5fa"/>
            <path d="M50 35 C38 18,16 13,18 27 C20 35,36 38,50 35Z" fill="#dbeafe" opacity=".4"/>
        </g>
        <g class="bwr">
            <path d="M50 35 C64 12,92 9,90 27 C88 42,64 42,50 35Z" fill="#93c5fd"/>
            <path d="M50 35 C70 38,93 52,88 60 C81 65,62 52,50 35Z" fill="#60a5fa"/>
            <path d="M50 35 C62 18,84 13,82 27 C80 35,64 38,50 35Z" fill="#dbeafe" opacity=".4"/>
        </g>
        <ellipse cx="50" cy="38" rx="2.5" ry="11" fill="#1e40af"/>
        <circle cx="50" cy="25" r="3" fill="#1e40af"/>
        <path d="M50 24 Q43 15 40 9" stroke="#1e40af" stroke-width="1" fill="none" stroke-linecap="round"/>
        <path d="M50 24 Q57 15 60 9" stroke="#1e40af" stroke-width="1" fill="none" stroke-linecap="round"/>
        <circle cx="40" cy="9" r="2" fill="#1e40af"/>
        <circle cx="60" cy="9" r="2" fill="#1e40af"/>
    </svg>

    <div class="text-center px-8 max-w-sm relative z-10">
        {{-- Ornament top — realistic butterfly --}}
        <div class="mb-6">
            <svg class="w-14 h-14 mx-auto" viewBox="0 0 100 65" fill="none" style="filter:drop-shadow(0 2px 8px rgba(37,99,235,.25))">
                <g style="transform-box:fill-box;transform-origin:right center;animation:bflyStatL 0.52s ease-in-out infinite">
                    <path d="M50 35 C36 12,8 9,10 27 C12 42,36 42,50 35Z" fill="#2563eb"/>
                    <path d="M50 35 C30 38,7 52,12 60 C19 65,38 52,50 35Z" fill="#1d4ed8"/>
                    <path d="M50 35 C38 18,16 13,18 27 C20 35,36 38,50 35Z" fill="#93c5fd" opacity=".35"/>
                </g>
                <g style="transform-box:fill-box;transform-origin:left center;animation:bflyStatR 0.52s ease-in-out infinite">
                    <path d="M50 35 C64 12,92 9,90 27 C88 42,64 42,50 35Z" fill="#2563eb"/>
                    <path d="M50 35 C70 38,93 52,88 60 C81 65,62 52,50 35Z" fill="#1d4ed8"/>
                    <path d="M50 35 C62 18,84 13,82 27 C80 35,64 38,50 35Z" fill="#93c5fd" opacity=".35"/>
                </g>
                <ellipse cx="50" cy="38" rx="2.5" ry="11" fill="#1e3a5f"/>
                <circle cx="50" cy="25" r="3" fill="#1e3a5f"/>
                <path d="M50 24 Q43 15 40 9" stroke="#1e3a5f" stroke-width="1.2" fill="none" stroke-linecap="round"/>
                <path d="M50 24 Q57 15 60 9" stroke="#1e3a5f" stroke-width="1.2" fill="none" stroke-linecap="round"/>
                <circle cx="40" cy="9" r="2.2" fill="#1e3a5f"/>
                <circle cx="60" cy="9" r="2.2" fill="#1e3a5f"/>
            </svg>
        </div>

        <p class="font-cormo text-blue-700/70 text-xs tracking-[0.35em] uppercase mb-3">Undangan Pernikahan</p>

        <h1 class="font-great text-5xl text-blue-900 leading-tight mb-1">
            {{ $invitation->groom_name }}
        </h1>
        <p class="font-cormo text-2xl text-blue-500/70 italic my-1">&</p>
        <h1 class="font-great text-5xl text-blue-900 leading-tight">
            {{ $invitation->bride_name }}
        </h1>

        @if($guest)
        <div class="mt-6 mb-4 px-4 py-3 rounded-2xl border border-blue-200 bg-white/50">
            <p class="text-[10px] text-blue-400 uppercase tracking-widest mb-0.5">Kepada Yth.</p>
            <p class="font-cormo text-blue-900 text-lg">{{ $guest->name }}</p>
            @if($guest->allocated_seats > 1)
            <p class="text-xs text-blue-400 mt-0.5">({{ $guest->allocated_seats }} kursi)</p>
            @endif
        </div>
        @else
        <div class="mt-6 mb-4"></div>
        @endif

        <button data-coi-btn @click="opened = true; $store.invitation.openEnvelope()"
                class="inline-flex items-center gap-3 px-8 py-3.5 rounded-2xl text-white font-medium text-sm tracking-wide shadow-xl hover:shadow-2xl transition-all duration-300 active:scale-95"
                style="background: linear-gradient(135deg, #2563eb, #1e3a5f)">
            <svg class="w-5 h-5 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
            Buka Undangan
        </button>
        <p class="text-[11px] text-blue-400/70 mt-4 font-cormo italic">Sentuh untuk membuka ✦</p>
    </div>
</div>

{{-- ══════════════════════════════════════════════ --}}
{{-- MAIN INVITATION CONTENT                        --}}
{{-- ══════════════════════════════════════════════ --}}
<div data-coi-main
     x-show="opened"
     x-transition:enter="transition ease-out duration-700"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     style="padding-bottom:72px">

    {{-- ── HERO ──────────────────────────────── --}}
    <section id="nav-top" class="relative min-h-screen flex flex-col items-center justify-center overflow-hidden bg-bb-hero">
        {{-- Realistic butterfly decorations on hero --}}
        <svg class="butterfly w-32 h-32 opacity-10" style="top:6%;right:0%;animation-delay:-2s" viewBox="0 0 100 65" fill="none">
            <g class="bwl">
                <path d="M50 35 C36 12,8 9,10 27 C12 42,36 42,50 35Z" fill="white"/>
                <path d="M50 35 C30 38,7 52,12 60 C19 65,38 52,50 35Z" fill="white" opacity=".7"/>
                <path d="M50 35 C38 18,16 13,18 27 C20 35,36 38,50 35Z" fill="white" opacity=".2"/>
            </g>
            <g class="bwr">
                <path d="M50 35 C64 12,92 9,90 27 C88 42,64 42,50 35Z" fill="white"/>
                <path d="M50 35 C70 38,93 52,88 60 C81 65,62 52,50 35Z" fill="white" opacity=".7"/>
                <path d="M50 35 C62 18,84 13,82 27 C80 35,64 38,50 35Z" fill="white" opacity=".2"/>
            </g>
            <ellipse cx="50" cy="38" rx="2" ry="10" fill="white" opacity=".5"/>
            <circle cx="50" cy="25" r="2.5" fill="white" opacity=".5"/>
        </svg>
        <svg class="butterfly w-20 h-20 opacity-[0.08]" style="bottom:10%;left:2%;animation-delay:-4s" viewBox="0 0 100 65" fill="none">
            <g class="bwl">
                <path d="M50 35 C36 12,8 9,10 27 C12 42,36 42,50 35Z" fill="white"/>
                <path d="M50 35 C30 38,7 52,12 60 C19 65,38 52,50 35Z" fill="white" opacity=".7"/>
                <path d="M50 35 C38 18,16 13,18 27 C20 35,36 38,50 35Z" fill="white" opacity=".2"/>
            </g>
            <g class="bwr">
                <path d="M50 35 C64 12,92 9,90 27 C88 42,64 42,50 35Z" fill="white"/>
                <path d="M50 35 C70 38,93 52,88 60 C81 65,62 52,50 35Z" fill="white" opacity=".7"/>
                <path d="M50 35 C62 18,84 13,82 27 C80 35,64 38,50 35Z" fill="white" opacity=".2"/>
            </g>
            <ellipse cx="50" cy="38" rx="2" ry="10" fill="white" opacity=".5"/>
            <circle cx="50" cy="25" r="2.5" fill="white" opacity=".5"/>
        </svg>

        {{-- Cover Photo overlay with parallax --}}
        @if($invitation->cover_photo)
        <div class="absolute inset-0 overflow-hidden">
            <img src="{{ $invitation->cover_photo_url }}" alt="Cover"
                 class="opacity-30 w-full object-cover"
                 style="height:130%;top:-15%;position:absolute;left:0;right:0"
                 data-parallax-hero>
            <div class="absolute inset-0" style="background: linear-gradient(to bottom, rgba(30,58,95,0.6) 0%, rgba(15,36,68,0.85) 100%)"></div>
        </div>
        @endif

        <div class="relative z-10 text-center px-6 py-24">
            @if($invitation->opening_quote)
            <div class="mb-8 max-w-xs mx-auto" data-aos="fade-down">
                <p class="font-cormo text-blue-200/80 text-sm italic leading-relaxed">"{{ $invitation->opening_quote }}"</p>
                @if($invitation->opening_quote_source)
                <p class="text-blue-300/50 text-xs mt-1">— {{ $invitation->opening_quote_source }}</p>
                @endif
            </div>
            @endif

            <p class="font-cormo text-blue-200/70 text-xs tracking-[0.4em] uppercase mb-4" data-aos="fade-up">The Wedding of</p>

            <h1 class="font-great text-7xl text-white leading-none" data-aos="fade-up" data-aos-delay="100">
                {{ $invitation->groom_name }}
            </h1>
            <div class="my-3 flex items-center justify-center gap-4" data-aos="fade-up" data-aos-delay="150">
                <div class="h-px w-12 bg-gradient-to-r from-transparent to-blue-300/50"></div>
                <svg class="w-8 h-5 opacity-60" viewBox="0 0 100 60" fill="#38bdf8">
                    <path d="M50 30 Q30 5 5 15 Q20 35 50 30z"/><path d="M50 30 Q70 5 95 15 Q80 35 50 30z"/>
                    <path d="M50 30 Q25 55 5 45 Q20 25 50 30z" opacity=".5"/><path d="M50 30 Q75 55 95 45 Q80 25 50 30z" opacity=".5"/>
                </svg>
                <div class="h-px w-12 bg-gradient-to-l from-transparent to-blue-300/50"></div>
            </div>
            <h1 class="font-great text-7xl text-white leading-none" data-aos="fade-up" data-aos-delay="200">
                {{ $invitation->bride_name }}
            </h1>

            @if($events->isNotEmpty())
            @php $mainEvent = $events->first(); @endphp
            <div class="mt-10" data-aos="fade-up" data-aos-delay="300">
                <p class="font-cormo text-blue-200/80 text-lg">
                    {{ \Carbon\Carbon::parse($mainEvent->date)->translatedFormat('l, d F Y') }}
                </p>
            </div>
            @endif

            <div class="mt-16 animate-bounce">
                <svg class="w-6 h-6 text-blue-300/50 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 9l-7 7-7-7"/>
                </svg>
            </div>
        </div>
    </section>

    {{-- ── COUPLE ────────────────────────────── --}}
    <section id="nav-couple" class="py-16 px-6 bg-bb-light">
        <div class="max-w-lg mx-auto text-center">
            <div class="bb-divider mb-8">
                <svg class="w-7 h-4 flex-shrink-0" viewBox="0 0 100 60" fill="#38bdf8" opacity=".6">
                    <path d="M50 30 Q30 5 5 15 Q20 35 50 30z"/><path d="M50 30 Q70 5 95 15 Q80 35 50 30z"/>
                    <path d="M50 30 Q25 55 5 45 Q20 25 50 30z" opacity=".5"/><path d="M50 30 Q75 55 95 45 Q80 25 50 30z" opacity=".5"/>
                </svg>
            </div>

            <p class="font-cormo text-blue-400/70 text-xs tracking-[0.35em] uppercase mb-10" data-aos="fade-up">Mempelai</p>

            <div class="grid grid-cols-2 gap-8">
                {{-- Groom --}}
                <div data-aos="fade-right">
                    @if($invitation->groom_photo)
                    <div class="w-32 h-32 rounded-full overflow-hidden mx-auto mb-4 ring-4 ring-blue-200 shadow-lg">
                        <img src="{{ $invitation->groom_photo_url }}" class="w-full h-full object-cover" alt="{{ $invitation->groom_name }}">
                    </div>
                    @else
                    <div class="w-32 h-32 rounded-full mx-auto mb-4 flex items-center justify-center shadow-lg ring-4 ring-blue-100"
                         style="background: linear-gradient(135deg, #2563eb, #1e3a5f)">
                        <span class="font-great text-4xl text-white">{{ substr($invitation->groom_name, 0, 1) }}</span>
                    </div>
                    @endif
                    <h3 class="font-great text-2xl text-blue-900 mb-1">{{ $invitation->groom_name }}</h3>
                    @if($invitation->groom_full_name)
                    <p class="text-xs text-gray-500 mb-2">{{ $invitation->groom_full_name }}</p>
                    @endif
                    @if($invitation->groom_father || $invitation->groom_mother)
                    <p class="text-xs text-blue-400">Putra dari</p>
                    <p class="text-xs text-blue-800/70">{{ $invitation->groom_father }}{{ $invitation->groom_father && $invitation->groom_mother ? ' & ' : '' }}{{ $invitation->groom_mother }}</p>
                    @endif
                </div>

                {{-- Bride --}}
                <div data-aos="fade-left">
                    @if($invitation->bride_photo)
                    <div class="w-32 h-32 rounded-full overflow-hidden mx-auto mb-4 ring-4 ring-blue-200 shadow-lg">
                        <img src="{{ $invitation->bride_photo_url }}" class="w-full h-full object-cover" alt="{{ $invitation->bride_name }}">
                    </div>
                    @else
                    <div class="w-32 h-32 rounded-full mx-auto mb-4 flex items-center justify-center shadow-lg ring-4 ring-blue-100"
                         style="background: linear-gradient(135deg, #38bdf8, #2563eb)">
                        <span class="font-great text-4xl text-white">{{ substr($invitation->bride_name, 0, 1) }}</span>
                    </div>
                    @endif
                    <h3 class="font-great text-2xl text-blue-900 mb-1">{{ $invitation->bride_name }}</h3>
                    @if($invitation->bride_full_name)
                    <p class="text-xs text-gray-500 mb-2">{{ $invitation->bride_full_name }}</p>
                    @endif
                    @if($invitation->bride_father || $invitation->bride_mother)
                    <p class="text-xs text-blue-400">Putri dari</p>
                    <p class="text-xs text-blue-800/70">{{ $invitation->bride_father }}{{ $invitation->bride_father && $invitation->bride_mother ? ' & ' : '' }}{{ $invitation->bride_mother }}</p>
                    @endif
                </div>
            </div>
        </div>
    </section>

    {{-- ── EVENTS ────────────────────────────── --}}
    @if($events->isNotEmpty())
    <section id="nav-events" class="py-16 px-6 bg-bb-events relative overflow-hidden">
        {{-- Butterfly bg decorations --}}
        <svg class="absolute top-4 right-4 w-24 h-14 opacity-10" viewBox="0 0 100 60" fill="white">
            <path d="M50 30 Q30 5 5 15 Q20 35 50 30z"/><path d="M50 30 Q70 5 95 15 Q80 35 50 30z"/>
            <path d="M50 30 Q25 55 5 45 Q20 25 50 30z" opacity=".6"/><path d="M50 30 Q75 55 95 45 Q80 25 50 30z" opacity=".6"/>
        </svg>
        <svg class="absolute bottom-4 left-4 w-20 h-12 opacity-10" viewBox="0 0 100 60" fill="white">
            <path d="M50 30 Q30 5 5 15 Q20 35 50 30z"/><path d="M50 30 Q70 5 95 15 Q80 35 50 30z"/>
            <path d="M50 30 Q25 55 5 45 Q20 25 50 30z" opacity=".6"/><path d="M50 30 Q75 55 95 45 Q80 25 50 30z" opacity=".6"/>
        </svg>

        <div class="max-w-lg mx-auto relative z-10">
            <p class="font-cormo text-blue-200/70 text-xs tracking-[0.35em] uppercase text-center mb-10" data-aos="fade-up">Jadwal Acara</p>

            <div class="space-y-6">
                @foreach($events as $event)
                <div class="rounded-2xl p-6 border border-white/20" style="background: rgba(255,255,255,0.1); backdrop-filter: blur(8px);" data-aos="fade-up">
                    <h3 class="font-great text-2xl text-white mb-3">{{ $event->name }}</h3>
                    <div class="space-y-1.5 text-blue-100/90 text-sm">
                        <p>📅 {{ \Carbon\Carbon::parse($event->date)->translatedFormat('l, d F Y') }}</p>
                        <p>🕐 {{ $event->time_start }}{{ $event->time_end ? ' — '.$event->time_end : '' }} WIB</p>
                        <p>📍 {{ $event->venue }}</p>
                        @if($event->venue_address)
                        <p class="text-blue-200/60 text-xs pl-5">{{ $event->venue_address }}</p>
                        @endif
                    </div>

                    {{-- Countdown --}}
                    @php $targetDate = \Carbon\Carbon::parse($event->date)->toIso8601String(); @endphp
                    <div x-data="countdown('{{ $targetDate }}')" class="flex gap-2 mt-4">
                        @foreach(['days'=>'Hari','hours'=>'Jam','minutes'=>'Mnt','seconds'=>'Dtk'] as $unit => $label)
                        <div class="text-center countdown-box px-3 py-2 flex-1">
                            <div class="font-cormo text-2xl font-semibold text-white" x-text="{{ $unit }}"></div>
                            <div class="text-[10px] text-blue-200/70">{{ $label }}</div>
                        </div>
                        @endforeach
                    </div>

                    <div class="flex flex-wrap gap-3 mt-4">
                        @if($event->venue_maps_url)
                        <a href="{{ $event->venue_maps_url }}" target="_blank"
                           class="flex items-center gap-1.5 text-blue-200/80 text-xs hover:text-white transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            Google Maps
                        </a>
                        @endif

                        <div x-data="saveToCalendar({
                            title: '{{ addslashes($event->name . ' - ' . $invitation->getCoupleName()) }}',
                            date: '{{ $event->date }}',
                            time: '{{ $event->time_start }}',
                            description: '{{ addslashes('Pernikahan ' . $invitation->getCoupleName()) }}',
                            location: '{{ addslashes($event->venue . ' ' . $event->venue_address) }}'
                        })">
                            <button @click="addToCalendar()" class="flex items-center gap-1.5 text-blue-200/80 text-xs hover:text-white transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                Simpan Kalender
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- ── LOVE STORY ────────────────────────── --}}
    @if($invitation->story)
    <section class="py-16 px-6 bg-bb-light">
        <div class="max-w-lg mx-auto text-center">
            <div class="bb-divider mb-8">
                <svg class="w-7 h-4 flex-shrink-0" viewBox="0 0 100 60" fill="#38bdf8" opacity=".6">
                    <path d="M50 30 Q30 5 5 15 Q20 35 50 30z"/><path d="M50 30 Q70 5 95 15 Q80 35 50 30z"/>
                    <path d="M50 30 Q25 55 5 45 Q20 25 50 30z" opacity=".5"/><path d="M50 30 Q75 55 95 45 Q80 25 50 30z" opacity=".5"/>
                </svg>
            </div>
            <p class="font-cormo text-blue-400/70 text-xs tracking-[0.35em] uppercase mb-8" data-aos="fade-up">Cerita Kami</p>
            <p class="font-cormo text-blue-900/80 text-lg leading-relaxed italic" data-aos="fade-up">{{ $invitation->story }}</p>
        </div>
    </section>
    @endif

    {{-- ── GALLERY COLLAGE ──────────────────── --}}
    @if($galleries->isNotEmpty())
    <section id="nav-gallery" class="py-16" style="background: var(--bb-pale)">
        <div class="max-w-lg mx-auto px-6">
            <p class="font-cormo text-blue-400/70 text-xs tracking-[0.35em] uppercase text-center mb-8" data-aos="fade-up">Galeri</p>
        </div>
        @include('partials.gallery-collage', [
            'galleries'   => $galleries,
            'gcCellClass' => 'rounded-2xl border-2 border-blue-100',
            'gcGap'       => 6,
        ])
    </section>
    @endif

    {{-- ── GIFT ──────────────────────────────── --}}
    @if($gifts->isNotEmpty())
    <section class="py-16 px-6 bg-bb-light">
        <div class="max-w-lg mx-auto">
            <div class="bb-divider mb-8">
                <svg class="w-7 h-4 flex-shrink-0" viewBox="0 0 100 60" fill="#38bdf8" opacity=".6">
                    <path d="M50 30 Q30 5 5 15 Q20 35 50 30z"/><path d="M50 30 Q70 5 95 15 Q80 35 50 30z"/>
                    <path d="M50 30 Q25 55 5 45 Q20 25 50 30z" opacity=".5"/><path d="M50 30 Q75 55 95 45 Q80 25 50 30z" opacity=".5"/>
                </svg>
            </div>
            <p class="font-cormo text-blue-400/70 text-xs tracking-[0.35em] uppercase text-center mb-2" data-aos="fade-up">Hadiah Pernikahan</p>
            <p class="text-center text-xs text-blue-400/60 mb-8" data-aos="fade-up">Doa restu Anda adalah hadiah terbaik. Namun jika ingin berbagi kebaikan:</p>

            <div class="space-y-4">
                @foreach($gifts as $gift)
                <div class="gift-card p-5" data-aos="fade-up">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0"
                             style="background: linear-gradient(135deg, #dbeafe, #bfdbfe)">
                            @if($gift->type === 'qris')
                            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                            @else
                            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                            @endif
                        </div>
                        <div class="flex-1">
                            <p class="font-semibold text-blue-900 text-sm">{{ $gift->label ?: ($gift->bank_name ?: 'QRIS') }}</p>
                            @if($gift->account_number)
                            <p class="font-mono text-blue-700 text-lg font-bold mt-1">{{ $gift->account_number }}</p>
                            <p class="text-xs text-gray-500">{{ $gift->account_name }}</p>
                            <button onclick="navigator.clipboard.writeText('{{ $gift->account_number }}')"
                                    class="mt-2 text-xs text-blue-500 hover:text-blue-700 flex items-center gap-1 transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                Salin nomor
                            </button>
                            @endif
                            @if($gift->qris_image)
                            <img src="{{ $gift->qris_image_url }}" alt="QRIS" class="mt-3 max-w-[200px] rounded-xl shadow-sm border border-blue-100">
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- ── RSVP ──────────────────────────────── --}}
    @if($invitation->is_open)
    <section class="py-16 px-6 bg-bb-rsvp"
             style="--rsvp-accent:#2563eb;--rsvp-accent-bg:rgba(37,99,235,0.1);--rsvp-gradient:linear-gradient(135deg,#2563eb,#1e3a5f);--rsvp-label:rgba(37,99,235,0.75);--rsvp-border:rgba(147,197,253,0.7);--rsvp-input-bg:rgba(239,246,255,0.7);--rsvp-locked-bg:rgba(37,99,235,0.05)">
        <div class="max-w-lg mx-auto">
            <div class="bb-divider mb-8">
                <svg class="w-7 h-4 flex-shrink-0" viewBox="0 0 100 60" fill="#38bdf8" opacity=".6">
                    <path d="M50 30 Q30 5 5 15 Q20 35 50 30z"/><path d="M50 30 Q70 5 95 15 Q80 35 50 30z"/>
                    <path d="M50 30 Q25 55 5 45 Q20 25 50 30z" opacity=".5"/><path d="M50 30 Q75 55 95 45 Q80 25 50 30z" opacity=".5"/>
                </svg>
            </div>
            <p class="font-cormo text-blue-400/70 text-xs tracking-[0.35em] uppercase text-center mb-2" data-aos="fade-up">Konfirmasi Kehadiran</p>
            <p class="text-center text-xs text-blue-400/70 mb-8" data-aos="fade-up">
                Mohon konfirmasi sebelum {{ $invitation->rsvp_deadline ? \Carbon\Carbon::parse($invitation->rsvp_deadline)->translatedFormat('d F Y') : 'hari H' }}
            </p>
            <livewire:invitation.rsvp-form :invitation="$invitation" :guest="$guest" />
        </div>
    </section>
    @endif

    {{-- ── WISHES ────────────────────────────── --}}
    <section class="py-16 px-6 bg-bb-light">
        <div class="max-w-lg mx-auto">
            <div class="bb-divider mb-8">
                <svg class="w-7 h-4 flex-shrink-0" viewBox="0 0 100 60" fill="#38bdf8" opacity=".6">
                    <path d="M50 30 Q30 5 5 15 Q20 35 50 30z"/><path d="M50 30 Q70 5 95 15 Q80 35 50 30z"/>
                    <path d="M50 30 Q25 55 5 45 Q20 25 50 30z" opacity=".5"/><path d="M50 30 Q75 55 95 45 Q80 25 50 30z" opacity=".5"/>
                </svg>
            </div>
            <p class="font-cormo text-blue-400/70 text-xs tracking-[0.35em] uppercase text-center mb-8" data-aos="fade-up">Ucapan & Doa</p>
            <livewire:invitation.guest-wishes :invitation="$invitation" />
        </div>
    </section>

    {{-- ── FOOTER ────────────────────────────── --}}
    <footer class="py-10 text-center px-6 relative overflow-hidden bg-bb-hero">
        {{-- Butterflies in footer --}}
        <svg class="absolute top-3 left-4 w-16 h-10 opacity-15" viewBox="0 0 100 60" fill="white">
            <path d="M50 30 Q30 5 5 15 Q20 35 50 30z"/><path d="M50 30 Q70 5 95 15 Q80 35 50 30z"/>
            <path d="M50 30 Q25 55 5 45 Q20 25 50 30z" opacity=".6"/><path d="M50 30 Q75 55 95 45 Q80 25 50 30z" opacity=".6"/>
        </svg>
        <svg class="absolute top-3 right-4 w-14 h-8 opacity-15" viewBox="0 0 100 60" fill="white">
            <path d="M50 30 Q30 5 5 15 Q20 35 50 30z"/><path d="M50 30 Q70 5 95 15 Q80 35 50 30z"/>
            <path d="M50 30 Q25 55 5 45 Q20 25 50 30z" opacity=".6"/><path d="M50 30 Q75 55 95 45 Q80 25 50 30z" opacity=".6"/>
        </svg>

        <p class="font-cormo text-blue-200/70 text-sm italic mb-2">Merupakan suatu kehormatan atas kehadiran Anda</p>
        <h2 class="font-great text-3xl text-white">{{ $invitation->getCoupleName() }}</h2>

        @if($show_watermark)
        <div class="mt-6">
            <p class="text-blue-300/40 text-xs">Dibuat dengan ❤ oleh</p>
            <a href="{{ config('app.url') }}" class="text-blue-300/60 text-xs hover:text-white transition-colors">Invora.id</a>
        </div>
        @endif
    </footer>

    {{-- ── BOTTOM NAVBAR ──────────────────────── --}}
    @include('partials.invitation-navbar', ['navStyle' => 'blue'])

</div>{{-- end opened --}}

{{-- ── MUSIC CONTROL ─────────────────────────── --}}
@if($invitation->music_url)
<div x-show="opened" class="fixed bottom-20 right-4 z-[80]">
    <button @click="$store.invitation.toggleMusic()"
            class="w-12 h-12 rounded-full music-pill flex items-center justify-center shadow-xl border border-white/20 transition-all hover:scale-105">
        <svg x-show="!$store.invitation.musicPlaying" class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M9.383 3.076A1 1 0 0110 4v12a1 1 0 01-1.707.707L4.586 13H2a1 1 0 01-1-1V8a1 1 0 011-1h2.586l3.707-3.707a1 1 0 011.09-.217zM14.657 2.929a1 1 0 011.414 0A9.972 9.972 0 0119 10a9.972 9.972 0 01-2.929 7.071 1 1 0 01-1.414-1.414A7.971 7.971 0 0017 10c0-2.21-.894-4.208-2.343-5.657a1 1 0 010-1.414zm-2.829 2.828a1 1 0 011.415 0A5.983 5.983 0 0115 10a5.984 5.984 0 01-1.757 4.243 1 1 0 01-1.415-1.415A3.984 3.984 0 0013 10a3.983 3.983 0 00-1.172-2.828 1 1 0 010-1.415z" clip-rule="evenodd"/>
        </svg>
        <svg x-show="$store.invitation.musicPlaying" class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zM7 8a1 1 0 012 0v4a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v4a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
        </svg>
    </button>
    @if($invitation->music_name)
    <div class="absolute right-14 top-1/2 -translate-y-1/2 music-pill rounded-full px-3 py-1.5 shadow-sm"
         x-show="$store.invitation.musicPlaying">
        <p class="text-xs text-blue-200 truncate max-w-[120px]">♪ {{ $invitation->music_name }}</p>
    </div>
    @endif
</div>
@endif

{{-- ── WATERMARK ──────────────────────────────── --}}
@if($show_watermark)
<div x-show="opened" class="watermark pointer-events-auto">
    <a href="{{ config('app.url') }}" target="_blank" class="hover:text-blue-500 transition-colors">
        ❤ Invora.id
    </a>
</div>
@endif

<div id="butterfly-canvas"></div>

@include('partials.cinematic-opening')
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    AOS.init({ duration: 900, once: true, offset: 50 });

    // ── Animated Flying Butterflies ─────────────────────────────
    (function () {
        var canvas = document.getElementById('butterfly-canvas');
        if (!canvas) return;

        // Colour palettes matching the blue theme
        var PAL = [
            { c1:'#2563eb', c2:'#1d4ed8', body:'#1e3a5f' },
            { c1:'#38bdf8', c2:'#0284c7', body:'#0c4a6e' },
            { c1:'#60a5fa', c2:'#3b82f6', body:'#1e3a5f' },
            { c1:'#93c5fd', c2:'#60a5fa', body:'#1e40af' },
            { c1:'#bfdbfe', c2:'#93c5fd', body:'#2563eb' },
            { c1:'#38bdf8', c2:'#2563eb', body:'#1e3a5f' },
        ];

        function mkBfly(p) {
            // SVG butterfly: bwl = left wings group, bwr = right wings group
            // Wings fold toward body centre (50,35) when scaleX → 0
            return '<svg class="bfly-svg" viewBox="0 0 100 65" xmlns="http://www.w3.org/2000/svg" style="width:100%;height:100%">'
                // ── left wings ──
                + '<g class="bwl">'
                +   '<path d="M50 35 C36 12,8 9,10 27 C12 42,36 42,50 35Z"             fill="'+p.c1+'"/>'
                +   '<path d="M50 35 C30 38,7 52,12 60 C19 65,38 52,50 35Z"            fill="'+p.c2+'"/>'
                +   '<path d="M50 35 C38 18,16 13,18 27 C20 35,36 38,50 35Z"           fill="'+p.c1+'" opacity=".28"/>'
                + '</g>'
                // ── right wings ──
                + '<g class="bwr">'
                +   '<path d="M50 35 C64 12,92 9,90 27 C88 42,64 42,50 35Z"            fill="'+p.c1+'"/>'
                +   '<path d="M50 35 C70 38,93 52,88 60 C81 65,62 52,50 35Z"           fill="'+p.c2+'"/>'
                +   '<path d="M50 35 C62 18,84 13,82 27 C80 35,64 38,50 35Z"           fill="'+p.c1+'" opacity=".28"/>'
                + '</g>'
                // ── body ──
                + '<ellipse cx="50" cy="38" rx="2.5" ry="11" fill="'+p.body+'"/>'
                + '<circle  cx="50" cy="25" r="3"           fill="'+p.body+'"/>'
                // ── antennae ──
                + '<path d="M50 24 Q43 15 40 9" stroke="'+p.body+'" stroke-width="1" fill="none" stroke-linecap="round"/>'
                + '<path d="M50 24 Q57 15 60 9" stroke="'+p.body+'" stroke-width="1" fill="none" stroke-linecap="round"/>'
                + '<circle cx="40" cy="9" r="2" fill="'+p.body+'"/>'
                + '<circle cx="60" cy="9" r="2" fill="'+p.body+'"/>'
                + '</svg>';
        }

        var N = 7;
        for (var i = 0; i < N; i++) {
            var p      = PAL[i % PAL.length];
            var sz     = 36 + Math.random() * 36;         // 36–72 px wide
            var topPct = 6 + Math.random() * 78;          // 6–84 % from top
            var fdur   = 13 + Math.random() * 17;         // 13–30 s flight
            var wdur   = 3  + Math.random() * 4;          // 3–7 s wave cycle
            var fdel   = -(Math.random() * fdur);         // already mid-flight on load
            var wdel   = -(Math.random() * wdur);         // already mid-wave on load
            var wy1    = (Math.random() > .5 ? -1 : 1) * (15 + Math.random() * 35);
            var wy2    = -(wy1 * 0.6) + (Math.random() > .5 ? 8 : -8);
            var fs     = 0.22 + Math.random() * 0.32;     // 0.22–0.54 s flap speed
            var bop    = 0.50 + Math.random() * 0.42;     // 0.5–0.92 opacity

            // Vertical wave wrapper
            var wrap = document.createElement('div');
            wrap.className = 'bfly-wrap';
            wrap.style.cssText =
                'top:'     + topPct + '%;'
              + '--wdur:'  + wdur + 's;'
              + '--wdel:'  + wdel + 's;'
              + '--wy1:'   + wy1  + 'px;'
              + '--wy2:'   + wy2  + 'px;';

            // Horizontal flight layer
            var go = document.createElement('div');
            go.className = 'bfly-go';
            go.style.cssText =
                '--fdur:' + fdur + 's;'
              + '--fdel:' + fdel + 's;'
              + '--bop:'  + bop  + ';';

            // Inner container — sets flap speed via --fs (inherited by SVG child)
            var inner = document.createElement('div');
            inner.style.cssText =
                'width:'  + sz + 'px;'
              + 'height:' + (sz * 0.65) + 'px;'
              + '--fs:'   + fs + 's;';
            inner.innerHTML = mkBfly(p);

            go.appendChild(inner);
            wrap.appendChild(go);
            canvas.appendChild(wrap);
        }
    })();
</script>
</body>
</html>
