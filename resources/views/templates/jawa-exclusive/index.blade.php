<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<meta property="og:title"  content="{{ $invitation->getCoupleName() }} — Jawa Exclusive">
<meta property="og:image"  content="{{ $invitation->cover_photo_url ?? asset('img/og-default.jpg') }}">
<meta name="theme-color"   content="#4A3324">
<title>{{ $invitation->getCoupleName() }} — Jawa Exclusive</title>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;0,700;1,300;1,400;1,600&family=Noto+Serif:ital,wght@0,400;0,600;1,400&family=Poppins:wght@300;400;500&display=swap" rel="stylesheet">
@vite(['resources/css/app.css','resources/js/app.js'])
@livewireStyles
@livewireScriptConfig
<style>
/* ═══════════════════════════════════════════════════════
   JAWA EXCLUSIVE — Keraton Wedding Template
   Dark Brown · Javanese Gold · Ivory · Cream · Maroon · Deep Green
═══════════════════════════════════════════════════════ */
:root{
    --brown:  #4A3324;
    --brown2: #3A2518;
    --brown3: #5C4030;
    --gold:   #D4AF37;
    --gold2:  #C9A227;
    --gold-d: rgba(212,175,55,.18);
    --ivory:  #FBF5DD;
    --cream:  #E7E1B1;
    --maroon: #7A2E2E;
    --green:  #306D29;
    --text:   #2A1F14;
    --muted:  #6B5040;
    --muted2: #9A8070;
}
*{margin:0;padding:0;box-sizing:border-box}
[x-cloak]{display:none!important}
html{scroll-behavior:smooth}
body{
    background:var(--ivory);
    color:var(--text);
    font-family:'Poppins',sans-serif;
    overflow-x:hidden;
    -webkit-font-smoothing:antialiased;
}

/* ── Kawung (light bg) ── */
.je-kw-light{
    background-color:var(--ivory);
    background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='60' height='60'%3E%3Cellipse cx='30' cy='8' rx='9' ry='8' fill='none' stroke='%23D4AF37' stroke-width='0.45' opacity='0.12'/%3E%3Cellipse cx='30' cy='52' rx='9' ry='8' fill='none' stroke='%23D4AF37' stroke-width='0.45' opacity='0.12'/%3E%3Cellipse cx='8' cy='30' rx='8' ry='9' fill='none' stroke='%23D4AF37' stroke-width='0.45' opacity='0.12'/%3E%3Cellipse cx='52' cy='30' rx='8' ry='9' fill='none' stroke='%23D4AF37' stroke-width='0.45' opacity='0.12'/%3E%3C/svg%3E");
    background-size:60px 60px;
}
/* ── Kawung (dark bg) ── */
.je-kw-dark{
    background-color:var(--brown);
    background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='60' height='60'%3E%3Cellipse cx='30' cy='8' rx='9' ry='8' fill='none' stroke='%23D4AF37' stroke-width='0.45' opacity='0.09'/%3E%3Cellipse cx='30' cy='52' rx='9' ry='8' fill='none' stroke='%23D4AF37' stroke-width='0.45' opacity='0.09'/%3E%3Cellipse cx='8' cy='30' rx='8' ry='9' fill='none' stroke='%23D4AF37' stroke-width='0.45' opacity='0.09'/%3E%3Cellipse cx='52' cy='30' rx='8' ry='9' fill='none' stroke='%23D4AF37' stroke-width='0.45' opacity='0.09'/%3E%3C/svg%3E");
    background-size:60px 60px;
}

/* ── Parang strip ── */
.je-parang{
    height:18px;
    background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='80' height='18'%3E%3Cpath d='M0 9 Q20 1 40 9 Q60 17 80 9' fill='none' stroke='%23D4AF37' stroke-width='0.7' opacity='0.28'/%3E%3C/svg%3E");
    background-size:80px 18px;background-repeat:repeat-x;
}

/* ── Gold divider ── */
.je-divider{
    display:flex;align-items:center;gap:.75rem;
    margin:.9rem auto 1.4rem;max-width:240px;
}
.je-divider::before,.je-divider::after{content:'';flex:1;height:1px}
.je-divider::before{background:linear-gradient(to right,transparent,var(--gold))}
.je-divider::after {background:linear-gradient(to left, transparent,var(--gold))}

/* ── Section title ── */
.je-title{
    font-family:'Cormorant Garamond',serif;
    font-size:clamp(.82rem,2.2vw,1rem);
    letter-spacing:.3em;text-transform:uppercase;
    text-align:center;color:var(--brown);
}
.je-title-light{ color:var(--gold) }

/* ── Section padding ── */
.je-sec{ padding:72px 24px;max-width:860px;margin:0 auto }

/* ── Card ── */
.je-card{
    background:rgba(251,245,221,.65);
    border:1px solid rgba(212,175,55,.22);
    border-radius:6px;
    box-shadow:0 4px 20px rgba(74,51,36,.07);
    backdrop-filter:blur(8px);
    -webkit-backdrop-filter:blur(8px);
}
.je-card-dark{
    background:rgba(255,255,255,.04);
    border:1px solid rgba(212,175,55,.18);
    border-radius:6px;
    box-shadow:0 4px 20px rgba(0,0,0,.15);
}

/* ═══ GEBYOK COVER ═══════════════════════════════════ */
#je-cover{
    position:fixed;inset:0;z-index:95;
    display:flex;align-items:center;justify-content:center;
    overflow:hidden;
}

/* Morning light atmosphere */
#je-bg{
    position:absolute;inset:0;
    background:
        radial-gradient(ellipse 65% 55% at 50% 25%,rgba(212,175,55,.14) 0%,transparent 65%),
        linear-gradient(to bottom,#140a04 0%,#2e1608 45%,#140a04 100%);
}
/* Soft fog drift */
#je-fog{
    position:absolute;inset:0;pointer-events:none;
    background:radial-gradient(ellipse 110% 50% at 50% 110%,rgba(251,245,221,.07) 0%,transparent 65%);
    animation:je-fog 9s ease-in-out infinite alternate;
}
@keyframes je-fog{
    from{opacity:.5;transform:translateY(0)}
    to  {opacity:.9;transform:translateY(-10px)}
}

/* ── Gebyok door perspective container ── */
#je-doors{
    position:absolute;inset:0;
    perspective:1500px;perspective-origin:50% 50%;
    overflow:hidden;
}
.je-door{
    position:absolute;top:0;bottom:0;width:50%;
    will-change:transform;
    transform-style:preserve-3d;
    backface-visibility:hidden;
}
#je-door-l{left:0;transform-origin:left center}
#je-door-r{right:0;transform-origin:right center}

/* Wood texture */
.je-wood{
    position:absolute;inset:0;
    background:
        repeating-linear-gradient(
            to right,
            #1e0d05 0%,  #321507 4%,
            #3e1e0b 8%,  #2e1408 12%,
            #1e0d05 16%, #311709 20%,
            #432210 26%, #311709 32%,
            #1e0d05 38%
        );
}
/* Gold outer frame */
.je-door-frame{
    position:absolute;inset:0;
    border:2px solid rgba(212,175,55,.4);
    box-shadow:
        inset 0 0 48px rgba(0,0,0,.35),
        inset 0 0 12px rgba(212,175,55,.04);
}
#je-door-l .je-door-frame{border-right-color:rgba(212,175,55,.65)}
#je-door-r .je-door-frame{border-left-color:rgba(212,175,55,.65)}

/* Inner carved panel */
.je-panel{
    position:absolute;inset:10% 8%;
    border:1px solid rgba(212,175,55,.28);
}
.je-panel::before,.je-panel::after{
    content:'';position:absolute;
    width:22px;height:22px;
    border:1px solid rgba(212,175,55,.38);
}
.je-panel::before{top:8px;left:8px;border-right:none;border-bottom:none}
.je-panel::after {bottom:8px;right:8px;border-left:none;border-top:none}
.je-panel-corner-bl{
    position:absolute;bottom:8px;left:8px;
    width:22px;height:22px;
    border:1px solid rgba(212,175,55,.38);
    border-right:none;border-top:none;
}
.je-panel-corner-tr{
    position:absolute;top:8px;right:8px;
    width:22px;height:22px;
    border:1px solid rgba(212,175,55,.38);
    border-left:none;border-bottom:none;
}

