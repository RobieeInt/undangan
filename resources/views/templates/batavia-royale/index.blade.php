<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<meta property="og:title"  content="{{ $invitation->getCoupleName() }} — Batavia Royale">
<meta property="og:image"  content="{{ $invitation->cover_photo_url ?? asset('img/og-default.jpg') }}">
<title>{{ $invitation->getCoupleName() }} — Batavia Royale</title>
<link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700&family=Cormorant+Garamond:ital,wght@0,400;0,600;1,400;1,600&family=Poppins:wght@300;400;500&display=swap" rel="stylesheet">
@vite(['resources/css/app.css','resources/js/app.js'])
<style>
/* ═══════════════════════════════════════════════════════════
   BATAVIA ROYALE — Colonial Ballroom Wedding Template
   Palette: Navy · Gold · Champagne · Ivory · Dark Wood
═══════════════════════════════════════════════════════════ */
:root{
    --navy:      #0F1E3A;
    --navy2:     #1D2F5A;
    --navy3:     #152444;
    --gold:      #D4AF37;
    --gold2:     #C9A227;
    --gold-dim:  rgba(212,175,55,.18);
    --champagne: #E6D5B8;
    --ivory:     #F7F3EA;
    --darkwood:  #4A3324;
}
*{margin:0;padding:0;box-sizing:border-box}
[x-cloak]{display:none!important}
html{scroll-behavior:smooth}
body{
    background:var(--navy);
    color:var(--champagne);
    font-family:'Poppins',sans-serif;
    overflow-x:hidden;
}

/* ── Gold divider ───────────────────────────────────────── */
.br-divider{
    display:flex;align-items:center;gap:.75rem;
    margin:1.25rem auto;max-width:220px;
}
.br-divider::before,.br-divider::after{
    content:'';flex:1;height:1px;
}
.br-divider::before{background:linear-gradient(to right,transparent,var(--gold))}
.br-divider::after {background:linear-gradient(to left, transparent,var(--gold))}

/* ── Section base ───────────────────────────────────────── */
.br-section{padding:80px 24px;max-width:860px;margin:0 auto}
.br-title{
    font-family:'Cinzel',serif;color:var(--gold);
    font-size:clamp(1rem,2.5vw,1.4rem);
    letter-spacing:.25em;text-transform:uppercase;text-align:center;
}

/* ── Card ───────────────────────────────────────────────── */
.br-card{
    background:rgba(255,255,255,.04);
    border:1px solid var(--gold-dim);
    border-radius:8px;
    backdrop-filter:blur(10px);
    -webkit-backdrop-filter:blur(10px);
}

/* ── Art-Deco corner frame ──────────────────────────────── */
.br-frame{position:relative}
.br-frame::before,.br-frame::after{
    content:'';position:absolute;width:28px;height:28px;
    border:1.5px solid rgba(212,175,55,.45);
}
.br-frame::before{top:10px;left:10px;
    border-right:none;border-bottom:none}
.br-frame::after {bottom:10px;right:10px;
    border-left:none;border-top:none}
.br-frame .br-corner-br{
    position:absolute;bottom:10px;left:10px;
    width:28px;height:28px;
    border:1.5px solid rgba(212,175,55,.45);
    border-right:none;border-top:none;
}
.br-frame .br-corner-tr{
    position:absolute;top:10px;right:10px;
    width:28px;height:28px;
    border:1.5px solid rgba(212,175,55,.45);
    border-left:none;border-bottom:none;
}

/* ── Curtains ───────────────────────────────────────────── */
#br-curtain-l,#br-curtain-r{
    position:fixed;top:0;width:52%;height:100%;z-index:92;
    will-change:transform;overflow:hidden;
    transform-origin:top center;
}
#br-curtain-l{left:0}
#br-curtain-r{right:0}

.br-velvet{
    width:100%;height:100%;
    background:
        repeating-linear-gradient(
            to right,
            #071326 0%,  #0d1e3a 5%,
            #152444 10%, #0d1e3a 15%,
            #071326 20%, #0e1c38 25%,
            #1a2d52 32%, #0e1c38 38%,
            #071326 44%
        );
}
#br-curtain-l .br-velvet{
    border-right:3px solid var(--gold);
    box-shadow:inset -24px 0 48px rgba(212,175,55,.06),2px 0 24px rgba(212,175,55,.14);
}
#br-curtain-r .br-velvet{
    border-left:3px solid var(--gold);
    box-shadow:inset 24px 0 48px rgba(212,175,55,.06),-2px 0 24px rgba(212,175,55,.14);
}
.br-pelmet{
    position:absolute;top:0;left:0;right:0;height:72px;z-index:2;
    background:linear-gradient(to bottom,#060f20 0%,#0c1830 100%);
    border-bottom:2px solid var(--gold);
}
/* Scalloped pelmet bottom */
.br-pelmet::after{
    content:'';position:absolute;bottom:-1px;left:0;right:0;height:18px;
    background:
        radial-gradient(ellipse 40px 18px at 20px 0,transparent 60%,#0c1830 62%) 0px 0,
        radial-gradient(ellipse 40px 18px at 20px 0,transparent 60%,#0c1830 62%) 40px 0;
    background-size:40px 100%;
}

/* ── Cover screen ───────────────────────────────────────── */
#br-cover{
    position:fixed;inset:0;z-index:96; /* above curtains (92) so button is clickable */
    display:flex;flex-direction:column;
    align-items:center;justify-content:center;
    background:transparent; /* show velvet curtains through */
    overflow:hidden;
}

/* ── Gold dust particles ────────────────────────────────── */
.br-dust{
    position:fixed;border-radius:50%;
    background:var(--gold);pointer-events:none;z-index:5;
    animation:br-rise linear infinite;
}
@keyframes br-rise{
    0%  {transform:translateY(110vh) rotate(0deg);opacity:0}
    6%  {opacity:.55}
    94% {opacity:.25}
    100%{transform:translateY(-8vh) rotate(360deg);opacity:0}
}

/* ── Hero ───────────────────────────────────────────────── */
#br-hero{
    position:relative;min-height:100vh;
    display:flex;align-items:center;justify-content:center;overflow:hidden;
}
#br-hero-bg{
    position:absolute;inset:0;
    background-size:cover;background-position:center;
    animation:br-kenburns 22s ease-in-out infinite alternate;
}
@keyframes br-kenburns{from{transform:scale(1)}to{transform:scale(1.12)}}
@keyframes br-bounce{
    0%,100%{transform:translateX(-50%) translateY(0)}
    50%    {transform:translateX(-50%) translateY(7px)}
}