/* Pelmet / crown bar */
#je-pelmet{
    position:absolute;top:0;left:0;right:0;height:72px;z-index:3;
    background:linear-gradient(to bottom,#100702 0%,#1e0e06 100%);
    border-bottom:1.5px solid rgba(212,175,55,.45);
    display:flex;align-items:center;justify-content:center;
    gap:1rem;
}

/* Center seam glow */
#je-seam{
    position:absolute;top:0;bottom:0;left:50%;z-index:4;
    width:2px;transform:translateX(-50%);
    background:linear-gradient(to bottom,
        transparent 0%,
        rgba(212,175,55,.35) 15%,
        rgba(212,175,55,.9) 50%,
        rgba(212,175,55,.35) 85%,
        transparent 100%);
    box-shadow:0 0 16px 4px rgba(212,175,55,.3),0 0 40px 10px rgba(212,175,55,.1);
    pointer-events:none;
    animation:je-seam 2.8s ease-in-out infinite;
}
@keyframes je-seam{
    0%,100%{opacity:.7}
    50%{opacity:1;box-shadow:0 0 24px 6px rgba(212,175,55,.52),0 0 60px 16px rgba(212,175,55,.18)}
}

/* ── Cover card (floats above doors) ── */
#je-card{
    position:absolute;inset:0;z-index:6;
    display:flex;align-items:center;justify-content:center;
    pointer-events:none;
}
.je-card-wrap{
    text-align:center;
    padding:2.5rem 2rem 2.8rem;
    max-width:min(88vw,380px);width:100%;
    background:rgba(20,10,4,.58);
    backdrop-filter:blur(6px);-webkit-backdrop-filter:blur(6px);
    border:1px solid rgba(212,175,55,.2);border-radius:4px;
    box-shadow:0 8px 48px rgba(0,0,0,.5);
    pointer-events:auto;
}
.je-open-btn{
    display:inline-flex;align-items:center;gap:.55rem;
    padding:.88rem 2.2rem;
    font-family:'Cormorant Garamond',serif;
    font-size:.92rem;font-weight:600;
    letter-spacing:.16em;text-transform:uppercase;
    color:var(--gold);
    background:transparent;
    border:1px solid rgba(212,175,55,.48);border-radius:2px;
    cursor:pointer;
    transition:background .3s,border-color .3s;
    animation:je-btn-breathe 3s ease-in-out infinite;
}
@keyframes je-btn-breathe{
    0%,100%{box-shadow:0 0 10px rgba(212,175,55,.08)}
    50%    {box-shadow:0 0 28px rgba(212,175,55,.28)}
}
.je-open-btn:hover{background:rgba(212,175,55,.1);border-color:var(--gold)}
.je-open-btn:active{transform:scale(.98)}

/* ── Ambient dust ── */
.je-dust{
    position:fixed;border-radius:50%;
    background:var(--gold);pointer-events:none;z-index:5;
    animation:je-rise linear infinite;
}
@keyframes je-rise{
    0%  {transform:translateY(10px) rotate(0);opacity:0}
    5%  {opacity:.4}
    90% {opacity:.15}
    100%{transform:translateY(-100vh) rotate(360deg);opacity:0}
}

/* ── Post-reveal: floating motes (naik) ── */
@keyframes je-mote-up{
    0%  {transform:translateY(0) scale(.6); opacity:0}
    6%  {opacity:.9}
    40% {opacity:.75}
    85% {opacity:.35}
    100%{transform:translateY(-88vh) scale(.3);opacity:0}
}
/* ── Post-reveal: settling dust (turun) ── */
@keyframes je-mote-settle{
    0%  {transform:translateY(0) scale(1); opacity:0}
    10% {opacity:.85}
    100%{transform:translateY(60px) scale(.4);opacity:0}
}
/* ── Hero ambient breath ── */
@keyframes je-hero-breath{
    0%,100%{opacity:.06}
    50%    {opacity:.18}
}
/* ── Section gold shimmer sweep ── */
@keyframes je-shimmer-sweep{
    0%  {transform:translateX(-120%)}
    100%{transform:translateX(220%)}
}

/* ═══ HERO ═══════════════════════════════════════════ */
#je-hero{
    position:relative;min-height:100vh;
    display:flex;align-items:center;justify-content:center;overflow:hidden;
}
#je-hero-bg{
    position:absolute;inset:0;
    background-size:cover;background-position:center;
    animation:je-ken 24s ease-in-out infinite alternate;
}
@keyframes je-ken{from{transform:scale(1)}to{transform:scale(1.1)}}
@keyframes je-bounce{
    0%,100%{transform:translateX(-50%) translateY(0)}
    50%    {transform:translateX(-50%) translateY(6px)}
}

/* ═══ PHOTO FRAME ═════════════════════════════════════ */
.je-photo{
    position:relative;
    width:clamp(96px,36vw,150px);
    aspect-ratio:3/4;
    margin:0 auto;
}
.je-photo::before{
    content:'';position:absolute;inset:-6px;
    border:1px solid rgba(212,175,55,.32);border-radius:2px;z-index:0;
}
.je-photo::after{
    content:'';position:absolute;inset:-12px;
    border:1px solid rgba(212,175,55,.14);border-radius:2px;z-index:0;
}
.je-photo img,.je-photo-ph{
    position:absolute;inset:0;width:100%;height:100%;
    object-fit:cover;border-radius:2px;z-index:1;
    display:block;
}
.je-photo-ph{
    background:rgba(74,51,36,.07);
    display:flex;align-items:center;justify-content:center;
}

/* ═══ GALLERY ═════════════════════════════════════════ */
.je-gallery{
    display:grid;grid-template-columns:repeat(3,1fr);gap:6px;
}
.je-gi{
    position:relative;overflow:hidden;
    border:1px solid rgba(212,175,55,.18);border-radius:4px;
    aspect-ratio:4/3;background:var(--cream);cursor:pointer;
}
.je-gi.tall{aspect-ratio:3/4}
.je-gi.wide{grid-column:span 2}
.je-gi img{width:100%;height:100%;object-fit:cover;transition:transform .6s ease;display:block}
.je-gi:hover img{transform:scale(1.07)}
.je-gcap{
    position:absolute;bottom:0;left:0;right:0;
    padding:.35rem .6rem;
    background:linear-gradient(transparent,rgba(74,51,36,.82));
    font-size:.65rem;color:var(--cream);
    opacity:0;transition:opacity .3s;
}
.je-gi:hover .je-gcap{opacity:1}

/* ═══ WISH ════════════════════════════════════════════ */
.je-wish{
    border-left:2px solid rgba(212,175,55,.28);
    padding:.85rem 1rem;
    background:rgba(251,245,221,.6);
    border-radius:0 6px 6px 0;
}

/* ═══ SCROLL REVEAL ═══════════════════════════════════ */
.je-reveal{
    opacity:0;transform:translateY(28px);
    transition:opacity .72s ease,transform .72s ease;
    position:relative;overflow:hidden; /* untuk shimmer sweep */
}
.je-reveal.in{opacity:1;transform:translateY(0)}
/* Gold shimmer sweep saat section masuk viewport */
.je-reveal.in::after{
    content:'';
    position:absolute;inset:0;
    background:linear-gradient(105deg,transparent 35%,rgba(212,175,55,.07) 50%,transparent 65%);
    animation:je-shimmer-sweep .9s ease-out .1s forwards;
    pointer-events:none;z-index:0;
}
@media(prefers-reduced-motion:reduce){
    .je-reveal{opacity:1;transform:none}
    .je-reveal.in::after{display:none}
}

/* ═══ NAVBAR ══════════════════════════════════════════ */
#je-nav{
    position:fixed;bottom:14px;left:50%;transform:translateX(-50%);
    z-index:70;display:none;
    background:rgba(74,51,36,.9);backdrop-filter:blur(16px);
    -webkit-backdrop-filter:blur(16px);
    border:1px solid rgba(212,175,55,.25);border-radius:999px;
    padding:6px 10px;gap:2px;
}
@media(min-width:768px){#je-nav{top:14px;bottom:auto}}
.je-nav-a{
    display:flex;flex-direction:column;align-items:center;gap:2px;
    padding:5px 9px;border-radius:999px;cursor:pointer;
    color:var(--cream);text-decoration:none;
    font-size:0;border:none;background:transparent;
    font-family:'Poppins',sans-serif;
    transition:background .2s,color .2s;
}
@media(min-width:420px){.je-nav-a{font-size:.55rem}}
.je-nav-a:hover,.je-nav-a.on{background:rgba(212,175,55,.14);color:var(--gold)}
.je-nav-a svg{width:17px;height:17px}

/* ── Music btn ── */
#je-music{
    position:fixed;top:18px;right:18px;z-index:72;
    width:38px;height:38px;border-radius:50%;
    background:rgba(74,51,36,.88);backdrop-filter:blur(10px);
    border:1px solid rgba(212,175,55,.3);
    display:flex;align-items:center;justify-content:center;
    cursor:pointer;transition:border-color .2s;
}
#je-music:hover{border-color:var(--gold)}

/* ── Watermark ── */
.je-wm{
    text-align:center;margin-top:2rem;font-size:.6rem;
    letter-spacing:.2em;color:rgba(74,51,36,.2);
    font-family:'Cormorant Garamond',serif;
}
</style>
</head>

<body x-init="$store.invitation.initMusic('{{ $invitation->music_url }}',{{ $invitation->music_autoplay ? 'true' : 'false' }})">

{{-- ── AMBIENT DUST ─────────────────────────────────── --}}
<div id="je-particles" aria-hidden="true"></div>