/* ── Couple photo frame ─────────────────────────────────── */
.br-photo{
    position:relative;
    width:clamp(90px,36vw,150px); /* responsive, bukan fixed */
    aspect-ratio:4/5;             /* jaga rasio, buang height fixed */
    border:2px solid var(--gold);border-radius:2px;
    overflow:hidden;margin:0 auto;
}
.br-photo::after{
    content:'';position:absolute;inset:5px;
    border:1px solid rgba(212,175,55,.3);
    border-radius:1px;z-index:2;pointer-events:none;
}
/* pastikan img mengisi penuh frame responsive */
.br-photo img{
    width:100%;height:100%;object-fit:cover;display:block;
}

/* ── Gallery grid ───────────────────────────────────────── */
.br-gallery{
    display:grid;
    grid-template-columns:repeat(3,1fr);
    gap:6px;
}
.br-gitem{
    position:relative;overflow:hidden;
    border:1px solid var(--gold-dim);border-radius:4px;
    aspect-ratio:4/3;cursor:pointer;background:#0d1a30;
}
.br-gitem.br-tall{aspect-ratio:3/4}
.br-gitem.br-span2{grid-column:span 2}
.br-gitem img{
    width:100%;height:100%;object-fit:cover;
    transition:transform .6s ease;display:block;
}
.br-gitem:hover img{transform:scale(1.07)}
.br-gcap{
    position:absolute;bottom:0;left:0;right:0;
    padding:.4rem .65rem;
    background:linear-gradient(transparent,rgba(10,20,45,.88));
    font-size:.68rem;color:rgba(230,213,184,.85);
    opacity:0;transition:opacity .3s;
}
.br-gitem:hover .br-gcap{opacity:1}

/* ── Wish card ──────────────────────────────────────────── */
.br-wish{
    border-left:2px solid var(--gold-dim);
    padding:.9rem 1.1rem;
    background:rgba(255,255,255,.025);
    border-radius:0 8px 8px 0;
}

/* ── Scroll reveal ──────────────────────────────────────── */
.br-reveal{
    opacity:0;transform:translateY(36px);
    transition:opacity .75s ease,transform .75s ease;
}
.br-reveal.in{opacity:1;transform:translateY(0)}
@media(prefers-reduced-motion:reduce){.br-reveal{opacity:1;transform:none}}

/* ── Navbar ─────────────────────────────────────────────── */
#br-nav{
    position:fixed;bottom:14px;left:50%;transform:translateX(-50%);
    z-index:70;
    background:rgba(10,20,48,.88);backdrop-filter:blur(18px);-webkit-backdrop-filter:blur(18px);
    border:1px solid rgba(212,175,55,.28);border-radius:999px;
    padding:7px 12px;display:none;gap:2px;
}
@media(min-width:768px){#br-nav{top:14px;bottom:auto}}
.br-nav-a{
    display:flex;flex-direction:column;align-items:center;gap:2px;
    padding:5px 9px;border-radius:999px;cursor:pointer;
    color:var(--champagne);text-decoration:none;
    font-size:0;transition:background .2s,color .2s;
    border:none;background:transparent;font-family:'Poppins',sans-serif;
}
@media(min-width:420px){.br-nav-a{font-size:.58rem}}
.br-nav-a:hover,.br-nav-a.on{background:rgba(212,175,55,.14);color:var(--gold)}
.br-nav-a svg{width:17px;height:17px}

/* ── Music button ───────────────────────────────────────── */
#br-music{
    position:fixed;top:18px;right:18px;z-index:72;
    width:38px;height:38px;border-radius:50%;
    background:rgba(10,20,48,.85);backdrop-filter:blur(10px);
    border:1px solid rgba(212,175,55,.35);
    display:flex;align-items:center;justify-content:center;
    cursor:pointer;transition:border-color .2s;
}
#br-music:hover{border-color:var(--gold)}

/* ── RSVP skin ──────────────────────────────────────────── */
#br-rsvp-wrap{--rsvp-section-bg:#0F1E3A}

/* ── Watermark ──────────────────────────────────────────── */
.br-wm{
    text-align:center;margin-top:2rem;
    font-size:.6rem;letter-spacing:.22em;
    color:rgba(212,175,55,.22);font-family:'Cinzel',serif;
}
</style>
</head>

<body x-init="$store.invitation.initMusic('{{ $invitation->music_url }}',{{ $invitation->music_autoplay ? 'true' : 'false' }})">

{{-- ── AMBIENT DUST ─────────────────────────────────────────────── --}}
<div id="br-particles" aria-hidden="true"></div>