{{-- ═══════════════════════════════════════════════════
     GEBYOK DOOR REVEAL
═══════════════════════════════════════════════════ --}}
<div id="je-cover">

    {{-- Morning atmosphere --}}
    <div id="je-bg"></div>
    <div id="je-fog"></div>

    {{-- Pelmet / crown bar --}}
    <div id="je-pelmet" aria-hidden="true">
        <div style="height:1px;width:48px;background:linear-gradient(to right,transparent,rgba(212,175,55,.4))"></div>
        {{-- Gunungan / mountain ornament --}}
        <svg width="72" height="44" viewBox="0 0 72 44" fill="none">
            <path d="M36 2L68 42H4Z" fill="none" stroke="rgba(212,175,55,.45)" stroke-width=".9"/>
            <path d="M36 8L62 40H10Z" fill="none" stroke="rgba(212,175,55,.25)" stroke-width=".6"/>
            <path d="M36 16L52 38H20Z" fill="none" stroke="rgba(212,175,55,.18)" stroke-width=".5"/>
            <circle cx="36" cy="26" r="4" fill="none" stroke="rgba(212,175,55,.5)" stroke-width=".7"/>
            <line x1="0" y1="42" x2="72" y2="42" stroke="rgba(212,175,55,.2)" stroke-width=".6"/>
        </svg>
        <div style="height:1px;width:48px;background:linear-gradient(to left,transparent,rgba(212,175,55,.4))"></div>
    </div>

    {{-- Gebyok doors --}}
    <div id="je-doors" aria-hidden="true">

        {{-- Left door --}}
        <div id="je-door-l" class="je-door">
            <div class="je-wood"></div>
            <div class="je-door-frame"></div>
            <div class="je-panel">
                <div class="je-panel-corner-bl"></div>
                <div class="je-panel-corner-tr"></div>
                {{-- Carved lotus medallion (SVG) --}}
                <svg style="position:absolute;inset:0;width:100%;height:100%" viewBox="0 0 200 400" fill="none" preserveAspectRatio="xMidYMid meet">
                    <rect x="8" y="8" width="184" height="384" stroke="rgba(212,175,55,.22)" stroke-width=".7" rx="1"/>
                    {{-- Center lotus --}}
                    <g transform="translate(100,200)">
                        <circle r="32" stroke="rgba(212,175,55,.3)" stroke-width=".8"/>
                        <circle r="20" stroke="rgba(212,175,55,.22)" stroke-width=".6"/>
                        <circle r="9"  stroke="rgba(212,175,55,.38)" stroke-width=".7" fill="rgba(212,175,55,.06)"/>
                        <path d="M0,-32 C8,-22 8,-14 0,-10 C-8,-14 -8,-22 0,-32" stroke="rgba(212,175,55,.28)" stroke-width=".6" fill="rgba(212,175,55,.05)"/>
                        <path d="M0, 32 C8, 22 8, 14 0, 10 C-8, 14 -8, 22 0, 32" stroke="rgba(212,175,55,.28)" stroke-width=".6" fill="rgba(212,175,55,.05)"/>
                        <path d="M-32,0 C-22,8 -14,8 -10,0 C-14,-8 -22,-8 -32,0" stroke="rgba(212,175,55,.28)" stroke-width=".6" fill="rgba(212,175,55,.05)"/>
                        <path d="M 32,0 C 22,8  14,8  10,0 C 14,-8  22,-8  32,0" stroke="rgba(212,175,55,.28)" stroke-width=".6" fill="rgba(212,175,55,.05)"/>
                        <path d="M-22,-22 C-14,-16 -10,-10 -7,-7 C-12,-12 -18,-16 -22,-22" stroke="rgba(212,175,55,.18)" stroke-width=".5" fill="rgba(212,175,55,.04)"/>
                        <path d="M 22,-22 C 14,-16  10,-10  7,-7 C 12,-12  18,-16  22,-22" stroke="rgba(212,175,55,.18)" stroke-width=".5" fill="rgba(212,175,55,.04)"/>
                        <path d="M-22, 22 C-14, 16 -10, 10 -7, 7 C-12, 12 -18, 16 -22, 22" stroke="rgba(212,175,55,.18)" stroke-width=".5" fill="rgba(212,175,55,.04)"/>
                        <path d="M 22, 22 C 14, 16  10, 10  7, 7 C 12, 12  18, 16  22, 22" stroke="rgba(212,175,55,.18)" stroke-width=".5" fill="rgba(212,175,55,.04)"/>
                    </g>
                    {{-- Top sulur border --}}
                    <path d="M18 46 Q50 30 100 40 Q150 50 182 46" stroke="rgba(212,175,55,.2)" stroke-width=".6"/>
                    <path d="M18 56 Q50 42 100 50 Q150 58 182 56" stroke="rgba(212,175,55,.13)" stroke-width=".5"/>
                    {{-- Bottom sulur border --}}
                    <path d="M18 354 Q50 368 100 360 Q150 352 182 354" stroke="rgba(212,175,55,.2)" stroke-width=".6"/>
                    <path d="M18 344 Q50 356 100 350 Q150 344 182 344" stroke="rgba(212,175,55,.13)" stroke-width=".5"/>
                    {{-- Small corner diamonds --}}
                    <path d="M26 88 L32 82 L38 88 L32 94 Z" stroke="rgba(212,175,55,.2)" stroke-width=".5" fill="rgba(212,175,55,.04)"/>
                    <path d="M26 312 L32 306 L38 312 L32 318 Z" stroke="rgba(212,175,55,.2)" stroke-width=".5" fill="rgba(212,175,55,.04)"/>
                </svg>
            </div>
        </div>

        {{-- Right door (mirror) --}}
        <div id="je-door-r" class="je-door">
            <div class="je-wood"></div>
            <div class="je-door-frame"></div>
            <div class="je-panel">
                <div class="je-panel-corner-bl"></div>
                <div class="je-panel-corner-tr"></div>
                <svg style="position:absolute;inset:0;width:100%;height:100%" viewBox="0 0 200 400" fill="none" preserveAspectRatio="xMidYMid meet">
                    <rect x="8" y="8" width="184" height="384" stroke="rgba(212,175,55,.22)" stroke-width=".7" rx="1"/>
                    <g transform="translate(100,200)">
                        <circle r="32" stroke="rgba(212,175,55,.3)" stroke-width=".8"/>
                        <circle r="20" stroke="rgba(212,175,55,.22)" stroke-width=".6"/>
                        <circle r="9"  stroke="rgba(212,175,55,.38)" stroke-width=".7" fill="rgba(212,175,55,.06)"/>
                        <path d="M0,-32 C8,-22 8,-14 0,-10 C-8,-14 -8,-22 0,-32" stroke="rgba(212,175,55,.28)" stroke-width=".6" fill="rgba(212,175,55,.05)"/>
                        <path d="M0, 32 C8, 22 8, 14 0, 10 C-8, 14 -8, 22 0, 32" stroke="rgba(212,175,55,.28)" stroke-width=".6" fill="rgba(212,175,55,.05)"/>
                        <path d="M-32,0 C-22,8 -14,8 -10,0 C-14,-8 -22,-8 -32,0" stroke="rgba(212,175,55,.28)" stroke-width=".6" fill="rgba(212,175,55,.05)"/>
                        <path d="M 32,0 C 22,8  14,8  10,0 C 14,-8  22,-8  32,0" stroke="rgba(212,175,55,.28)" stroke-width=".6" fill="rgba(212,175,55,.05)"/>
                        <path d="M-22,-22 C-14,-16 -10,-10 -7,-7 C-12,-12 -18,-16 -22,-22" stroke="rgba(212,175,55,.18)" stroke-width=".5" fill="rgba(212,175,55,.04)"/>
                        <path d="M 22,-22 C 14,-16  10,-10  7,-7 C 12,-12  18,-16  22,-22" stroke="rgba(212,175,55,.18)" stroke-width=".5" fill="rgba(212,175,55,.04)"/>
                        <path d="M-22, 22 C-14, 16 -10, 10 -7, 7 C-12, 12 -18, 16 -22, 22" stroke="rgba(212,175,55,.18)" stroke-width=".5" fill="rgba(212,175,55,.04)"/>
                        <path d="M 22, 22 C 14, 16  10, 10  7, 7 C 12, 12  18, 16  22, 22" stroke="rgba(212,175,55,.18)" stroke-width=".5" fill="rgba(212,175,55,.04)"/>
                    </g>
                    <path d="M18 46 Q50 30 100 40 Q150 50 182 46" stroke="rgba(212,175,55,.2)" stroke-width=".6"/>
                    <path d="M18 56 Q50 42 100 50 Q150 58 182 56" stroke="rgba(212,175,55,.13)" stroke-width=".5"/>
                    <path d="M18 354 Q50 368 100 360 Q150 352 182 354" stroke="rgba(212,175,55,.2)" stroke-width=".6"/>
                    <path d="M18 344 Q50 356 100 350 Q150 344 182 344" stroke="rgba(212,175,55,.13)" stroke-width=".5"/>
                    <path d="M162 88 L168 82 L174 88 L168 94 Z" stroke="rgba(212,175,55,.2)" stroke-width=".5" fill="rgba(212,175,55,.04)"/>
                    <path d="M162 312 L168 306 L174 312 L168 318 Z" stroke="rgba(212,175,55,.2)" stroke-width=".5" fill="rgba(212,175,55,.04)"/>
                </svg>
            </div>
        </div>

    </div>{{-- /je-doors --}}

    {{-- Center seam --}}
    <div id="je-seam" aria-hidden="true"></div>

    {{-- Cover card (above doors) --}}
    <div id="je-card">
        <div class="je-card-wrap">
            {{-- Top ornament --}}
            <div style="display:flex;align-items:center;justify-content:center;gap:.6rem;margin-bottom:1.6rem">
                <div style="height:1px;width:44px;background:linear-gradient(to right,transparent,rgba(212,175,55,.55))"></div>
                <svg width="16" height="16" viewBox="0 0 16 16"><path d="M8 1L9.5 6H15L10.5 9L12 14L8 11L4 14L5.5 9L1 6H6.5Z" fill="#D4AF37" opacity=".65"/></svg>
                <div style="height:1px;width:44px;background:linear-gradient(to left,transparent,rgba(212,175,55,.55))"></div>
            </div>

            <p style="font-family:'Cormorant Garamond',serif;font-size:.58rem;letter-spacing:.48em;color:rgba(212,175,55,.6);text-transform:uppercase;margin-bottom:1rem">Undangan Pernikahan</p>

            <h1 style="font-family:'Cormorant Garamond',serif;font-size:clamp(1.85rem,8vw,2.8rem);color:var(--gold);line-height:1.05;text-shadow:0 0 28px rgba(212,175,55,.35)">{{ $invitation->groom_name }}</h1>
            <p  style="font-family:'Cormorant Garamond',serif;font-size:clamp(1.6rem,6vw,2.2rem);color:rgba(251,245,221,.65);font-style:italic;margin:.25rem 0">&amp;</p>
            <h1 style="font-family:'Cormorant Garamond',serif;font-size:clamp(1.85rem,8vw,2.8rem);color:var(--gold);line-height:1.05;text-shadow:0 0 28px rgba(212,175,55,.35)">{{ $invitation->bride_name }}</h1>

            @if($events->isNotEmpty())
            <p style="font-family:'Poppins',sans-serif;font-size:.7rem;color:rgba(212,175,55,.5);margin-top:.85rem;letter-spacing:.04em">
                {{ \Carbon\Carbon::parse($events->first()->date)->translatedFormat('d F Y') }}
            </p>
            @endif

            @if($guest ?? null)
            <div style="margin:1.25rem auto .4rem;height:1px;max-width:100px;background:linear-gradient(to right,transparent,rgba(212,175,55,.28),transparent)"></div>
            <p style="font-size:.58rem;letter-spacing:.22em;color:rgba(212,175,55,.48);text-transform:uppercase">Kepada Yth.</p>
            <p style="font-family:'Cormorant Garamond',serif;font-size:1.15rem;color:rgba(251,245,221,.85);margin-top:.25rem">{{ $guest->name }}</p>
            @if(isset($guest->allocated_seats) && $guest->allocated_seats > 0)
            <p style="font-size:.62rem;color:rgba(212,175,55,.35);margin-top:.15rem">{{ $guest->allocated_seats }} kursi</p>
            @endif
            @endif

            <div style="margin-top:2rem">
                <button class="je-open-btn" onclick="openJE()">
                    <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l9-9 9 9M5 10v10a1 1 0 001 1h4v-6h4v6h4a1 1 0 001-1V10"/></svg>
                    Buka Undangan
                </button>
            </div>
        </div>
    </div>

</div>{{-- /je-cover --}}

{{-- ═══════════════════════════════════════════════════
     MAIN CONTENT
═══════════════════════════════════════════════════ --}}
<div id="je-main" style="display:none;padding-bottom:88px">

    {{-- Music btn --}}
    @if($invitation->music_url)
    <div id="je-music" x-data @click="$store.invitation.toggleMusic()" title="Musik">
        <svg x-show="!$store.invitation.musicPlaying" style="color:var(--gold)" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2z"/></svg>
        <svg x-show=" $store.invitation.musicPlaying" style="color:var(--gold)" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    </div>
    @endif

    {{-- ── HERO ────────────────────────────────────────── --}}
    <section id="nav-top" style="position:relative;min-height:100vh;display:flex;align-items:center;justify-content:center;overflow:hidden">
        @if($invitation->cover_photo_url)
        <div id="je-hero-bg" style="position:absolute;inset:0;background-image:url('{{ $invitation->cover_photo_url }}');background-size:cover;background-position:center"></div>
        @endif
        <div style="position:absolute;inset:0" class="je-kw-dark" @if($invitation->cover_photo_url) style="opacity:.5" @endif></div>
        <div style="position:absolute;inset:0;background:linear-gradient(to bottom,rgba(74,51,36,.15) 0%,rgba(74,51,36,.5) 55%,rgba(26,12,4,.94) 100%)"></div>

        <div class="je-reveal" style="position:relative;z-index:2;text-align:center;padding:2rem;max-width:600px;width:100%">
            {{-- Gunungan ornament --}}
            <svg width="160" height="32" viewBox="0 0 160 32" fill="none" style="display:block;margin:0 auto 1.5rem">
                <line x1="0" y1="16" x2="54" y2="16" stroke="#D4AF37" stroke-opacity=".22" stroke-width=".8"/>
                <path d="M64 16L72 5L80 16L72 27Z" fill="#D4AF37" opacity=".38"/>
                <rect x="78" y="10" width="6" height="12" fill="#D4AF37" opacity=".18" rx="1"/>
                <path d="M86 16L94 5L102 16L94 27Z" fill="#D4AF37" opacity=".38"/>
                <line x1="110" y1="16" x2="160" y2="16" stroke="#D4AF37" stroke-opacity=".22" stroke-width=".8"/>
            </svg>

            <p style="font-family:'Cormorant Garamond',serif;font-size:.58rem;letter-spacing:.48em;color:rgba(212,175,55,.6);text-transform:uppercase;margin-bottom:1.2rem">Pernikahan</p>
            <h1 style="font-family:'Cormorant Garamond',serif;font-size:clamp(2.4rem,9vw,4.2rem);color:var(--gold);line-height:1;text-shadow:0 2px 28px rgba(212,175,55,.42)">{{ $invitation->groom_name }}</h1>
            <p  style="font-family:'Cormorant Garamond',serif;font-size:clamp(1.8rem,7vw,3rem);color:rgba(251,245,221,.72);font-style:italic;margin:.3rem 0">&amp;</p>
            <h1 style="font-family:'Cormorant Garamond',serif;font-size:clamp(2.4rem,9vw,4.2rem);color:var(--gold);line-height:1;text-shadow:0 2px 28px rgba(212,175,55,.42)">{{ $invitation->bride_name }}</h1>

            @if($events->isNotEmpty())
            <div style="display:flex;align-items:center;justify-content:center;gap:.75rem;margin-top:1.6rem">
                <div style="height:1px;width:32px;background:rgba(212,175,55,.22)"></div>
                <p style="font-family:'Poppins',sans-serif;font-size:.78rem;color:rgba(251,245,221,.55);letter-spacing:.05em">
                    {{ \Carbon\Carbon::parse($events->first()->date)->translatedFormat('l, d F Y') }}
                </p>
                <div style="height:1px;width:32px;background:rgba(212,175,55,.22)"></div>
            </div>
            @endif
        </div>

        <div style="position:absolute;bottom:28px;left:50%;animation:je-bounce 2s ease-in-out infinite">
            <svg width="20" height="20" fill="none" stroke="rgba(212,175,55,.38)" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 9l-7 7-7-7"/></svg>
        </div>
    </section>

    {{-- ── MEMPELAI ─────────────────────────────────────── --}}
    <section id="nav-couple" class="je-kw-light" style="padding:72px 24px">
        <div class="je-reveal" style="max-width:860px;margin:0 auto">
            <p class="je-title">Mempelai</p>
            <div class="je-divider">
                <svg width="12" height="12" viewBox="0 0 16 16"><path d="M8 1L9.5 6H15L10.5 9L12 14L8 11L4 14L5.5 9L1 6H6.5Z" fill="#D4AF37" opacity=".55"/></svg>
            </div>

            @if($invitation->opening_quote)
            <blockquote style="font-family:'Noto Serif',serif;font-style:italic;color:var(--muted);text-align:center;font-size:clamp(.88rem,2.2vw,1rem);line-height:1.9;max-width:520px;margin:0 auto 3rem">
                {{ $invitation->opening_quote }}
                @if($invitation->opening_quote_source)
                <cite style="display:block;font-size:.78rem;margin-top:.5rem;opacity:.5;font-style:normal">— {{ $invitation->opening_quote_source }}</cite>
                @endif
            </blockquote>
            @endif

            <div style="display:grid;grid-template-columns:1fr auto 1fr;align-items:start;gap:clamp(.5rem,.75rem,1.5rem);max-width:560px;margin:0 auto">

                {{-- Pengantin Pria --}}
                <div style="text-align:center">
                    <div class="je-photo">
                        @if($invitation->groom_photo_url)
                        <img src="{{ $invitation->groom_photo_url }}" alt="{{ $invitation->groom_name }}">
                        @else
                        <div class="je-photo-ph"><svg width="40" height="40" fill="none" stroke="rgba(74,51,36,.22)" viewBox="0 0 24 24" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg></div>
                        @endif
                    </div>
                    <p style="font-family:'Cormorant Garamond',serif;color:var(--brown);font-size:1.05rem;font-weight:600;margin-top:1rem">{{ $invitation->groom_name }}</p>
                    @if($invitation->groom_full_name)
                    <p style="font-family:'Poppins',sans-serif;font-size:.7rem;color:var(--muted);margin-top:.15rem">{{ $invitation->groom_full_name }}</p>
                    @endif
                    @if($invitation->groom_father || $invitation->groom_mother)
                    <p style="font-size:.68rem;color:rgba(74,51,36,.42);margin-top:.5rem;line-height:1.75">
                        Putra dari<br>
                        @if($invitation->groom_father)<span style="color:var(--muted)">{{ $invitation->groom_father }}</span>@endif
                        @if($invitation->groom_father && $invitation->groom_mother)<br>@endif
                        @if($invitation->groom_mother)<span style="color:var(--muted)">{{ $invitation->groom_mother }}</span>@endif
                    </p>
                    @endif
                </div>

                {{-- Ampersand --}}
                <div style="text-align:center;padding-top:clamp(28px,10vw,48px)">
                    <p style="font-family:'Cormorant Garamond',serif;font-size:clamp(2rem,7vw,2.8rem);color:rgba(212,175,55,.45);font-style:italic;line-height:1">&amp;</p>
                </div>

                {{-- Pengantin Wanita --}}
                <div style="text-align:center">
                    <div class="je-photo">
                        @if($invitation->bride_photo_url)
                        <img src="{{ $invitation->bride_photo_url }}" alt="{{ $invitation->bride_name }}">
                        @else
                        <div class="je-photo-ph"><svg width="40" height="40" fill="none" stroke="rgba(74,51,36,.22)" viewBox="0 0 24 24" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg></div>
                        @endif
                    </div>
                    <p style="font-family:'Cormorant Garamond',serif;color:var(--brown);font-size:1.05rem;font-weight:600;margin-top:1rem">{{ $invitation->bride_name }}</p>
                    @if($invitation->bride_full_name)
                    <p style="font-family:'Poppins',sans-serif;font-size:.7rem;color:var(--muted);margin-top:.15rem">{{ $invitation->bride_full_name }}</p>
                    @endif
                    @if($invitation->bride_father || $invitation->bride_mother)
                    <p style="font-size:.68rem;color:rgba(74,51,36,.42);margin-top:.5rem;line-height:1.75">
                        Putri dari<br>
                        @if($invitation->bride_father)<span style="color:var(--muted)">{{ $invitation->bride_father }}</span>@endif
                        @if($invitation->bride_father && $invitation->bride_mother)<br>@endif
                        @if($invitation->bride_mother)<span style="color:var(--muted)">{{ $invitation->bride_mother }}</span>@endif
                    </p>
                    @endif
                </div>

            </div>
        </div>
    </section>

    {{-- Parang strip --}}
    <div class="je-parang" style="background-color:var(--ivory)"></div>

    {{-- ── KISAH CINTA ──────────────────────────────────── --}}
    @if($invitation->story)
    <section class="je-kw-light" style="padding:56px 24px">
        <div class="je-reveal" style="max-width:860px;margin:0 auto;text-align:center">
            <p class="je-title">Kisah Cinta</p>
            <div class="je-divider"><svg width="12" height="12" viewBox="0 0 16 16"><path d="M8 1L9.5 6H15L10.5 9L12 14L8 11L4 14L5.5 9L1 6H6.5Z" fill="#D4AF37" opacity=".55"/></svg></div>
            <p style="font-family:'Noto Serif',serif;font-size:clamp(.88rem,2.2vw,1rem);line-height:1.95;color:var(--muted);max-width:580px;margin:0 auto">{{ $invitation->story }}</p>
        </div>
    </section>
    @endif

    {{-- ── DETAIL ACARA ─────────────────────────────────── --}}
    @if($events->isNotEmpty())
    <section id="nav-events" class="je-kw-dark" style="padding:72px 24px">
        <div class="je-reveal" style="max-width:860px;margin:0 auto">
            <p class="je-title je-title-light">Detail Acara</p>
            <div class="je-divider"><svg width="12" height="12" viewBox="0 0 16 16"><path d="M8 1L9.5 6H15L10.5 9L12 14L8 11L4 14L5.5 9L1 6H6.5Z" fill="#D4AF37" opacity=".55"/></svg></div>

            <div style="display:flex;flex-direction:column;gap:1.15rem;margin-top:1.5rem;max-width:640px;margin-left:auto;margin-right:auto">
                @foreach($events as $ev)
                <div class="je-card-dark" style="padding:24px 24px 20px">
                    <p style="font-family:'Cormorant Garamond',serif;color:var(--gold);font-size:.88rem;letter-spacing:.18em;text-transform:uppercase;margin-bottom:1rem">{{ $ev->name }}</p>
                    <div style="display:grid;grid-template-columns:18px 1fr;gap:.5rem .85rem;font-size:.8rem">
                        <span style="color:rgba(212,175,55,.5);line-height:1.5">📅</span>
                        <span style="color:var(--cream);line-height:1.5">{{ \Carbon\Carbon::parse($ev->date)->translatedFormat('l, d F Y') }}</span>
                        <span style="color:rgba(212,175,55,.5);line-height:1.5">🕐</span>
                        <span style="color:var(--cream);line-height:1.5">{{ $ev->time_start }}{{ $ev->time_end ? ' – '.$ev->time_end : '' }} WIB</span>
                        <span style="color:rgba(212,175,55,.5);line-height:1.5">📍</span>
                        <span style="color:var(--cream);line-height:1.5">
                            {{ $ev->venue }}
                            @if($ev->venue_address)
                            <br><span style="font-size:.72rem;opacity:.5">{{ $ev->venue_address }}</span>
                            @endif
                        </span>
                    </div>
                    <div style="display:flex;flex-wrap:wrap;gap:.55rem;margin-top:1.1rem">
                        @if($ev->venue_maps_url)
                        <a href="{{ $ev->venue_maps_url }}" target="_blank"
                           style="display:inline-flex;align-items:center;gap:.4rem;
                                  font-family:'Cormorant Garamond',serif;font-size:.68rem;
                                  letter-spacing:.14em;text-transform:uppercase;
                                  color:var(--gold);border:1px solid rgba(212,175,55,.22);
                                  padding:7px 14px;border-radius:2px;text-decoration:none;
                                  transition:background .2s"
                           onmouseover="this.style.background='rgba(212,175,55,.08)'"
                           onmouseout="this.style.background='transparent'">
                            <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            Lokasi
                        </a>
                        @endif
                        <button onclick="window.saveToCalendar({title:'{{ addslashes('Pernikahan '.$invitation->getCoupleName()) }}',start:'{{ \Carbon\Carbon::parse($ev->date)->setTimeFromTimeString($ev->time_start ?? '00:00:00')->format('Ymd\THis') }}',end:'{{ \Carbon\Carbon::parse($ev->date)->setTimeFromTimeString($ev->time_end ?? '02:00:00')->format('Ymd\THis') }}',location:'{{ addslashes(($ev->venue ?? '').' '.($ev->venue_address ?? '')) }}',description:'Undangan pernikahan {{ addslashes($invitation->getCoupleName()) }}'})"
                           style="display:inline-flex;align-items:center;gap:.4rem;
                                  font-family:'Cormorant Garamond',serif;font-size:.68rem;
                                  letter-spacing:.14em;text-transform:uppercase;
                                  color:var(--gold);border:1px solid rgba(212,175,55,.22);
                                  padding:7px 14px;border-radius:2px;cursor:pointer;
                                  background:transparent;transition:background .2s"
                           onmouseover="this.style.background='rgba(212,175,55,.08)'"
                           onmouseout="this.style.background='transparent'">
                            <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            Kalender
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- ── GALERI ───────────────────────────────────────── --}}
    @if($galleries->isNotEmpty())
    <section id="nav-gallery" class="je-kw-light" style="padding:72px 24px">
        <div class="je-reveal" style="max-width:860px;margin:0 auto">
            <p class="je-title">Galeri Foto</p>
            <div class="je-divider"><svg width="12" height="12" viewBox="0 0 16 16"><path d="M8 1L9.5 6H15L10.5 9L12 14L8 11L4 14L5.5 9L1 6H6.5Z" fill="#D4AF37" opacity=".55"/></svg></div>
            <div class="je-gallery" style="margin-top:1.25rem">
                @foreach($galleries->take(8) as $gphoto)
                <div class="je-gi {{ $loop->iteration===1 ? 'wide' : '' }} {{ in_array($loop->iteration,[4,7]) ? 'tall' : '' }}">
                    <img src="{{ $gphoto->image_url }}" alt="{{ $gphoto->caption ?? '' }}" loading="lazy">
                    @if($gphoto->caption)<div class="je-gcap">{{ $gphoto->caption }}</div>@endif
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- ── RSVP ─────────────────────────────────────────── --}}
    @if($invitation->is_open)
    <section id="nav-rsvp" class="je-kw-dark" style="padding:72px 24px">
        <div class="je-reveal" style="max-width:860px;margin:0 auto">
            <p class="je-title je-title-light">Konfirmasi Kehadiran</p>
            <div class="je-divider"><svg width="12" height="12" viewBox="0 0 16 16"><path d="M8 1L9.5 6H15L10.5 9L12 14L8 11L4 14L5.5 9L1 6H6.5Z" fill="#D4AF37" opacity=".55"/></svg></div>
            @if($invitation->rsvp_deadline)
            <p style="text-align:center;font-size:.72rem;color:rgba(212,175,55,.4);margin-bottom:1.25rem">
                Konfirmasi sebelum {{ \Carbon\Carbon::parse($invitation->rsvp_deadline)->translatedFormat('d F Y') }}
            </p>
            @endif
            <div style="--rsvp-section-bg:var(--brown)">
                @livewire('invitation.rsvp-form', ['invitation' => $invitation, 'guest' => $guest ?? null])
            </div>
        </div>
    </section>
    @endif

    {{-- ── UCAPAN ───────────────────────────────────────── --}}
    @if($recentWishes->isNotEmpty())
    <section class="je-kw-light" style="padding:72px 24px">
        <div class="je-reveal" style="max-width:860px;margin:0 auto">
            <p class="je-title">Ucapan &amp; Doa</p>
            <div class="je-divider"><svg width="12" height="12" viewBox="0 0 16 16"><path d="M8 1L9.5 6H15L10.5 9L12 14L8 11L4 14L5.5 9L1 6H6.5Z" fill="#D4AF37" opacity=".55"/></svg></div>
            <div style="display:flex;flex-direction:column;gap:.65rem;margin-top:1.25rem">
                @foreach($recentWishes as $wish)
                <div class="je-wish">
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:.32rem">
                        <p style="font-family:'Cormorant Garamond',serif;font-size:.9rem;font-weight:600;color:var(--brown)">{{ $wish->name }}</p>
                        <span style="font-size:.6rem;padding:2px 8px;border-radius:999px;
                            {{ $wish->attendance === 'hadir'
                                ? 'background:rgba(48,109,41,.1);color:rgba(48,109,41,.75)'
                                : 'background:rgba(74,51,36,.06);color:rgba(74,51,36,.38)' }}">
                            {{ $wish->attendance === 'hadir' ? '✦ Hadir' : 'Tidak Hadir' }}
                        </span>
                    </div>
                    <p style="font-size:.8rem;color:var(--muted);line-height:1.65">{{ $wish->message }}</p>
                    <p style="font-size:.6rem;color:rgba(74,51,36,.28);margin-top:.3rem">{{ \Carbon\Carbon::parse($wish->created_at)->diffForHumans() }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- ── HADIAH ───────────────────────────────────────── --}}
    @if($gifts->isNotEmpty())
    <section class="je-kw-light" style="padding:72px 24px">
        <div class="je-reveal" style="max-width:860px;margin:0 auto">
            <p class="je-title">Hadiah Pernikahan</p>
            <div class="je-divider"><svg width="12" height="12" viewBox="0 0 16 16"><path d="M8 1L9.5 6H15L10.5 9L12 14L8 11L4 14L5.5 9L1 6H6.5Z" fill="#D4AF37" opacity=".55"/></svg></div>
            <div style="display:flex;flex-direction:column;gap:.9rem;margin-top:1.25rem;max-width:500px;margin-left:auto;margin-right:auto">
                @foreach($gifts as $gift)
                @if($gift->type === 'bank')
                <div class="je-card" style="padding:1rem 1.3rem;display:flex;align-items:center;gap:.9rem">
                    <div style="width:42px;height:42px;flex-shrink:0;border:1px solid rgba(212,175,55,.22);border-radius:3px;display:flex;align-items:center;justify-content:center">
                        <svg width="20" height="20" fill="none" stroke="rgba(212,175,55,.55)" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                    </div>
                    <div style="flex:1;min-width:0">
                        <p style="font-family:'Cormorant Garamond',serif;font-size:.75rem;color:var(--brown);letter-spacing:.1em;text-transform:uppercase;font-weight:600">{{ $gift->bank_name }}</p>
                        <p style="font-size:.92rem;color:var(--text);letter-spacing:.04em;margin:.12rem 0">{{ $gift->account_number }}</p>
                        <p style="font-size:.7rem;color:var(--muted)">{{ $gift->account_name }}</p>
                    </div>
                    <button onclick="navigator.clipboard.writeText('{{ $gift->account_number }}').then(()=>this.textContent='✓').catch(()=>{})"
                            style="flex-shrink:0;background:rgba(212,175,55,.08);border:1px solid rgba(212,175,55,.2);
                                   color:var(--brown);padding:5px 11px;border-radius:3px;
                                   font-size:.62rem;cursor:pointer;font-family:'Poppins',sans-serif;
                                   transition:background .2s"
                            onmouseover="this.style.background='rgba(212,175,55,.18)'"
                            onmouseout="this.style.background='rgba(212,175,55,.08)'">Salin</button>
                </div>
                @endif
                @if($gift->type === 'qris' && $gift->qris_image_url)
                <div class="je-card" style="padding:1rem 1.3rem;text-align:center">
                    <p style="font-family:'Cormorant Garamond',serif;font-size:.75rem;color:var(--brown);letter-spacing:.1em;text-transform:uppercase;font-weight:600;margin-bottom:.85rem">{{ $gift->label ?? 'QRIS' }}</p>
                    <img src="{{ $gift->qris_image_url }}" alt="QRIS" style="max-width:160px;margin:0 auto;display:block;border:1px solid rgba(212,175,55,.18);border-radius:4px;padding:8px;background:white">
                </div>
                @endif
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- ── PENUTUP ──────────────────────────────────────── --}}
    <section class="je-kw-dark" style="padding:68px 24px;text-align:center;border-top:1px solid rgba(212,175,55,.1)">
        <div class="je-reveal">
            <svg width="160" height="32" viewBox="0 0 160 32" fill="none" style="display:block;margin:0 auto 1.5rem">
                <line x1="0" y1="16" x2="54" y2="16" stroke="#D4AF37" stroke-opacity=".18" stroke-width=".8"/>
                <path d="M64 16L72 5L80 16L72 27Z" fill="#D4AF37" opacity=".28"/>
                <rect x="78" y="10" width="6" height="12" fill="#D4AF37" opacity=".14" rx="1"/>
                <path d="M86 16L94 5L102 16L94 27Z" fill="#D4AF37" opacity=".28"/>
                <line x1="110" y1="16" x2="160" y2="16" stroke="#D4AF37" stroke-opacity=".18" stroke-width=".8"/>
            </svg>
            <p style="font-family:'Noto Serif',serif;font-style:italic;font-size:.95rem;color:rgba(231,225,177,.45);line-height:1.95">
                Merupakan suatu kehormatan dan kebahagiaan<br>apabila Bapak/Ibu/Saudara/i berkenan hadir
            </p>
            <p style="font-family:'Cormorant Garamond',serif;font-size:1.2rem;color:var(--gold);margin-top:1.4rem;letter-spacing:.08em;font-weight:600">
                {{ $invitation->groom_name }} &amp; {{ $invitation->bride_name }}
            </p>
            @if($show_watermark ?? false)
            <p class="je-wm">POWERED BY INVORA.ID</p>
            @endif
        </div>
    </section>