{{-- ── VELVET CURTAINS ─────────────────────────────────────────── --}}
<div id="br-curtain-l" aria-hidden="true">
    <div class="br-pelmet"></div>
    <div class="br-velvet" style="padding-top:72px"></div>
</div>
<div id="br-curtain-r" aria-hidden="true">
    <div class="br-pelmet"></div>
    <div class="br-velvet" style="padding-top:72px"></div>
</div>

{{-- ── COVER SCREEN ────────────────────────────────────────────── --}}
<div id="br-cover">
    {{-- Cover content floats above the velvet curtains --}}
    <div style="position:relative;z-index:2;text-align:center;padding:2.5rem 2rem 2.8rem;max-width:480px;width:calc(100% - 2rem);
                background:rgba(6,12,28,.58);backdrop-filter:blur(6px);-webkit-backdrop-filter:blur(6px);
                border:1px solid rgba(212,175,55,.16);border-radius:4px;
                box-shadow:0 8px 48px rgba(0,0,0,.4)">

        {{-- Art-deco top ornament --}}
        <div style="display:flex;align-items:center;justify-content:center;gap:.6rem;margin-bottom:2rem">
            <div style="height:1px;width:52px;background:linear-gradient(to right,transparent,rgba(212,175,55,.6))"></div>
            <svg width="22" height="22" viewBox="0 0 24 24"><path d="M12 2L14.2 9H21L15.4 13.5L17.6 21L12 16.5L6.4 21L8.6 13.5L3 9H9.8Z" fill="#D4AF37" opacity=".65"/></svg>
            <div style="height:1px;width:52px;background:linear-gradient(to left,transparent,rgba(212,175,55,.6))"></div>
        </div>

        <p style="font-family:'Cinzel',serif;font-size:.6rem;letter-spacing:.5em;color:rgba(212,175,55,.65);text-transform:uppercase;margin-bottom:1.1rem">The Wedding Of</p>

        <h1 style="font-family:'Cinzel',serif;font-size:clamp(2rem,8vw,3.2rem);color:var(--gold);line-height:1.05;text-shadow:0 0 36px rgba(212,175,55,.3)">{{ $invitation->groom_name }}</h1>
        <p  style="font-family:'Cormorant Garamond',serif;font-size:clamp(2rem,7vw,3rem);color:var(--champagne);font-style:italic;margin:.3rem 0;opacity:.85">&amp;</p>
        <h1 style="font-family:'Cinzel',serif;font-size:clamp(2rem,8vw,3.2rem);color:var(--gold);line-height:1.05;text-shadow:0 0 36px rgba(212,175,55,.3)">{{ $invitation->bride_name }}</h1>

        @if($events->isNotEmpty())
        <p style="font-family:'Cormorant Garamond',serif;font-size:.95rem;color:rgba(230,213,184,.55);margin-top:.9rem;letter-spacing:.04em">
            {{ \Carbon\Carbon::parse($events->first()->date)->translatedFormat('d F Y') }}
        </p>
        @endif

        @if($guest ?? null)
        <div style="margin:1.5rem auto .5rem;height:1px;max-width:110px;background:linear-gradient(to right,transparent,rgba(212,175,55,.35),transparent)"></div>
        <p style="font-size:.6rem;letter-spacing:.25em;color:rgba(212,175,55,.55);text-transform:uppercase">Kepada Yth.</p>
        <p style="font-family:'Cormorant Garamond',serif;font-size:1.2rem;color:var(--champagne);margin-top:.3rem">{{ $guest->name }}</p>
        @if(isset($guest->allocated_seats) && $guest->allocated_seats > 0)
        <p style="font-size:.65rem;color:rgba(212,175,55,.4);margin-top:.2rem">{{ $guest->allocated_seats }} kursi</p>
        @endif
        @endif

        {{-- Open button --}}
        <div style="margin-top:2.2rem">
            <button id="br-open-btn" onclick="openBR()"
                    style="font-family:'Cinzel',serif;font-size:.7rem;letter-spacing:.2em;text-transform:uppercase;
                           background:transparent;border:1px solid var(--gold);color:var(--gold);
                           padding:13px 34px;border-radius:2px;cursor:pointer;
                           position:relative;overflow:hidden;transition:color .3s">
                <span style="position:relative;z-index:1">Buka Undangan</span>
                <span id="br-btn-fill" style="position:absolute;inset:0;background:var(--gold);transform:scaleX(0);transform-origin:left;transition:transform .3s;z-index:0"></span>
            </button>
        </div>
    </div>
</div>