</div>{{-- /je-main --}}

{{-- ── NAVBAR ────────────────────────────────────────── --}}
<nav id="je-nav" role="navigation">
    <a href="#nav-top"    class="je-nav-a" title="Beranda">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
        <span>Beranda</span>
    </a>
    <a href="#nav-couple"  class="je-nav-a" title="Mempelai">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
        <span>Mempelai</span>
    </a>
    @if($events->isNotEmpty())
    <a href="#nav-events"  class="je-nav-a" title="Acara">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        <span>Acara</span>
    </a>
    @endif
    @if($galleries->isNotEmpty())
    <a href="#nav-gallery" class="je-nav-a" title="Galeri">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        <span>Galeri</span>
    </a>
    @endif
    @if($invitation->is_open)
    <a href="#nav-rsvp"    class="je-nav-a" title="RSVP">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
        <span>RSVP</span>
    </a>
    @endif
</nav>

{{-- ═══ SCRIPTS ══════════════════════════════════════ --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js" defer></script>
<script>
/* ── Ambient gold dust ── */
(function(){
    var c = document.getElementById('je-particles');
    if(!c) return;
    for(var i=0;i<32;i++){
        var p = document.createElement('div');
        var s = (1+Math.random()*2.5).toFixed(1);
        p.className = 'je-dust';
        p.style.cssText =
            'left:'+Math.random()*100+'%;'
            +'width:'+s+'px;height:'+s+'px;'
            +'animation-duration:'+(16+Math.random()*14).toFixed(1)+'s;'
            +'animation-delay:'+(Math.random()*16).toFixed(1)+'s;';
        c.appendChild(p);
    }
})();

/* ═══ GEBYOK DOOR REVEAL ══════════════════════════════ */
function openJE(){
    if(typeof gsap === 'undefined'){ setTimeout(openJE, 120); return; }

    var cover  = document.getElementById('je-cover');
    var doorL  = document.getElementById('je-door-l');
    var doorR  = document.getElementById('je-door-r');
    var card   = document.getElementById('je-card');
    var seam   = document.getElementById('je-seam');
    var main   = document.getElementById('je-main');
    var navbar = document.getElementById('je-nav');

    var tl = gsap.timeline({
        onComplete: function(){
            cover.style.display  = 'none';
            main.style.display   = 'block';
            navbar.style.display = 'flex';

            gsap.fromTo('#je-main',
                {opacity:0, y:20},
                {opacity:1, y:0, duration:.65, ease:'power2.out'}
            );
            initScrollReveal();
            startPostReveal(); /* ← floating motes + hero breath */

            if(window.Alpine){
                var store = window.Alpine.store('invitation');
                if(store) store.openEnvelope();
            }
        }
    });

    // 1. Fade & lift cover card
    tl.to(card, {opacity:0, y:-14, duration:.32, ease:'power2.in'}, 0);

    // 2. Seam glow burst
    tl.to(seam, {
        boxShadow:'0 0 40px 12px rgba(212,175,55,.72),0 0 80px 24px rgba(212,175,55,.3)',
        duration:.18, yoyo:true, repeat:1
    }, 0.1);

    // 3. Left door swings open — rotateY(-100deg), hinged on left
    tl.to(doorL, {
        rotateY: -100,
        duration: 1.45,
        ease: 'power4.inOut',
        transformOrigin: 'left center'
    }, 0.28);

    // 4. Right door swings open — mirror
    tl.to(doorR, {
        rotateY: 100,
        duration: 1.45,
        ease: 'power4.inOut',
        transformOrigin: 'right center'
    }, 0.28);

    // 5. Gold dust burst from center
    tl.call(function(){
        for(var i=0;i<22;i++){
            var p = document.createElement('div');
            var sz = (2+Math.random()*5).toFixed(1);
            p.style.cssText =
                'position:fixed;border-radius:50%;background:#D4AF37;pointer-events:none;z-index:99;'
                +'left:50%;top:50%;width:'+sz+'px;height:'+sz+'px;opacity:1;';
            document.body.appendChild(p);
            gsap.to(p,{
                x:(Math.random()-.5)*window.innerWidth*.82,
                y:(Math.random()-.5)*window.innerHeight*.72,
                opacity:0,
                duration:.9+Math.random()*.8,
                ease:'power2.out',
                onComplete:function(){ this.targets()[0].remove() }
            });
        }
    }, null, [], 0.5);

    // 6. Morning light flash on reveal
    var ray = document.createElement('div');
    ray.style.cssText =
        'position:fixed;inset:0;z-index:88;pointer-events:none;'
        +'background:radial-gradient(ellipse at 50% 50%,rgba(212,175,55,.15) 0%,transparent 65%);';
    document.body.appendChild(ray);
    tl.to(ray, {opacity:0, duration:.55, ease:'power2.out', onComplete:function(){ray.remove()}}, 0.7);

    // 7. Settling dust — debu kayu jati beterbangan setelah pintu terbuka
    tl.call(function(){
        for(var i=0;i<28;i++){
            (function(){
                var p  = document.createElement('div');
                var sz = (1.5+Math.random()*5).toFixed(1); /* 1.5–6.5px */
                p.style.cssText = [
                    'position:fixed','z-index:98','border-radius:50%',
                    'background:#D4AF37','pointer-events:none',
                    /* sebar dari tengah layar, area lebih luas */
                    'left:'+(20+Math.random()*60)+'%',
                    'top:' +(20+Math.random()*60)+'%',
                    'width:'+sz+'px','height:'+sz+'px',
                    'box-shadow:0 0 '+(parseFloat(sz)*2)+'px rgba(212,175,55,.7)',
                    'will-change:transform,opacity',
                    'animation:je-mote-settle '+(1.2+Math.random()*2.2).toFixed(1)+'s ease-out '
                              +(Math.random()*.9).toFixed(2)+'s forwards',
                ].join(';');
                document.body.appendChild(p);
                setTimeout(function(){ if(p.parentNode) p.remove() },5000);
            })();
        }
    }, null, [], 0.55);

    // 8. Fog + bg fades
    tl.to('#je-fog', {opacity:0, duration:.4}, 1.0);
    tl.to('#je-bg',  {opacity:0, duration:.45}, 1.1);
}

/* ═══ POST-REVEAL EFFECTS ══════════════════════════════
   Dipanggil dari onComplete openJE()
═══════════════════════════════════════════════════════ */
function startPostReveal(){
    startFloatingMotes();
    startHeroBreath();
}

/* ── 1. Floating gold motes — naik terus selama buka undangan ── */
function startFloatingMotes(){
    var c = document.createElement('div');
    c.id  = 'je-motes';
    c.style.cssText = 'position:fixed;inset:0;z-index:6;pointer-events:none;overflow:hidden;';
    document.body.appendChild(c);

    function spawnBatch(){
        if(document.hidden){ setTimeout(spawnBatch, 4000); return; }

        var n = 3 + Math.floor(Math.random()*3); /* 3–5 per batch */
        for(var i=0;i<n;i++){
            (function(){
                var p   = document.createElement('div');
                var sz  = (2 + Math.random()*4).toFixed(1);   /* 2–6px, jelas terlihat */
                var dur = (8  + Math.random()*10).toFixed(1); /* lebih cepat = lebih sering kelihatan */
                var dl  = (Math.random()*1.5).toFixed(2);
                var lft = Math.random()*100;
                var bot = Math.random()*40;
                p.style.cssText = [
                    'position:absolute',
                    'left:'+lft+'%',
                    'bottom:'+bot+'%',
                    'width:'+sz+'px','height:'+sz+'px',
                    'border-radius:50%',
                    'background:#D4AF37',                                /* solid, bukan gradient transparan */
                    'box-shadow:0 0 '+(parseFloat(sz)*2)+'px '+(parseFloat(sz))+'px rgba(212,175,55,.55)', /* glow */
                    'will-change:transform,opacity',
                    'animation:je-mote-up '+dur+'s ease-in '+dl+'s forwards',
                ].join(';');
                c.appendChild(p);
                setTimeout(function(){
                    if(p.parentNode) p.remove();
                },(parseFloat(dur)+parseFloat(dl)+.5)*1000);
            })();
        }
        setTimeout(spawnBatch, 2000+Math.random()*2000); /* lebih sering: tiap 2–4 detik */
    }
    setTimeout(spawnBatch, 500);
}

/* ── 2. Hero ambient breath — cahaya pagi yang bernapas di pendopo ── */
function startHeroBreath(){
    var hero = document.getElementById('nav-top');
    if(!hero) return;

    /* Radial glow yang bernapas */
    var glow = document.createElement('div');
    glow.style.cssText = [
        'position:absolute','inset:0','z-index:4','pointer-events:none',
        'background:radial-gradient(ellipse 55% 45% at 50% 38%,rgba(212,175,55,.08) 0%,transparent 68%)',
        'animation:je-hero-breath 7s ease-in-out infinite',
    ].join(';');
    hero.appendChild(glow);

    /* Soft warm tone di bawah — seperti cahaya lantai pendopo */
    var floor = document.createElement('div');
    floor.style.cssText = [
        'position:absolute','bottom:0','left:0','right:0','height:35%','z-index:4','pointer-events:none',
        'background:linear-gradient(to top,rgba(212,175,55,.055) 0%,transparent 100%)',
        'animation:je-hero-breath 9s ease-in-out 3s infinite',
    ].join(';');
    hero.appendChild(floor);
}

/* ── Scroll reveal ── */
function initScrollReveal(){
    var els = document.querySelectorAll('.je-reveal');
    if(!els.length) return;
    var obs = new IntersectionObserver(function(entries){
        entries.forEach(function(e,idx){
            if(e.isIntersecting){
                setTimeout(function(){ e.target.classList.add('in') }, idx*60);
                obs.unobserve(e.target);
            }
        });
    },{threshold:0.1});
    els.forEach(function(el){ obs.observe(el) });
}

/* ── Navbar active state ── */
document.addEventListener('scroll', function(){
    var ids = ['nav-top','nav-couple','nav-events','nav-gallery','nav-rsvp'];
    var active = '';
    ids.forEach(function(id){
        var el = document.getElementById(id);
        if(el && el.getBoundingClientRect().top <= window.innerHeight/2) active = id;
    });
    document.querySelectorAll('#je-nav .je-nav-a').forEach(function(a){
        a.classList.toggle('on', a.getAttribute('href') === '#'+active);
    });
},{passive:true});
</script>

@livewireScripts
</body>
</html>