{{-- ══ MAIN CONTENT ════════════════════════════════════════════════ --}}
<div id="br-main" style="display:none;padding-bottom:88px">

    {{-- Music toggle --}}
    @if($invitation->music_url)
    <div id="br-music" x-data @click="$store.invitation.toggleMusic()" title="Musik">
        <svg x-show="!$store.invitation.musicPlaying" style="color:var(--gold)" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2z"/></svg>
        <svg x-show=" $store.invitation.musicPlaying" style="color:var(--gold)" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    </div>
    @endif

    {{-- ── HERO ──────────────────────────────────────────────────────── --}}
    <section id="nav-top" style="position:relative;min-height:100vh;display:flex;align-items:center;justify-content:center;overflow:hidden">
        {{-- Background --}}
        @if($invitation->cover_photo_url)
        <div id="br-hero-bg" style="position:absolute;inset:0;background-image:url('{{ $invitation->cover_photo_url }}');background-size:cover;background-position:center;animation:br-kenburns 22s ease-in-out infinite alternate"></div>
        @else
        <div style="position:absolute;inset:0;background:linear-gradient(135deg,#0a1428 0%,#1a2e52 50%,#0a1428 100%)"></div>
        @endif
        <div style="position:absolute;inset:0;background:linear-gradient(to bottom,rgba(15,30,58,.35) 0%,rgba(15,30,58,.7) 55%,rgba(10,20,40,.95) 100%)"></div>

        {{-- Content --}}
        <div class="br-reveal" style="position:relative;z-index:2;text-align:center;padding:2rem;max-width:580px;width:100%">
            {{-- Top SVG ornament --}}
            <svg width="220" height="22" viewBox="0 0 220 22" fill="none" style="display:block;margin:0 auto 1.5rem">
                <line x1="0" y1="11" x2="78" y2="11" stroke="#D4AF37" stroke-opacity=".35" stroke-width=".8"/>
                <path d="M92 11L98 3L104 11L98 19Z" fill="#D4AF37" opacity=".55"/>
                <rect x="108" y="8" width="5" height="7" fill="#D4AF37" opacity=".3"/>
                <path d="M116 11L122 3L128 11L122 19Z" fill="#D4AF37" opacity=".55"/>
                <line x1="142" y1="11" x2="220" y2="11" stroke="#D4AF37" stroke-opacity=".35" stroke-width=".8"/>
            </svg>

            <p style="font-family:'Cinzel',serif;font-size:.58rem;letter-spacing:.5em;color:rgba(212,175,55,.65);text-transform:uppercase;margin-bottom:1.4rem">The Wedding Of</p>
            <h1 style="font-family:'Cinzel',serif;font-size:clamp(2.4rem,9vw,4.2rem);color:var(--gold);line-height:1;text-shadow:0 2px 32px rgba(212,175,55,.38)">{{ $invitation->groom_name }}</h1>
            <p  style="font-family:'Cormorant Garamond',serif;font-size:clamp(2rem,7vw,3.2rem);color:var(--champagne);font-style:italic;margin:.35rem 0;opacity:.82">&amp;</p>
            <h1 style="font-family:'Cinzel',serif;font-size:clamp(2.4rem,9vw,4.2rem);color:var(--gold);line-height:1;text-shadow:0 2px 32px rgba(212,175,55,.38)">{{ $invitation->bride_name }}</h1>

            @if($events->isNotEmpty())
            <div style="display:flex;align-items:center;justify-content:center;gap:.8rem;margin-top:1.8rem">
                <div style="height:1px;width:36px;background:rgba(212,175,55,.28)"></div>
                <p style="font-family:'Cormorant Garamond',serif;font-size:1rem;color:rgba(230,213,184,.7);letter-spacing:.04em">
                    {{ \Carbon\Carbon::parse($events->first()->date)->translatedFormat('l, d F Y') }}
                </p>
                <div style="height:1px;width:36px;background:rgba(212,175,55,.28)"></div>
            </div>
            @endif
        </div>

        {{-- Scroll arrow --}}
        <div style="position:absolute;bottom:28px;left:50%;animation:br-bounce 2s ease-in-out infinite">
            <svg width="20" height="20" fill="none" stroke="rgba(212,175,55,.45)" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 9l-7 7-7-7"/></svg>
        </div>
    </section>

    {{-- ── MEMPELAI ──────────────────────────────────────────────────── --}}
    <section id="nav-couple" class="br-section">
        <div class="br-reveal">
            <p class="br-title">Mempelai</p>
            <div class="br-divider">
                <svg width="14" height="14" viewBox="0 0 16 16" fill="#D4AF37" opacity=".6"><path d="M8 1L9.5 6H15L10.5 9L12 14L8 11L4 14L5.5 9L1 6H6.5Z"/></svg>
            </div>

            @if($invitation->opening_quote)
            <blockquote style="font-family:'Cormorant Garamond',serif;font-style:italic;color:rgba(230,213,184,.65);text-align:center;font-size:1rem;line-height:1.9;max-width:520px;margin:0 auto 3rem">
                {{ $invitation->opening_quote }}
                @if($invitation->opening_quote_source)
                <cite style="display:block;font-size:.82rem;margin-top:.5rem;opacity:.55;font-style:normal">— {{ $invitation->opening_quote_source }}</cite>
                @endif
            </blockquote>
            @endif

            <div style="display:grid;grid-template-columns:1fr auto 1fr;align-items:start;gap:clamp(.5rem,.75rem,1.5rem);max-width:560px;margin:0 auto">
                {{-- Pengantin Pria --}}
                <div style="text-align:center">
                    <div class="br-photo">
                        @if($invitation->groom_photo_url)
                        <img src="{{ $invitation->groom_photo_url }}" alt="{{ $invitation->groom_name }}">
                        @else
                        <div style="width:100%;height:100%;background:rgba(212,175,55,.05);display:flex;align-items:center;justify-content:center">
                            <svg width="44" height="44" fill="none" stroke="rgba(212,175,55,.25)" viewBox="0 0 24 24" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                        @endif
                    </div>
                    <p style="font-family:'Cinzel',serif;color:var(--gold);font-size:1.05rem;margin-top:1rem">{{ $invitation->groom_name }}</p>
                    @if($invitation->groom_full_name)
                    <p style="font-family:'Cormorant Garamond',serif;font-size:.88rem;color:rgba(230,213,184,.6);margin-top:.2rem">{{ $invitation->groom_full_name }}</p>
                    @endif
                    @if($invitation->groom_father || $invitation->groom_mother)
                    <p style="font-size:.7rem;color:rgba(230,213,184,.4);margin-top:.6rem;line-height:1.7">
                        Putra dari<br>
                        @if($invitation->groom_father)<span style="opacity:.75">{{ $invitation->groom_father }}</span>@endif
                        @if($invitation->groom_father && $invitation->groom_mother)<br>@endif
                        @if($invitation->groom_mother)<span style="opacity:.75">{{ $invitation->groom_mother }}</span>@endif
                    </p>
                    @endif
                </div>

                {{-- Ampersand divider --}}
                <div style="text-align:center;padding-top:clamp(28px,10vw,48px)">
                    <p style="font-family:'Cormorant Garamond',serif;font-size:clamp(2rem,7vw,2.8rem);color:rgba(212,175,55,.45);font-style:italic;line-height:1">&amp;</p>
                </div>

                {{-- Pengantin Wanita --}}
                <div style="text-align:center">
                    <div class="br-photo">
                        @if($invitation->bride_photo_url)
                        <img src="{{ $invitation->bride_photo_url }}" alt="{{ $invitation->bride_name }}">
                        @else
                        <div style="width:100%;height:100%;background:rgba(212,175,55,.05);display:flex;align-items:center;justify-content:center">
                            <svg width="44" height="44" fill="none" stroke="rgba(212,175,55,.25)" viewBox="0 0 24 24" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                        @endif
                    </div>
                    <p style="font-family:'Cinzel',serif;color:var(--gold);font-size:1.05rem;margin-top:1rem">{{ $invitation->bride_name }}</p>
                    @if($invitation->bride_full_name)
                    <p style="font-family:'Cormorant Garamond',serif;font-size:.88rem;color:rgba(230,213,184,.6);margin-top:.2rem">{{ $invitation->bride_full_name }}</p>
                    @endif
                    @if($invitation->bride_father || $invitation->bride_mother)
                    <p style="font-size:.7rem;color:rgba(230,213,184,.4);margin-top:.6rem;line-height:1.7">
                        Putri dari<br>
                        @if($invitation->bride_father)<span style="opacity:.75">{{ $invitation->bride_father }}</span>@endif
                        @if($invitation->bride_father && $invitation->bride_mother)<br>@endif
                        @if($invitation->bride_mother)<span style="opacity:.75">{{ $invitation->bride_mother }}</span>@endif
                    </p>
                    @endif
                </div>
            </div>
        </div>
    </section>

    {{-- Separator --}}
    <div style="max-width:500px;margin:0 auto;padding:0 24px">
        <svg viewBox="0 0 500 24" fill="none"><line x1="0" y1="12" x2="195" y2="12" stroke="#D4AF37" stroke-opacity=".15" stroke-width=".8"/><path d="M210 12L217 4L224 12L217 20Z" fill="#D4AF37" opacity=".35"/><rect x="228" y="8" width="14" height="8" fill="#D4AF37" opacity=".2" rx="1"/><path d="M246 12L253 4L260 12L253 20Z" fill="#D4AF37" opacity=".35"/><line x1="275" y1="12" x2="500" y2="12" stroke="#D4AF37" stroke-opacity=".15" stroke-width=".8"/></svg>
    </div>

    {{-- ── STORY ─────────────────────────────────────────────────────── --}}
    @if($invitation->story)
    <section class="br-section" style="padding-top:60px">
        <div class="br-reveal" style="text-align:center">
            <p class="br-title">Kisah Cinta</p>
            <div class="br-divider"><svg width="14" height="14" viewBox="0 0 16 16" fill="#D4AF37" opacity=".6"><path d="M8 1L9.5 6H15L10.5 9L12 14L8 11L4 14L5.5 9L1 6H6.5Z"/></svg></div>
            <p style="font-family:'Cormorant Garamond',serif;font-size:1.05rem;line-height:1.9;color:rgba(230,213,184,.65);max-width:580px;margin:0 auto">{{ $invitation->story }}</p>
        </div>
    </section>
    @endif

    {{-- ── EVENTS ────────────────────────────────────────────────────── --}}
    @if($events->isNotEmpty())
    <section id="nav-events" class="br-section" style="padding-top:60px">
        <div class="br-reveal">
            <p class="br-title">Detail Acara</p>
            <div class="br-divider"><svg width="14" height="14" viewBox="0 0 16 16" fill="#D4AF37" opacity=".6"><path d="M8 1L9.5 6H15L10.5 9L12 14L8 11L4 14L5.5 9L1 6H6.5Z"/></svg></div>

            <div style="display:flex;flex-direction:column;gap:1.25rem;margin-top:1.75rem">
                @foreach($events as $ev)
                <div class="br-card br-frame" style="padding:28px 28px 24px">
                    <div class="br-corner-br"></div>
                    <div class="br-corner-tr"></div>
                    <p style="font-family:'Cinzel',serif;color:var(--gold);font-size:.85rem;letter-spacing:.18em;margin-bottom:1.1rem">{{ strtoupper($ev->name) }}</p>
                    <div style="display:grid;grid-template-columns:18px 1fr;gap:.55rem .9rem;font-size:.82rem">
                        <span style="color:rgba(212,175,55,.5);line-height:1.5">📅</span>
                        <span style="color:var(--champagne);line-height:1.5">{{ \Carbon\Carbon::parse($ev->date)->translatedFormat('l, d F Y') }}</span>
                        <span style="color:rgba(212,175,55,.5);line-height:1.5">🕐</span>
                        <span style="color:var(--champagne);line-height:1.5">
                            {{ $ev->time_start }}{{ $ev->time_end ? ' – '.$ev->time_end : '' }} WIB
                        </span>
                        <span style="color:rgba(212,175,55,.5);line-height:1.5">📍</span>
                        <span style="color:var(--champagne);line-height:1.5">
                            {{ $ev->venue }}
                            @if($ev->venue_address)
                            <br><span style="font-size:.75rem;opacity:.55">{{ $ev->venue_address }}</span>
                            @endif
                        </span>
                    </div>
                    <div style="display:flex;flex-wrap:wrap;gap:.6rem;margin-top:1.2rem">
                        @if($ev->venue_maps_url)
                        <a href="{{ $ev->venue_maps_url }}" target="_blank"
                           style="display:inline-flex;align-items:center;gap:.4rem;
                                  font-family:'Cinzel',serif;font-size:.6rem;letter-spacing:.15em;text-transform:uppercase;
                                  color:var(--gold);border:1px solid rgba(212,175,55,.28);
                                  padding:7px 14px;border-radius:2px;text-decoration:none;
                                  transition:background .2s"
                           onmouseover="this.style.background='rgba(212,175,55,.08)'"
                           onmouseout="this.style.background='transparent'">
                            <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            Lokasi
                        </a>
                        @endif
                        <button onclick="window.saveToCalendar({title:'{{ addslashes('Pernikahan '.$invitation->getCoupleName()) }}',start:'{{ \Carbon\Carbon::parse($ev->date)->setTimeFromTimeString($ev->time_start ?? '00:00:00')->format('Ymd\THis') }}',end:'{{ \Carbon\Carbon::parse($ev->date)->setTimeFromTimeString($ev->time_end ?? '02:00:00')->format('Ymd\THis') }}',location:'{{ addslashes(($ev->venue ?? '').' '.($ev->venue_address ?? '')) }}',description:'Undangan pernikahan {{ addslashes($invitation->getCoupleName()) }}'})"
                           style="display:inline-flex;align-items:center;gap:.4rem;
                                  font-family:'Cinzel',serif;font-size:.6rem;letter-spacing:.15em;text-transform:uppercase;
                                  color:var(--gold);border:1px solid rgba(212,175,55,.28);
                                  padding:7px 14px;border-radius:2px;cursor:pointer;
                                  background:transparent;transition:background .2s"
                           onmouseover="this.style.background='rgba(212,175,55,.08)'"
                           onmouseout="this.style.background='transparent'">
                            <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            Kalender
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- ── GALLERY ───────────────────────────────────────────────────── --}}
    @if($galleries->isNotEmpty())
    <section id="nav-gallery" class="br-section" style="padding-top:60px">
        <div class="br-reveal">
            <p class="br-title">Galeri</p>
            <div class="br-divider"><svg width="14" height="14" viewBox="0 0 16 16" fill="#D4AF37" opacity=".6"><path d="M8 1L9.5 6H15L10.5 9L12 14L8 11L4 14L5.5 9L1 6H6.5Z"/></svg></div>
            <div class="br-gallery" style="margin-top:1.25rem">
                @foreach($galleries->take(8) as $idx => $gphoto)
                <div class="br-gitem
                    {{ $loop->iteration === 1 ? 'br-span2' : '' }}
                    {{ in_array($loop->iteration, [4,7]) ? 'br-tall' : '' }}">
                    <img src="{{ $gphoto->image_url }}" alt="{{ $gphoto->caption ?? '' }}" loading="lazy">
                    @if($gphoto->caption)
                    <div class="br-gcap">{{ $gphoto->caption }}</div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- ── RSVP ─────────────────────────────────────────────────────── --}}
    @if($invitation->is_open)
    <section id="nav-rsvp" class="br-section" style="padding-top:60px">
        <div class="br-reveal">
            <p class="br-title">Konfirmasi Kehadiran</p>
            <div class="br-divider"><svg width="14" height="14" viewBox="0 0 16 16" fill="#D4AF37" opacity=".6"><path d="M8 1L9.5 6H15L10.5 9L12 14L8 11L4 14L5.5 9L1 6H6.5Z"/></svg></div>
            @if($invitation->rsvp_deadline)
            <p style="text-align:center;font-size:.75rem;color:rgba(212,175,55,.42);margin-bottom:1.25rem">
                Konfirmasi sebelum {{ \Carbon\Carbon::parse($invitation->rsvp_deadline)->translatedFormat('d F Y') }}
            </p>
            @endif
            <div id="br-rsvp-wrap">
                @livewire('invitation.rsvp-form', ['invitation' => $invitation, 'guest' => $guest ?? null])
            </div>
        </div>
    </section>
    @endif

    {{-- ── UCAPAN ────────────────────────────────────────────────────── --}}
    @if($recentWishes->isNotEmpty())
    <section class="br-section" style="padding-top:60px">
        <div class="br-reveal">
            <p class="br-title">Ucapan &amp; Doa</p>
            <div class="br-divider"><svg width="14" height="14" viewBox="0 0 16 16" fill="#D4AF37" opacity=".6"><path d="M8 1L9.5 6H15L10.5 9L12 14L8 11L4 14L5.5 9L1 6H6.5Z"/></svg></div>
            <div style="display:flex;flex-direction:column;gap:.7rem;margin-top:1.25rem">
                @foreach($recentWishes as $wish)
                <div class="br-wish">
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:.4rem">
                        <p style="font-family:'Cinzel',serif;font-size:.78rem;color:var(--gold)">{{ $wish->name }}</p>
                        <span style="font-size:.62rem;padding:2px 8px;border-radius:999px;
                            {{ $wish->attendance === 'hadir'
                                ? 'background:rgba(212,175,55,.1);color:rgba(212,175,55,.7)'
                                : 'background:rgba(255,255,255,.04);color:rgba(230,213,184,.35)' }}">
                            {{ $wish->attendance === 'hadir' ? '✦ Hadir' : 'Tidak Hadir' }}
                        </span>
                    </div>
                    <p style="font-size:.83rem;color:rgba(230,213,184,.62);line-height:1.65">{{ $wish->message }}</p>
                    <p style="font-size:.62rem;color:rgba(212,175,55,.28);margin-top:.35rem">{{ \Carbon\Carbon::parse($wish->created_at)->diffForHumans() }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- ── HADIAH ────────────────────────────────────────────────────── --}}
    @if($gifts->isNotEmpty())
    <section class="br-section" style="padding-top:60px">
        <div class="br-reveal">
            <p class="br-title">Hadiah Pernikahan</p>
            <div class="br-divider"><svg width="14" height="14" viewBox="0 0 16 16" fill="#D4AF37" opacity=".6"><path d="M8 1L9.5 6H15L10.5 9L12 14L8 11L4 14L5.5 9L1 6H6.5Z"/></svg></div>
            <div style="display:flex;flex-direction:column;gap:1rem;margin-top:1.25rem">
                @foreach($gifts as $gift)
                @if($gift->type === 'bank')
                <div class="br-card" style="padding:1.1rem 1.4rem;display:flex;align-items:center;gap:1rem">
                    <div style="width:44px;height:44px;flex-shrink:0;border:1px solid rgba(212,175,55,.25);border-radius:3px;display:flex;align-items:center;justify-content:center">
                        <svg width="22" height="22" fill="none" stroke="rgba(212,175,55,.55)" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                    </div>
                    <div style="flex:1;min-width:0">
                        <p style="font-family:'Cinzel',serif;font-size:.72rem;color:var(--gold);letter-spacing:.12em">{{ strtoupper($gift->bank_name) }}</p>
                        <p style="font-size:.95rem;color:var(--champagne);letter-spacing:.04em;margin:.15rem 0">{{ $gift->account_number }}</p>
                        <p style="font-size:.72rem;color:rgba(230,213,184,.45)">{{ $gift->account_name }}</p>
                    </div>
                    <button onclick="navigator.clipboard.writeText('{{ $gift->account_number }}').then(()=>this.textContent='✓').catch(()=>{})"
                            style="flex-shrink:0;background:rgba(212,175,55,.09);border:1px solid rgba(212,175,55,.2);
                                   color:var(--gold);padding:5px 12px;border-radius:3px;
                                   font-size:.62rem;cursor:pointer;letter-spacing:.06em;
                                   font-family:'Cinzel',serif;transition:background .2s"
                            onmouseover="this.style.background='rgba(212,175,55,.18)'"
                            onmouseout="this.style.background='rgba(212,175,55,.09)'">Salin</button>
                </div>
                @endif
                @if($gift->type === 'qris' && $gift->qris_image_url)
                <div class="br-card" style="padding:1.1rem 1.4rem;text-align:center">
                    <p style="font-family:'Cinzel',serif;font-size:.72rem;color:var(--gold);letter-spacing:.12em;margin-bottom:.9rem">{{ $gift->label ?? 'QRIS' }}</p>
                    <img src="{{ $gift->qris_image_url }}" alt="QRIS" style="max-width:170px;margin:0 auto;display:block;border:1px solid rgba(212,175,55,.18);border-radius:4px;padding:8px;background:white">
                </div>
                @endif
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- ── PENUTUP ───────────────────────────────────────────────────── --}}
    <section style="padding:56px 24px;text-align:center;border-top:1px solid rgba(212,175,55,.08)">
        <div class="br-reveal">
            <svg width="220" height="22" viewBox="0 0 220 22" fill="none" style="display:block;margin:0 auto 1.5rem">
                <line x1="0" y1="11" x2="78" y2="11" stroke="#D4AF37" stroke-opacity=".2" stroke-width=".8"/>
                <path d="M92 11L98 3L104 11L98 19Z" fill="#D4AF37" opacity=".38"/>
                <rect x="108" y="8" width="5" height="7" fill="#D4AF37" opacity=".22" rx="1"/>
                <path d="M116 11L122 3L128 11L122 19Z" fill="#D4AF37" opacity=".38"/>
                <line x1="142" y1="11" x2="220" y2="11" stroke="#D4AF37" stroke-opacity=".2" stroke-width=".8"/>
            </svg>
            <p style="font-family:'Cormorant Garamond',serif;font-style:italic;font-size:1rem;color:rgba(230,213,184,.45);line-height:1.9">
                Merupakan suatu kehormatan bagi kami apabila<br>Bapak/Ibu/Saudara/i berkenan hadir
            </p>
            <p style="font-family:'Cinzel',serif;font-size:1.15rem;color:var(--gold);margin-top:1.4rem;letter-spacing:.1em">
                {{ $invitation->groom_name }} &amp; {{ $invitation->bride_name }}
            </p>
            @if($show_watermark ?? false)
            <p class="br-wm">POWERED BY INVORA.ID</p>
            @endif
        </div>
    </section>

</div>{{-- /br-main --}}

{{-- ── NAVBAR ───────────────────────────────────────────────────── --}}
<nav id="br-nav" role="navigation">
    <a href="#nav-top"    class="br-nav-a" title="Beranda">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
        <span>Beranda</span>
    </a>
    <a href="#nav-couple"  class="br-nav-a" title="Mempelai">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
        <span>Mempelai</span>
    </a>
    @if($events->isNotEmpty())
    <a href="#nav-events"  class="br-nav-a" title="Acara">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        <span>Acara</span>
    </a>
    @endif
    @if($galleries->isNotEmpty())
    <a href="#nav-gallery" class="br-nav-a" title="Galeri">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        <span>Galeri</span>
    </a>
    @endif
    @if($invitation->is_open)
    <a href="#nav-rsvp"    class="br-nav-a" title="RSVP">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
        <span>RSVP</span>
    </a>
    @endif
</nav>

{{-- ══ SCRIPTS ══════════════════════════════════════════════════════ --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js" defer></script>
<script>
/* ── Ambient gold dust ───────────────────────────────────────────── */
(function spawnDust(){
    var c = document.getElementById('br-particles');
    if(!c) return;
    for(var i=0;i<45;i++){
        var p = document.createElement('div');
        var s = (Math.random()*3+1.2).toFixed(1);
        p.className = 'br-dust';
        p.style.cssText = 'left:'+Math.random()*100+'%;'
            +'width:'+s+'px;height:'+s+'px;'
            +'animation-duration:'+(14+Math.random()*16).toFixed(1)+'s;'
            +'animation-delay:'+(Math.random()*14).toFixed(1)+'s;';
        c.appendChild(p);
    }
})();

/* ── Open button hover ───────────────────────────────────────────── */
(function(){
    var btn  = document.getElementById('br-open-btn');
    var fill = document.getElementById('br-btn-fill');
    if(!btn || !fill) return;
    btn.addEventListener('mouseenter',function(){
        fill.style.transform='scaleX(1)';
        btn.style.color='#0F1E3A';
    });
    btn.addEventListener('mouseleave',function(){
        fill.style.transform='scaleX(0)';
        btn.style.color='var(--gold)';
    });
})();

/* ── Royal Curtain Reveal ────────────────────────────────────────── */
function openBR(){
    if(typeof gsap === 'undefined'){
        // Fallback if GSAP CDN still loading
        setTimeout(openBR, 150); return;
    }
    var cover  = document.getElementById('br-cover');
    var cl     = document.getElementById('br-curtain-l');
    var cr     = document.getElementById('br-curtain-r');
    var main   = document.getElementById('br-main');
    var navbar = document.getElementById('br-nav');

    var tl = gsap.timeline({
        onComplete: function(){
            cover.style.display  = 'none';
            cl.style.display     = 'none';
            cr.style.display     = 'none';
            main.style.display   = 'block';
            navbar.style.display = 'flex';

            // Fade in main content
            gsap.fromTo('#br-main',
                {opacity:0, y:24},
                {opacity:1, y:0, duration:.7, ease:'power2.out'}
            );

            // Init scroll reveals
            initScrollReveal();

            // Autoplay music after user gesture (sticky activation)
            if(window.Alpine){
                var store = window.Alpine.store('invitation');
                if(store){
                    store.openEnvelope();
                }
            }
        }
    });

    // 1. Fade cover
    tl.to(cover, {opacity:0, duration:.35, ease:'power2.in'}, 0);

    // 2. Left curtain sweeps left
    tl.to(cl, {
        x:'-120%', rotation:-2, scale:1.03,
        duration:1.35, ease:'power4.inOut'
    }, 0.08);

    // 3. Right curtain sweeps right (mirror)
    tl.to(cr, {
        x:'120%', rotation:2, scale:1.03,
        duration:1.35, ease:'power4.inOut'
    }, 0.08);

    // 4. Gold dust burst from center
    tl.call(function(){
        for(var i=0;i<24;i++){
            var p = document.createElement('div');
            var size = (2 + Math.random()*5).toFixed(1);
            p.style.cssText =
                'position:fixed;border-radius:50%;background:#D4AF37;pointer-events:none;z-index:99;'
                +'left:50%;top:50%;width:'+size+'px;height:'+size+'px;';
            document.body.appendChild(p);
            gsap.to(p, {
                x:(Math.random()-.5)*window.innerWidth*.85,
                y:(Math.random()-.5)*window.innerHeight*.75,
                opacity:0,
                duration:1+(Math.random()*.9),
                ease:'power2.out',
                onComplete:function(){this.targets()[0].remove()},
            });
        }
    }, null, [], 0.55);

    // 5. Light ray flash
    var ray = document.createElement('div');
    ray.style.cssText = 'position:fixed;inset:0;background:radial-gradient(ellipse at center,rgba(212,175,55,.18) 0%,transparent 65%);pointer-events:none;z-index:88;';
    document.body.appendChild(ray);
    tl.to(ray, {opacity:0, duration:.6, ease:'power2.out', onComplete:function(){ray.remove()}}, 0.6);
}

/* ── Scroll reveal ───────────────────────────────────────────────── */
function initScrollReveal(){
    var els = document.querySelectorAll('.br-reveal');
    if(!els.length) return;
    var obs = new IntersectionObserver(function(entries){
        entries.forEach(function(e,i){
            if(e.isIntersecting){
                setTimeout(function(){e.target.classList.add('in')}, i*70);
                obs.unobserve(e.target);
            }
        });
    },{threshold:0.1});
    els.forEach(function(el){obs.observe(el)});
}

/* ── Navbar active state on scroll ─────────────────────────────── */
document.addEventListener('scroll', function(){
    var sections = ['nav-top','nav-couple','nav-events','nav-gallery','nav-rsvp'];
    var active = '';
    sections.forEach(function(id){
        var el = document.getElementById(id);
        if(el && el.getBoundingClientRect().top <= window.innerHeight/2) active = id;
    });
    document.querySelectorAll('#br-nav .br-nav-a').forEach(function(a){
        a.classList.toggle('on', a.getAttribute('href') === '#'+active);
    });
}, {passive:true});
</script>

</body>
</html>
